<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
            $table->softDeletes();
        });

        DB::table('tags')->insert([
            ['name' => 'economy'],
            ['name' => 'politics'],
            ['name' => 'Europe'],
            ['name' => 'EU'],
            ['name' => 'USA'],
            ['name' => 'world'],
            ['name' => 'ecology'],
            ['name' => 'protest'],
            ['name' => 'culture'],
            ['name' => 'science'],
            ['name' => 'technology'],
            ['name' => 'space'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tags', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
