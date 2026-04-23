<?php

namespace App\Http\Controllers;

use App\Models\BorrowLog;
use App\Models\InventoryItem;
use App\Models\RequestLog;
use Illuminate\Http\Request;

class BorrowController extends Controller
{
    public function borrowItem()
    {
        $items = InventoryItem::all();
        // $items = InventoryItem::where('status', 'Available')->get();
        return view('borrows.borrowitem', compact('items'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'request_type' => 'required|in:Borrow Item,Room Scheduling',
            'inventory_item_id' => 'required|exists:inventory_items,id',
            'borrower_name' => 'required|string|max:255',
            'borrowed_at' => 'required|date', // Issued Date
            'location' => 'required|string|max:255', // Venue
            'reason' => 'required|string|max:255',   // Purpose
            'time_from' => 'required|date_format:H:i',
            'time_to' => 'required|date_format:H:i',
        ]);

        $item = InventoryItem::findOrFail($validated['inventory_item_id']);
        // Create a pending request log for the borrow request
        RequestLog::create([
            'request_type'       => $validated['request_type'],
            'item_name'          => $item->item_name,
            'inventory_item_id'  => $item->id,
            'requester_name'     => $validated['borrower_name'],
            'borrowed_at'        => $validated['borrowed_at'], // issued date
            'location'           => $validated['location'],
            'reason'             => $validated['reason'],
            'time_from'          => $validated['time_from'],
            'time_to'            => $validated['time_to'],
            'status'             => 'Pending',
        ]);

        return redirect()->route('dashboard')->with('success', 'Borrow request submitted for approval.');
    }
}
