<?php

namespace App\Factories;

use App\Article;
use App\Image;

class ImageFactory
{
	public static function createDefaultArticleCoverImage(): Image
	{
		return static::createArticleCoverImage(
			Article::DEFAULT_COVER_IMAGE, 
			Article::DEFAULT_COVER_IMAGE_DESCRIPTION
		);
	}

	public static function createArticleCoverImage(?string $path = null, ?string $description = null): Image
	{
		$image = new Image();
        $image->path = $path ?? Article::DEFAULT_COVER_IMAGE;
        $image->description = $description ?? Article::DEFAULT_COVER_IMAGE_DESCRIPTION;
        $image->role = 'cover_image';

        return $image;
	}
}