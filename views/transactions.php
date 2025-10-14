<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Транзакции</title>
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            }

            body {
                background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .upload-container {
                background: white;
                border-radius: 16px;
                box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
                padding: 40px;
                max-width: 1000px;
                width: 95%;
            }

            h1 {
                color: #0057B8;
                margin-bottom: 16px;
                font-size: 28px;
                text-align: center;
            }

            p.subtitle {
                color: #666;
                margin-bottom: 24px;
                line-height: 1.6;
                font-size: 16px;
                text-align: center;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                text-align: center;
                background: #fff;
                border-radius: 12px;
                overflow: hidden;
            }

            thead tr {
                background: linear-gradient(90deg, #0057B8 0%, #004495 100%);
                color: #fff;
            }

            table tr th, table tr td {
                padding: 12px;
                border: 1px #eee solid;
                font-size: 14px;
            }

            tbody tr:nth-child(even) {
                background: #f8fbff;
            }

            tfoot tr th, tfoot tr td {
                font-size: 18px;
                background: #f6f7f9;
            }

            tfoot tr th {
                text-align: right;
            }

            @media (max-width: 600px) {
                .upload-container {
                    padding: 24px 16px;
                }

                table tr th, table tr td {
                    padding: 10px 8px;
                    font-size: 13px;
                }
            }
        </style>
    </head>
    <body>
        <div class="upload-container">
            <h1>Транзакции</h1>
            <p class="subtitle">Сводная таблица доходов и расходов</p>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Check #</th>
                        <th>Description</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($transactions)): ?>
                        <?php foreach ($transactions as $transaction): ?>
                            <tr>
                                <td><?= $transaction['date'] ?></td>
                                <td><?= $transaction['check_number'] ?></td>
                                <td><?= $transaction['description'] ?></td>
                                <td><?= $transaction['amount'] ?></td>
                            </tr>
                        <?php endforeach ?>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <?php if (!empty($totals)): ?>
                        <tr>
                            <th colspan="3">Total Income:</th>
                            <td><?= $totals['totalIncome'] ?></td>
                        </tr>
                        <tr>
                            <th colspan="3">Total Expense:</th>
                            <td><?= $totals['totalExpense'] ?></td>
                        </tr>
                        <tr>
                            <th colspan="3">Net Total:</th>
                            <td><?= $totals['totalNet'] ?></td>
                        </tr>
                    <?php endif; ?>
                </tfoot>
            </table>
        </div>
    </body>
</html>