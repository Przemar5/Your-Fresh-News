<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('title');
            $table->string('image_path');
            $table->integer('index');
            $table->timestamps();
            $table->softDeletes();
        });

        DB::table('ratings')->insert([
            [
                'type' => 'like',
                'title' => 'Like',
                'image_path' => 'like',
                'index' => 1,
            ],
            [
                'type' => 'dislike',
                'title' => 'Dislike',
                'image_path' => 'dislike',
                'index' => 2,
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ratings', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
