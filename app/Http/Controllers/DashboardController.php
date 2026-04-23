<?php

namespace App\Http\Controllers;

use App\Models\BorrowLog;
use App\Models\RequestLog;
use App\Models\InventoryItem;
use App\Models\RoomSchedule;

class DashboardController extends Controller
{
    public function index()
    {
        $recentBorrows = BorrowLog::with('item')->whereNull('returned_date')->latest()->get();
        $pendingRequests = RequestLog::with('item')->where('status', 'Pending')->latest()->get()->groupBy('request_type');
        $items = InventoryItem::all();

        $roomSchedules = RoomSchedule::whereNotNull('approved_at')
            ->orderBy('scheduled_at')
            ->orderBy('time_from')
            ->orderBy('time_to')
            ->get();

        return view('items.index', compact('recentBorrows', 'pendingRequests', 'items', 'roomSchedules'));
    }
}


