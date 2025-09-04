<?php

declare(strict_types=1);

namespace App;

use App\Exceptions\ViewNotFoundException;

/**
 * Класс для работы с представлениями.
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
     * Метод для создания экземпляра View.
     *
     * @param string $view Путь к файлу шаблона
     * @param array $params Параметры для передачи в шаблон
     */
    public static function make(string $view, array $params = []): static
    {
        return new static($view, $params);
    }

    /**
     * Рендерит шаблон и возвращает результат как строку.
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

    public function __toString(): string
    {
        return $this->render();
    }

    public function __get(string $name)
    {
        return $this->params[$name] ?? null;
    }
}
