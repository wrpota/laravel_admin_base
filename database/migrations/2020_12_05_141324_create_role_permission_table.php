<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolePermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_permission', function (Blueprint $table) {
            $table->id();
            $table->integer('role_id');
            $table->integer('permission_id');

            $table->index('role_id');
            $table->index('permission_id');
        });
        $connection = config('database.default');
        $prefix = config("database.connections.{$connection}.prefix");
        DB::statement("alter table `{$prefix}role_permission` comment '角色权限'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_permission');
    }
}
