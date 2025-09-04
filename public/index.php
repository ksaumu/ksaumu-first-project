<?php

declare(strict_types = 1);

use App\App;
use App\Config;
use App\Controllers\HomeController;
use App\Router;

require_once __DIR__ . '/../vendor/autoload.php';

/* Запись НЕИЗМЕНЯЕМОГО объекта 'Dotenv' в $dotenv с указанной директорией где лежит '.env' */
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
/* Чтение файла .env по указанной директории и запись найденых значений в суперглобальный массив $_ENV и $_SERVER */
$dotenv->load();

define('STORAGE_PATH', __DIR__ . '/../storage');
define('VIEW_PATH', __DIR__ . '/../views');

$router = new Router();

/* Регистрация маршрутов */
$router
    ->get('/', [HomeController::class, 'index']);

/* Создание и запуск приложения */
new App(
    $router,
    ['uri' => $_SERVER['REQUEST_URI'], 'method' => $_SERVER['REQUEST_METHOD']],
    new Config($_ENV)
)->run();
