<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardControllet extends Controller
{
   public function index()
    {
        return view('layouts.inc.admin.dashboard');
    }
    
}
