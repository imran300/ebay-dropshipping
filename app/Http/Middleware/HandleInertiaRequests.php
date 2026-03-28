<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $primaryNavigation = [
            [
                'label' => 'Overview',
                'route' => 'dashboard',
                'active' => 'dashboard',
            ],
            [
                'label' => 'Products',
                'route' => 'products.index',
                'active' => 'products.*',
            ],
            [
                'label' => 'Listings',
                'route' => 'listings.index',
                'active' => 'listings.*',
            ],
            [
                'label' => 'Orders',
                'route' => 'orders.index',
                'active' => 'orders.*',
            ],
        ];

        if ($request->user()?->is_admin) {
            $primaryNavigation[] = [
                'label' => 'Settings',
                'route' => 'settings.index',
                'active' => 'settings.*',
            ];
        }

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'can_manage_settings' => fn () => (bool) $request->user()?->is_admin,
            'navigation' => [
                'primary' => $primaryNavigation,
                'mobile' => $primaryNavigation,
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
            ],
        ];
    }
}
