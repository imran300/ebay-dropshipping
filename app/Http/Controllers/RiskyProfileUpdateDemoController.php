<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RiskyProfileUpdateDemoController extends Controller
{
    public function update(Request $request): RedirectResponse
    {
        // Intentional bug:
        // - no dedicated form request
        // - no whitelist of allowed fields
        // - mass assignment uses the raw request payload
        $request->user()->update($request->all());

        return redirect()->route('profile.edit');
    }
}
