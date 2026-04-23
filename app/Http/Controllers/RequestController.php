<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequestLog;
use App\Models\BorrowLog;
use App\Models\InventoryItem;
use App\Models\RoomSchedule;
use App\Models\Room;

class RequestController extends Controller
{
    // ✅ Generalized status updater (e.g. for use in PATCH routes)
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:Approved,Disapproved,Disregarded',
        ]);

        $log = RequestLog::findOrFail($id);
        $redirectTo = $request->input('redirect_to');

        if ($validated['status'] === 'Disregarded') {
            $log->delete();
            // return redirect()->route('dashboard')->with('success', 'Request disregarded and deleted.');
            return $redirectTo === 'user-dashboard'
            ? redirect('/user-dashboard')->with('success', 'Request disregarded and deleted.')
            : redirect()->route('dashboard')->with('success', 'Request disregarded and deleted.');
        }
        // Handle disapproved
        if ($validated['status'] === 'Disapproved') {
            $log->status = 'Disapproved';
            $log->save();
            // return redirect()->route('dashboard')->with('success', 'Request disapproved.');
            return $redirectTo === 'user-dashboard'
            ? redirect('/user-dashboard')->with('success', 'Request disapproved.')
            : redirect()->route('dashboard')->with('success', 'Request disapproved.');
        }
        // ✅ Save approved status
    if ($validated['status'] === 'Approved') {
        // 🕒 Check for conflicting approved/pending schedules
        if ($log->request_type === 'Room Scheduling') {
            $conflict = \App\Models\RequestLog::where('request_type', 'Room Scheduling')
                ->where('location', $log->location)
                ->where('borrowed_at', $log->borrowed_at)
                ->where('id', '!=', $log->id) // Exclude current request
                ->whereIn('status', ['Approved'])
                ->where(function ($query) use ($log) {
                    $query->whereBetween('time_from', [$log->time_from, $log->time_to])
                        ->orWhereBetween('time_to', [$log->time_from, $log->time_to])
                        ->orWhere(function ($query2) use ($log) {
                            $query2->where('time_from', '<=', $log->time_from)
                                ->where('time_to', '>=', $log->time_to);
                        });
                })
                ->exists();

            // Stop approval and show an error if conflict exists
            if ($conflict) {
                return $redirectTo === 'user-dashboard'
                    ? redirect('/user-dashboard')->with('errors', '⚠️ The selected room is already requested for the chosen date and time.')
                    : redirect()->route('dashboard')->with('errors', '⚠️ The selected room is already requested for the chosen date and time.');
            }
        }
        $log->status = 'Approved';
        $log->save();
    
        // If approved and of type "Borrow Item", add to borrow logs
        if ($log->request_type === 'Borrow Item') {
            $item = InventoryItem::where('item_name', $log->item_name)->first();

            if ($item) {
                $item->status = 'Unavailable';
                $item->location = $log->location; // ← This will update the item's location
                $item->save();

                BorrowLog::create([
                    'inventory_item_id' => $item->id,
                    'item_name' => $item->item_name,
                    'borrower_name' => $log->requester_name,
                    'borrowed_at' => $log->borrowed_at,
                    'location' => $log->location,
                    'reason' => $log->reason,
                    'time_from' => $log->time_from,
                    'time_to' => $log->time_to,
                ]);
            }
        }
        else if ($log->request_type === 'Room Scheduling') {
            // Save room schedule
            $room = Room::where('room_name', $log->location)->first();
            if ($room) {
                // $room->status = 'Unavailable';
                // $room->save();
            RoomSchedule::create([
                'room_name'       => $log->location,       // stored as location during form
                'requester_name'  => $log->requester_name,
                'scheduled_at'    => $log->borrowed_at,    // schedule date
                'time_from'       => $log->time_from,
                'time_to'         => $log->time_to,
                'purpose'         => $log->reason,
                'material'        => $log->material,
                'level'           => $log->level,
                'department'      => $log->department,
                'approved_at'     => now(),
            ]);
            }
        }
        // return redirect()->route('dashboard')->with('success', 'Request approved successfully.');
        return $redirectTo === 'user-dashboard'
            ? redirect('/user-dashboard')->with('success', 'Request approved successfully.')
            : redirect()->route('dashboard')->with('success', 'Request approved successfully.');
    }
    // return redirect()->route('dashboard')->with('success', 'Request status updated.');
    }
}
