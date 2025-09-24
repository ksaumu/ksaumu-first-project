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

    public function writeTransactions()
    {

    }
}