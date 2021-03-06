<?php

namespace App\Http\Controllers;

use App\Article;
use App\Category;
use App\Comment;
use App\Image;
use App\Rating;
use App\Tag;
use App\User;
use App\Http\Controllers\ErrorController;
use App\Handlers\FileHandler;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ArticleController extends Controller
{
    private $pathToCoverImage = 'images/cover_images/';
    private $defaultImage = 'noimage.png';


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware([
            'auth',
            'writer',
            'verified',
        ])->except([
            'index',
            'show',
        ]);

        $this->middleware([
            'identity',
        ])->only([
            'edit',
            'update',
            'destroy',
        ]);
    }

    /**
     * Get a validator for an incoming tag create/edit request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data, $id = '')
    {
        if (!empty($id)) {
            $id = ',' . $id;
        }

        return Validator::make($data, [
            'title' => [
                'required',
                'between:15,255',
                'unique:articles,title' . $id,
                'regex:/^[\w\-\!@#$%&\*()+=|{}[\]:;,<>?\.\" ]+$/',
            ],
            'slug' => [
                'required',
                'between:15,255',
                'unique:articles,slug' . $id,
                'regex:/^[0-9a-zA-Z_\-]+$/'
            ],
            'categories' => [
                'required',
                'array',
                // 'between:1,11',
                'exists:categories,id'
            ],
            'body' => [
                'required',
                'min:15',
                // 'regex:/^[\w\-!@#$%&\*()+=|{}[\]:;,<>?\. ]+$/'
            ],
            'tags' => [
                'nullable',
                'array',
                // 'integer',
                'exists:tags,id'
            ],
            'cover_image' => [
                'nullable',
                'file',
                'image',
                // 'sometimes',
                // 'image',
                // 'mimes:jpg,jpeg,png,bmp,svg,gif',
                'max:20000',
            ],
            'description' => [
                'nullable',
                'max:255',
                'regex:/^[\w\-!@#$%&\*()+=|{}[\]:;,<>?\. ]+$/'
            ]
        ]);
    }

    /**
     * Store uploaded file.
     *
     * @param File $file
     * @param string $driver
     * @return string $filename
     */
    private function storeFile($file)
    {
        $filename = time() . '.' . $file->getClientOriginalExtension();
        $fullFilename = $this->pathToCoverImage . $filename;

        if (Storage::disk('assets')->put($fullFilename, file_get_contents($file))) {
            return $filename;

        } else {
            return false;
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articles = Article::orderBy('created_at', 'DESC')->paginate(10);
        $categories = Category::orderBy('id', 'ASC')->get();
        $tags = Tag::orderBy('id', 'ASC')->get();
        $writers = User::writers();

        return view('articles.index')
            ->with('articles', $articles)
            ->with('categories', $categories)
            ->with('tags', $tags)
            ->with('writers', $writers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all()->sortBy('id');
        $tags = Tag::all()->sortBy('id');

        return view('articles.create')->with([
            'categories' => $categories,
            'tags' => $tags
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            return back()->withErrors($validator->errors());
        }

        // Create article
        $article = new Article();
        $article->title = $request->input('title');
        $article->slug = $request->input('slug');
        $article->body = $request->input('body');
        $article->user_id = Auth::id();
        $saved = $article->save();

        if ($saved) {
            // Handle file upload
            if ($request->hasFile('cover_image')) {
                $file = $request->file('cover_image');

                if (!($filename = $this->storeFile($file))) {
                    return back()->with([
                        'error' => "Internal error. Cover image wasn't added."
                    ]);
                }
            
            } else {
                $filename = $this->defaultImage;
            }

            $image = new Image();
            $image->path = $filename;
            $image->description = $request->input('description') ?? 'Cover image';
            $image->role = 'cover_image';
            $stored = $image->save();

            if ($stored) {
                $imageId = DB::getPdo()->lastInsertId();

                $article->image()->sync($imageId, true);
                $article->categories()->sync($request->input('categories'), true);
                $article->tags()->sync($request->input('tags'), true);

                return redirect()->route('articles.show', $article->slug)->with([
                    'article' => $article,
                    'success' => 'New article was created successfully.'
                ]);

            } else {
                return redirect()->route('articles.show', $article->slug)->with([
                    'article' => $article,
                    'error' => "Internal error. New article was created but an image wasn't uploaded."
                ]);
            }

        } else {
            return back()->with([
                'article' => $article,
                'error' => "Internal error. Article wasn't created."
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $article = Article::where('slug', $slug)->first();
        $ratings = Rating::all()->sortBy('index');

        if (empty($article)) {
            return ErrorController::error404();
        }

        if ($image = $article->image()->first()) {
            $coverImage = Image::find($image->id);
            $filePath = 'images/cover_images/' . $coverImage->path;
            $coverImage->url = Storage::disk('assets')->url($filePath);
        }

        return view('articles.show')->with([
            'article' => $article,
            'coverImage' => $coverImage,
            'ratings' => $ratings,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        $article = Article::where('slug', $slug)->first();

        if (empty($article)) {
            return ErrorController::error404();
        }

        if (!Auth::user()->is('admin') && $article->id != Auth::user()->id) {
            return back();
        }

        $categories = Category::all()->sortBy('id');
        $tags = Tag::all()->sortBy('id');
        $articleTags = Tag::whereHas('articles')->get() ?? [];

        return view('articles.edit')->with([
            'article' => $article,
            'oldArticleTitle' => $article->title,
            'categories' => $categories,
            'tags' => $tags
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $article = Article::find($id);

        if (empty($article)) {
            return ErrorController::error404();
        }

        if (!auth::user()->is('admin') && $article->id != Auth::user()->id) {
            return back();
        }

        if (!empty($article)) {
            // Validate request
            $validator = $this->validator($request->all(), $id);

            $oldArticleTitle = $article->title;
            $article->title = $request->input('title');
            $article->slug = $request->input('slug');
            $article->body = $request->input('body');

            if ($validator->fails()) {
                echo $article->title;
                dd($validator->errors());
                die;
                return back()->withErrors($validator->errors())
                    ->with('article', $article)
                    ->with('oldArticleTitle', $oldArticleTitle);
            }

            $saved = $article->save();

            if ($saved) {
                // Handle file upload
                // If not specified, ignore
                if ($request->hasFile('cover_image')) {
                    $file = $request->file('cover_image');

                    if (!($filename = $this->storeFile($file, 'local'))) {
                        return back()->with([
                            'error' => "Internal error. Your article was uploaded but cover image wasn't uploaded."
                        ]);
                    }
                    
                    $newImage = new Image();
                    $newImage->path = $filename;
                    $newImage->description = $request->input('description') ?? '';
                    $newImage->role = 'cover_image';
                    $saved = $newImage->save();

                    if ($saved) {
                        try {
                            if ($oldImage = $article->image()->first()) {
                                $oldImage = Image::find($oldImage->id);
                                $filePath = public_path() . 
                                    '/' . $this->pathToCoverImage . $oldImage->path;

                                if (is_readable($filePath) && $oldImage->path != $this->defaultImage) {
                                    unlink($filePath);
                                }
                                
                                $article->image()->detach();
                                $oldImage->delete();
                            }

                        } catch(Exception $e) {
                            return back()->with([
                                'success' => "Internal error. Your article was uploaded but cover image wasn't uploaded."
                            ]);
                        }

                        $article->save();
                        $imageId = DB::getPdo()->lastInsertId();
                        $article->image()->sync($imageId, true);
                    
                    } else {
                        return back()->with([
                            'error' => "Internal error. Your article was uploaded but cover image wasn't uploaded."
                        ]);
                    }
                }

                $article->categories()->sync($request->input('categories'), true);
                $article->tags()->sync($request->input('tags'), true);

                return redirect()->route('articles.show', $article->slug)->with([
                    'article' => $article,
                    'error' => 'Article was updated successfully.'
                ]);

            } else {
                return back()->with([
                    'article' => $article,
                    'error' => "Internal error. Article wasn't updated."
                ]);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $article = Article::find($id);

        if (empty($article)) {
            return ErrorController::error404();
        }

        if (!auth::user()->is('admin') && $article->id != Auth::user()->id) {
            return back();
        }

        if (!empty($article)) {
            $coverImage = Image::find($article->image()->first()->id);
            $article->image()->detach();
            $article->categories()->detach();
            $article->tags()->detach();
            $article->delete();

            if ($coverImage->path != $this->defaultImage) {
                $filePath = public_path() . 
                    '/' . $this->pathToCoverImage . $coverImage->path;

                if (is_readable($filePath)) {
                    unlink($filePath);
                }
            }
            
            $coverImage->delete();

            return redirect()->route('articles.index')->with([
                'success' => 'Article was deleted successfully.'
            ]);
        }
    }
}
