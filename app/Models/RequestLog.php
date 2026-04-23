<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestLog extends Model
{
    protected $fillable = ['request_type', 'item_name', 'inventory_item_id', 'requester_name', 'borrowed_at', 'location', 'reason', 'material','time_from', 'time_to', 'status', 'level', 'department'];
    //
    // App\Models\RequestLog.php
    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id');
    }

}
