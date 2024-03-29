<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIllegalworkregistersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('illegalworkregisters', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('name');
            $table->text('photo')->nullable();
            $table->text('address')->nullable();
            $table->string('vehicle_no')->nullable();
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->unsignedBigInteger('ppid');
            $table->foreign('ppid')->references('id')->on('users');
            $table->unsignedBigInteger('psid');
            $table->foreign('psid')->references('id')->on('policestations');
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
        Schema::dropIfExists('illegalworkregisters');
    }
}
