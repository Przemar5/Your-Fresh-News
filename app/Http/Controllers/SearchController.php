<?php

namespace App\Http\Controllers;

use App\Article;
use App\Category;
use App\Tag;
use App\User;
use App\Handlers\PhraseSearcher;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;
// use App\Support\Collection as PaginableCollection;

class SearchController extends Controller
{
    /**
     * Display resources searched by phrase, writer, categories and tags.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phrase' => [
                'nullable',
            ],
            'writer' => [
                'nullable',
                'regex:[\w\d\-]',
                'exists:users,id',
            ],
            'categories' => [
                'nullable',
                'array',
                'exists:categories,id',
            ],
            'tags' => [
                'nullable',
                'array',
                'exists:tags,id',
            ],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $requestedPhrase = $request->input('phrase') ?? null;
        $requestedCategories = $request->input('categories') ?? null;
        $requestedTags = $request->input('tags') ?? null;
        $requestedWriter = $request->input('writer') ?? null;

        $categories = Category::orderBy('id', 'ASC')->get();
        $tags = Tag::orderBy('id', 'ASC')->get();
        $writers = User::writers()->sortBy('id');

        $results = new Collection();

        $articles = Article::whereHas('user', function ($query) use ($requestedWriter) {
                if (!empty($requestedWriter)) {
                    $query->where('user_id', $requestedWriter);
                }
            })
            ->whereHas('categories', function ($query) use ($requestedCategories) {
                if (!empty($requestedCategories)) {
                    $query->whereIn('category_id', $requestedCategories);
                }
            })
            ->whereHas('tags', function ($query) use ($requestedTags) {
                if (!empty($requestedTags)) {
                    $query->whereIn('tag_id', $requestedTags);
                }
            })
            ->orderBy('created_at', 'DESC')
            ->get();

        if (!empty($requestedPhrase)) {
            $searcher = new PhraseSearcher($requestedPhrase);

            foreach ($articles as $article) {
                $searcher->setArticle($article);

                if ($searcher->contains()) {
                    $searcher->prepareResult();
                    
                    $results->push($searcher->getArticle());
                }
            }
            
        } else {
            $results = $articles;
        }

        return view('pages.search')->with('articles', $results)
            ->with('categories', $categories)
            ->with('tags', $tags)
            ->with('writers', $writers)
            ->with('initialPhrase', $requestedPhrase)
            ->with('initialCategories', $requestedCategories)
            ->with('initialTags', $requestedTags)
            ->with('initialWriter', $requestedWriter);
    }
}
