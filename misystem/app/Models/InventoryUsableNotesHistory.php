<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryUsableNotesHistory extends Model
{
    protected $table = 'inventory_usable_notes_histories';

    protected $fillable = [
        'inventory_id',
        'usable_notes',
        'fixed_date',
        'changed_at',
    ];

    public function inventory()
    {
        return $this->belongsTo(MisOfficeInventory::class, 'inventory_id');
    }
}

