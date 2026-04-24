<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\BorrowLog;
use App\Models\Room;
use Illuminate\Http\Request;

class InventoryItemController extends Controller{
    // Show all inventory items
    public function index(){
        $items = InventoryItem::paginate(10);
        if (!session('logged_in') || session('admin_id') != 1) {
            return redirect('/login');
        }
        return view('items.inventoryitems', compact('items'));
    }

    // Show form to create a new inventory item
    public function create(){
        return view('items.create');
    }

    // Store new inventory item in DB
    public function store(Request $request){
        // Validate the form input
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
        ]);

        // If location is empty, set default
        if (empty($validated['location'])) {
            $validated['location'] = 'MIS Office';
        }

        InventoryItem::create($validated);

        return redirect()->route('inventoryitems')
                         ->with('success', 'Inventory item added successfully.');
    }

    // Show form to edit an existing item
    public function edit(InventoryItem $item){
        return view('items.edit', compact('item'));
    }

    // Update item in DB
    public function update(Request $request, InventoryItem $item){
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'location' => 'string|max:255',
        ]);

        $item->update($validated);

        return redirect()->route('inventoryitems')
                         ->with('success', 'Inventory item updated successfully.');
    }

    // Delete an item
    public function destroy(InventoryItem $item){
        $item->delete();

        return redirect()->route('inventoryitems')
                         ->with('success', 'Inventory item deleted successfully.');
    }

    // Optional: Show a single item
    public function show(InventoryItem $item){
        return view('items.show', compact('item'));
    }

    public function returnItem($id){
        $item = InventoryItem::findOrFail($id);

        if ($item->status !== 'Unavailable') {
            return redirect()->route('inventoryitems')->with('success', 'Item is already available.');
        }

        $borrowLog = BorrowLog::where('inventory_item_id', $id)->latest()->first();

        if ($borrowLog) {
            $borrowLog->returned_date = now();
            $borrowLog->save();
        }

        $item->status = 'Available';
        $item->location = 'MIS Office';
        $item->save();

        return redirect()->route('inventoryitems')->with('success', 'Item has been marked as returned.');
    }

}
