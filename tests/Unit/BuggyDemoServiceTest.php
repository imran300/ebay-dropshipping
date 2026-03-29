<?php

namespace Tests\Unit;

use App\Services\BuggyDemoService;
use PHPUnit\Framework\TestCase;

class BuggyDemoServiceTest extends TestCase
{
    public function test_it_calculates_profit_with_the_current_buggy_logic(): void
    {
        $service = new BuggyDemoService();

        $this->assertSame(15.0, $service->calculateProfit(20.0, 10.0, 5.0));
    }

    public function test_it_marks_an_order_ready_when_any_flag_is_true(): void
    {
        $service = new BuggyDemoService();

        $this->assertTrue($service->isOrderReady(false, true, false));
    }

    public function test_it_formats_a_sku_without_zero_padding(): void
    {
        $service = new BuggyDemoService();

        $this->assertSame('SKU-7', $service->formatSku('SKU', 7));
    }
}
