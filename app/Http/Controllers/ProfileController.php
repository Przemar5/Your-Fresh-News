<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\ErrorController;
use App\Validation\Rules\CurrentUserPassword;
use App\Support\SessionHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
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
            'identity',
        ])->only([
            'edit',
            'update',
            'delete',
            'destroy',
        ]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data, $id)
    {
        return Validator::make($data, [
            'login' => ['required', 'string', 'between:2,255', "unique:users,login,$id,id,deleted_at,NULL", 'regex:/^[\w\-\@\#\&\+\/\.]+$/'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $id . ',id,deleted_at,NULL', 'regex:/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD'],
            'old_password' => ['required', 'string', 'between:8,255', new CurrentUserPassword()],
            'password' => ['nullable', 'string', 'between:8,255', 'confirmed'],
            'avatar' => ['nullable', 'image', 'max:20000'],
            'delete_avatar' => ['nullable'],
            'info' => ['nullable', 'string'],
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
        $fullFilename = User::AVATAR_PATH . $filename;

        if (Storage::disk('assets')->put($fullFilename, file_get_contents($file))) {
            return $filename;

        } else {
            return false;
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
    	$user = User::find($id);

        if (empty($user)) {
            return ErrorController::error404();
        }

        return view('profiles.show')->with('user', $user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    	$user = User::find($id);

    	if (empty($user)) {
            return ErrorController::error404();
    	}

    	return view('profiles.edit')->with('user', $user);
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
        $user = User::find($id);

        if (empty($user)) {
            return ErrorController::error404();
        }

        $validator = $this->validator($request->all(), $id);

        if ($validator->fails()) {
        	return back()->withErrors($validator->errors());
        }

        $user->login = $request->input('login');
        $user->email = $request->input('email');
        $user->info = $request->input('info');
        $newPassword = $request->input('password');
        if ($newPassword !== null && $newPassword !== '' && $user->id === Auth::user()->id) {
            $user->password = Hash::make($newPassword);
        }

        $oldAvatar = $user->avatar;

        if ($request->hasFile('avatar') || $request->input('delete_avatar') == 'on') {
            if (!empty($oldAvatar) && $oldAvatar != User::DEFAULT_AVATAR) {
            	$filePath = $this->getAvatarFilePath($oldAvatar);

	            if (is_readable($filePath) && $oldAvatar != User::DEFAULT_AVATAR) {
	                unlink($filePath);
	            }
            }

            $user->avatar = User::DEFAULT_AVATAR;
        }

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = $this->storeFile($file);

            if (!$filename) {
                return back()->with([
                    'error' => "Internal error. Cannot upload your avatar."
                ]);
            }

            $user->avatar = $filename;
        }

        if ($user->save()) {
            SessionHelper::addFlash('success', 'Profile was updated successfully.');
        }
        
        return redirect()->route('profiles.show', $user->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $user = User::find($id);

        if (empty($user)) {
            return ErrorController::error404();
        }

        return view('profiles.delete')->with('user', $user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $loggedUser = User::find(Auth::id());

        if (empty($user)) {
            return ErrorController::error404();
        }

        if ($user->avatar && $user->avatar != User::DEFAULT_AVATAR) {
            $filePath = $this->getAvatarFilePath($user->avatar);

            if (is_readable($filePath)) {
                unlink($filePath);
            }
        }

        $user->delete();

        return redirect()->route('articles.index')->with([
            'success' => 'Account was deleted successfully.',
        ]);
    }

    /*
     * Get file path of stored user's avatar
     *
     * @return string
     */
    public function getAvatarFilePath(string $filename)
    {
        return public_path() . '/' . User::AVATAR_PATH . $filename;
    }

}
