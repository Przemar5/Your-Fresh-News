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
use App\Http\Controllers\Traits\HasPermissions;
use App\Handlers\FileHandler;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ArticleController extends Controller
{
    use HasPermissions;

    private FileHandler $fileHandler;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(FileHandler $fileHandler)
    {
        $this->middleware([
            'auth',
            'writer',
            'verified',
        ])->except([
            'index',
            'show',
        ]);

        $this->fileHandler = $fileHandler;
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
        $fullFilename = Article::COVER_IMAGE_PATH . $filename;

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
        $categories = Category::whereNotNull('parent_id')->orderBy('id', 'ASC')->get();
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
        
        $filename = Article::DEFAULT_COVER_IMAGE;

        try {
            DB::connection()->beginTransaction();

            if ($request->hasFile('cover_image')) {
                $file = $request->file('cover_image');
                $filename = $this->fileHandler->storeFile($file, Article::COVER_IMAGE_PATH);
            }

            // Create image entity
            $image = new Image();
            $image->path = $filename;
            $image->description = $request->input('description') ?? Article::DEFAULT_COVER_IMAGE_DESCRIPTION;
            $image->role = 'cover_image';

            if (!$image->save()) {
                throw new \Exception("Cover image wasn't added.");
            }

            // Create article
            $article = new Article();
            $article->title = $request->input('title');
            $article->slug = $request->input('slug');
            $article->body = $request->input('body');
            $article->user_id = Auth::id();

            if (!$article->save()) {
                throw new \Exception("Article wasn't created.");
            }

            $article->image()->sync($image->id, true);
            $article->categories()->sync($request->input('categories'), true);
            $article->tags()->sync($request->input('tags'), true);

            if (!$article->save()) {
                throw new \Exception("Article wasn't created.");
            }

            DB::connection()->commit();

            return redirect()->route('articles.show', $article->slug)->with([
                'article' => $article,
                'success' => 'New article was created successfully.'
            ]);

        } catch(\Exception $e) {
            DB::connection()->rollBack();

            $filePath = public_path() . Article::COVER_IMAGE_PATH . $filename;

            if ($filename !== Article::DEFAULT_COVER_IMAGE && file_exists($filePath)) {
                unlink($filePath);
            }

            dd($e);
            
            return back()->with([
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
            $filePath = Article::COVER_IMAGE_PATH . $coverImage->path;
            $coverImage->url = Storage::disk('assets')->url($filePath);
        } else {
            throw new \Exception('Missing cover image.');
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

        $this->abortIfNotOwnerOrAdmin($comment);

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

        $this->abortIfNotOwnerOrAdmin($comment);

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
                                $filePath = public_path() . Article::COVER_IMAGE_PATH . $oldImage->path;

                                if (is_readable($filePath) && $oldImage->path != Article::DEFAULT_COVER_IMAGE) {
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

        $this->abortIfNotOwnerOrAdmin($comment);

        if (!empty($article)) {
            $coverImage = Image::find($article->image()->first()->id);
            $article->image()->detach();
            $article->categories()->detach();
            $article->tags()->detach();
            $article->delete();

            if ($coverImage->path != Article::DEFAULT_COVER_IMAGE) {
                $filePath = public_path() . Article::COVER_IMAGE_PATH . $coverImage->path;

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
