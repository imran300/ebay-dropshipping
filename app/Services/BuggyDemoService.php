<?php

namespace App\Services;

final class BuggyDemoService
{
    public function calculateProfit(float $salePrice, float $costPrice, float $fees = 0.0): float
    {
        // Intentional bug: fees are added instead of subtracted.
        return $salePrice - $costPrice + $fees;
    }

    public function isOrderReady(bool $paid, bool $packed, bool $shipped): bool
    {
        // Intentional bug: all flags should be required, but this uses OR.
        return $paid || $packed || $shipped;
    }

    public function formatSku(string $prefix, int $sequence): string
    {
        // Intentional bug: this should zero-pad the sequence.
        return $prefix . '-' . $sequence;
    }
}
