<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();

            $table->string('role');
            $table->string('ordernumber')->nullable();
            $table->string('village')->nullable();
            $table->bigInteger('mobile')->nullable();
            $table->string('address')->nullable();
            $table->date('joindate')->nullable();
            $table->date('enddate')->nullable();
            $table->integer('psdistance')->nullable();
            $table->string('photo')->nullable();
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->unsignedBigInteger('psid')->nullable();
            $table->foreign('psid')->references('id')->on('policestations');
            $table->text('taluka')->nullable();
            $table->text('dangerzone')->nullable();

            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
