<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->after('email');
            $table->bigInteger('mobile')->after('role')->nullable();
            $table->string('village')->after('mobile')->nullable();
            $table->text('address')->after('village')->nullable();
            $table->date('joindate')->after('address')->nullable();
            $table->date('enddate')->after('joindate')->nullable();
            $table->double('psdistance')->after('enddate')->nullable();
            $table->text('photo')->after('psdistance')->nullable();
            $table->double('latitude')->after('photo')->nullable();
            $table->double('longitude')->after('latitude')->nullable();
            $table->unsignedBigInteger('psid')->after('longitude')->nullable();
            $table->foreign('psid')->references('id')->on('policestation');
            $table->text('taluka')->after('psid')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
