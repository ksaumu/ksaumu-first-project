<?php

declare(strict_types = 1);

namespace App\Exceptions;

/**
 * Исключение: маршрут не найден (HTTP 404).
 */
class RouteNotFoundException extends \Exception
{
    protected $message = '404 Not Found';
}
