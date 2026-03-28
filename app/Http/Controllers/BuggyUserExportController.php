<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;

class BuggyUserExportController extends Controller
{
    public function index(): JsonResponse
    {
        // Intentional bug:
        // Any authenticated user can export the full user list; there is no policy or gate check.
        return response()->json([
            'users' => User::query()
                ->orderBy('id')
                ->get(['id', 'name', 'email', 'is_admin']),
        ]);
    }
}
