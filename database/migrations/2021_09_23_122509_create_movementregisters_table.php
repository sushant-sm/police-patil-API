<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovementregistersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movementregisters', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('subtype');
            $table->text('address');
            $table->double('latitude');
            $table->double('longitude');
            $table->dateTime('datetime');
            $table->boolean('essue');
            $table->integer('attendance');
            $table->text('description');
            $table->text('photo');
            $table->unsignedBigInteger('ppid');
            $table->foreign('ppid')->references('id')->on('users');
            $table->unsignedBigInteger('psid');
            $table->foreign('psid')->references('id')->on('policestation');
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
        Schema::dropIfExists('movementregisters');
    }
}
