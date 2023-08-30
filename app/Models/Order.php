<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'commerce_booking';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'booking_id';
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'date:d M Y',
        'updated_at' => 'date:d M Y',
    ];
}
