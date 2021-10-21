<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDisasterhelpersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disasterhelpers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('skill');
            $table->bigInteger('mobile');
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
        Schema::dropIfExists('disasterhelpers');
    }
}
