<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rooms = Room::all();
        return view('items.manageroom', compact('rooms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_name' => 'required|string|max:255',
            'room_location' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        Room::create($validated);

        return redirect()->route('rooms.management')->with('success', 'Room added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Room $room)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Room $room)
    {
        return view('rooms.show', compact('room'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'room_name' => 'required|string|max:255',
            'room_location' => 'required|string|max:255',
            'status' => 'required|in:Available,Unavailable',
            'notes' => 'nullable|string|max:1000',
        ]);

        $room->update($validated);

        return redirect()->route('rooms.management')->with('success', 'Room updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        $room->delete();

        return redirect()->route('rooms.management')->with('success', 'Room deleted successfully.');
    }
    public function userManageroom()
    {
        $rooms = Room::all();

        return view('users.manageroom', compact('rooms'));
    }

    public function userStore(Request $request)
    {
        $request->validate([
            'room_name' => 'required|string|max:255',
            'room_location' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        Room::create([
            'room_name' => $request->room_name,
            'room_location' => $request->room_location,
            'notes' => $request->notes,
            'status' => 'Available',
        ]);

        return redirect()->route('user.manageroom')->with('success', 'Room added successfully!');
    }
    public function userUpdate(Request $request, Room $room)
    {
        $request->validate([
            'room_name' => 'required|string|max:255',
            'room_location' => 'required|string|max:255',
            'status' => 'required|in:Available,Unavailable',
            'notes' => 'nullable|string',
        ]);

        $room->update([
            'room_name' => $request->room_name,
            'room_location' => $request->room_location,
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        return redirect()->route('user.manageroom')->with('success', 'Room updated successfully!');
    }
    public function userDestroy(Room $room)
    {
        $room->delete();
        return redirect()->route('user.manageroom')->with('success', 'Room deleted successfully!');
    }

}
