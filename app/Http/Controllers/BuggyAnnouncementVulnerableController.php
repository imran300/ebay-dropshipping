<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BuggyAnnouncementVulnerableController extends Controller
{
    public function index(Request $request): Response
    {
        return Inertia::render('Demo/BuggyAnnouncementVulnerable', [
            'announcementHtml' => $request->string('message')->toString(),
        ]);
    }
}
