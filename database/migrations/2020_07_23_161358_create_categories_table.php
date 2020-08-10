<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->timestamps();
            $table->softDeletes();
        });

        DB::table('categories')->insert([
            [
                'name' => 'economy',
                'slug' => 'economy'
            ],
            [
                'name' => 'politics',
                'slug' => 'politics'
            ],
            [
                'name' => 'Europe',
                'slug' => 'europe'
            ],
            [
                'name' => 'EU',
                'slug' => 'eu'
            ],
            [
                'name' => 'US',
                'slug' => 'us'
            ],
            [
                'name' => 'world',
                'slug' => 'world'
            ],
            [
                'name' => 'ecology',
                'slug' => 'ecology'
            ],
            [
                'name' => 'culture',
                'slug' => 'culture'
            ],
            [
                'name' => 'science',
                'slug' => 'science'
            ],
            [
                'name' => 'technology',
                'slug' => 'technology'
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
