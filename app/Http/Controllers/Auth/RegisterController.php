<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    protected $redirectTo = '/forms';

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm(Request $request)
    {
        $user_data = [];

        if ($request->has('code')) {
            $user = User::where('email_token', $request->code)->first();
            abort_if(!$user, 404);

            $user_data = ['code' => $request->code, 'email' => $user->email];
        }

        return view('auth.register', compact('user_data'));
    }

    public function register(Request $request)
    {
        $has_code = $request->has('code');

        if ($has_code) {
            $user = User::where('email_token', $request->code)->first();
            abort_if(!$user, 404);
        }

        $this->validator($request->all(), $has_code)->validate();

        if ($has_code) {
            $this->updateUser($user, $request->all());
            Auth::login($user);
            return redirect($this->redirectTo);
        }

        $user = $this->create($request->all());

        // Se desejar enviar verificação de e-mail manual, faça aqui
        $user->sendEmailVerificationNotification();

        return view('auth.register-complete', compact('user'));
    }

    protected function validator(array $data, $ignore_email = false)
    {
        $rules = [
            'first_name' => ['required', 'string', 'min:3', 'max:100'],
            'last_name' => ['required', 'string', 'min:3', 'max:100'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];

        if (!$ignore_email) {
            $rules['email'] = ['required', 'email', 'max:190', 'unique:users,email,NULL,id,deleted_at,NULL'];
        }

        return Validator::make($data, $rules);
    }

    protected function create(array $data)
    {
        return User::create([
            'first_name' => ucwords($data['first_name'], '- '),
            'last_name' => ucwords($data['last_name'], '- '),
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'email_token' => bin2hex(random_bytes(32)),
        ]);
    }

    protected function updateUser(User $user, array $data)
    {
        $user->first_name = ucwords($data['first_name'], '- ');
        $user->last_name = ucwords($data['last_name'], '- ');
        $user->password = Hash::make($data['password']);
        $user->email_token = null;
        $user->save();
    }
}
