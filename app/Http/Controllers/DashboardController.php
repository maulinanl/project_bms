<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Initialize theme bootstrap
        $bootstrap = config('settings.KT_THEME_BOOTSTRAP.default');
        if ($bootstrap) {
            (new $bootstrap)->init();
        }

        return view('dashboard');
    }
}
