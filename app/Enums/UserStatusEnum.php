<?php

namespace App\Enums;

/**
 * Enumerates the status of users.
 */
enum UserStatusEnum: int
{
    /**
     * The status indicating that a user is pending activation.
     */
    case PENDING = 1;

    /**
     * The status indicating that a user account is active.
     */
    case ACTIVE = 2;

    /**
     * The status indicating that a user account is blocked.
     */
    case BLOCK = 3;
}
