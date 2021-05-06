<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
            $table->id();
            $table->string('username')->comment('用户名');
            $table->string('phone')->unique()->comment('手机号');
            $table->string('password')->comment('密码');
            $table->tinyInteger('status')->default(1)->comment('状态 0、已删除 1、正常 2、冻结');
            $table->string('email')->comment('邮箱');
            $table->string('wechat')->comment('微信号');
            $table->integer('sex')->comment('性别 0、未知 1、男 2、女');
            $table->integer('age')->comment('年龄');
            $table->rememberToken();
            $table->timestamps();
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
