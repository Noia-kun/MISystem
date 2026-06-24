<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SisterInventoryLocationHistory extends Model
{
    protected $table = 'sister_inventory_location_histories';

    protected $fillable = [
        'inventory_id',
        'old_location',
        'new_location',
        'changed_at',
    ];

    public function inventory()
    {
        return $this->belongsTo(SisterInventory::class, 'inventory_id');
    }
}
