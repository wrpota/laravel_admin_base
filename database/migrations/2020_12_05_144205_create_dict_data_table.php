<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDictDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dict_data', function (Blueprint $table) {
            $table->id();
            $table->integer('dict_id');
            $table->string('name', 45)->comment('名称');
            $table->string('value')->comment('值');
            $table->tinyInteger('status')->default(1)->comment('状态 0删除 1启用 2禁用');
            $table->timestamps();

            $table->index('dict_id');
        });
        $connection = config('database.default');
        $prefix = config("database.connections.{$connection}.prefix");
        DB::statement("alter table `{$prefix}dict_data` comment '字典值'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dict_data');
    }
}
