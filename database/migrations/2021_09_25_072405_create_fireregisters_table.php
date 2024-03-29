<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFireregistersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fireregisters', function (Blueprint $table) {
            $table->id();
            $table->string('address');
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->date('date');
            $table->time('time')->nullable();
            $table->string('reason')->nullable();
            $table->string('loss')->nullable();
            $table->text('photo')->nullable();
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
        Schema::dropIfExists('fireregisters');
    }
}
