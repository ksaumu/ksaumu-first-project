<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Загрузка файла</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/css/styles.css">
</head>
<body>
<div class="container upload-container">
        <form action="/transactions" method="get" class="top-actions">
            <button type="submit" class="icon-btn" aria-label="Показать">
                <i class="fas fa-eye"></i>
            </button>
        </form>
        <div class="upload-icon">
            <i class="fas fa-cloud-upload-alt"></i>
        </div>
        <h1>Загрузка файла</h1>
        <p>Загрузите файл c транзакциями в формате CVS</p>

        <form action="/upload" method="post" enctype="multipart/form-data" id="upload-form">
            <button type="button" class="upload-btn" id="upload-button">
                <i class="fas fa-upload"></i> ЗАГРУЗИТЬ ФАЙЛ
            </button>

            <input type="file" id="file-input" name="transactions">
        </form>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const uploadButton = document.getElementById('upload-button');
        const fileInput = document.getElementById('file-input');
        const form = document.getElementById('upload-form');

        // Обработчик клика по кнопке
        uploadButton.addEventListener('click', function() {
            fileInput.click();
        });

        // Обработчик выбора файла
        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                // Добавляем анимацию загрузки
                uploadButton.classList.add('uploading');
                uploadButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ЗАГРУЗКА...';

                // Имитация процесса загрузки
                setTimeout(function() {
                    // Отправляем форму
                    form.submit();
                }, 1500);
            }
        });
    });
</script>
</body>
</html>