<?php

namespace App\Http\Controllers;

use App\Services\SettingsService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SettingsController extends Controller
{
    public function index(Request $request, SettingsService $settingsService): Response
    {
        abort_unless($request->user()?->is_admin, 403);

        return Inertia::render('Settings/Index', [
            'settings' => $settingsService->getForUser($request->user()->id),
        ]);
    }

    public function store(Request $request, SettingsService $settingsService)
    {
        abort_unless($request->user()?->is_admin, 403);

        $validated = $request->validate([
            'ebay_fee_rate' => ['required', 'numeric', 'between:0,100'],
            'default_shipping_cost' => ['required', 'numeric', 'min:0'],
            'low_stock_threshold' => ['required', 'integer', 'min:1'],
            'min_margin_threshold' => ['required', 'numeric', 'min:0'],
        ]);

        $settingsService->save($request->user()->id, $validated);

        return redirect()
            ->route('settings.index')
            ->with('success', 'Settings updated.');
    }
}
