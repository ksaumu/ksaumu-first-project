<?php

declare(strict_types = 1);

namespace App\Controllers;

use App\View;

/**
 * Контроллер для обработки запросов главной страницы.
 * 
 * Отвечает за отображение главной страницы приложения с формой загрузки файлов.
 */
class HomeController
{
    /**
     * Обрабатывает запрос на главную страницу.
     * 
     * Возвращает представление с формой для загрузки CSV файлов с транзакциями.
     * 
     * @return View Представление главной страницы
     */
    public function index(): View
    {
        return View::make('index');
    }
}
