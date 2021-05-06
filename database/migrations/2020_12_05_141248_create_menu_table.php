<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu', function (Blueprint $table) {
            $table->id();
            $table->integer('pid')->default(0)->comment('父级id');
            $table->string('title', 45)->comment('标题');
            $table->string('icon', 45)->comment('图标');
            $table->string('href', 64)->nullable()->comment('链接url');
            $table->tinyInteger('type')->comment('类型 0、目录 1、菜单');
            $table->string('open_type')->default('_iframe')->comment('打开方式 _iframe|_blank');
            $table->smallInteger('level')->default(1)->comment('菜单层级');
            $table->tinyInteger('status')->default(1)->comment('状态 0、删除 1、启用 2、禁用');

            $table->smallInteger('sort')->comment('排序 asc');
            $table->timestamps();

            $table->index('pid');
        });
        $connection = config('database.default');
        $prefix = config("database.connections.{$connection}.prefix");
        DB::statement("alter table `{$prefix}menu` comment '菜单表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu');
    }
}
