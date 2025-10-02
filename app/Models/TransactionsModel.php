<?php

declare(strict_types=1);

namespace App\Models;

class TransactionsModel extends Model
{
    public function readTransactions(string $filePath, ?callable $transactionHandler = null): array
    {
        $transactions = [];

        $file = fopen($filePath, 'r');
        fgetcsv($file, escape: '');

        while (($transaction = fgetcsv($file, escape: '')) !== false) {
            if ($transactionHandler !== null) {
                $transaction = $transactionHandler($transaction);
            }
            $transactions[] = $transaction;
        }

        fclose($file);

        return $transactions;
    }

    private function transactionHandler(array $transactions): array
    {
        [$date, $check_number, $description, $amount] = $transactions;

        $amount = (float) str_replace([',', '$'], '', $amount);
        $date = date('M j, Y', strtotime($date));

        return [
            'date' => $date,
            'check_number' => $check_number,
            'description' => $description,
            'amount' => $amount,
        ];
    }

    public function writeTransactions(array $transactions): void
    {
        $sql = "INSERT INTO my_db.transactions (date, check_number, description, amount) 
                VALUES (:date, :check_number, :description, :amount)";

        $stmt = $this->db->prepare($sql);

        try {
            foreach ($transactions as $transaction) {
                $stmt->execute($transaction);
            }
            echo "Транзакции успешно добавлены";
        } catch (PDOException $e) {
            die("Ошибка запроса: " . $e->getMessage());
        }
    }
}