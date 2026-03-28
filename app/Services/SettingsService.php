<?php

namespace App\Services;

use App\Models\Setting;

class SettingsService
{
    private const DEFAULTS = [
        'ebay_fee_rate' => 0.1295,
        'default_shipping_cost' => 0.00,
        'low_stock_threshold' => 5,
        'min_margin_threshold' => 0.00,
    ];

    /**
     * @return array{ebay_fee_rate: float, default_shipping_cost: float, low_stock_threshold: int, min_margin_threshold: float}
     */
    public function getForUser(int $userId): array
    {
        $settings = self::DEFAULTS;

        $storedValues = Setting::query()
            ->where('user_id', $userId)
            ->whereIn('key', array_keys(self::DEFAULTS))
            ->pluck('value', 'key')
            ->all();

        foreach ($storedValues as $key => $value) {
            if (! is_string($key) || ! array_key_exists($key, self::DEFAULTS)) {
                continue;
            }

            $settings[$key] = $this->castValue($key, $value);
        }

        return $settings;
    }

    public function save(int $userId, array $data): void
    {
        foreach (array_keys(self::DEFAULTS) as $key) {
            if (! array_key_exists($key, $data)) {
                continue;
            }

            Setting::query()->updateOrCreate(
                [
                    'user_id' => $userId,
                    'key' => $key,
                ],
                [
                    'value' => (string) $data[$key],
                ],
            );
        }
    }

    private function castValue(string $key, mixed $value): float|int
    {
        return match ($key) {
            'low_stock_threshold' => (int) $value,
            default => (float) $value,
        };
    }
}

