<?php
namespace App\Repositories\Interfaces;

interface  AdminTaskRepositoryInterface
{
    /**
     * Paginate tasks for the user.
     *
     * @param int $perPage Number of tasks per page.
     * @param int $page    Page number.
     * @return mixed
     */
    public function paginateTasks(int $perPage, int $page);

    /**
     * Destroy a task by ID.
     *
     * @param mixed $taskId Task ID.
     * @return mixed
     */
    public function destroy($taskId);

    /**
     * Find a task by ID.
     *
     * @param mixed $taskId Task ID.
     * @return mixed
     */
    public function find($taskId);

    /**
     * Store a new task.
     *
     * @param array $data Task data.
     * @return mixed
     */
    public function store(array $data);

    /**
     * Update a task by ID.
     *
     * @param mixed $taskId     Task ID.
     * @param array $updatedData Updated task data.
     * @return mixed
     */
    public function updateTask($taskId, array $updatedData);

    /**
     * Get the total number of records.
     *
     * @return mixed
     */
    public function totalRecords();

    /**
     * Get the total number of records with filtering.
     *
     * @param string $searchValue Search value.
     * @return mixed
     */
    public function totalRecordwithFilter($searchValue);

    /**
     * Get all tasks with optional search, pagination, and filtering.
     *
     * @param string $searchValue Search value.
     * @param int    $rowPerPage  Number of tasks per page.
     * @param int    $start       Starting index of tasks.
     * @return mixed
     */
    public function allTasks($searchValue, $rowPerPage, $start);


}
