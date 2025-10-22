<?php

declare(strict_types=1);

namespace App;

use PDO;

/**
 * Класс-обертка для работы с базой данных через PDO.
 *
 * @mixin PDO
 */
class DB
{
    private PDO $pdo;

    /**
     * Создает подключение к базе данных.
     *
     * @param array $config Массив с параметрами подключения
     */
    public function __construct(array $config)
    {
        $defaultOptions = [
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];

        try {
            $this->pdo = new PDO(
                $config['driver'] . ':host=' . $config['host'] . ';dbname=' . $config['database'],
                $config['user'],
                $config['pass'],
                $config['options'] ?? $defaultOptions
            );
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int) $e->getCode());
        }
    }

    /**
     * Магический метод для вызова методов PDO.
     *
     * @param string $name Название метода
     * @param array $arguments Аргументы метода
     * @return mixed Результат вызова метода PDO
     */
    public function __call(string $name, array $arguments): mixed
    {
        return call_user_func_array([$this->pdo, $name], $arguments);
    }
}
