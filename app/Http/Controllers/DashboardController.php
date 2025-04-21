<?php

namespace App\Http\Controllers;

use App\Models\Sector;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index()
    {
        $sectors = Sector::withCount(['administrators', 'regularUsers'])->get();
        
        return view('dashboard', [
            'sectors' => $sectors
        ]);
    }
} 