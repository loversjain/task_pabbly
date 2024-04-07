<?php

namespace App\Repositories;

use App\Models\User\User;

/**
 * Class LoginRepository
 * Implements the LoginRepositoryInterface for interacting with user login data.
 */
class LoginRepository implements \App\Repositories\Interfaces\LoginRepositoryInterface
{
    /**
     * The User model instance.
     *
     * @var User
     */
    protected User $userModel;

    /**
     * Create a new LoginRepository instance.
     */
    public function __construct()
    {
        $this->userModel = new User;
    }

    /**
     * Find a user by their email address.
     *
     * @param string $email The email address of the user to find.
     * @return mixed The user data if found, otherwise null.
     */
    public function findByEmail(string $email)
    {
        return $this->userModel->where('email', $email)->first();
    }
}
