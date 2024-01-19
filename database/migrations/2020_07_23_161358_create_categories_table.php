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
            $table->foreignId('parent_id')->nullable()->constrained('categories')->default(null);
            $table->integer('index_in_parent_category')->nullable()->default(null);
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['parent_id', 'index_in_parent_category']);
        });

        DB::table('categories')->insert([
            [
                'name' => 'Topics',
                'slug' => 'topics',
            ],
            [
                'name' => 'Places',
                'slug' => 'places',
            ],
        ]);

        $topicsCategoryId = DB::table('categories')
            ->select('id')
            ->where(['slug' => 'topics'])
            ->first()
            ->id;
        
        $placesCategoryId = DB::table('categories')
            ->select('id')
            ->where(['slug' => 'places'])
            ->first()
            ->id;

        $topicsIndex = 1;
        $placesIndex = 1;

        DB::table('categories')->insert([
            [
                'name' => 'politics',
                'slug' => 'politics',
                'parent_id' => $topicsCategoryId,
                'index_in_parent_category' => $topicsIndex++
            ],
            [
                'name' => 'economy',
                'slug' => 'economy',
                'parent_id' => $topicsCategoryId,
                'index_in_parent_category' => $topicsIndex++
            ],
            [
                'name' => 'EU',
                'slug' => 'eu',
                'parent_id' => $topicsCategoryId,
                'index_in_parent_category' => $topicsIndex++
            ],
            [
                'name' => 'US',
                'slug' => 'us',
                'parent_id' => $topicsCategoryId,
                'index_in_parent_category' => $topicsIndex++
            ],
            [
                'name' => 'science',
                'slug' => 'science',
                'parent_id' => $topicsCategoryId,
                'index_in_parent_category' => $topicsIndex++
            ],
            [
                'name' => 'technology',
                'slug' => 'technology',
                'parent_id' => $topicsCategoryId,
                'index_in_parent_category' => $topicsIndex++
            ],
            [
                'name' => 'ecology',
                'slug' => 'ecology',
                'parent_id' => $topicsCategoryId,
                'index_in_parent_category' => $topicsIndex++
            ],
            [
                'name' => 'LGBTQ+',
                'slug' => 'lgbt',
                'parent_id' => $topicsCategoryId,
                'index_in_parent_category' => $topicsIndex++
            ],
            [
                'name' => 'culture',
                'slug' => 'culture',
                'parent_id' => $topicsCategoryId,
                'index_in_parent_category' => $topicsIndex++
            ],
            [
                'name' => 'society',
                'slug' => 'society',
                'parent_id' => $topicsCategoryId,
                'index_in_parent_category' => $topicsIndex++
            ],
            [
                'name' => 'stars',
                'slug' => 'stars',
                'parent_id' => $topicsCategoryId,
                'index_in_parent_category' => $topicsIndex++
            ],
            [
                'name' => 'movies',
                'slug' => 'movies',
                'parent_id' => $topicsCategoryId,
                'index_in_parent_category' => $topicsIndex++
            ],
            [
                'name' => 'music',
                'slug' => 'music',
                'parent_id' => $topicsCategoryId,
                'index_in_parent_category' => $topicsIndex++
            ],
            [
                'name' => 'religion',
                'slug' => 'religion',
                'parent_id' => $topicsCategoryId,
                'index_in_parent_category' => $topicsIndex++
            ],
            [
                'name' => 'refugees',
                'slug' => 'refugees',
                'parent_id' => $topicsCategoryId,
                'index_in_parent_category' => $topicsIndex++
            ],
            [
                'name' => 'world',
                'slug' => 'world',
                'parent_id' => $placesCategoryId,
                'index_in_parent_category' => $placesIndex++
            ],
            [
                'name' => 'Europe',
                'slug' => 'europe',
                'parent_id' => $placesCategoryId,
                'index_in_parent_category' => $placesIndex++
            ],
            [
                'name' => 'North America',
                'slug' => 'north-america',
                'parent_id' => $placesCategoryId,
                'index_in_parent_category' => $placesIndex++
            ],
            [
                'name' => 'Asia',
                'slug' => 'asia',
                'parent_id' => $placesCategoryId,
                'index_in_parent_category' => $placesIndex++
            ],
            [
                'name' => 'Asia Minor',
                'slug' => 'asia-minor',
                'parent_id' => $placesCategoryId,
                'index_in_parent_category' => $placesIndex++
            ],
            [
                'name' => 'Latin America',
                'slug' => 'latin-america',
                'parent_id' => $placesCategoryId,
                'index_in_parent_category' => $placesIndex++
            ],
            [
                'name' => 'Africa',
                'slug' => 'africa',
                'parent_id' => $placesCategoryId,
                'index_in_parent_category' => $placesIndex++
            ],
            [
                'name' => 'Australia',
                'slug' => 'australia',
                'parent_id' => $placesCategoryId,
                'index_in_parent_category' => $placesIndex++
            ],
            [
                'name' => 'Antarctica',
                'slug' => 'antarctica',
                'parent_id' => $placesCategoryId,
                'index_in_parent_category' => $placesIndex++
            ],
            [
                'name' => 'Moon',
                'slug' => 'moon',
                'parent_id' => $placesCategoryId,
                'index_in_parent_category' => $placesIndex++
            ],
            [
                'name' => 'space',
                'slug' => 'space',
                'parent_id' => $placesCategoryId,
                'index_in_parent_category' => $placesIndex++
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
