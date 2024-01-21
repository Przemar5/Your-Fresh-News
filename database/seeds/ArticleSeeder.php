<?php

use App\Article;
use App\User;
use App\Category;
use App\Tag;
use App\Factories\ImageFactory;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use Faker\Factory as FakerFactory;

class ArticleSeeder extends Seeder
{
    private Faker $faker;

    public function __construct()
    {
        $this->faker = FakerFactory::create();
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 60; $i++) {
            $article = $this->generateRandomArticleData();
            if (!$article->save()) {
                continue;
            }

            $image = ImageFactory::createDefaultArticleCoverImage();
            if (!$image->save()) {
                continue;
            }

            $article->image()->sync($image->id, true);

            for ($j = 0; $j < rand(1, 3); $j++) {
                $article->categories()->attach(Category::all()->random()->id);
            }
            for ($j = 0; $j < rand(0, 3); $j++) {
                $article->tags()->attach(Tag::all()->random()->id);
            }

            $article->save();
        }
    }

    private function generateRandomArticleData()
    {
        $title = implode(' ', $this->faker->unique()->words(rand(3, 7), false));
        $slug = urlencode(str_replace(' ', '-', lcfirst($title)));

        $article = new Article();
        $article->title = $title;
        $article->slug = $slug;
        $article->body = $this->faker->realText(rand(600, 1800), 2);
        $article->user_id = User::all()->random()->id;

        return $article;
    }
}
