<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email', 255)->collation('utf8mb4_unicode_ci')->unique();
            $table->string('password', 255)->collation('utf8mb4_unicode_ci');
            $table->string('name', 255)->collation('utf8mb4_unicode_ci');
            $table->string('role', 45)->collation('utf8mb4_unicode_ci')->default('user')->comment('admin, user');
            $table->string('status', 45)->collation('utf8mb4_unicode_ci')->default('inactive')->comment('active, inactive');
            $table->timestamp('created_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->integer('delete_flg')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}

