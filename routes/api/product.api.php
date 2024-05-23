<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::group([
                 'prefix'     => 'user/store/product',
                 'middleware' => ['check.auth.header', 'auth:api'],
                 'namespace'  => 'Product',
             ],
    function () {
        // Tạo sản phẩm
        Route::post('/', [ProductController::class, 'create']);

        // Cập nhật thông tin sản phẩm
        Route::put('/{productId}', [ProductController::class, 'update']);

        // Cập nhật trạng thái sản phẩm
        Route::put('/status/{productId}', [ProductController::class, 'updateStatus']);

        // Xóa sản phẩm
        Route::delete('/{productId}', [ProductController::class, 'delete']);

        // Lấy danh sách sản phẩm theo cửa hàng
        Route::get('/', [ProductController::class, 'getProducts']);

        // Tìm sản phẩm đã active
        Route::get('/search', [ProductController::class, 'search']);

        // Lấy chi tiết sản phẩm
        Route::get('/{productId}', [ProductController::class, 'getDetail']);

    }
);
