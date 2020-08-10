<?php

namespace App\Http\Controllers;

use App\Article;
use App\Category;
use App\Http\Controllers\ErrorController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
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
     * Get a validator for an incoming category create/edit request.
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
                'unique:categories,name' . $id,
                'regex:/^[\w\-\+\#\$\%\^\&\@\!\(\)\|\=\,\.\<\>\/\?\" ]+$/'
            ],
            'slug' => [
                'required',
                'max:255',
                'unique:categories,slug' . $id,
                'regex:/^[0-9a-zA-Z_\-]+$/'
            ],
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::orderBy('id', 'DESC')->paginate(20);

        return view('categories.index')->with('categories', $categories);
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

        $category = new Category();
        $category->name = $request->input('name');
        $category->slug = $request->input('slug');
        $saved = $category->save();

        if ($saved) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => 'New category was created successfully.',
                    'category' => [
                        'id' => $category->id,
                        'name' => $category->name,
                        'slug' => $category->slug
                    ]
                ]);

            } else {
                return redirect()->route('categories.index')
                    ->with('success', 'New category was created successfully.');
            }
        
        } else {
            if ($request->ajax()) {
                return response()->json([
                    'error' => "Internal error. New category wasn't created."
                ]);

            } else {
                return redirect()->route('categories.index')
                    ->with('error', "Internal error. Category wasn't created.");
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show(string $slug)
    {
        $category = Category::where('slug', $slug)->first();

        if (empty($category)) {
            return ErrorController::error404();
        }

        $articles = $category->articlesPaginated(10, 'created_at', 'DESC');

        return view('categories.show')->with('articles', $articles)
            ->with('category', $category);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function edit(string $slug)
    {
        $category = Category::where('slug', $slug)->first();

        if (empty($category)) {
            return ErrorController::error404();
        }
        
        return view('categories.edit')->with('category', $category);
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
        $category = Category::find($id);

        if (empty($category)) {
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

        $category->name = $request->input('name');
        $category->slug = $request->input('slug');
        $saved = $category->save();

        if ($saved) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => 'Category updated successfully.',
                    'name' => $category->name,
                    'slug' => $category->slug
                ]);

            } else {
                return redirect()->route('categories.show', $category->slug)
                    ->with('success', 'Category updated successfully.');
            }
        
        } else {
            if ($request->ajax()) {
                return response()->json([
                    'error' => "Internal error. Category wasn't updated."
                ]);

            } else {
                return redirect()->route('categories.show', $category->slug)
                    ->with('error', "Category wasn't updated.");
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
        $category = Category::find($id);

        if (empty($category)) {
             ErrorController::error404();
        }

        $category->delete();
        
        if (request()->ajax()) {
            return response()->json([
                'success' => 'Category deleted successfully.'
            ]);

        } else {
            return redirect()->route('categories.index')
                ->with('success', 'Category was deleted successfully.');
        }
    }
}
