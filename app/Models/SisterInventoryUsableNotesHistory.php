<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SisterInventoryUsableNotesHistory extends Model
{
    protected $table = 'sister_inventory_usable_notes_histories';

    protected $fillable = [
        'inventory_id',
        'usable_notes',
        'fixed_date',
        'changed_at',
    ];

    public function inventory()
    {
        return $this->belongsTo(SisterInventory::class, 'inventory_id');
    }
}
