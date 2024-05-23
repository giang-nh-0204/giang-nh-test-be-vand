<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

/**
 * @mixin Builder
 */
class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $with = ['stores'];

    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';
    /**
     * The primary key associated with the table.e
     *
     * @var string
     */
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'email',
        'password',
        'name',
        'role',
        'status',
        'create_at',
        'update_at',
        'delete_flg'
    ];

    protected $hidden = array(
        'password',
        'create_at',
        'update_at',
        'delete_flg'
    );

    public function stores(): HasMany
    {
        return $this->hasMany('App\Models\Store', 'user_id', 'id')
                    ->where('stores.status', 'active')
                    ->where('stores.delete_flg', 0);
    }
}
