<?php

declare(strict_types=1);

namespace App;

/**
 * Класс конфигурации приложения.
 *
 * Позволяет получать настройки окружения, в том числе параметры подключения к БД.
 *
 * @property-read ?array $db Параметры подключения к базе данных
 */
class Config
{
    protected array $config = [];

    /**
     * Инициализирует конфигурацию из переменных окружения.
     *
     * @param array $env Ассоциативный массив переменных окружения
     */
    public function __construct(array $env)
    {
        $this->config = [
            'db' => [
                'host'     => $env['DB_HOST'],
                'user'     => $env['DB_USER'],
                'pass'     => $env['DB_PASS'],
                'database' => $env['DB_DATABASE'],
                'driver'   => $env['DB_DRIVER'] ?? 'mysql',
            ],
        ];
    }

    /**
     * Возвращает значение параметра конфигурации или null.
     *
     * @param string $name Имя раздела конфигурации
     * @return mixed
     */
    public function __get(string $name)
    {
        return $this->config[$name] ?? null;
    }
}
