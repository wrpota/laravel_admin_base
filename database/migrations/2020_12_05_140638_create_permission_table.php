<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permission', function (Blueprint $table) {
            $table->id();
            $table->integer('pid')->default(0)->comment('父级id');
            $table->string('name', 45)->comment('权限名');
            $table->string('identification', 45)->comment('权限标识');
            $table->string('remark')->default('')->comment('备注');
            $table->smallInteger('level')->default(1)->comment('权限层级');
            $table->tinyInteger('status')->default(1)->comment('状态 0、删除 1、启用 2、禁用');
            $table->smallInteger('sort')->comment('排序');
            $table->timestamps();

            $table->index('pid');
        });
        $connection = config('database.default');
        $prefix = config("database.connections.{$connection}.prefix");
        DB::statement("alter table `{$prefix}permission` comment '权限表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permission');
    }
}
