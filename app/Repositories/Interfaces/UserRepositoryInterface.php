<?php

namespace App\Repositories\Interfaces;

use App\Models\User;
use App\Repositories\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param $email
     * @return User|Model|object|null
     */
    public function getByEmail($email);

    public function getDetail($id);
}
