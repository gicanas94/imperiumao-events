<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('records', function (Blueprint $table) {
            $table->increments('id');
            $table->string('event_id');
            $table->integer('user_id');
            $table->integer('from_record')->nullable();
            $table->integer('server');
            $table->string('participants');
            $table->integer('drop');
            $table->string('levels');
            $table->string('inscription');
            $table->string('maps');
            $table->string('organizers');
            $table->string('winners')->nullable();
            $table->string('prizes')->nullable();
            $table->string('comments')->nullable();
            $table->integer('organizes')->nullable();
            $table->integer('suspended')->nullable();
            $table->integer('finished')->nullable();
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
        Schema::dropIfExists('records');
    }
}
