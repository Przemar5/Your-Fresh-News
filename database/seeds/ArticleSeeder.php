<?php

use App\Article;
use App\User;
use App\Category;
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
            $articleId = DB::table('articles')->insertGetId($this->generateRandomArticleData());
            $lastArticle = Article::find($articleId);
            for ($j = 0; $j < rand(1, 3); $j++) {
                $lastArticle->categories()->attach(Category::all()->random()->id);
            }
            $lastArticle->save();
        }
    }

    private function generateRandomArticleData()
    {
        $title = implode(' ', $this->faker->unique()->words(rand(3, 7), false));
        $slug = urlencode(str_replace(' ', '-', lcfirst($title)));

        return [
            'title' => $title,
            'slug' => $slug,
            'body' => $this->faker->realText(rand(600, 1800), 2),
            'user_id' => User::all()->random()->id,
        ];
    }
}
