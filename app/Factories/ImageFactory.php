<?php

namespace App\Factories;

use App\Article;
use App\Image;

class ImageFactory
{
	public static function createDefaultArticleCoverImage(): Image
	{
		$image = new Image();
        $image->path = Article::DEFAULT_COVER_IMAGE;
        $image->description = Article::DEFAULT_COVER_IMAGE_DESCRIPTION;
        $image->role = 'cover_image';

        return $image;
	}
}