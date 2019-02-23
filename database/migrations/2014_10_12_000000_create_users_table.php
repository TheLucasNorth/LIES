<?php

use Illuminate\Support\Facades\Schema;
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
            $table->string('name')->nullable();
            $table->string('votingCode')->unique();
            $table->string('password');
            $table->string('passwordPlain')->nullable();
            $table->rememberToken();
            $table->boolean('hasVoted')->default(0);
            $table->boolean('admin')->default(0);
            $table->timestamps();
        });

        DB::table('users')->insert(
            ['id' => '1', 'name' => 'Default Admin', 'votingCode' => 'admin', 'password' => \Illuminate\Support\Facades\Hash::make('password'), 'admin' => 1]);
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
