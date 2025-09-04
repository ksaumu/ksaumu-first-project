<?php

declare(strict_types = 1);

namespace App\Controllers;

use App\View;

/**
 * Контроллер для обработки запросов главной страницы.
 */
class HomeController
{
    /**
     * Обрабатывает запрос на главную страницу.
     */
    public function index(): View
    {
        return View::make('index');
    }
}
