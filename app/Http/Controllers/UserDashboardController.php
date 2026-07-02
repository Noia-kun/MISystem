<?php

namespace App\Http\Controllers;
use App\Models\RequestLog;
use Illuminate\Http\Request;

class UserDashboardController extends Controller
{
    public function index(){
        // Get pending requests (existing)
        $pendingRequests = RequestLog::with('item')
            ->where('status', 'Pending')
            ->where('request_type', 'Room Scheduling')
            ->latest()
            ->get();
        
        // Get stats counts (new)
        $totalRequests = RequestLog::where('request_type', 'Room Scheduling')->count();
        $pendingRequestsCount = RequestLog::where('request_type', 'Room Scheduling')
            ->where('status', 'Pending')
            ->count();
        $approvedRequestsCount = RequestLog::where('request_type', 'Room Scheduling')
            ->where('status', 'Approved')
            ->count();
        $disapprovedRequestsCount = RequestLog::where('request_type', 'Room Scheduling')
            ->where('status', 'Disapproved')
            ->count();
        
        return view('users.index', compact(
            'pendingRequests',
            'totalRequests',
            'pendingRequestsCount',
            'approvedRequestsCount',
            'disapprovedRequestsCount'
        ));
    }
}
