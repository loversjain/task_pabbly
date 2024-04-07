<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Enums\CacheEnum;
use App\Enums\PaginationCount;
use App\Enums\UserStatusEnum;
use App\Models\Task;
use App\Repositories\AdminTaskRepository;
use App\Validation\Admin\AddTaskRequest;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\I18n\Time;
use Config\Services;

class AdminController extends BaseController
{
    /**
     * @var $adminRepository Holds an instance of the AdminRepository.
     */
    protected $adminTaskRepository;


    /**
     * AdminController constructor.
     * Initializes the AdminRepository.
     */
    public function __construct()
    {
        $this->adminTaskRepository = \Config\Services::AdminTaskRepository();
    }

    /**
     * Display the admin dashboard.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse|\CodeIgniter\HTTP\Response|\CodeIgniter\HTTP\ResponseInterface View or redirect response.
     */
    public function index()
    {
        return view('admin/dashboard');
    }

    /**
     * Display the form for creating a new task.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse|\CodeIgniter\HTTP\Response|\CodeIgniter\HTTP\ResponseInterface View or redirect response.
     */
    public function getTaskForm()
    {
        return view('admin/task/get_task');
    }

    /**
     * Retrieves all tasks for the admin user.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse|\CodeIgniter\HTTP\Response|\CodeIgniter\HTTP\ResponseInterface
     *      Returns a view displaying all tasks for the admin user.
     */
    public function getAllTask()
    {
        // Get the page number from the request, default to 1
        $page = $this->request->getVar('page') ?? PaginationCount::ADMIN_TASK_PAGE_COUNT->value;
        log_message('info', 'Page number: ' . $page);
        // Fetch paginated tasks using the admin task repository
        $data['tasks'] = $this->adminTaskRepository->paginateTasks(
            PaginationCount::ADMIN_TASK_PAGE_COUNT->value, $page);
        log_message('info', 'Number of tasks fetched: ' . count($data['tasks']));
        // Return the view with the fetched tasks data
        return view('admin/task/all_task', $data);
    }

    /**
     * Stores a new task in the database.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse|\CodeIgniter\HTTP\Response|\CodeIgniter\HTTP\ResponseInterface
     *      Returns a redirection to the all tasks page for admin users or displays the task creation form with validation errors.
     */
    public function storeTask()
    {
        // Get the session
        $session = session();

        // Set validation rules
        $validation = \Config\Services::validation()->setRules(AddTaskRequest::rules());
        log_message('info', 'Attempting to store task...');
        // Run validation
        if (!$validation->withRequest($this->request)->run()) {
            // If validation fails, return validation errors to the view
            log_message('error', 'Validation failed: ' . implode(', ', $validation->getErrors()));
            return view('admin/task/get_task', [
                'validation' => $validation,
            ]);
        }

        // Retrieve task details from the form submission
        $title = $this->request->getVar('Title');
        $description = $this->request->getVar('Description');
        $dueDate = $this->request->getVar('Due_date');
        $dueDate = date('Y-m-d', strtotime($dueDate));

        // Prepare task data for insertion
        $data = [
            "title" => $title,
            'description' => $description,
            "due_date" => $dueDate,
            'created_by' => session()->id
        ];

        try {
            // Attempt to store the task
            $adminData = $this->adminTaskRepository->store($data);
            log_message('info', 'Task stored successfully.');
        } catch (\Exception $e) {
            // Handle exceptions
            log_message('error', 'Error storing task: ' . $e->getMessage());

        }

        // If task stored successfully, set flash message
        if ($adminData) {
            $session->setFlashdata('msg', 'Task has been recorded.');
        }

        // Redirect to all tasks page for admin users
        return $this->response->redirect(site_url('/admin/all-task'));
    }


    /**
     * Fetches tasks for data tables AJAX requests.
     *
     * @return \CodeIgniter\HTTP\Response
     *      Returns a JSON response containing tasks data for data tables AJAX requests.
     */
    public function fetchTasks()
    {
        try {


            log_message('info', 'Fetching tasks...');

            // Get the request and post data
            $request = service('request');
            $postData = $request->getPost();

            // Extract post data
            $dtpostData = $postData['data'];
            $response = array();

            // Read values from the post data
            $draw = $dtpostData['draw'];
            $start = $dtpostData['start'];
            $rowperpage = $dtpostData['length']; // Rows display per page
            $columnIndex = $dtpostData['order'][0]['column']; // Column index
            $columnName = $dtpostData['columns'][$columnIndex]['data']; // Column name
            $columnSortOrder = $dtpostData['order'][0]['dir']; // asc or desc
            $searchValue = $dtpostData['search']['value']; // Search value

            log_message('info', 'Post data: ' . json_encode($postData));

            // Total number of records without filtering
            $totalRecords = $this->adminTaskRepository->totalRecords();

            // Total number of records with filtering
            $totalRecordwithFilter = $this->adminTaskRepository->totalRecordwithFilter($searchValue);

            // Generate a unique cache key based on the query parameters
            $cacheKey = 'task_query_ajax_' . md5(serialize([$searchValue, $rowperpage, $start]));

            // Check if the cached data exists
            if (!$cachedData = cache($cacheKey)) {
                // If not cached, execute the query and cache the results
                $records = $this->adminTaskRepository->allTasks($searchValue, $rowperpage, $start);

                // Cache the query results
                cache()->save($cacheKey, $records, CacheEnum::TASK_CACHE_EXPIRY->value);
                log_message('info', 'Caching query results with cache key: ' . $cacheKey);
            } else {
                // If cached data exists, use it
                $records = $cachedData;
                log_message('info', 'Using cached data for cache key: ' . $cacheKey);
            }

            // Initialize an array to hold task data
            $data = array();

            // Loop through the records and add task data to the array
            foreach ($records as $record) {
                $data[] = [
                    "id" => $record['id'],
                    "title" => $record['title'],
                    "description" => $record['description'],
                    "due_date" => $record['due_date'],
                    "status" => $record['status'],
                    "created_by" => $record['created_by_name'],
                    "updated_by" => $record['updated_by_name'] ?? 'N/A'
                ];
            }

            // Prepare the response array
            $response = [
                "draw" => intval($draw),
                "iTotalRecords" => $totalRecords,
                "iTotalDisplayRecords" => $totalRecordwithFilter,
                "taskData" => $data,
                "token" => csrf_hash()
            ];
            log_message('info', 'Response: ' . json_encode($response));

            // Return a JSON response
            return $this->response->setJSON($response);
        }
        catch(\Exception $e) {
            log_message('error', 'Error fetching task: ' . $e->getMessage());

        }
    }


    /**
     * Deletes a task.
     *
     * If the request is made via AJAX, the task ID is retrieved from the POST data,
     * and the task is deleted using the admin task repository. A JSON response is
     * returned indicating whether the task deletion was successful or not.
     *
     * If the request is not made via AJAX, the method redirects to an appropriate page.
     *
     * @return \CodeIgniter\HTTP\Response|\CodeIgniter\HTTP\RedirectResponse
     *      Returns a JSON response if the request is made via AJAX, or redirects to
     *      an appropriate page if the request is not made via AJAX.
     */
    public function deleteTask()
    {
        try {

            log_message('info', 'Admin Deleting task...');

            // Check if it's an AJAX request
            if ($this->request->isAJAX()) {
                // Get the task ID from the POST data
                $taskId = $this->request->getPost('taskId');

                // Attempt to delete the task
                if ($this->adminTaskRepository->destroy($taskId)) {
                    // Task deleted successfully
                    // You can return a success message or any other data if needed
                    log_message('info', 'Task deleted successfully');
                    return $this->response->setJSON(['success' => true, 'message' => 'Task deleted successfully']);
                } else {
                    // Task deletion failed
                    // You can return an error message or any other data if needed
                    log_message('error', 'Failed to delete task');
                    return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete task']);
                }
            } else {
                // If it's not an AJAX request, redirect to an appropriate page
                log_message('error', 'Non-AJAX request received');
                return redirect()->to('/admin/all-task'); // Adjust the redirect URL as needed
            }
        }
        catch(\Exception $e) {
            log_message('error', 'Error deleting task: ' . $e->getMessage());

        }
    }


    /**
     * Displays the form for editing a task.
     *
     * Loads the TaskModel and retrieves the task data based on the provided task ID.
     * The task data is then passed to the view for editing.
     *
     * @param int $taskId The ID of the task to be edited.
     * @return \CodeIgniter\HTTP\ViewResponse Returns a view response containing the edit task form.
     */
    public function editTask(int $taskId)
    {
        try {
            log_message('info', 'admin editTask task...');
            // Retrieve the task data based on task ID
            $task = $this->adminTaskRepository->find($taskId);

            // Pass the task data to the view for editing
            return view('admin/task/edit_task', ['task' => $task]);
        }
        catch(\Exception $e) {
            log_message('error', 'Error editing task: ' . $e->getMessage());

        }


    }


    /**
     * Updates a task based on the form submission.
     *
     * Retrieves the task ID and updated data from the form submission.
     * Validates the form submission and returns validation errors if any.
     * If validation passes, attempts to update the task record using the admin task repository.
     * If the update is successful, redirects to a success page or displays a success message.
     * If the update fails, redirects to an error page or displays an error message.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse Redirects to the appropriate page after updating the task.
     */
    public function updateTask()
    {
        try {

            log_message('info', 'Admin Updating task...');
            $session = session();
            $validation = \Config\Services::validation()->setRules(AddTaskRequest::rules());
            $taskId = $this->request->getVar('task_id');

            if (!$validation->withRequest($this->request)->run()) {
                // If validation fails, return validation errors to the view
                return redirect()->to(base_url('index.php/admin/edit-task/' . $taskId))
                    ->with('validation', $validation);
            }

            // Retrieve the task ID and updated data from form submission
            $dueDate = $this->request->getPost('Due_date');
            $dueDate = date('Y-m-d', strtotime($dueDate));
            $updatedData = [
                'title' => $this->request->getPost('Title'),
                'description' => $this->request->getPost('Description'),
                'due_date' => $dueDate,
                'updated_by' => session()->id
            ];

            // Load the TaskModel
            $updatedTask = $this->adminTaskRepository->updateTask($taskId, $updatedData);

            // Attempt to update the task record
            if ($updatedTask) {
                // Task updated successfully
                // Redirect to a success page or display a success message
                log_message('info', 'Task updated successfully');
                $session->setFlashdata('msg', 'Task has been updated.');
                return redirect()->to('/admin/all-task');
            } else {
                // Task update failed
                // Redirect to an error page or display an error message
                log_message('error', 'Failed to update task');
                $session->setFlashdata('msg', 'Task is not updated.');
                return redirect()->to('/admin/all-task');
            }
        }
        catch(\Exception $e) {
            log_message('error', 'Error updating task: ' . $e->getMessage());

        }
    }


    /**
     * Confirms a task via AJAX request.
     *
     * Checks if the request is an AJAX request.
     * Retrieves the task ID from the POST data.
     * Fetches the task by ID using the admin task repository.
     * Checks if the task exists. If not, returns a JSON response indicating task not found.
     * Updates the task status to confirmed (assuming 'status' is the field representing the task status).
     * Logs any error that occurs during the update process.
     * Returns a JSON response indicating success or failure of the task confirmation.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface A JSON response indicating success or failure of the task confirmation.
     */
    public function confirmTask()
    {
        try {


            // Check if it's an AJAX request
            if ($this->request->isAJAX()) {
                log_message('info', 'Admin Confirming task...');
                // Get the task ID from the POST data
                $taskId = $this->request->getPost('taskId');
                log_message('info', 'Received task ID: ' . $taskId);
                // Fetch the task by ID using the admin task repository
                $task = $this->adminTaskRepository->find($taskId);

                // Check if the task exists
                if (!$task) {
                    // Task not found, return a JSON response
                    log_message('error', 'Task not found for ID: ' . $taskId);
                    return $this->response->setJSON(['success' => false, 'message' => 'Task not found']);
                }

                // Update the task status to confirmed (assuming 'status' is the field representing the task status)
                $updatedData = ['status' => UserStatusEnum::ACTIVE->value]; // Assuming '2' represents the confirmed status
                try {
                    $this->adminTaskRepository->updateTask($taskId, $updatedData);
                    log_message('info', 'Task confirmed successfully');
                    // Update successful
                } catch (\Exception $e) {
                    // Update failed, handle the error
                    // Log the error message
                    log_message('error', 'Error updating task: ' . $e->getMessage());
                    // Return a JSON response indicating failure of task confirmation
                    return $this->response->setJSON(['success' => false, 'message' => 'Task not confirmed']);
                }

                // You can perform additional logic here if needed, such as sending notifications, logging, etc.

                // Return a JSON response indicating success of task confirmation
                return $this->response->setJSON(['success' => true, 'message' => 'Task confirmed successfully']);
            }
        }
        catch(\Exception $e) {
            log_message('error', 'Error confirming task: ' . $e->getMessage());

        }
    }

}
