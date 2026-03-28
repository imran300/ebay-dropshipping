<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class BuggyUserReportController extends Controller
{
    public function index(): JsonResponse
    {
        // Intentional bug:
        // - loads all rows into memory
        // - executes a query inside the loop (N+1 style)
        $users = User::query()
            ->get()
            ->map(function (User $user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'peer_count' => DB::table('users')
                        ->where('id', '!=', $user->id)
                        ->count(),
                ];
            });

        return response()->json([
            'users' => $users,
        ]);
    }
}
