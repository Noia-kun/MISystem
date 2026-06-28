<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MisOfficeInventory extends Model
{
    protected $table = 'mis_office_inventory';
    
    protected $fillable = [
        'item_name',
        'propertynum',
        'serialnum',
        'category',
        'item_set',
        'location',
        'description',
        'condition',
        'usable_notes',
        'date_purchased',
    ];
    // ✅ Relationship to location history
    public function locationHistories()
    {
        return $this->hasMany(InventoryLocationHistory::class, 'inventory_id');
    }

    // ✅ Relationship to usable notes history
    public function usableNotesHistories()
    {
        return $this->hasMany(InventoryUsableNotesHistory::class, 'inventory_id');
    }

}
