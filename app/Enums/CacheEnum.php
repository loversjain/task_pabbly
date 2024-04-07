<?php

namespace App\Enums;

/**
 * Enumerates cache expiry values.
 */
enum CacheEnum: int
{
    /**
     * The cache expiry value for task caching.
     */
    case TASK_CACHE_EXPIRY = 1;
}
