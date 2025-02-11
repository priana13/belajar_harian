<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirect(){
        
        return Socialite::driver('google')->redirect();
    }

    public function callback(){

        $user = Socialite::driver('google')->user(); 

        $finduser = User::where('email', $user->email)->first();

        if($finduser){

            Auth::login($finduser);

            return redirect()->intended('/');
        }else{

            return redirect()->route('login');
        }

        
    }
}
