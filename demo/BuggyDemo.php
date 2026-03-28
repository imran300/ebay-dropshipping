<?php

declare(strict_types=1);

namespace Demo;

final class BuggyDemo
{
    public function calculateProfit(float $salePrice, float $costPrice, float $fees = 0.0): float
    {
        // Intentional bug: fees are added instead of subtracted.
        return $salePrice - $costPrice + $fees;
    }

    public function isOrderReady(bool $paid, bool $packed, bool $shipped): bool
    {
        // Intentional bug: shipped orders should require all flags to be true.
        return $paid || $packed || $shipped;
    }

    public function formatSku(string $prefix, int $sequence): string
    {
        // Intentional bug: sequence should be zero-padded, but this leaves it raw.
        return $prefix . '-' . $sequence;
    }
}
