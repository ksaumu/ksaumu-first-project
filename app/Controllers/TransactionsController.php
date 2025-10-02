<?php

declare(strict_types=1);

namespace App\Controllers;

use App\View;

/**
 * Контроллер для работы с транзакциями.
 * 
 * Отвечает за отображение списка транзакций и связанной статистики.
 */
class TransactionsController
{
    /**
     * Отображает страницу со списком транзакций.
     * 
     * Возвращает представление с таблицей транзакций и статистикой.
     * 
     * @return View Представление страницы транзакций
     */
    public function index(): View
    {
        return View::make('transactions');
    }
}