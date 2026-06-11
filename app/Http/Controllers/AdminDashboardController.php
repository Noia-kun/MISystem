<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\InventoryItem;

class AdminDashboardController extends Controller
{
    public function index()
    {
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
        
        $disapprovedRequests = DB::connection('dtr')
            ->table('tbl_requests')
            ->where('status', 'Disapproved')
            ->count();

        $totalInventory = InventoryItem::count();
        
        $recentRequests = DB::connection('dtr')
            ->table('tbl_requests')
            ->orderBy('datetime_requested', 'desc')
            ->limit(5)
            ->get();
        
        return view('users.admin-dashboard', compact(
            'totalRequests',
            'pendingRequests',
            'approvedRequests',
            'disapprovedRequests',
            'totalInventory',
            'recentRequests'
        ));
    }
}