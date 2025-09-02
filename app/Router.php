<?php

declare(strict_types=1);

namespace App;

use App\Exceptions\RouteNotFoundException;

/**
 * Кастомный маршрутизатор.
 */
class Router
{
    private array $routes;

    /**
     * Регистрирует ОБРАБОТЧИК для конкретного HTTP-МЕТОДА и МАРШРУТА во внутреннем многомерном массивe .
     *
     * @param string $requestMethod HTTP-метод (например, 'get', 'post')
     * @param string $route URI маршрута (например, '/users')
     * @param callable|array $action Обработчик маршрута - функция или массив [Класс, метод]
     */
    public function register(string $requestMethod, string $route, callable|array $action): self
    {
        /*
         * Структура массива: [метод][маршрут] = обработчик
         * Пример: $this->routes['get']['/'] = [HomeController::class, 'index']
         * */
        $this->routes[$requestMethod][$route] = $action;

        return $this;
    }

    /**
     * Регистрирует маршрут для обработки HTTP GET-запросов
     *
     * @param string $route URI путь маршрута (например: '/', '/transaction')
     * @param callable|array $action Обработчик маршрута (функция или [Класс, метод])
     * @return self Возвращает $this
     */
    public function get(string $route, callable|array $action): self
    {
        return $this->register('get', $route, $action);
    }

    /**
     * Регистрирует маршрут для обработки HTTP POST-запросов.
     * Используется для регистрации обработчиков, которые должны реагировать
     * на HTTP POST запросы (обычно отправка форм, создание ресурсов).
     *
     * @param string $route URI путь маршрута (например: '/users', '/contact')
     * @param callable|array $action Обработчик маршрута (функция или массив [Класс, метод])
     * @return self Возвращает $this
     */
    public function post(string $route, callable|array $action): self
    {
        return $this->register('post', $route, $action);
    }

    /**
     * Возвращает все зарегистрированные маршруты
     *
     * Этот метод предоставляет доступ к внутреннему массиву маршрутов,
     * который содержит все зарегистрированные обработчики, сгруппированные
     * по HTTP-методам и путям маршрутов.
     *
     * @return array Ассоциативный массив маршрутов в формате:
     *               [
     *                 'get' => [
     *                   '/route1' => action1,
     *                   '/route2' => action2,
     *                 ],
     *                 'post' => [
     *                   '/route3' => action3,
     *                 ]
     *               ]
     */
    public function routes(): array
    {
        return $this->routes;
    }

    /**
     * Разрешает входящий HTTP-запрос, находя соответствующий обработчик маршрута
     *
     * @param string $requestUri URI запроса (включая query-параметры, если есть)
     * @param string $requestMethod HTTP-метод запроса (в нижнем регистре)
     * @return mixed Возвращает результат выполнения обработчика маршрута
     * @throws RouteNotFoundException Выбрасывается если маршрут не найден или обработчик невалиден
     */
    public function resolve(string $requestUri, string $requestMethod): mixed
    {
        /* Извлекает чистый путь URL. */
        $route = explode('?', $requestUri)[0];
        /* Пытается найти обработчик в массиве маршрутов */
        $action = $this->routes[$requestMethod][$route] ?? null;

        if (! $action) {
            throw new RouteNotFoundException();
        }

        if (is_callable($action)) {
            return call_user_func($action);
        }

        if (is_array($action)) {
            [$class, $method] = $action;

            if (class_exists($class)) {
                $class = new $class();

                if (method_exists($class, $method)) {
                    return call_user_func_array([$class, $method], []);
                }
            }
        }

        throw new RouteNotFoundException();
    }
}
