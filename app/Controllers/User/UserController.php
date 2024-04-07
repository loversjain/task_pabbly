<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Enums\CacheEnum;
use App\Enums\PaginationCount;
use App\Enums\UserStatusEnum;
use App\Models\Task;
use App\Validation\User\AddTaskRequest;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Cache\Cache;

class UserController extends BaseController
{
    /**
     * @var $adminRepository Holds an instance of the AdminRepository.
     */
    protected $userTaskRepository;


    /**
     * UserController constructor.
     * Initializes the UserTaskRepository.
     */
    public function __construct()
    {
        $this->userTaskRepository = \Config\Services::UserTaskRepository();
    }

    /**
     * Display the user dashboard.
     *
     * @return \CodeIgniter\HTTP\Response|void
     */
    public function index()
    {
        return view('user/dashboard');
    }
    /**
     * Display the task creation form.
     *
     * @return \CodeIgniter\HTTP\Response|void
     */
    public function getTaskForm()
    {
        return view('user/task/get_task');
    }

    /**
     * Fetch and display all tasks.
     *
     * @return \CodeIgniter\HTTP\Response|void
     */
    public function getAllTask()
    {
        try {


            // Get page number from request, default to 1
            $page = $this->request->getVar('page') ?? PaginationCount::USER_TASK_PAGE_COUNT->value;

            // Fetch paginated tasks using the TaskRepository
            $data['tasks'] = $this->userTaskRepository->paginateTasks(
                PaginationCount::USER_TASK_PAGE_COUNT->value, $page
            );

            return view('user/task/all_task', $data);
        }
        catch(\Exception $e) {
            log_message('error', 'Error getAllTask : ' . $e->getMessage());

        }
    }

    /**
     * Store a new task record.
     *
     * @return \CodeIgniter\HTTP\Response|void
     */
    public function storeTask()
    {
        try {


            $session = session();
            $validation = \Config\Services::validation()->setRules(AddTaskRequest::rules());
            if (!$validation->withRequest($this->request)->run()) {
                // If validation fails, return validation errors to the view
                return view('user/task/get_task', [
                    'validation' => $validation,
                ]);
            }

            $title = $this->request->getVar('Title');
            $description = $this->request->getVar('Description');
            $dueDate = $this->request->getVar('Due_date');
            $dueDate = date('Y-m-d', strtotime($dueDate));

            // Format the Time object into yyyy-mm-dd format
            $data = ["title" => $title, 'description' => $description, "due_date" => $dueDate, 'created_by' => session()->id];
            try {
                $userData = $this->userTaskRepository->store($data);
            } catch (\Exception $e) {
                die(print_r($e->getMessage()));
            }

            if ($userData) {
                $session->setFlashdata('msg', 'Task has been recorded.');
            }
            return $this->response->redirect(site_url('/user/all-task'));
        }
        catch(\Exception $e) {
            log_message('error', 'Error storing task - user : ' . $e->getMessage());

        }

    }

    /**
     * Fetch tasks for DataTables AJAX request.
     *
     * @return \CodeIgniter\HTTP\Response|void
     */
    public function fetchTasks()
    {
        try {


            $request = service('request');
            $postData = $request->getPost();

            $dtpostData = $postData['data'];
            $response = array();

            ## Read value
            $draw = $dtpostData['draw'];
            $start = $dtpostData['start'];
            $rowperpage = $dtpostData['length']; // Rows display per page
            $columnIndex = $dtpostData['order'][0]['column']; // Column index
            $columnName = $dtpostData['columns'][$columnIndex]['data']; // Column name
            $columnSortOrder = $dtpostData['order'][0]['dir']; // asc or desc
            $searchValue = $dtpostData['search']['value']; // Search value

            ## Total number of records without filtering
            $totalRecords = $this->userTaskRepository->totalRecords();

            ## Total number of records with filtering
            $totalRecordwithFilter = $this->userTaskRepository->totalRecordwithFilter($searchValue);


            // Set the cache time in seconds (e.g., 3600 seconds = 1 hour)

            // Generate a unique cache key based on the query parameters
            $cacheKey = 'user_task_query_ajax_' . md5(serialize([$searchValue, $rowperpage, $start]));


            // Check if the cached data exists
            if (!$cachedData = cache($cacheKey)) {
                // If not cached, execute the query and cache the results
                $records = $this->userTaskRepository->allTasks($searchValue, $rowperpage, $start);

                // Cache the query results
                cache()->save($cacheKey, $records, CacheEnum::TASK_CACHE_EXPIRY->value);
            } else {
                // If cached data exists, use it
                $records = $cachedData;
            }
            log_message('debug', 'Cache Key: ' . $cacheKey);
            $data = array();

            foreach ($records as $record) {

                // Add task data to the array
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

            ## Response
            $response = array(
                "draw" => intval($draw),
                "iTotalRecords" => $totalRecords,
                "iTotalDisplayRecords" => $totalRecordwithFilter,
                "taskData" => $data,
                "token" => csrf_hash()
            );

            return $this->response->setJSON($response);
        }
        catch(\Exception $e) {
            log_message('error', 'Error fetching task - user : ' . $e->getMessage());

        }
    }

    /**
     * Delete a task.
     *
     * @return \CodeIgniter\HTTP\Response|void
     */

    public function deleteTask()
    {
        try {

            log_message('info', 'User Editing task...');
            // Check if it's an AJAX request
            if ($this->request->isAJAX()) {
                // Get the task ID from the POST data
                $taskId = $this->request->getPost('taskId');


                // Attempt to delete the task
                if ($this->userTaskRepository->destroy($taskId)) {
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
                return redirect()->to('/user/all-task'); // Adjust the redirect URL as needed
            }
        }
        catch(\Exception $e) {
            log_message('error', 'Error deleting task - user : ' . $e->getMessage());

        }
    }

    /**
     * Display the task edit form for a specific task.
     *
     * @param int $taskId The ID of the task to be edited.
     * @return string|\CodeIgniter\HTTP\RedirectResponse Displays the task edit form if the task is found, otherwise redirects to the task list page.
     */
    public function editTask($taskId)
    {
        try {

            log_message('info', 'User Editing task...');
            // Retrieve the task data based on task ID
            $task = $this->userTaskRepository->find($taskId);

            // Check if the task exists
            if (!$task) {
                log_message('error', 'Task not found for ID: ' . $taskId);
                // Task not found, redirect to task list page or display an error message
                return redirect()->to('/user/all-task')->with('error', 'Task not found.');
            }

            // Pass the task data to the view for editing
            return view('user/task/edit_task', ['task' => $task]);
        }
        catch(\Exception $e) {
            log_message('error', 'Error edting task - user : ' . $e->getMessage());

        }
    }


    /**
     * Update a task based on form submission.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse Redirects to the task edit page with validation errors if validation fails, otherwise redirects to the task list page.
     */
    public function updateTask()
    {
        try {

            log_message('info', 'User Updating task...');
            $session = session();
            $validation = \Config\Services::validation()->setRules(AddTaskRequest::rules());
            $taskId = $this->request->getVar('task_id');

            if (!$validation->withRequest($this->request)->run()) {
                // If validation fails, return validation errors to the view
                return redirect()->to(base_url('index.php/user/edit-task/' . $taskId))
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
            $updateTask = $this->userTaskRepository->updateTask($taskId, $updatedData);
            // Attempt to update the task record
            if ($updateTask) {
                // Task updated successfully
                // Redirect to a success page or display a success message
                log_message('error', 'Failed to update task');
                $session->setFlashdata('msg', 'Task has been updated.');
                return redirect()->to('/user/all-task');
            } else {
                // Task update failed
                // Redirect to an error page or display an error message
                log_message('error', 'Failed to update task');
                $session->setFlashdata('msg', 'Task is not updated.');
                return redirect()->to('/user/all-task');
            }
        }
        catch(\Exception $e) {
            log_message('error', 'Error updating task - user : ' . $e->getMessage());

        }
    }



}
