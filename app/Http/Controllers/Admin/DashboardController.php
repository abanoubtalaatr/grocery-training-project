<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'revenue'   => ['value' => '$48,295', 'change' => '+12.5%', 'trend' => 'up'],
            'orders'    => ['value' => '1,284',   'change' => '+8.1%',  'trend' => 'up'],
            'customers' => ['value' => '6,741',   'change' => '+3.2%',  'trend' => 'up'],
            'products'  => ['value' => '342',     'change' => '-1.0%',  'trend' => 'down'],
        ];

        $recentOrders = collect([
            ['id' => '#ORD-1021', 'customer' => 'Sara Ahmed',   'total' => '$120.00', 'status' => 'completed', 'date' => '2025-06-15'],
            ['id' => '#ORD-1020', 'customer' => 'Omar Khaled',  'total' => '$89.50',  'status' => 'pending',   'date' => '2025-06-14'],
            ['id' => '#ORD-1019', 'customer' => 'Lina Hassan',  'total' => '$240.00', 'status' => 'processing','date' => '2025-06-14'],
            ['id' => '#ORD-1018', 'customer' => 'Youssef Adel', 'total' => '$55.75',  'status' => 'cancelled', 'date' => '2025-06-13'],
            ['id' => '#ORD-1017', 'customer' => 'Nour Sameh',   'total' => '$310.00', 'status' => 'completed', 'date' => '2025-06-13'],
        ]);

        return view('pages.admin.dashboard.index', compact('stats', 'recentOrders'));
    }
}
