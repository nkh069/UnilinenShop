<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TestController extends Controller
{
    public function index()
    {
        Log::info('TestController::index - Testing admin controller');
        return response()->json(['message' => 'Admin test controller works!']);
    }
} 