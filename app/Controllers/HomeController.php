<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\TransactionsModel;
use App\View;
use Exception;

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

    /**
     * Обрабатывает загрузку CSV файла с транзакциями.
     *
     * Процесс обработки:
     * 1. Сохраняет загруженный файл во временную директорию
     * 2. Читает транзакции из CSV файла
     * 3. Записывает транзакции в базу данных
     *
     * @return View
     * @throws Exception
     */
    public function upload(): View
    {
        // Генерируем уникальное имя для временного файла
        $filePath = STORAGE_PATH . DIRECTORY_SEPARATOR . ((string)random_int(1, 10000)) . '.csv';

        // Перемещаем загруженный файл во временную директорию
        if (!move_uploaded_file($_FILES['transactions']['tmp_name'], $filePath)) {
            throw new Exception('Ошибка при сохранении загруженного файла');
        }

        // Создаем экземпляр модели для работы с транзакциями
        $transactions = new TransactionsModel();

        // Читаем транзакции из CSV файла с применением обработчика
        $data = $transactions->readTransactions($filePath, [$transactions, 'transactionHandler']);

        // Записываем транзакции в базу данных
        foreach ($data as $transaction) {
            $transactions->writeTransaction($transaction);
        }

        return View::make('index');
    }
}
