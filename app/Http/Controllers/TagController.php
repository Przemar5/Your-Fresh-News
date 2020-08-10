<?php

namespace App\Http\Controllers;

use App\Tag;
use App\Http\Controllers\ErrorController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class TagController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware([
            'auth',
            'admin',
            'verified',
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
    protected function validator(array $data, $id = '')
    {
        if (!empty($id)) {
            $id = ',' . $id;
        }

        return Validator::make($data, [
            'name' => [
                'required',
                'max:255',
                'unique:tags,name' . $id,
                'regex:/^[\w\-\+\&\% ]+$/'
            ]
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = Tag::orderBy('id', 'DESC')->paginate(20);

        return view('tags.index')->with('tags', $tags);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response or \Illuminate\Http\JsonResponse
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

        $tag = new Tag();
        $tag->name = $request->input('name');
        $saved = $tag->save();

        if ($saved) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => 'New tag was created successfully.',
                    'tag' => [
                        'id' => $tag->id,
                        'name' => $tag->name
                    ]
                ]);  
            
            } else {
                return redirect()->route('tags.index')
                    ->with('success', 'New tag was created successfully.');
            }

        } else {
            if ($request->ajax()) {
                return response()->json([
                    'error' => "Internal error. New tag wasn't created."
                ]);

            } else {
                return redirect()->route('tags.index')
                    ->with('error', "Internal error. Tag wasn't created.");
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
        $tag = Tag::find($id);

        if (empty($tag)) {
            return ErrorController::error404();
        }

        $articles = $tag->articlesPaginatedBy(10, 'created_at', 'DESC');

        return view('tags.show')->with('articles', $articles)
            ->with('tag', $tag);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tag = Tag::find($id);

        if (empty($tag)) {
            return ErrorController::error404();
        }

        return view('tags.edit')->with('tag', $tag);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @return \Illuminate\Http\Response or \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $tag = Tag::find($id);

        if (empty($tag)) {
            return ErrorController::error404();
        }

        $validator = $this->validator($request->all(), $id);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['errors' => $validator->errors()]);

            } else {
                return back()->withErrors($validator->errors());
            }
        }

        $tag->name = $request->input('name');
        $saved = $tag->save();

        if ($saved) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => 'tag updated successfully.',
                    'name' => $tag->name
                ]);    
            
            } else {
                return redirect()->route('tags.show', $tag->id)
                    ->with('success', 'Tag updated successfully.');
            }

        } else {
            if ($request->ajax()) {
                return response()->json([
                    'error' => "Internal error. Tag wasn't updated."
                ]);

            } else {
                return redirect()->route('tags.show', $tag->id)
                    ->with('error', "Internal error. Tag wasn't updated.");
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tag = Tag::find($id);

        if (empty($tag)) {
            return ErrorController::error404();
        }

        $tag->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => 'Tag was deleted successfully.'
            ]);

        } else {
            return redirect()->route('tags.index')
                ->with('success', 'Tag was deleted successfully.');
        }
    }
}
