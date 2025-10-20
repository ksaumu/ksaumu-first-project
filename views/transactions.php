<?php
use function App\Utils\formatDate;
use function App\Utils\formatDollarAmount;
?>
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

            .header-row {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 16px;
                margin-bottom: 8px;
            }

            .add-btn {
                width: 36px;
                height: 36px;
                border: none;
                border-radius: 8px;
                background: linear-gradient(90deg, #0057B8 0%, #004495 100%);
                color: #fff;
                font-size: 22px;
                font-weight: 600;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                box-shadow: 0 6px 18px rgba(0, 87, 184, 0.25);
                transition: transform 0.08s ease, box-shadow 0.2s ease;
            }

            .add-btn:hover {
                transform: translateY(-1px);
                box-shadow: 0 8px 22px rgba(0, 68, 149, 0.3);
            }

            .add-btn:active {
                transform: translateY(0);
                box-shadow: 0 4px 12px rgba(0, 68, 149, 0.25);
            }

            .edit-btn {
                width: 36px;
                height: 36px;
                border: none;
                border-radius: 8px;
                background: linear-gradient(90deg, #0057B8 0%, #004495 100%);
                color: #fff;
                font-size: 18px; /* тонкий карандаш визуально сбалансирован */
                font-weight: 600;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                box-shadow: 0 6px 18px rgba(0, 87, 184, 0.25);
                transition: transform 0.08s ease, box-shadow 0.2s ease;
            }

            .edit-btn:hover {
                transform: translateY(-1px);
                box-shadow: 0 8px 22px rgba(0, 68, 149, 0.3);
            }

            .edit-btn:active {
                transform: translateY(0);
                box-shadow: 0 4px 12px rgba(0, 68, 149, 0.25);
            }

            .actions {
                display: flex;
                align-items: center;
                gap: 8px;
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

                .add-btn {
                    width: 32px;
                    height: 32px;
                    font-size: 20px;
                }

                .edit-btn {
                    width: 32px;
                    height: 32px;
                    font-size: 18px;
                }
            }
            /* Modal styles */
            .modal-overlay {
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.4);
                display: none;
                align-items: center;
                justify-content: center;
                padding: 16px;
                z-index: 1000;
            }

            .modal-overlay.open {
                display: flex;
            }

            .modal {
                background: #fff;
                border-radius: 12px;
                box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
                width: 100%;
                max-width: 520px;
                padding: 20px;
            }

            .modal-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 12px;
            }

            .modal-header h2 {
                font-size: 20px;
                color: #004495;
            }

            .modal-close {
                width: 36px;
                height: 36px;
                border: none;
                border-radius: 8px;
                background: #eef2f7;
                color: #333;
                font-size: 20px;
                cursor: pointer;
            }

            .modal-form .form-row {
                display: grid;
                grid-template-columns: 1fr;
                gap: 12px;
                margin: 12px 0;
            }

            .modal-form label {
                display: flex;
                flex-direction: column;
                gap: 6px;
                font-size: 14px;
                color: #333;
            }

            .modal-form input[type="text"],
            .modal-form input[type="date"],
            .modal-form input[type="number"] {
                padding: 10px 12px;
                border: 1px solid #dfe3e8;
                border-radius: 8px;
                background: #fff;
                font-size: 14px;
            }

            .modal-actions {
                display: flex;
                justify-content: flex-end;
                gap: 8px;
                margin-top: 8px;
            }

            .modal-cancel {
                border: 1px solid #dfe3e8;
                background: #fff;
                color: #333;
                padding: 10px 14px;
                border-radius: 8px;
                cursor: pointer;
            }

            .modal-submit {
                border: none;
                background: linear-gradient(90deg, #0057B8 0%, #004495 100%);
                color: #fff;
                padding: 10px 14px;
                border-radius: 8px;
                cursor: pointer;
                box-shadow: 0 6px 18px rgba(0, 87, 184, 0.25);
            }
        </style>
    </head>
    <body>
        <div class="upload-container">
            <div class="header-row">
                <h1 style="text-align: left; margin-bottom: 0;">Транзакции</h1>
                <div class="actions">
                    <button class="add-btn" type="button" aria-label="Добавить">+</button>
                    <button class="edit-btn" type="button" aria-label="Редактировать">🖉</button>
                </div>
            </div>
            <p class="subtitle">Сводная таблица доходов и расходов</p>
            
            <!-- Modal: Add Transaction -->
            <div id="add-transaction-modal" class="modal-overlay" aria-hidden="true" role="dialog" aria-modal="true">
                <div class="modal">
                    <div class="modal-header">
                        <h2>Новая транзакция</h2>
                        <button type="button" class="modal-close" aria-label="Закрыть">×</button>
                    </div>
                    <form action="/addTransaction" method="post" class="modal-form">
                        <div class="form-row">
                            <label>
                                Дата
                                <input type="date" name="date" value="<?= date('Y-m-d') ?>" required>
                            </label>
                            <label>
                                Чек #
                                <input type="text" name="check_number">
                            </label>
                            <label>
                                Описание
                                <input type="text" name="description" required>
                            </label>
                            <label>
                                Сумма
                                <input type="number" name="amount" step="0.01" required>
                            </label>
                        </div>
                        <div class="modal-actions">
                            <button type="button" class="modal-cancel">Отмена</button>
                            <button type="submit" class="modal-submit">Сохранить</button>
                        </div>
                    </form>
                </div>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
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
                                <td><?= $transaction['id'] ?></td>
                                <td><?= formatDate($transaction['date']) ?></td>
                                <td><?= $transaction['check_number'] ?></td>
                                <td><?= $transaction['description'] ?></td>
                                <td><?= formatDollarAmount($transaction['amount']) ?></td>
                            </tr>
                        <?php endforeach ?>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <?php if (!empty($totals)): ?>
                        <tr>
                            <th colspan="4">Total Income:</th>
                            <td><?= formatDollarAmount($totals['totalIncome']) ?></td>
                        </tr>
                        <tr>
                            <th colspan="4">Total Expense:</th>
                            <td><?= formatDollarAmount($totals['totalExpense']) ?></td>
                        </tr>
                        <tr>
                            <th colspan="4">Net Total:</th>
                            <td><?= formatDollarAmount($totals['totalNet']) ?></td>
                        </tr>
                    <?php endif; ?>
                </tfoot>
            </table>
        </div>
        <script>
            (function () {
                var openBtn = document.querySelector('.add-btn');
                var overlay = document.getElementById('add-transaction-modal');
                if (!openBtn || !overlay) return;

                var closeBtn = overlay.querySelector('.modal-close');
                var cancelBtn = overlay.querySelector('.modal-cancel');

                function openModal() {
                    overlay.classList.add('open');
                    overlay.setAttribute('aria-hidden', 'false');
                }

                function closeModal() {
                    overlay.classList.remove('open');
                    overlay.setAttribute('aria-hidden', 'true');
                }

                openBtn.addEventListener('click', openModal);
                if (closeBtn) closeBtn.addEventListener('click', closeModal);
                if (cancelBtn) cancelBtn.addEventListener('click', closeModal);

                overlay.addEventListener('click', function (e) {
                    if (e.target === overlay) closeModal();
                });

                document.addEventListener('keydown', function (e) {
                    if (e.key === 'Escape' && overlay.classList.contains('open')) {
                        closeModal();
                    }
                });
            })();
        </script>
    </body>
</html>