<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Загрузка файла</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            text-align: center;
            max-width: 500px;
            width: 90%;
            position: relative;
        }

        .upload-icon {
            font-size: 64px;
            color: #0057B8;
            margin-bottom: 20px;
        }

        h1 {
            color: #0057B8;
            margin-bottom: 16px;
            font-size: 28px;
        }

        p {
            color: #666;
            margin-bottom: 30px;
            line-height: 1.6;
            font-size: 18px;
        }

        /* Кнопка загрузки в стиле Ozon */
        .upload-btn {
            background: linear-gradient(90deg, #0057B8 0%, #004495 100%);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 22px 42px;
            font-size: 20px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 6px 18px rgba(0, 87, 184, 0.3);
            position: relative;
            overflow: hidden;
        }

        .upload-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 22px rgba(0, 87, 184, 0.4);
        }

        .upload-btn:active {
            transform: translateY(1px);
            box-shadow: 0 4px 12px rgba(0, 87, 184, 0.3);
        }

        .upload-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.5s;
        }

        .upload-btn:hover::before {
            left: 100%;
        }

        .upload-btn i {
            margin-right: 12px;
            font-size: 24px;
        }

        /* Верхняя правая квадратная кнопка (показ) */
        .top-actions {
            position: absolute;
            top: 16px;
            right: 16px;
        }

        .icon-btn {
            width: 44px;
            height: 44px;
            background: linear-gradient(90deg, #0057B8 0%, #004495 100%);
            color: #fff;
            border: none;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 6px 18px rgba(0, 87, 184, 0.3);
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .icon-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 22px rgba(0, 87, 184, 0.4);
        }

        .icon-btn:active {
            transform: translateY(1px);
            box-shadow: 0 4px 12px rgba(0, 87, 184, 0.3);
        }

        .icon-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.5s;
        }

        .icon-btn:hover::before {
            left: 100%;
        }

        .icon-btn i {
            font-size: 18px;
        }

        /* Скрытый input file */
        #file-input {
            display: none;
        }

        /* Анимация загрузки */
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .uploading {
            animation: pulse 1.5s infinite;
            background: linear-gradient(90deg, #004495 0%, #003375 100%);
        }

        /* Адаптивность */
        @media (max-width: 600px) {
            .upload-container {
                padding: 30px 20px;
            }

            h1 {
                font-size: 24px;
            }

            p {
                font-size: 16px;
            }

            .upload-btn {
                padding: 18px 32px;
                font-size: 18px;
            }
        }
    </style>
</head>
<body>
    <div class="upload-container">
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