<?php

namespace App\Http\Controllers;

use App\Article;
use App\Comment;
use App\Rating;
use App\User;
use App\ArticleUserRating;
use App\CommentUserRating;
use App\Http\Controllers\ErrorController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RatingController extends Controller
{
    private $pathToCoverImage = 'public/images/icons/';
    private $defaultImage = 'noimage.png';

    protected const CREATE = true;
    protected const UPDATE = false;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware([
            'auth',
            'verified'
        ])->except([
            'index',
            'show'
        ]);
    }

    /**
     * Get a validator for an incoming tag create/edit request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data, ?bool $create = true)
    {
        if ($create) {
            $rules = [
                'type' => [
                    'required',
                    'regex:/^[0-9a-zA-Z_\- ]+$/',
                    'unique:ratings,type',
                ],
                'title' => [
                    'required',
                    'regex:/^[0-9a-zA-Z_\-\+ ]+$/',
                ],
                'image' => [
                    'required',
                    'image',
                    'mimes:jpg,jpeg,png,bmp,svg,gif,php,html',
                    'max:2000',
                ],
                'index' => [
                    'required',
                    'integer',
                    'unique:ratings,index',
                ]
            ];
        
        } else {

        }

        return Validator::make($data, $rules);
    }

    /**
     * Store uploaded file.
     *
     * @param File $file
     * @param string $driver
     * @return string $filename
     */
    private function storeFile($file, string $type, string $driver)
    {
        $filename = time() . '.' . $file->getClientOriginalExtension();

        if (Storage::disk('local')->put($filename, file_get_contents($file))) {
            return $filename;

        } else {
            return false;
        }
    }

    /**
     * Rate an article
     *
     * @return \Illuminate\Http\Response
     */
    public function rateArticle(Request $request, $articleId)
    {
        Validator($request->all(), [
            'rating_type' => [
                'required',
                'regex:/^[0-9a-zA-Z_\- ]+$/',
                'exists:ratings,type'
            ],
        ])->validate();

        $article = Article::find($articleId);

        if (empty($article)) {
            return ErrorController::error404();
        }

        $ratingId = Rating::where('type', $request->input('rating_type'))->first()->id;
        $aur = ArticleUserRating::where('article_id', $articleId)
            ->where('user_id', Auth::id())->first();
        
        if (!empty($aur)) {
            $prevRatingId = $aur->rating_id;
        
        } else {
            $prevRatingId = null;
        }
        
        ArticleUserRating::where('article_id', $articleId)
            ->where('user_id', Auth::id())->delete();

        if ($prevRatingId != $ratingId || $prevRatingId == null) {
            $aur = new ArticleUserRating();
            $aur->article_id = $articleId;
            $aur->user_id = Auth::id();
            $aur->rating_id = $ratingId;
            $saved = $aur->save();

            if ($saved) {
                if (request()->ajax()) {
                    return response()->json([
                        'success' => true,
                        'likes' => $article->likes()->count(),
                        'dislikes' => $article->dislikes()->count(),
                        'liked' => $article->likedBy(Auth::id()),
                        'disliked' => $article->dislikedBy(Auth::id()),
                    ]);
                
                } else {
                    return back()->with([
                        'success' => true
                    ]);
                }

            } else {
                if (request()->ajax()) {
                    return response()->json([
                        'error' => true,
                        'likes' => $article->likes()->count(),
                        'dislikes' => $article->dislikes()->count(),
                        'liked' => $article->likedBy(Auth::id()),
                        'disliked' => $article->dislikedBy(Auth::id()),
                    ]);
                
                } else {
                    return back()->with([
                        'error' => true
                    ]);
                }
            }
        }
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'likes' => $article->likes()->count(),
                'dislikes' => $article->dislikes()->count(),
                'liked' => $article->likedBy(Auth::id()),
                'disliked' => $article->dislikedBy(Auth::id()),
            ]);
        
        } else {
            return back()->with([
                'success' => true
            ]);
        }
    }

    /**
     * Rate a comment.
     *
     * @return \Illuminate\Http\Response
     */
    public function rateComment(Request $request, $commentId)
    {
        Validator($request->all(), [
            'rating_type' => [
                'required',
                'regex:/^[0-9a-zA-Z_\- ]+$/',
                'exists:ratings,type'
            ],
        ])->validate();

        $comment = Comment::find($commentId);

        if (empty($comment)) {
            return ErrorController::error404();
        }

        $ratingId = Rating::where('type', $request->input('rating_type'))->first()->id;
        $cur = CommentUserRating::where('comment_id', $commentId)
            ->where('user_id', Auth::id())->first();
        
        if (!empty($cur)) {
            $prevRatingId = $cur->rating_id;
        
        } else {
            $prevRatingId = null;
        }
        
        CommentUserRating::where('comment_id', $commentId)
            ->where('user_id', Auth::id())->delete();

        if ($prevRatingId != $ratingId || $prevRatingId == null) {
            $cur = new CommentUserRating();
            $cur->comment_id = $commentId;
            $cur->user_id = Auth::id();
            $cur->rating_id = $ratingId;
            $saved = $cur->save();

            if ($saved) {
                if (request()->ajax()) {
                    return response()->json([
                        'success' => true,
                        'likes' => $comment->likes()->count(),
                        'dislikes' => $comment->dislikes()->count(),
                        'liked' => $comment->likedBy(Auth::id()),
                        'disliked' => $comment->dislikedBy(Auth::id()),
                    ]);
                
                } else {
                    return back()->with([
                        'success' => true,
                    ]);
                }

            } else {
                if (request()->ajax()) {
                    return response()->json([
                        'error' => true,
                        'likes' => $comment->likes()->count(),
                        'dislikes' => $comment->dislikes()->count(),
                        'liked' => $comment->likedBy(Auth::id()),
                        'disliked' => $comment->dislikedBy(Auth::id()),
                    ]);
                
                } else {
                    return back()->with([
                        'error' => true,
                    ]);
                }
            }
        }

        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'likes' => $comment->likes()->count(),
                'dislikes' => $comment->dislikes()->count(),
                'liked' => $comment->likedBy(Auth::id()),
                'disliked' => $comment->dislikedBy(Auth::id()),
            ]);
        
        } else {
            return back()->with([
                'success' => true
            ]);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ratings = Rating::all()->sortBy('id');

        return view('ratings.index')->with('ratings', $ratings);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validator($request->all(), self::CREATE)->validate();

        // Create rating category
        $rating = new Rating();
        $rating->type = $request->input('type');
        $rating->title = $request->input('title');
        $rating->image_path = $request->input('image');

        // Handle file upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');

            if (!($filename = $this->storeFile($file, $rating->type, 'local'))) {

                if (request()->ajax()) {
                    return response()->json([
                        'error' => 'Internal error. Please try again.'
                    ]);

                } else {
                    return back()->with([
                        'error' => "Internal error. Please try again."
                    ]);
                }
            }
        
        } else {
            $filename = $this->defaultImage;
        }

        $rating->image_path = $filename;
        $saved = $rating->save();

        if ($saved) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => 'New rating type was added successfully.',
                    'rating' => [
                        'type' => $rating->type,
                        'title' => $rating->title,
                        'image' => $rating->image_path
                    ]
                ]);
            
            } else {
                return back()->with([
                    'error' => 'Something gone wrong. Please try again.',
                ]);
            }

        } else {
            if (request()->ajax()) {
                return back()->with([
                    'success' => 'New rating type was added successfully.',
                ]);
            
            } else {
                return response()->json([
                    'error' => 'Something gone wrong. Please write your comment again.',
                ]);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
