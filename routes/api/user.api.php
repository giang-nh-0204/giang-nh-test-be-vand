<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::group([
                 'prefix'    => '',
                 'namespace' => 'User',
             ],
    function () {
        // Đăng nhập
        Route::post('/login', [UserController::class, 'login']);

        // Đăng xuất
        Route::post('/logout', [UserController::class, 'logout'])->middleware('auth:api');
    }
);

Route::group([
                 'prefix'     => 'user',
                 'middleware' => ['auth:api'],
                 'namespace'  => 'User',
             ],
    function () {
        // Cập nhật trạng thái
        Route::put('/status/{userId}', [UserController::class, 'updateStatus']);
    }
);
