<?php

declare(strict_types=1);

namespace App\Utils;

/**
 * Выполняет HTTP-перенаправление на указанный путь.
 *
 * @param string $path Путь, на который будет выполнено перенаправление.
 * @return void
 */
function redirect(string $path): void
{
    header('Location: ' . $path);
}
