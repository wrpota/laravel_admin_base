<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateAdminTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin', function (Blueprint $table) {
            $table->id();
            $table->string('username', 45);
            $table->string('password');
            $table->rememberToken();
            $table->tinyInteger('status')->default(1)->comment('账号状态 0、已删除 1、正常 2、禁用');
            $table->timestamps();
        });
        $connection = config('database.default');
        $prefix = config("database.connections.{$connection}.prefix");
        DB::statement("alter table `{$prefix}admin` comment '后台用户表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin');
    }
}
