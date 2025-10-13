<?php

declare(strict_types=1);

namespace App\Controllers;

use App\View;
use App\Models\TransactionsModel;

/**
 * Контроллер для обработки загрузки файлов.
 *
 * Обрабатывает загрузку CSV файлов с транзакциями и их сохранение в базу данных.
 */
class FileUploadController
{
    /**
     * Обрабатывает загрузку CSV файла с транзакциями.
     *
     * Процесс обработки:
     * 1. Сохраняет загруженный файл во временную директорию
     * 2. Читает транзакции из CSV файла
     * 3. Записывает транзакции в базу данных
     *
     * @return void
     * @throws \Exception При ошибке обработки файла
     */
    public function upload(): void
    {
        // Генерируем уникальное имя для временного файла
        $filePath = STORAGE_PATH . DIRECTORY_SEPARATOR . ((string)random_int(1, 10000)) . '.csv';

        // Перемещаем загруженный файл во временную директорию
        if (!move_uploaded_file($_FILES['transactions']['tmp_name'], $filePath)) {
            throw new \Exception('Ошибка при сохранении загруженного файла');
        }

        // Создаем экземпляр модели для работы с транзакциями
        $transactions = new TransactionsModel();

        // Читаем транзакции из CSV файла с применением обработчика
        $data = $transactions->readTransactions($filePath, [$transactions, 'transactionHandler']);

        // Записываем транзакции в базу данных
        $transactions->writeTransactions($data);
    }
}