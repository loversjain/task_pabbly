<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Auth\LoginController::index');
$routes->post('/login', 'Auth\LoginController::login');
$routes->get('/logout', 'Auth\LoginController::logout');

// For admin routes
$routes->group('admin', ['filter' => 'authGuard'], function($routes) {
    $routes->get('admin-dashboard', 'Admin\AdminController::index');
    $routes->get('all-task', 'Admin\AdminController::getAllTask');
    $routes->get('get-task', 'Admin\AdminController::getTaskForm');
    $routes->post('store-task', 'Admin\AdminController::storeTask');
    $routes->post('fetch', 'Admin\AdminController::fetchTasks');
    $routes->post('delete-task', 'Admin\AdminController::deleteTask');
    $routes->get('edit-task/(:num)', 'Admin\AdminController::editTask/$1');
    $routes->post('update-task', 'Admin\AdminController::updateTask');
    $routes->post('confirm-task', 'Admin\AdminController::confirmTask');
});

// For user routes
$routes->group('user', ['filter' => 'authGuard'], function($routes) {
    $routes->get('dashboard', 'User\UserController::index');
    $routes->get('all-task', 'User\UserController::getAllTask');
    $routes->get('get-task', 'User\UserController::getTaskForm');
    $routes->post('store-task', 'User\UserController::storeTask');
    $routes->post('fetch', 'User\UserController::fetchTasks');
    $routes->post('delete-task', 'User\UserController::deleteTask');
    $routes->get('edit-task/(:num)', 'User\UserController::editTask/$1');
    $routes->post('update-task', 'User\UserController::updateTask');
    $routes->post('confirm-task', 'User\UserController::confirmTask');
});





