<?php

declare(strict_types=1);

namespace App\Models;

use PDO;
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
     * @return array<int, array<string, mixed>> Массив транзакций
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
     * @param array{0:string,1:string,2:string,3:string} $transactions Сырые данные [date, check_number, description, amount]
     * @return array{date:string,check_number:string,description:string,amount:float} Обработанная транзакция
     */
    private function transactionHandler(array $transactions): array
    {
        // Извлекаем данные из массива
        [$date, $check_number, $description, $amount] = $transactions;

        // Очищаем сумму от запятых и символа доллара, преобразуем в число
        $amount = (float) str_replace([',', '$'], '', $amount);

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
     * @param array<int, array{date:string,check_number:string,description:string,amount:float}> $transactions Транзакции
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

    /**
     * Возвращает список транзакций.
     *
     * @return array<int, array{date:string,check_number:string,description:string,amount:float}>
     * @throws PDOException При ошибке запроса
     */
    public function showTransactions(): array
    {
        $sql = "SELECT date, check_number, description, amount
                FROM my_db.transactions";

        try {
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new PDOException(
                "Ошибка получения транзакций: " . $e->getMessage(), (int) $e->getCode(), $e
            );
        }
    }

    /**
     * Возвращает агрегированные суммы доходов, расходов и чистого итога.
     *
     * @return array{totalIncome:float,totalExpense:float,totalNet:float}
     * @throws PDOException При ошибке получения сумм транзакций
     */
    public function getTotals(): array
    {
        $sqlIncome = "SELECT SUM(amount)
                      FROM my_db.transactions
                      WHERE amount > 0";

        $sqlExpense = "SELECT SUM(amount)
                       FROM my_db.transactions
                       WHERE amount < 0";

        try {
            $totalIncome = (float) $this->db->query($sqlIncome)->fetchColumn();
            $totalExpense = (float) $this->db->query($sqlExpense)->fetchColumn();
            $totalNet = $totalIncome + $totalExpense;

            return [
                'totalIncome' => $totalIncome,
                'totalExpense' => $totalExpense,
                'totalNet' => $totalNet
            ];
        } catch (PDOException $e) {
            throw new PDOException(
                "Ошибка получения сумм транзакций: " . $e->getMessage(), (int) $e->getCode(), $e
            );
        }

    }
}