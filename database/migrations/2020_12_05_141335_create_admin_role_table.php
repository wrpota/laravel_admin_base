<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_role', function (Blueprint $table) {
            $table->id();
            $table->integer('admin_id');
            $table->integer('role_id');

            $table->index('admin_id');
            $table->index('role_id');
        });
        $connection = config('database.default');
        $prefix = config("database.connections.{$connection}.prefix");
        DB::statement("alter table `{$prefix}admin_role` comment '用户角色'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_role');
    }
}
