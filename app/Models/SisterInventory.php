<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SisterInventory extends Model
{
    protected $table = 'sister_inventory';
    
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

    public function locationHistories()
    {
        return $this->hasMany(SisterInventoryLocationHistory::class, 'inventory_id');
    }

    public function usableNotesHistories()
    {
        return $this->hasMany(SisterInventoryUsableNotesHistory::class, 'inventory_id');
    }
}
