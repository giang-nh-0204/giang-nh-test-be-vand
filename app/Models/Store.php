<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin Builder
 */
class Store extends Model
{
    protected $with = ['products'];

    public $timestamps = false;

    protected $table = 'stores';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'user_id',
        'name',
        'description',
        'address',
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

    public function products(): HasMany
    {
        return $this->hasMany('App\Models\Product', 'store_id', 'id')
                    ->where('products.status', 'active')
                    ->where('products.delete_flg', 0);
    }
}
