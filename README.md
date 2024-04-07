# Simple Task Management System

## Introduction

Welcome to the Simple Task Management System! This web application allows users to create, manage, and track tasks effectively. It provides a user-friendly interface for task creation, listing, viewing, editing, and deletion, along with user authentication to ensure data security.

## Features

1. **Task Creation:** Users can create new tasks by filling out a form with title, description, and due date fields.
2. **Task List:** Display a paginated list of all tasks, showing title, due date, and status (e.g., "pending," "completed"), with AJAX support for seamless updates.
3. **Task Details:** View detailed information about a specific task, including its description and due date.
4. **Task Editing:** Allow users to edit existing tasks, updating the title, description, and due date as needed.
5. **Task Deletion:** Provide a confirmation dialogue before allowing users to delete a task, ensuring data safety.
6. **Task Status Update:** Users can mark tasks as completed or change their status to reflect progress.
7. **User Authentication:** Implement a basic user authentication system to control access, ensuring that only authorized users can perform CRUD operations on tasks.

## Installation

1. Clone the repository to your local machine:

    ```
    git clone https://github.com/loversjain/simple-task-management.git
    ```

2. Navigate to the project directory:

    ```
    cd simple-task-management
    ```

3. Install dependencies:

    ```
    composer install
    ```

4. Configure your environment variables and updating it with your database credentials:

    ```
    App/Config/database.php
    public $default = [
        'DSN'      => '',
        'hostname' => 'localhost',
        'username' => 'root',
        'password' => '12345',
        'database' => 'emp-pabbly',
        'DBDriver' => 'MySQLi',
        'DBPrefix' => '',
        'pConnect' => false,
        'DBDebug'  => (ENVIRONMENT !== 'development'),
        'cacheOn'  => false,
        'cacheDir' => '',
        'charset'  => 'utf8',
        'DBCollat' => 'utf8_general_ci',
        'swapPre'  => '',
        'encrypt'  => false,
        'compress' => false,
        'strictOn' => false,
        'failover' => [],
        'port'     => 3306,
    ];
    ```

5. Set up your database and update the database configuration in the `App/Config/database.php` file.

6. Run the database migrations:

    ```
    php spark migrate
    ```

7. Serve the application:

    ```
    php spark serve
    ```

8. Access your application at [http://localhost:8080](http://localhost:8080).

## Usage

1. Login (Admin/User) with existing credentials.
2. Admin can Create, view, edit, delete and confirm tasks as per your requirements.
3. User can Create, view, edit and delete tasks as per your requirements.
4. Manage task statuses and due dates to stay organized.
5. Logout when done to ensure data security.

## Highlights
1.  Use Repository Pattern
2.  Use Enum
3.  Use Admin Lte v3
4.  Use Request File for validation rule
5.  Use Datatables
6.  Use Migrations
7.  Use Cache
8.  Use Filter(Middleware)
9.  Use Ajax



## Routes

### Admin Routes

- **Admin Dashboard**: `/admin/admin-dashboard`
  - Method: GET
  - Controller Method: `Admin\AdminController::index`

- **All Tasks**: `/admin/all-task`
  - Method: GET
  - Controller Method: `Admin\AdminController::getAllTask`

- **Get Task Form**: `/admin/get-task`
  - Method: GET
  - Controller Method: `Admin\AdminController::getTaskForm`

- **Store Task**: `/admin/store-task`
  - Method: POST
  - Controller Method: `Admin\AdminController::storeTask`

- **Fetch Tasks**: `/admin/fetch`
  - Method: POST
  - Controller Method: `Admin\AdminController::fetchTasks`

- **Delete Task**: `/admin/delete-task`
  - Method: POST
  - Controller Method: `Admin\AdminController::deleteTask`

- **Edit Task**: `/admin/edit-task/{task_id}`
  - Method: GET
  - Controller Method: `Admin\AdminController::editTask`

- **Update Task**: `/admin/update-task`
  - Method: POST
  - Controller Method: `Admin\AdminController::updateTask`

- **Confirm Task**: `/admin/confirm-task`
  - Method: POST
  - Controller Method: `Admin\AdminController::confirmTask`

### User Routes

- **User Dashboard**: `/user/dashboard`
  - Method: GET
  - Controller Method: `User\UserController::index`

- **All Tasks**: `/user/all-task`
  - Method: GET
  - Controller Method: `User\UserController::getAllTask`

- **Get Task Form**: `/user/get-task`
  - Method: GET
  - Controller Method: `User\UserController::getTaskForm`

- **Store Task**: `/user/store-task`
  - Method: POST
  - Controller Method: `User\UserController::storeTask`

- **Fetch Tasks**: `/user/fetch`
  - Method: POST
  - Controller Method: `User\UserController::fetchTasks`

- **Delete Task**: `/user/delete-task`
  - Method: POST
  - Controller Method: `User\UserController::deleteTask`

- **Edit Task**: `/user/edit-task/{task_id}`
  - Method: GET
  - Controller Method: `User\UserController::editTask`

- **Update Task**: `/user/update-task`
  - Method: POST
  - Controller Method: `User\UserController::updateTask`

- **Confirm Task**: `/user/confirm-task`
  - Method: POST
  - Controller Method: `User\UserController::confirmTask`

 
## Contributing

Contributions are welcome! Please follow the guidelines for submitting bug reports, feature requests, or pull requests.

## License

This project is licensed under the [MIT License](LICENSE).

## Credits

- Built with [CodeIgniter 4](https://codeigniter.com).
- Pagination and AJAX implemented using [CodeIgniter 4 Pagination Library](https://codeigniter.com/user_guide/libraries/pagination.html).
- Authentication powered by [CodeIgniter 4 User Guide - Authentication](https://codeigniter.com/user_guide/libraries/auth.html).

## Author

[Lovers Jain](https://github.com/loversjain)

