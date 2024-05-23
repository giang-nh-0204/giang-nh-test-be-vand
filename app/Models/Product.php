<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Builder
 */
class Product extends Model
{
    public $timestamps = false;

    protected $table = 'products';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'store_id',
        'name',
        'description',
        'price',
        'quantity',
        'status',
        'create_at',
        'update_at',
        'delete_flg',
    ];

    protected $hidden = [
        'create_at',
        'update_at',
        'delete_flg'
    ];
}
