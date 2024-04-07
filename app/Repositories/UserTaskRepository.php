<?php

namespace App\Repositories;

use App\Models\Task;

class UserTaskRepository implements \App\Repositories\Interfaces\UserTaskRepositoryInterface
{
    /**
     * @var Task $taskModel The Task model instance.
     */
    protected $taskModel;

    /**
     * UserTaskRepository constructor.
     */
    public function __construct()
    {
        $this->taskModel = new Task();
    }

    /**
     * Paginate tasks for the user.
     *
     * @param int $perPage Number of tasks per page.
     * @param int $page    Page number.
     * @return array
     */
    public function paginateTasks(int $perPage, int $page): ?array
    {
        // Get the current page's tasks
        $tasks = $this->taskModel->where('created_by', session()->id)
            ->paginate($perPage, 'default', $page);

        // Return the tasks and pagination links as an array
        return [
            'tasks' => $tasks,
        ];
    }

    /**
     * Store a new task.
     *
     * @param array $data Task data.
     * @return mixed
     */
    public function store(array $data)
    {
        return $this->taskModel->insert($data);
    }

    /**
     * Delete a task by ID.
     *
     * @param mixed $taskId Task ID.
     * @return mixed
     */
    public function destroy($taskId)
    {
        return $this->taskModel->delete($taskId);
    }

    /**
     * Find a task by ID.
     *
     * @param mixed $taskId Task ID.
     * @return mixed
     */
    public function find($taskId)
    {
        return $this->taskModel->find($taskId);
    }

    /**
     * Update a task by ID.
     *
     * @param mixed $taskId     Task ID.
     * @param array $updatedData Updated task data.
     * @return mixed
     */
    public function updateTask($taskId, array $updatedData)
    {
        return $this->taskModel->update($taskId, $updatedData);
    }

    /**
     * Get the total number of records.
     *
     * @return mixed
     */
    public function totalRecords()
    {
        return $this->taskModel->select('id')
            ->where("tasks.created_by", session()->id)
            ->countAllResults();
    }

    /**
     * Get the total number of records with filtering.
     *
     * @param string $searchValue Search value.
     * @return mixed
     */
    public function totalRecordwithFilter($searchValue)
    {
        return $this->taskModel->select('id')
            ->where('tasks.created_by', session()->id)
            ->groupStart() // Start grouping for OR conditions
            ->like('title', $searchValue)
            ->orLike('description', $searchValue)
            ->groupEnd() // End grouping for OR conditions
            ->countAllResults();
    }

    /**
     * Get all tasks with optional search, pagination, and filtering.
     *
     * @param string $searchValue Search value.
     * @param int    $rowperpage  Number of tasks per page.
     * @param int    $start       Starting index of tasks.
     * @return mixed
     */
    public function allTasks($searchValue, $rowperpage, $start)
    {
        return $this->taskModel->select('tasks.id, tasks.title, tasks.due_date, tasks.status, tasks.description, 
                created_by_user.name as created_by_name, updated_by_user.name as updated_by_name')
            ->join('users as created_by_user', 'tasks.created_by = created_by_user.id', 'left')
            ->join('users as updated_by_user', 'tasks.updated_by = updated_by_user.id', 'left')
            ->groupStart() // Start grouping for OR conditions
            ->like('tasks.title', $searchValue)
            ->orLike('tasks.description', $searchValue)
            ->groupEnd() // End grouping for OR conditions
            ->where('tasks.created_by', session()->id)
            ->orderBy('tasks.id', 'desc')
            ->findAll($rowperpage, $start);
    }
}
