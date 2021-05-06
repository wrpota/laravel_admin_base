<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role', function (Blueprint $table) {
            $table->id();
            $table->string('name', 45)->comment('用户组名');
            $table->string('remark')->nullable()->comment('备注');
            $table->tinyInteger('status')->default(1)->comment('状态 0、删除 1、启用 2、禁用');
            $table->timestamps();
        });
        $connection = config('database.default');
        $prefix = config("database.connections.{$connection}.prefix");
        DB::statement("alter table `{$prefix}role` comment '角色表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role');
    }
}
