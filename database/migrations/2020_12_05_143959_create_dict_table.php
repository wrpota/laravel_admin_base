<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDictTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dict', function (Blueprint $table) {
            $table->id();
            $table->string('code', 45);
            $table->string('name', 45)->comment('字典名称');
            $table->tinyInteger('status')->default(1)->comment('状态 0删除 1启用 2禁用');
            $table->index('code');
        });
        $connection = config('database.default');
        $prefix = config("database.connections.{$connection}.prefix");
        DB::statement("alter table `{$prefix}dict` comment '字典'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dict');
    }
}
