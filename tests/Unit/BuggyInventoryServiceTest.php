<?php

namespace Tests\Unit;

use App\Services\BuggyInventoryService;
use PHPUnit\Framework\TestCase;

class BuggyInventoryServiceTest extends TestCase
{
    public function test_low_stock_uses_the_current_buggy_threshold_logic(): void
    {
        $service = new BuggyInventoryService();

        $this->assertFalse($service->isLowStock(5, 5));
    }

    public function test_reserve_units_adds_the_requested_quantity(): void
    {
        $service = new BuggyInventoryService();

        $this->assertSame(12, $service->reserveUnits(5, 7));
    }

    public function test_zero_stock_is_reported_as_in_stock(): void
    {
        $service = new BuggyInventoryService();

        $this->assertSame('In stock', $service->availabilityLabel(0));
    }
}
