<?php

namespace App\Http\Controllers;

use App\User;
use App\Mail\ContactMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
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
     * Show the application contact page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('pages.contact');
    }

    public function send(Request $request)
    {
        // dd($request->all());

        $validator = Validator::make($request->all(), [
            'name' => ['nullable', 'string', 'max:255', 'regex:/^[\w\- ]+$/'],
            'email' => ['required', 'string', 'email', 'max:255', 'regex:/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD'],
            'subject' => ['nullable', 'string', 'max:255', 'regex:/^[\w\-\ \$\#\@\!\%\^\*\(\)\=\+\[|{\}\]\|\;\:\"\'\,\.\<\.\?\/ ]+$/'],
            'message' => ['required', 'string', 'max:3000', 'regex:/^[\w\-\ \$\#\@\!\%\^\*\(\)\=\+\[|{\}\]\|\;\:\"\'\,\.\<\.\?\/ ]+$/']
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors());
        }

        $data = [
            'name' => $request->name,
            'from' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
        ];
        $user = User::find(1);

        Mail::send('emails.contact', ['data' => $data], function ($m) use ($user, $data) {
            $m->from($data['from'], $data['name']);
            $m->to($user->email, $user->name);
            $m->subject($data['subject']);
        });

        if (Mail::failures()) {
            return back()->with('error', "Something gone wrong and Your email wasn't send. Please try again.");
        }

        return redirect()->route('home')->with('success', 'Your email was send successfully.');
    }
}
