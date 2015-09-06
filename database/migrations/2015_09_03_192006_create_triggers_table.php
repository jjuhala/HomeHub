<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('triggers', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('name');
            $table->string('control_id');
            $table->string('limit');
            $table->enum('operand', ['=', '>', '<', '%']);
            $table->boolean('timed');
            $table->boolean('only_once');
            $table->integer('frequency');
            $table->string('action_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('triggers');
    }
}
