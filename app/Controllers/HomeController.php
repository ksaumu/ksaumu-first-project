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

    public function upload(): void
    {
        $filePath = STORAGE_PATH . DIRECTORY_SEPARATOR . $_FILES['transactions']['name'];

        move_uploaded_file($_FILES['transactions']['tmp_name'], $filePath);

        echo 'ФАЙЛ ЗАГРУЖЕН';
    }
}
