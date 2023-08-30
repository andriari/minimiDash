<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'minimi_product_variant';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'variant_id';
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'updated_at' => 'date:d M Y',
    ];
}
