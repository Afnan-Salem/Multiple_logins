<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->string('fname');
            $table->string('surname');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('gender');
            $table->boolean('activated');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('orchestra_officers', function (Blueprint $table) {
            $table->integer('officer_id')->unsigned();
            $table->string('orchestra_name');
            $table->foreign('officer_id')
                ->references ('id')
                ->on ('users')
                ->onDelete('cascade');
        });
    }

}
