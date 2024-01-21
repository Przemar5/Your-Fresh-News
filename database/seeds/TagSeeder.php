<?php

use App\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tags = [
            'economy',
            'politics',
            'Europe',
            'EU',
            'USA',
            'world',
            'ecology',
            'protest',
            'culture',
            'science',
            'technology',
            'space',
        ];

        foreach ($tags as $tag) {
            $this->createTag($tag);
        }
    }

    private function createTag(?string $name = null): void
    {
        $tag = new Tag();
        $tag->name = $name;
        $tag->save();
    }
}
