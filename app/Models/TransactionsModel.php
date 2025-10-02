<?php

declare(strict_types=1);

namespace App\Models;

use PDOException;

/**
 * Модель для работы с транзакциями.
 * 
 * Обеспечивает чтение транзакций из CSV файлов и запись их в базу данных.
 */
class TransactionsModel extends Model
{
    /**
     * Читает транзакции из CSV файла.
     * 
     * @param string $filePath Путь к CSV файлу с транзакциями
     * @param callable|null $transactionHandler Обработчик для преобразования данных транзакции
     * @return array Массив транзакций
     * @throws \Exception Если файл не может быть открыт
     */
    public function readTransactions(string $filePath, ?callable $transactionHandler = null): array
    {
        $transactions = [];

        // Открываем файл для чтения
        $file = fopen($filePath, 'r');
        if ($file === false) {
            throw new \Exception("Не удалось открыть файл: $filePath");
        }

        // Пропускаем заголовок CSV файла
        fgetcsv($file, escape: '');

        // Читаем данные построчно
        while (($transaction = fgetcsv($file, escape: '')) !== false) {
            // Применяем обработчик, если он передан
            if ($transactionHandler !== null) {
                $transaction = $transactionHandler($transaction);
            }
            $transactions[] = $transaction;
        }

        fclose($file);

        return $transactions;
    }

    /**
     * Обрабатывает сырые данные транзакции из CSV.
     * 
     * Преобразует формат даты и очищает сумму от символов валюты.
     * 
     * @param array $transactions Массив с сырыми данными транзакции [дата, номер_чека, описание, сумма]
     * @return array Обработанные данные транзакции
     */
    private function transactionHandler(array $transactions): array
    {
        // Извлекаем данные из массива
        [$date, $check_number, $description, $amount] = $transactions;

        // Очищаем сумму от запятых и символа доллара, преобразуем в число
        $amount = (float) str_replace([',', '$'], '', $amount);
        
        // Преобразуем дату в читаемый формат
        $date = date('M j, Y', strtotime($date));

        return [
            'date' => $date,
            'check_number' => $check_number,
            'description' => $description,
            'amount' => $amount,
        ];
    }

    /**
     * Записывает транзакции в базу данных.
     * 
     * Использует подготовленные запросы для безопасной вставки данных.
     * 
     * @param array $transactions Массив транзакций для записи
     * @return void
     * @throws PDOException При ошибке выполнения запроса
     */
    public function writeTransactions(array $transactions): void
    {
        // SQL запрос для вставки транзакций
        $sql = "INSERT INTO my_db.transactions (date, check_number, description, amount)
                VALUES (:date, :check_number, :description, :amount)";

        // Подготавливаем запрос
        $stmt = $this->db->prepare($sql);

        try {
            // Выполняем вставку для каждой транзакции
            foreach ($transactions as $transaction) {
                $stmt->execute($transaction);
            }
            echo "Транзакции успешно добавлены";
        } catch (PDOException $e) {
            die("Ошибка запроса: " . $e->getMessage());
        }
    }
}