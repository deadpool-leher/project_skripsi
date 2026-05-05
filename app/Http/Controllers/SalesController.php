<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SalesAnalyticsService;

class SalesController extends Controller
{
    public function __construct(private SalesAnalyticsService $analytics)
    {
    }

    public function index(Request $request)
    {
        if (!session('admin_email')) {
            return redirect('/login');
        }

        $analytics = $this->analytics->getAnalytics($request->all());

        return view('sales', $analytics);
    }

    public function data(Request $request)
    {
        if (!session('admin_email')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($this->analytics->getAnalytics($request->all()));
    }
}
