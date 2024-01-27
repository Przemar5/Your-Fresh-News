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
        $categories = Category::all();
        $tags = Tag::all();
        $users = User::all();

        $fileContent = file_get_contents(__DIR__ . '/articles.txt');
        $articlesData = explode("\n\n\n\n", $fileContent);
        $articlesData = array_map(function ($a) {
            $data = explode("\n\n\n", $a);
            $additionalData = explode("\n", $data[2]);
            $result = [
                'title' => $data[0],
                'body' => $data[1],
                'tags' => [],
                'categories' => [],
                'user' => null,
                'photo' => null,
            ];
            foreach ($additionalData as $line) {
                [$field, $value] = explode(": ", $line);
                $result[$field] = is_array($result[$field]) ? explode(", ", $value) : $value;
            }
            
            return $result;

        }, $articlesData);

        try {
            DB::connection()->beginTransaction();

            for ($i = 0; $i < count($articlesData); $i++) {
                $articleData = $articlesData[$i];
                $selectedTags = $tags->whereIn('name', $articleData['tags'])->modelKeys();
                $selectedCategories = $categories->whereIn('name', $articleData['categories'])->modelKeys();
                $user = $users->where('login', $articleData['user'])->first();

                if (!$user) {
                    throw new \Exception('Missing user: ' . $articleData['user']);
                }

                $article = $this->generateArticle(
                    $articleData['title'], 
                    $articleData['body'], 
                    $user
                );

                if (!$article->save()) {
                    throw new \Exception('Cannot save article');
                }

                $image = ImageFactory::createArticleCoverImage($articleData['photo']);
                if (!$image->save()) {
                    throw new \Exception('Cannot save image');
                }

                $article->image()->sync($image->id, true);
                $article->categories()->sync($selectedCategories);
                $article->tags()->sync($selectedTags, true);

                if (!$article->save()) {
                    throw new \Exception('Cannot save article');
                }

                if ($i > 0 && $i % 20 === 0) {
                    DB::connection()->commit();
                    DB::connection()->beginTransaction();
                }
            }

            DB::connection()->commit();

        } catch (\Exception $e) {
            DB::connection()->rollBack();

            dd($e->getMessage() . ': ' . $e->getLine());
        }
    }

    private function generateArticle(string $title, string $body, User $author)
    {
        $slug = str_replace(['-', ',', "'", ':', '"'], '', strtolower($title));
        $slug = str_replace('  ', ' ', $slug);
        $slug = str_replace(' ', '-', $slug);

        $article = new Article();
        $article->title = $title;
        $article->slug = $slug;
        $article->body = $body;
        $article->user_id = $author->id;

        return $article;
    }
}
