<?php

declare(strict_types=1);

namespace App;

use App\Exceptions\ViewNotFoundException;

/**
 * Класс для работы с представлениями.
 *
 * Отвечает за подготовку параметров и безопасный рендер PHP-шаблонов.
 */
class View
{
    /**
     * Конструктор представления.
     *
     * @param string $view Путь к файлу шаблона (без расширения)
     * @param array $params Параметры для передачи в шаблон
     */
    public function __construct(
        protected string $view,
        protected array $params = []
    ) {
    }

    /**
     * Метод-фабрика для создания экземпляра представления.
     *
     * @param string $view Путь к файлу шаблона (без расширения)
     * @param array $params Ассоциативный массив параметров для шаблона
     * @return static Экземпляр представления
     */
    public static function make(string $view, array $params = []): static
    {
        return new static($view, $params);
    }

    /**
     * Рендерит шаблон и возвращает результат как строку.
     *
     * @return string HTML-содержимое отрендеренного шаблона
     * @throws ViewNotFoundException Если файл шаблона не найден
     */
    public function render(): string
    {
        $viewPath = VIEW_PATH . '/' . $this->view . '.php';

        if (! file_exists($viewPath)) {
            throw new ViewNotFoundException();
        }

        foreach($this->params as $key => $value) {
            $$key = $value;
        }

        ob_start();

        include $viewPath;

        return (string) ob_get_clean();
    }

    /**
     * Неявный рендер при приведении объекта к строке.
     *
     * @return string HTML-содержимое
     */
    public function __toString(): string
    {
        return $this->render();
    }

    /**
     * Доступ к переданным в шаблон параметрам как к свойствам.
     *
     * @param string $name Имя параметра
     * @return mixed
     */
    public function __get(string $name)
    {
        return $this->params[$name] ?? null;
    }
}
