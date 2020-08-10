<?php

namespace App\Http\Controllers;

use App\Article;
use App\Comment;
use App\User;
use App\Http\Controllers\ErrorController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    // For passing to validator
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
            'verified',
        ])->only([
            'create',
            'store',
        ]);

        $this->middleware([
            'auth', 
            'verified',
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
    protected function validator(array $data, ?bool $create = true)
    {
        if ($create) {
            $rules = [
                'title' => [
                    'required',
                    'between:5,255',
                    'regex:/^[\w\-!@#$%&\*()+=|{}[\]:;,<>?\. ]+$/',
                ],
                'body' => [
                    'required',
                    'between:1,800',
                    // 'regex:/^[\w\-!@#$%&\*()+=|{}[\]:;,<>?\. ]+$/',
                ],
                'article' => [
                    'required',
                    'integer',
                    'exists:articles,id'
                ],
                'parent_comment_id' => [
                    'nullable',
                    'integer',
                    'exists:comments,id'
                ]
            ];
        
        } else {
            $rules = [
                'title' => [
                    'required',
                    'between:5,255',
                    'regex:/^[\w\-!@#$%&\*()+=|{}[\]:;,<>?\. ]+$/',
                ],
                'body' => [
                    'required',
                    'between:1,800',
                    // 'regex:/^[\w\-!@#$%&\*()+=|{}[\]:;,<>?\. ]+$/'
                ]
            ];
        }

        return Validator::make($data, $rules);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $comment = Comment::find($id);

        if (empty($comment)) {
            return ErrorController::error404();
        }

        return view('comments.create')->with('comment', $comment);
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
            if ($request->ajax()) {
                return response()->json(['errors' => $validator->errors()]);

            } else {
                return back()->withErrors($validator->errors());
            }
        }

        $comment = new Comment();
        $comment->title = $request->input('title');
        $comment->body = $request->input('body');
        $comment->article_id = $request->input('article');
        $comment->parent_comment_id = $request->input('parent_comment_id') ?? null;
        $comment->user_id = Auth::id();
        $saved = $comment->save();

        if (request()->ajax()) {
            $user = User::find($comment->user_id);

            if ($saved) {
                $depth = ($comment->parent_comment_id) ? 1 : 2;
                
                return view('includes.components.comment')->with('comment', $comment)
                    ->with('depth', $depth);
            
            } else {
                return response()->json([
                    'error' => 'Something gone wrong. Please write your comment again.'
                ]);
            }

        } else {
            if ($saved) {
                return back()->with([
                        'success' => 'Your comment was added successfully.'
                ]);
            
            } else {
                return back()->with([
                    'error' => 'Something gone wrong. Please write your comment again.'
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
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $comment = Comment::find($id);

        if (empty($comment)) {
            return ErrorController::error404();
        }

        return view('comments.edit')->with('comment', $comment);
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
        $comment = Comment::find($id);

        if (empty($comment)) {
            return ErrorController::error404();
        }

        $validator = $this->validator($request->all(), self::UPDATE);

        if ($validator->fails()) {
            return back()->with('errors', $validator->errors());
        }

        if ($validator->fails()) {
            if (request()->ajax()) {
                return response()->json(
                    $validator->errors()
                );

            } else {
                // return redirect()->back()->withErrors($validator);
                return $validator->validateWithBag('comment');
            }
        }

        $comment->title = $request->input('title');
        $comment->body = $request->input('body');
        $saved = $comment->save();

        if ($saved) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => 'Comment was updated successfully.',
                    'title' => $comment->title,
                    'body' => $comment->body,
                ]);

            } else {
                return redirect()->route('articles.show', $comment->article->slug)->with([
                    'success' => 'Comment was updated successfully.'
                ]);
            }

        } else {
            if (request()->ajax()) {
                return response()->json([
                    'success' => 'Comment was updated successfully.'
                ]);

            } else {
                return back()->with([
                    'error' => "Internal error. Comment wasn't updated. Please try again.",
                    'comment' => $comment
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
        $comment = Comment::find($id);

        if (empty($comment)) {
            return ErrorController::error404();
        }

        $comment->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => 'Comment was deleted successfully.'
            ]);

        } else {
            return back()->with([
                'success' => 'Comment was deleted successfully.'
            ]);
        }
    }
}
