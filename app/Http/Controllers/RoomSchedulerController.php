<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RoomSchedule;
use App\Models\RequestLog;
use App\Models\Room;

class RoomSchedulerController extends Controller
{
    public function index()
    {
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
        if (!session('logged_in') || session('admin_id') != 1) {
            return redirect('/login');
        }
        return view('items.roomscheduler', compact('roomSchedules', 'roomLogs', 'rooms'));
    }
    public function store(Request $request)
    {
        // Validate that room_name is an array
        $request->validate([
            'room_name' => 'required|array',
            'room_name.*' => 'string',
            'scheduled_at' => 'required|date',
            'time_from' => 'required',
            'time_to' => 'required',
            'purpose' => 'required|string|max:255',
            'material' => 'nullable|string',
            'requester_name' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'level' => 'required|string|max:255',
        ]);

        $unavailableRooms = [];

        // Check conflicts for each selected room
        foreach ($request->room_name as $room) {
            $conflict = RequestLog::where('request_type', 'Room Scheduling')
                ->where('location', $room)
                ->where('borrowed_at', $request->scheduled_at)
                ->whereIn('status', ['Pending', 'Approved'])
                ->where(function ($query) use ($request) {
                    $query->whereBetween('time_from', [$request->time_from, $request->time_to])
                        ->orWhereBetween('time_to', [$request->time_from, $request->time_to])
                        ->orWhere(function ($query2) use ($request) {
                            $query2->where('time_from', '<=', $request->time_from)
                                ->where('time_to', '>=', $request->time_to);
                        });
                })
                ->exists();

            if ($conflict) {
                $unavailableRooms[] = $room;
            }
        }

        // If any rooms are unavailable, return with error
        if (!empty($unavailableRooms)) {
            return redirect()->back()
                ->withErrors(['conflict' => '⚠️ The following rooms are already booked: ' . implode(', ', $unavailableRooms)])
                ->withInput();
        }

        // Create a booking for each selected room
        foreach ($request->room_name as $room) {
            RequestLog::create([
                'request_type' => 'Room Scheduling',
                'item_name' => 'Room',
                'inventory_item_id' => null,
                'requester_name' => $request->requester_name,
                'location' => $room,
                'reason' => $request->purpose,
                'material' => $request->material,
                'borrowed_at' => $request->scheduled_at,
                'time_from' => $request->time_from,
                'time_to' => $request->time_to,
                'level' => $request->level,
                'department' => $request->department,
                'status' => 'Pending',
            ]);
        }

        return redirect()->route('roomscheduler.index')->with('success', 'Room schedule(s) created successfully!');
    }
    public function manageroom()
    {
        // You can fetch data here if needed, like rooms or schedules
        return view('items.manageroom');
    }
    public function destroy($id)
    {
        $schedule = RoomSchedule::findOrFail($id);
        RequestLog::where('request_type', 'Room Scheduling')
            ->where('location', $schedule->room_name)
            ->where('borrowed_at', $schedule->scheduled_at)
            ->delete();
        $schedule->delete();
        // $log = RequestLog::findOrFail($id);
        // $log['status'] = 'Deleted from Scheduled Room'; 

        return redirect()->route('roomscheduler.index')->with('success', 'Room booking removed successfully.');
    }
    public function userRoomscheduler()
    {
        // Approved Room Scheduling Requests (upcoming bookings)
        $roomSchedules = RoomSchedule::whereNotNull('approved_at')
            ->orderBy('scheduled_at')
            ->get();

         // Room Schedule Logs (approved past/today bookings)
        $roomLogs = RoomSchedule::whereNotNull('approved_at')
            ->orderByDesc('scheduled_at')
            ->get();
        
        $rooms = Room::all();
        if (!session('logged_in') || session('admin_id') != 2) {
            return redirect('/login');
        }
        return view('users.roomscheduler', compact('roomSchedules', 'roomLogs', 'rooms'));
    }

    public function userStore(Request $request)
    {
        // Validate that room_name is an array
        $request->validate([
            'room_name' => 'required|array',
            'room_name.*' => 'string',
            'scheduled_at' => 'required|date',
            'time_from' => 'required',
            'time_to' => 'required',
            'purpose' => 'required|string|max:255',
            'material' => 'nullable|string',
            'requester_name' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'level' => 'required|string|max:255',
        ]);

        $unavailableRooms = [];

        // Check conflicts for each selected room
        foreach ($request->room_name as $room) {
            $conflict = RequestLog::where('request_type', 'Room Scheduling')
                ->where('location', $room)
                ->where('borrowed_at', $request->scheduled_at)
                ->whereIn('status', ['Pending', 'Approved'])
                ->where(function ($query) use ($request) {
                    $query->whereBetween('time_from', [$request->time_from, $request->time_to])
                        ->orWhereBetween('time_to', [$request->time_from, $request->time_to])
                        ->orWhere(function ($query2) use ($request) {
                            $query2->where('time_from', '<=', $request->time_from)
                                ->where('time_to', '>=', $request->time_to);
                        });
                })
                ->exists();

            if ($conflict) {
                $unavailableRooms[] = $room;
            }
        }

        // If any rooms are unavailable, return with error
        if (!empty($unavailableRooms)) {
            return redirect()->back()
                ->withErrors(['conflict' => '⚠️ The following rooms are already booked: ' . implode(', ', $unavailableRooms)])
                ->withInput();
        }

        // Create a booking for each selected room
        foreach ($request->room_name as $room) {
            RequestLog::create([
                'request_type' => 'Room Scheduling',
                'item_name' => 'Room',
                'inventory_item_id' => null,
                'requester_name' => $request->requester_name,
                'location' => $room,
                'reason' => $request->purpose,
                'material' => $request->material,
                'borrowed_at' => $request->scheduled_at,
                'time_from' => $request->time_from,
                'time_to' => $request->time_to,
                'level' => $request->level,
                'department' => $request->department,
                'status' => 'Pending',
            ]);
        }

        return redirect()->route('user.roomscheduler')->with('success', 'Room schedule(s) created successfully!');
    }


    public function userDestroy($id)
    {
        $schedule = RoomSchedule::findOrFail($id);
        RequestLog::where('request_type', 'Room Scheduling')
            ->where('location', $schedule->room_name)
            ->where('borrowed_at', $schedule->scheduled_at)
            ->delete();
        $schedule->delete();

        return redirect()->route('user.roomscheduler')->with('success', 'Room booking removed successfully.');
    }
    public function publicRoomscheduler()
    {
        $roomSchedules = RoomSchedule::whereNotNull('approved_at')
            ->orderBy('scheduled_at')
            ->get();

        $roomLogs = RoomSchedule::whereNotNull('approved_at')
            ->orderByDesc('scheduled_at')
            ->get();

        $rooms = Room::all();

        // No session check — public access
        return view('viewbookings', compact('roomSchedules', 'roomLogs', 'rooms'));
    }

    public function publicStore(Request $request)
    {
        // Validate that room_name is an array
        $request->validate([
            'room_name' => 'required|array',
            'room_name.*' => 'string',
            'scheduled_at' => 'required|date',
            'time_from' => 'required',
            'time_to' => 'required',
            'purpose' => 'required|string|max:255',
            'material' => 'nullable|string',
            'requester_name' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'level' => 'required|string|max:255',
        ]);

        $unavailableRooms = [];

        // Check conflicts for each selected room
        foreach ($request->room_name as $room) {
            $conflict = RequestLog::where('request_type', 'Room Scheduling')
                ->where('location', $room)
                ->where('borrowed_at', $request->scheduled_at)
                ->whereIn('status', ['Pending', 'Approved'])
                ->where(function ($query) use ($request) {
                    $query->whereBetween('time_from', [$request->time_from, $request->time_to])
                        ->orWhereBetween('time_to', [$request->time_from, $request->time_to])
                        ->orWhere(function ($query2) use ($request) {
                            $query2->where('time_from', '<=', $request->time_from)
                                ->where('time_to', '>=', $request->time_to);
                        });
                })
                ->exists();

            if ($conflict) {
                $unavailableRooms[] = $room;
            }
        }

        // If any rooms are unavailable, return with error
        if (!empty($unavailableRooms)) {
            return redirect()->back()
                ->withErrors(['conflict' => '⚠️ The following rooms are already booked: ' . implode(', ', $unavailableRooms)])
                ->withInput();
        }

        // Create a booking for each selected room
        foreach ($request->room_name as $room) {
            RequestLog::create([
                'request_type' => 'Room Scheduling',
                'item_name' => 'Room',
                'inventory_item_id' => null,
                'requester_name' => $request->requester_name,
                'location' => $room,
                'reason' => $request->purpose,
                'material' => $request->material,
                'borrowed_at' => $request->scheduled_at,
                'time_from' => $request->time_from,
                'time_to' => $request->time_to,
                'level' => $request->level,
                'department' => $request->department,
                'status' => 'Pending',
            ]);
        }

        return redirect()->route('home')->with('success', 'Room request(s) submitted successfully!');
    }
}
