<?php

namespace App\Enums;

/**
 * Enumerates roles for users.
 */
enum RoleEnum: string
{
    /**
     * The role identifier for administrators.
     */
    case ADMIN = '1';
    case ADMIN_ROLE = "Admin";

    /**
     * The role identifier for regular users.
     */
    case USER = '2';
    case USER_ROLE = "User";
}
