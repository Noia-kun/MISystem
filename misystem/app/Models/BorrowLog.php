<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BorrowLog extends Model
{
    protected $fillable = ['id', 'item_name', 'inventory_item_id', 'borrower_name', 'borrowed_at', 'time_from', 'time_to'];
    protected $casts = [
    'borrowed_at' => 'datetime',];

    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id');
    }
    protected $dates = ['borrowed_at', 'returned_date'];

}
