<?php

declare(strict_types = 1);

namespace App\Utils;

/**
 * Форматирует числовое значение в денежный формат с символом доллара.
 *
 * @param float $amount Сумма для форматирования.
 * @return string Отформатированная строка (например, '$1,234.56' или '-$123.45').
 */
function formatDollarAmount(float $amount): string
{
    $isNegative = $amount < 0;

    return ($isNegative ? '-' : '') . '$' . number_format(abs($amount), 2);
}
