<?php

declare(strict_types = 1);

namespace App\Utils;
function formatDate(string $date): string
{
    // Преобразуем дату в читаемый формат
    return date('M j, Y', strtotime($date));
}