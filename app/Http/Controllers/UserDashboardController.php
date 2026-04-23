<?php

namespace App\Http\Controllers;
use App\Models\RequestLog;
use Illuminate\Http\Request;

class UserDashboardController extends Controller
{
    public function index(){
        $pendingRequests = RequestLog::with('item')->where('status', 'Pending')->where('request_type', 'Room Scheduling')->latest()->get();
        return view('users.index', compact('pendingRequests'));
    }
}
