<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\TransactionsModel;
use App\View;

use function App\Utils\redirect;

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
    public function transactions(): View
    {
        $model = new TransactionsModel();
        $transactions = $model->showTransactions();
        $totals = $model->getTotals();

        return View::make('transactions', ['transactions' => $transactions, 'totals' => $totals]);
    }

    /**
     * Добавляет новую транзакцию.
     *
     * Получает данные из POST-запроса, записывает их в базу данных и перенаправляет пользователя на страницу транзакций.
     *
     * @return void
     */
    public function addButton(): void
    {
        $model = new TransactionsModel();
        $model->writeTransaction($_POST);

        redirect('/transactions');
    }

    /**
     * Редактирует существующую транзакцию.
     *
     * Получает данные из POST-запроса, обновляет запись в базе данных и перенаправляет пользователя на страницу транзакций.
     *
     * @return void
     */
    public function editButton(): void
    {
        $modal = new TransactionsModel();
        $modal->editTransactions($_POST);

        redirect('/transactions');
    }
}
