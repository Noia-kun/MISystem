<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequestLog;
use App\Models\BorrowLog;
use App\Models\RoomSchedule;

class BackLogController extends Controller
{
    public function backlogs()
    {
        $requests = RequestLog::latest()->get();
        $borrows = BorrowLog::latest()->get();
        $roomLogs = RoomSchedule::latest()->get();

        return view('items.backlogs', compact('requests', 'borrows', 'roomLogs'));
    }
}
