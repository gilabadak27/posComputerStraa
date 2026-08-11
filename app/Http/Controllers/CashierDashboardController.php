<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CashierDashboardController extends Controller
{
    public function index(Request $request)
    {
        return view('cashier', [
            'date' => $request->query('date', today()->toDateString()),
        ]);
    }
}
