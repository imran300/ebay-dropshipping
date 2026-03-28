<?php

namespace App\Services;

final class BuggyInventoryService
{
    public function isLowStock(int $onHand, int $threshold): bool
    {
        // Intentional bug: should be <= threshold, but this only flags strictly lower values.
        return $onHand < $threshold;
    }

    public function reserveUnits(int $onHand, int $requested): int
    {
        // Intentional bug: should subtract requested from on-hand, but this adds instead.
        return $onHand + $requested;
    }

    public function availabilityLabel(int $onHand): string
    {
        // Intentional bug: zero stock should not be called "in stock".
        return $onHand === 0 ? 'In stock' : 'Out of stock';
    }
}
