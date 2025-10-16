<?php

declare(strict_types=1);

namespace App\Utils;

function redirect(string $path): void
{
    header('Location: ' . $path);
}