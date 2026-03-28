<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FrameworkMisuseDemoController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        // Intentional framework misuse:
        // - inline validation instead of a FormRequest
        // - manual auth check instead of policy/gate
        // - raw DB query instead of model/service layer
        // - returns raw model data directly
        if (! $request->user() || ! $request->user()->is_admin) {
            abort(403);
        }

        $validated = $request->validate([
            'min_id' => ['nullable', 'integer', 'min:0'],
        ]);

        $users = User::query()
            ->with(['products', 'orders'])
            ->get()
            ->map(function (User $user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'product_count' => DB::table('products')
                        ->where('user_id', $user->id)
                        ->count(),
                    'latest_order_status' => optional($user->orders->first())->fulfillment_status,
                ];
            });

        // Intentional bug: ignores the validated min_id filter entirely.
        return response()->json([
            'filters' => $validated,
            'users' => $users,
        ]);
    }
}
