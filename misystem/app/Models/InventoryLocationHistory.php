<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryLocationHistory extends Model
{
    protected $fillable = [
        'inventory_id',
        'old_location',
        'new_location',
        'changed_at',
    ];

    public function inventory()
    {
        return $this->belongsTo(MisOfficeInventory::class, 'inventory_id');
    }
}
