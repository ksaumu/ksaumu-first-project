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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/css/styles.css">
</head>
<body>
<div class="container transactions-container">
    <div class="header-row">
        <h1>Транзакции</h1>
        <div class="actions">
            <button class="action-btn add-btn" type="button" aria-label="Добавить"><i class="fa-solid fa-plus"></i></button>
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
            <form action="/addButton" method="post" class="modal-form">
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
                    <button type="button" class="modal-btn modal-cancel">Отмена</button>
                    <button type="submit" class="modal-btn modal-submit">Сохранить</button>
                </div>
            </form>
        </div>
    </div>
    <!-- Modal: Edit Transaction -->
    <div id="edit-transaction-modal" class="modal-overlay" aria-hidden="true" role="dialog" aria-modal="true">
        <div class="modal">
            <div class="modal-header">
                <h2>Редактировать транзакцию</h2>
                <button type="button" class="modal-close" aria-label="Закрыть">×</button>
            </div>
            <form action="/editButton" method="post" class="modal-form">
                <input type="hidden" name="id" id="edit-id">
                <div class="form-row">
                    <label>
                        Дата
                        <input type="date" name="date" id="edit-date" required>
                    </label>
                    <label>
                        Чек #
                        <input type="text" name="check_number" id="edit-check_number">
                    </label>
                    <label>
                        Описание
                        <input type="text" name="description" id="edit-description" required>
                    </label>
                    <label>
                        Сумма
                        <input type="number" name="amount" step="0.01" id="edit-amount" required>
                    </label>
                </div>
                <div class="modal-actions">
                    <button type="button" class="modal-btn modal-cancel">Отмена</button>
                    <button type="submit" class="modal-btn modal-submit">Сохранить</button>
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
            <th class="text-right font-mono">Amount</th>
            <th class="col-actions">Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($transactions)): ?>
            <?php foreach ($transactions as $transaction): ?>
                <tr>
                    <td><?= $transaction['id'] ?></td>
                    <td><?= formatDate($transaction['date']) ?></td>
                    <td><?= htmlspecialchars($transaction['check_number']) ?></td>
                    <td><?= htmlspecialchars($transaction['description']) ?></td>
                    <td class="text-right font-mono"><?= formatDollarAmount($transaction['amount']) ?></td>
                    <td class="actions-cell col-actions">
                        <form class="delete-form">
                            <button
                                    class="action-btn edit-btn"
                                    type="button"
                                    aria-label="Редактировать"
                                    data-id="<?= $transaction['id'] ?>"
                                    data-date="<?= date('Y-m-d', strtotime($transaction['date'])) ?>"
                                    data-check_number="<?= htmlspecialchars($transaction['check_number']) ?>"
                                    data-description="<?= htmlspecialchars($transaction['description']) ?>"
                                    data-amount="<?= $transaction['amount'] ?>"
                            >
                                <i class="fa-solid fa-pencil"></i>
                            </button>
                        </form>
                        <form action="/deleteButton" method="post" class="delete-form">
                            <input type="hidden" name="id" value="<?= $transaction['id'] ?>">
                            <button class="action-btn delete-btn" type="submit" aria-label="Удалить">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach ?>
        <?php endif; ?>
        </tbody>
        <tfoot>
        <?php if (!empty($totals)):
            ?>
            <tr>
                <th colspan="4" class="text-right">Total Income:</th>
                <td class="text-right font-mono"><?= formatDollarAmount($totals['totalIncome']) ?></td>
                <td></td>
            </tr>
            <tr>
                <th colspan="4" class="text-right">Total Expense:</th>
                <td class="text-right font-mono"><?= formatDollarAmount($totals['totalExpense']) ?></td>
                <td></td>
            </tr>
            <tr>
                <th colspan="4" class="text-right">Net Total:</th>
                <td class="text-right font-mono"><?= formatDollarAmount($totals['totalNet']) ?></td>
                <td></td>
            </tr>
        <?php endif; ?>
        </tfoot>
    </table>
</div>
<script>
    // Add Modal Logic
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
    // Edit Modal Logic
    (function () {
        var editOverlay = document.getElementById('edit-transaction-modal');
        if (!editOverlay) return;

        var editBtns = document.querySelectorAll('.edit-btn');
        var closeBtn = editOverlay.querySelector('.modal-close');
        var cancelBtn = editOverlay.querySelector('.modal-cancel');
        var form = editOverlay.querySelector('form');

        // Form fields
        var idInput = form.querySelector('#edit-id');
        var dateInput = form.querySelector('#edit-date');
        var checkInput = form.querySelector('#edit-check_number');
        var descriptionInput = form.querySelector('#edit-description');
        var amountInput = form.querySelector('#edit-amount');

        function openModal(event) {
            var btn = event.currentTarget;
            // Populate form
            idInput.value = btn.dataset.id;
            dateInput.value = btn.dataset.date;
            checkInput.value = btn.dataset.check_number;
            descriptionInput.value = btn.dataset.description;
            amountInput.value = btn.dataset.amount;

            editOverlay.classList.add('open');
            editOverlay.setAttribute('aria-hidden', 'false');
        }

        function closeModal() {
            editOverlay.classList.remove('open');
            editOverlay.setAttribute('aria-hidden', 'true');
        }

        editBtns.forEach(function(btn) {
            btn.addEventListener('click', openModal);
        });

        if (closeBtn) closeBtn.addEventListener('click', closeModal);
        if (cancelBtn) cancelBtn.addEventListener('click', closeModal);

        editOverlay.addEventListener('click', function (e) {
            if (e.target === editOverlay) closeModal();
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && editOverlay.classList.contains('open')) {
                closeModal();
            }
        });
    })();
</script>
</body>
</html>
