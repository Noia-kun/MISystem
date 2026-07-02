<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
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
    public function checkRooms(Request $request)
    {
        $rooms = $request->room_name;
    
        // Ensure $rooms is always an array
        if (!is_array($rooms)) {
            $rooms = [$rooms];
        }
        
        $unavailable = [];

        foreach ($rooms as $room) {
            $conflict = RequestLog::where('request_type', 'Room Scheduling')
                ->where('location', $room)
                ->where('borrowed_at', $request->scheduled_at)
                ->whereIn('status', ['Pending', 'Approved'])
                ->where(function($query) use ($request) {
                    $query->whereBetween('time_from', [$request->time_from, $request->time_to])
                        ->orWhereBetween('time_to', [$request->time_from, $request->time_to])
                        ->orWhere(function($q) use ($request) {
                            $q->where('time_from', '<=', $request->time_from)
                            ->where('time_to', '>=', $request->time_to);
                        });
                })
                ->exists();

            if ($conflict) {
                $unavailable[] = $room;
            }
        }

        return response()->json($unavailable);
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