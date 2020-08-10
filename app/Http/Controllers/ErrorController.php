<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ErrorController extends Controller
{
    /**
     * Show the application home page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //
    }

    public static function error403()
    {
    	return view('errors.403');
    }

    public static function error404()
    {
    	return view('errors.404');
    }
}
