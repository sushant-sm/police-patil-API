<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArmsregistersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('armsregisters', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('name');
            $table->bigInteger('mobile');
            $table->text('aadhar');
            $table->text('address');
            $table->double('latitude');
            $table->double('longitude');
            $table->text('licencenumber');
            $table->date('validity');
            $table->text('licencephoto');
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
        Schema::dropIfExists('armsregisters');
    }
}
