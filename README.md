# giang-nh-test-be-vand

Thông tin hệ thống api xác thực người dùng và quản lý cửa hàng - sản phẩm (Phần B. Application bài test back end VAND):

- Môi trường:
    - PHP 7.4.29
    - Laravel Framework 8.83.27
    - MySQL 8.0.29

- Cài đặt:
    - Cập nhật file .env:

        ```
        DB_DATABASE=your_db
        DB_USERNAME=your_db_username
        DB_PASSWORD=your_db_password
        ```

    - Tại thư mục dự án chạy lệnh:
        `composer install` để cài đặt package
        `php artisan migrate --seed` để tạo các bảng và dữ liệu giả
        `php artisan passport:install --force` để tạo ra các khóa bảo mật cần thiết của Laravel Passport
        `php artisan serve` chạy server

- Danh sách api:
    * User:
        - Đăng nhập:
            + PATH: `/api/login`
            + Method: POST
            + Query: email, password
            + Headers:

        - Đăng xuất:
            + PATH: `/api/logout`
            + Method: POST
            + Query:
            + Headers: Authorization Bearer your_token

        - Cập nhật trạng thái tài khoản:
            + PATH: `/api/user/status/{userId}`
            + Method: PUT
            + Query: status
            + Headers: Authorization Bearer your_token

    * Store:
        - Tạo cửa hàng:
            + PATH: `/api/user/store`
            + Method: POST
            + Query: user_id, name, description, address
            + Headers: Authorization Bearer your_token

        - Cập nhật thông tin cửa hàng:
            + PATH: `/api/user/store/{storeId}`
            + Method: PUT
            + Query: name, description, address
            + Headers: Authorization Bearer your_token

        - Cập nhật trạng thái cửa hàng:
            + PATH: `/api/user/store/status/{storeId}`
            + Method: PUT
            + Query: status
            + Headers: Authorization Bearer your_token

        - Xóa cửa hàng:
            + PATH: `/api/user/store/{storeId}`
            + Method: DELETE
            + Query:
            + Headers: Authorization Bearer your_token

        - Lấy danh sách cửa hàng theo người dùng:
            + PATH: `/api/user/store`
            + Method: GET
            + Query: user_id, limit, page
            + Headers: Authorization Bearer your_token

        - Tìm cửa hàng:
            + PATH: `/api/user/store/search`
            + Method: GET
            + Query: keyword, limit, page
            + Headers: Authorization Bearer your_token

        - Chi tiết cửa hàng:
            + PATH: `/api/us er/store/{storeId}`
            + Method: GET
            + Query:
            + Headers: Authorization Bearer your_token

    * Product:
        - Tạo sản phẩm:
            + PATH: `/api/user/store/product`
            + Method: POST
            + Query: store_id, name, description, price, quantity
            + Headers: Authorization Bearer your_token

        - Cập nhật thông tin sản phẩm:
            + PATH: `/api/user/store/product/{productId}`
            + Method: PUT
            + Query: name, description, price, quantity
            + Headers: Authorization Bearer your_token

        - Cập nhật trạng thái sản phẩm:
            + PATH: `/api/user/store/product/status/{productId}`
            + Method: PUT
            + Query: status
            + Headers: Authorization Bearer your_token

        - Xóa sản phẩm:
            + PATH: `/api/user/store/product/{productId}`
            + Method: DELETE
            + Query:
            + Headers: Authorization Bearer your_token

        - Lấy danh sách sản phẩm theo cửa hàng:
            + PATH: `/api/user/store/product`
            + Method: GET
            + Query: store_id, limit, page
            + Headers: Authorization Bearer your_token

        - Tìm sản phẩm:
            + PATH: `/api/user/store/product/search`
            + Method: GET
            + Query: keyword, limit, page
            + Headers: Authorization Bearer your_token

        - Chi tiết sản phẩm:
            + PATH: `/api/user/store/{productId}`
            + Method: GET
            + Query:
            + Headers: Authorization Bearer your_token
