<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;

class OrderGuest extends Model
{
    protected $table = 'order_guests';
    protected $primaryKey = 'id';
}
