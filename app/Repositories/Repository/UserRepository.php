<?php

namespace App\Repositories\Repository;

use App\Models\User;
use App\Repositories\BaseRepository;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    protected $instance;

    public function __construct(User $user)
    {
        parent::__construct($user);

        $this->instance = $user;
    }

    /**
     * @param $email
     * @return User|Model|object|null
     */
    public function getByEmail($email)
    {
        return $this->instance
            ->where('users.email', $email)
            ->where('users.delete_flg', 0)
            ->first();
    }

    /**
     * @param $id
     * @return User|Model|object|null
     */
    public function getDetail($id)
    {
        return $this->instance
            ->where('users.id', $id)
            ->where('users.status', 'active')
            ->where('users.delete_flg', 0)
            ->first();
    }
}
