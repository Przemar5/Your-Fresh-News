<?php

namespace App\Http\Controllers;

use App\Article;
use App\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Show the application home page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $newestArticles = Article::orderBy('created_at', 'DESC')->limit(13)->get();
        $mainCategories = ['world', 'economy'];
        $articles = new \stdClass();
        $categories = Category::all();

        foreach ($mainCategories as $category) {
            $articleCategories[] = Category::where('name', $category)->first();
            $articles->secondary[$category] = Article::byCategory($category)
                ->orderBy('created_at', 'DESC')->first();
        }

        $articles->primary = $newestArticles->slice(0, 5)->values()->all();
        $articles->ternary = $newestArticles->slice(5)->values()->all();

        return view('home')->with('articles', $articles)->with('articleCategories', $articleCategories)
            ->with('categories', $categories);
    }
}
