<?php

return [
    'httpStatusCode' => [
        '200' => 200,
        '201' => 201,
        '204' => 204,
        '205' => 205,
        '206' => 206,
        '400' => 400,
        '401' => 401,
        '403' => 403,
        '404' => 404,
        '500' => 500,
    ],
    'httpStatusText' => [
        '200' => 'OK',
        '201' => 'Created',
        '204' => 'No Content',
        '205' => 'Data already exists',
        '206' => 'No data exists',
        '400' => 'Bad Request',
        '401' => 'Unauthorized',
        '403' => 'Forbidden',
        '404' => 'Not Found',
        '500' => 'Internal Server Error',
    ],

    'message' => [
        'STATUS_UPDATED_ERROR' => 'Trạng thái đã cập nhật trước đó',
        'UPDATE_FAILED'        => 'Cập nhật không thành công',
        'UPDATED'              => 'Cập nhật thành công',
        'CREATED'              => 'Thêm thành công',
        'DELETE_FAILED'        => 'Xóa không thành công',
        'DELETED'              => 'Xóa thành công',

        'USER' => [
            'EMAIL_NOT_FOUND' => 'Không tìm thấy tài khoản với email này',
            'VALID_PASSWORD'  => 'Mật khẩu không đúng',
            'INACTIVE'        => 'Tài khoản này chưa được kích hoạt',
            'LOGOUT_SUCCESS'  => 'Đăng xuất thành công',
            'NOT_FOUND'       => 'Không tìm thấy người dùng'
        ],

        'STORE' => [
            'NOT_FOUND'   => 'Không tìm thấy cửa hàng',
            'NAME_EXISTS' => 'Tên cửa hàng đã tồn tại',
        ],

        'PRODUCT' => [
            'NOT_FOUND' => 'Không tìm thấy sản phẩm',
        ]
    ],
];
