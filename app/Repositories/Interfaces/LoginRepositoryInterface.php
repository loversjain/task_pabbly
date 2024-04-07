<?php

namespace App\Repositories\Interfaces;

/**
 * Interface LoginRepositoryInterface
 * Defines methods for interacting with login-related data.
 */
interface LoginRepositoryInterface
{
    /**
     * Find a user by their email address.
     *
     * @param string $email The email address of the user to find.
     * @return mixed The user data if found, otherwise null.
     */
    public function findByEmail(string $email);
}
