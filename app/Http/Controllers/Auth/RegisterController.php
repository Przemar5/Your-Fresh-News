<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Handlers\FileHandler;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;
    protected $redirectTo;

    private FileHandler $fileHandler;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(FileHandler $fileHandler)
    {
        $this->middleware('guest');

        $this->fileHandler = $fileHandler;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'login' => ['required', 'string', 'between:2,255', 'regex:/^[\w\-\@\#\&\+\/\.]+$/u'],
            'name' => ['required', 'string', 'between:2,255', 'regex:/^[\w\-]+$/u'],
            'surname' => ['required', 'string', 'between:2,255', 'regex:/^[\w\-]+$/u'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,NULL,id,deleted_at,NULL', 'regex:/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD'],
            'password' => ['required', 'string', 'between:8,255', 'confirmed'],
            'avatar' => ['nullable', 'image', 'max:20000'],
            'info' => ['nullable', 'string'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $request = request();
        $filename = User::DEFAULT_AVATAR;

        try {
            DB::connection()->beginTransaction();

            $filename = User::DEFAULT_AVATAR;
            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                $filename = $this->fileHandler->storeFile($file, User::AVATAR_PATH);
            }

            $user = new User();
            $user->login = $data['login'];
            $user->name = $data['name'];
            $user->surname = $data['surname'];
            $user->email = $data['email'];
            $user->password = Hash::make($data['password']);
            $user->avatar = $filename;
            $user->info = $data['info'] ?? '';
            
            if (!$user->save()) {
                throw new \Exception('User creation failed.');
            }

            $this->redirectTo = route('profiles.show', ['id' => $user->id]);
            $user->sendEmailVerificationNotification();
            
            DB::connection()->commit();

            return $user;

        } catch(\Exception $e) {
            DB::connection()->rollBack();

            $filePath = public_path() . User::AVATAR_PATH . $filename;

            if ($filename !== User::DEFAULT_AVATAR && file_exists()) {
                unlink(User::AVATAR_PATH . $filename);
            }

            throw $e;
        }
    }
}
