<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePublicplaceregistersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('publicplaceregisters', function (Blueprint $table) {
            $table->id();
            $table->string('place');
            $table->string('address');
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->text('photo')->nullable();
            $table->boolean('iscctv')->nullable();
            $table->boolean('isissue')->nullable();
            $table->string('issuereason')->nullable();
            $table->string('issuecondition')->nullable();
            $table->boolean('crimeregistered')->nullable();
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
        Schema::dropIfExists('publicplaceregisters');
    }
}
