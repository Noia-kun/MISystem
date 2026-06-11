<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboard2Controller extends Controller
{
    public function index()
    {
         // Fetch stats from DTR database connection
        $totalRequests = DB::connection('dtr')
            ->table('tbl_requests')
            ->count();
        
        $pendingRequests = DB::connection('dtr')
            ->table('tbl_requests')
            ->where('status', 'Pending')
            ->count();
        
        $approvedRequests = DB::connection('dtr')
            ->table('tbl_requests')
            ->where('status', 'Approved')
            ->count();
        
        $deniedRequests = DB::connection('dtr')
            ->table('tbl_requests')
            ->where('status', 'Disapproved')
            ->count();
        
        // Get 5 most recent requests (ordered by datetime_requested)
        $recentRequests = DB::connection('dtr')
            ->table('tbl_requests')
            ->orderBy('datetime_requested', 'desc')
            ->limit(5)
            ->get();
        
        // Pass data to the view
        return view('users.principal-dashboard', compact(
            'totalRequests',
            'pendingRequests',
            'approvedRequests',
            'deniedRequests',
            'recentRequests'
        ));
    }
}