<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class LogController extends Controller
{
    public function index(): View
    {
        $logPath = storage_path('logs/laravel.log');
        $lines = [];

        if (File::exists($logPath)) {
            $content = File::get($logPath);
            $rawLines = array_filter(explode("\n", $content));
            // Get last 150 lines
            $lines = array_reverse(array_slice($rawLines, -150));
        }

        return view('admin.logs.index', compact('lines'));
    }

    public function clear(): RedirectResponse
    {
        $logPath = storage_path('logs/laravel.log');
        if (File::exists($logPath)) {
            File::put($logPath, '');
        }
        return redirect()->route('admin.logs.index')->with('success', 'Application log cleared.');
    }
}
