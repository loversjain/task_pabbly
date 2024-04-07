<?php

namespace App\Enums;

/**
 * Enumerates pagination counts for different user roles.
 */
enum PaginationCount: int
{
    /**
     * The pagination count for user tasks.
     */
    case USER_TASK_PAGE_COUNT = 10;

    /**
     * The pagination count for admin tasks.
     */
    case ADMIN_TASK_PAGE_COUNT = 15;
}
