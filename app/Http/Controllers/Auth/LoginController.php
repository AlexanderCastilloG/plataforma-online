<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laravel\Socialite\Facades\Socialite;
use App\User;
use App\UserSocialAccount;
use App\Student;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    // sobreescribir el método Logout
    public function logout(Request $request) {
        auth()->logout();
        session()->flush(); //remover todas las sessiones
        return redirect('/login');
    }

    public function redirectToProvider(string $driver){
        return Socialite::driver($driver)->redirect();
    }

    public function handleProviderCallback(string $driver){
        if(!request()->has('code') || request()->has('denied')){
            // Helper session
            session()->flash('message', ['danger', 'Inicio de sesión cancelado']);
            return redirect('login');
        }

        $socialUser = Socialite::driver($driver)->user();    

        $user = null;
        $success = true;
        $email = $socialUser->email; // twitter no devuelve el email
        $check = User::whereEmail($email)->first(); // si el usuario existe en DB

        if($check) {
            $user = $check;
        }else {
            \DB::beginTransaction();
            
            try {
                $user = User::create([
                    "name" => $socialUser->name,
                    "email" => $email
                ]);

               UserSocialAccount::create([
                   "user_id" => $user->id,
                   "provider" => $driver,
                   "provider_uid" => $socialUser->id
               ]);

               Student::create([
                   "user_id" => $user->id
               ]);
                
            }catch (\Exception $exception) {
                $success = $exception->getMessage();
                \DB::rollBack();
            }
        }

        // Para que sea persistente tenemos que hacer un Commit
        if($success === true) {
            \DB::commit(); //para que toda esa información sea guardada en nuestra DB
            auth()->loginUsingId($user->id); //Inicia sesion con Id de un Usuario
            return redirect(route('home'));
        }

        session()->flash('message', ['danger', $success]);
        return redirect('login');

    }
}
