<?php

declare(strict_types=1);

namespace App\Controllers;

use App\View;
use App\Models\TransactionsModel;

/**
 * Контроллер для обработки загрузки файла.
 */
class FileUploadController
{
//    public function index(): View
//    {
//        return View::make('index');
//    }

    public function upload(): void
    {
        $filePath = STORAGE_PATH . DIRECTORY_SEPARATOR . ((string) rand(1, 10000)) . '.csv';

        move_uploaded_file($_FILES['transactions']['tmp_name'], $filePath);

        $transactions = new TransactionsModel();

        echo '<pre>';
        var_dump($transactions->readTransactions($filePath, [$transactions, 'transactionHandler']));
        echo '</pre>';
    }
}