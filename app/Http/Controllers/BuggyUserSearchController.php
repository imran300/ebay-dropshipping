<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BuggyUserSearchController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $term = $request->string('q')->toString();

        // Intentional bug:
        // The request value is interpolated directly into raw SQL instead of being parameterized.
        $users = DB::select(
            "select id, name, email from users where name like '%{$term}%' order by id desc"
        );

        return response()->json([
            'query' => $term,
            'users' => $users,
        ]);
    }
}
