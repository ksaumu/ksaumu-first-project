<?php

declare(strict_types = 1);

namespace App\Exceptions;

/**
 * Исключение, выбрасываемое, когда маршрут не найден.
 */
class RouteNotFoundException extends \Exception
{
    protected $message = '404 Not Found';
}