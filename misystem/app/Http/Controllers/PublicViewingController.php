<?php

namespace App\Http\Controllers;

use App\Models\RequestLog;
use App\Models\RoomSchedule;
use App\Models\Room;

class PublicviewingController extends Controller
{
    public function viewBookings(){
        $pendingRequests = RequestLog::with('item')
        ->where('status', 'Pending')
        ->where('request_type', 'Room Scheduling')
        ->latest()
        ->get();
        
        // Approved Room Scheduling Requests (upcoming bookings)
        $roomSchedules = RoomSchedule::whereNotNull('approved_at')
            ->orderBy('scheduled_at')
            ->orderBy('time_from')
            ->orderBy('time_to')
            ->get();

         // Room Schedule Logs (approved past/today bookings)
        $roomLogs = RoomSchedule::whereNotNull('approved_at')
            ->orderByDesc('scheduled_at')
            ->get();
        
        $rooms = Room::all();
        return view('viewbookings', compact('pendingRequests', 'roomSchedules', 'roomLogs', 'rooms'));
    }
    public function viewBookings2(){
        $pendingRequests = RequestLog::with('item')
        ->where('status', 'Pending')
        ->where('request_type', 'Room Scheduling')
        ->latest()
        ->get();
        
        // Approved Room Scheduling Requests (upcoming bookings)
        $roomSchedules = RoomSchedule::whereNotNull('approved_at')
            ->orderBy('scheduled_at')
            ->orderBy('time_from')
            ->orderBy('time_to')
            ->get();

         // Room Schedule Logs (approved past/today bookings)
        $roomLogs = RoomSchedule::whereNotNull('approved_at')
            ->orderByDesc('scheduled_at')
            ->get();
        
        $rooms = Room::all();
        return view('viewbookings2', compact('pendingRequests', 'roomSchedules', 'roomLogs', 'rooms'));
    }
}