<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SisterInventory;
use App\Models\SisterInventoryLocationHistory;
use App\Models\SisterInventoryUsableNotesHistory;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminInventoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $items = SisterInventory::with(['locationHistories', 'usableNotesHistories'])
            ->when($search, function($query) use ($search) {
                $query->where('item_name', 'LIKE', "%{$search}%")
                    ->orWhere('propertynum', 'LIKE', "%{$search}%")
                    ->orWhere('serialnum', 'LIKE', "%{$search}%")
                    ->orWhere('item_set', 'LIKE', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('users.admin-inventory', compact('items'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'propertynum' => 'required|string|max:255',
            'serialnum' => 'nullable|string|max:255',
            'category' => 'required|string|max:255',
            'item_set' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'condition' => 'required|in:Good,Usable,For Replacement/Disposal',
            'usable_notes' => [
                'nullable',
                'required_if:condition,Usable',
                'string',
                'max:255',
            ],
            'fixed_date' => [
                'nullable',
                'required_if:condition,Usable',
                'date',
            ],
            'date_purchased' => 'required|date',
        ]);

        if ($validated['condition'] !== 'Usable') {
            $validated['usable_notes'] = null;
            $validated['fixed_date'] = null;
        }

        $inventoryData = $validated;
        unset($inventoryData['fixed_date']);

        $inventoryItem = SisterInventory::create($inventoryData);

        if ($validated['condition'] === 'Usable') {
            SisterInventoryUsableNotesHistory::create([
                'inventory_id' => $inventoryItem->id,
                'usable_notes' => $validated['usable_notes'],
                'fixed_date' => $validated['fixed_date'],
            ]);
        }

        return redirect()->route('admin.inventory')
                         ->with('success', 'Inventory item added successfully.');
    }

    public function update(Request $request, SisterInventory $admininventory)
    {
        $oldNotes = $admininventory->usable_notes;

        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'propertynum' => 'required|string|max:255',
            'serialnum' => 'nullable|string|max:255',
            'category' => 'required|string|max:255',
            'item_set' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'condition' => 'required|in:Good,Usable,For Replacement/Disposal',
            'usable_notes' => [
                'nullable',
                'required_if:condition,Usable',
                'string',
                'max:255',
            ],
            'fixed_date' => [
                'nullable',
                'date',
                function ($attribute, $value, $fail) use ($request, $admininventory) {
                    if ($request->input('condition') === 'Usable') {
                        $oldNotes = $admininventory->usable_notes;
                        $newNotes = $request->input('usable_notes');

                        if (($newNotes ?? null) !== ($oldNotes ?? null) && empty($value)) {
                            $fail('Fixed date is required when usable notes change.');
                        }
                    }
                },
            ],
            'date_purchased' => 'required|date',
        ]);

        if ($validated['condition'] !== 'Usable') {
            $validated['usable_notes'] = null;
            $validated['fixed_date'] = null;
        }

        $inventoryData = $validated;
        unset($inventoryData['fixed_date']);

        // Log location change
        if ($admininventory->location !== $validated['location']) {
            SisterInventoryLocationHistory::create([
                'inventory_id' => $admininventory->id,
                'old_location' => $admininventory->location,
                'new_location' => $validated['location'],
                'changed_at' => now(),
            ]);
        }

        $admininventory->update($inventoryData);

        $notesChanged = ($validated['condition'] === 'Usable')
            && (($validated['usable_notes'] ?? null) !== ($oldNotes ?? null));

        if ($notesChanged) {
            SisterInventoryUsableNotesHistory::create([
                'inventory_id' => $admininventory->id,
                'usable_notes' => $validated['usable_notes'],
                'fixed_date' => $validated['fixed_date'],
            ]);
        }

        return redirect()->route('admin.inventory')
                         ->with('success', 'Inventory item updated successfully.');
    }

    public function destroy(SisterInventory $admininventory)
    {
        $admininventory->delete();

        return redirect()->route('admin.inventory')
                         ->with('success', 'Inventory item deleted successfully.');
    }

    public function export(): StreamedResponse
    {
        $filename = 'Sister_Inventory_Data_' . date('Y-m-d_H-i-s') . '.csv';
        $items = SisterInventory::all();

        $headers = [
            'Content-Type' => 'text/csv',
            'Cache-Control' => 'no-store, no-cache, must-revalidate',
            'Pragma' => 'no-cache',
        ];

        $columns = [
            'Item Name', 'Property No.', 'Serial No.', 'Category', 'Item Set', 'Location',
            'Description', 'Condition', 'Date Purchased'
        ];

        $callback = function() use ($items, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($items as $item) {
                fputcsv($file, [
                    $item->item_name,
                    $item->propertynum,
                    $item->serialnum ?? 'N/A',
                    $item->category,
                    $item->item_set ?? 'N/A',
                    $item->location ?? '-',
                    $item->description ?? '',
                    $item->condition,
                    $item->date_purchased,
                ]);
            }

            fclose($file);
        };

        return response()->streamDownload($callback, $filename, $headers);
    }
}
