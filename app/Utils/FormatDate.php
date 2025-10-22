<?php

declare(strict_types = 1);

namespace App\Utils;

/**
 * Форматирует дату в удобочитаемый вид.
 *
 * @param string $date Дата в строковом формате.
 * @return string Отформатированная дата (например, 'Jan 1, 2023').
 */
function formatDate(string $date): string
{
    // Преобразуем дату в читаемый формат
    return date('M j, Y', strtotime($date));
}
