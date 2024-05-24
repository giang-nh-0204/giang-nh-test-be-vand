<?php

use App\Http\Controllers\StoreController;
use Illuminate\Support\Facades\Route;

Route::group([
                 'prefix'     => 'user/store',
                 'middleware' => ['auth:api'],
                 'namespace'  => 'Store',
             ],
    function () {
        // Tạo cửa hàng
        Route::post('/', [StoreController::class, 'create']);

        // Cập nhật thông tin cửa hàng
        Route::put('/{storeId}', [StoreController::class, 'update']);

        // Cập nhật trạng thái cửa hàng
        Route::put('/status/{storeId}', [StoreController::class, 'updateStatus']);

        // Xóa cửa hàng
        Route::delete('/{storeId}', [StoreController::class, 'delete']);

        // Lấy danh sách cửa hàng theo user
        Route::get('/', [StoreController::class, 'getStores']);

        // Tìm cửa hàng đã active
        Route::get('/search', [StoreController::class, 'search']);

        // Lấy chi tiết cửa hàng
        Route::get('/{storeId}', [StoreController::class, 'getDetail']);
    }
);
