<?php

namespace Config;

use CodeIgniter\Config\BaseService;
use App\Repositories\UserTaskRepository;
use App\Repositories\AdminTaskRepository;
use App\Repositories\LoginRepository;

class Services extends BaseService
{

    public static function userTaskRepository(): \App\Repositories\Interfaces\UserTaskRepositoryInterface
    {
        return new UserTaskRepository();
    }

    public static function adminTaskRepository(): \App\Repositories\Interfaces\AdminTaskRepositoryInterface
    {
        return new AdminTaskRepository();
    }

    public static function loginRepository(): \App\Repositories\Interfaces\LoginRepositoryInterface
    {
        return new LoginRepository();
    }
}
