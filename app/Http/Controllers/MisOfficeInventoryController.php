<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MisOfficeInventory;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MisOfficeInventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $items = MisOfficeInventory::with(['locationHistories', 'usableNotesHistories'])
            ->when($search, function($query) use ($search) {
                $query->where('item_name', 'LIKE', "%{$search}%")
                    ->orWhere('propertynum', 'LIKE', "%{$search}%")
                    ->orWhere('item_set', 'LIKE', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        if (!session('logged_in') || session('admin_id') != 1) {
            return redirect('/login');
        }
        return view('items.misofficeinventory', compact('items'));
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
            'item_name' => 'required|string|max:255',
            'propertynum' => 'required|string|max:255',
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

        $officeItemData = $validated;
        unset($officeItemData['fixed_date']); // not stored on the main table

        $officeItem = MisOfficeInventory::create($officeItemData);

        if ($validated['condition'] === 'Usable') {
            \App\Models\InventoryUsableNotesHistory::create([
                'inventory_id' => $officeItem->id,
                'usable_notes' => $validated['usable_notes'],
                'fixed_date' => $validated['fixed_date'],
            ]);
        }

        return redirect()->route('officeinventory')
                         ->with('success', 'Inventory item added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /** 
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MisOfficeInventory $officeitem)
    {
        // Keep a copy of the previous notes so we can detect changes after saving.
        $oldNotes = $officeitem->usable_notes;
        
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'propertynum' => 'required|string|max:255',
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
                function ($attribute, $value, $fail) use ($request, $officeitem) {
                    // Only require `fixed_date` if the user is actually changing usable_notes.
                    // This avoids forcing a date when condition is Usable but notes are unchanged.
                    if ($request->input('condition') === 'Usable') {
                        $oldNotes = $officeitem->usable_notes;
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

        $officeItemData = $validated;
        unset($officeItemData['fixed_date']); // not stored on the main table

            // ✅ Log the location change (only if location actually changed)
        if ($officeitem->location !== $validated['location']) {
            \App\Models\InventoryLocationHistory::create([
                'inventory_id' => $officeitem->id,
                'old_location' => $officeitem->location,
                'new_location' => $validated['location'],
                'changed_at' => now(),
            ]);
        }

        $officeitem->update($officeItemData);

        $notesChanged = ($validated['condition'] === 'Usable')
            && (($validated['usable_notes'] ?? null) !== ($oldNotes ?? null));

        if ($notesChanged) {
            \App\Models\InventoryUsableNotesHistory::create([
                'inventory_id' => $officeitem->id,
                'usable_notes' => $validated['usable_notes'],
                'fixed_date' => $validated['fixed_date'],
            ]);
        }

        return redirect()->route('officeinventory')
                         ->with('success', 'Inventory item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MisOfficeInventory $officeitem)
    {
        $officeitem->delete();

        return redirect()->route('officeinventory')
                         ->with('success', 'Inventory item deleted successfully.');
    }
    public function export(): StreamedResponse
    {

        $filename = 'MIS Office Inventory Data_' . date('Y-m-d_H-i-s') . '.csv';
        $items = MisOfficeInventory::all();

        $headers = [
            'Content-Type' => 'text/csv',
            'Cache-Control' => 'no-store, no-cache, must-revalidate',
            'Pragma' => 'no-cache',
        ];

        $columns = [
            'Item Name', 'Property No.', 'Category', 'Item Set', 'Location',
            'Description', 'Condition', 'Date Purchased'
        ];

        $callback = function() use ($items, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($items as $item) {
                fputcsv($file, [
                    $item->item_name,
                    $item->propertynum,
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
