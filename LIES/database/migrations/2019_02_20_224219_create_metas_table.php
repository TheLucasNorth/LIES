<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('metas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('value')->nullable();
            $table->timestamps();
        });

        DB::table('metas')->insert(
            ['id' => '1', 'value' => 'LIES']);
        DB::table('metas')->insert(
            ['id' => '2', 'value' => '/privacy']);
        DB::table('metas')->insert(
            ['id' => '3', 'value' => 'This imprint can be removed or updated in the admin panel']);
        DB::table('metas')->insert(
            ['id' => '4', 'value' => 'Sample Privacy Policy can be updated in the admin interface, under system data']);

        DB::table('metas')->insert(
            ['id' => '5', 'value' => 1]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('metas');
    }
}
