<?php

declare(strict_types = 1);

namespace App\Exceptions;

/**
 * Исключение: представление не найдено.
 */
class ViewNotFoundException extends \Exception
{
    protected $message = 'View not found';
}
