<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWatchregistersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('watchregisters', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('name');
            $table->bigInteger('mobile')->nullable();
            $table->text('tadipar_area')->nullable();
            $table->date('tadipar_date')->nullable();
            $table->text('photo')->nullable();
            $table->text('aadhar')->nullable();
            $table->text('address')->nullable();
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->text('description')->nullable();
            $table->text('otherphoto')->nullable();
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
        Schema::dropIfExists('watchregisters');
    }
}
