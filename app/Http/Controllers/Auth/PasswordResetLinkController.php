<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use Illuminate\Support\Facades\Mail;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {       

        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $new_password = \uniqid();   
        $token = \hash('sha256', $new_password);
        $reset_link = route('password.reset' , $token);      

        $user = User::where('email', $request->email)->first();  
        
        if(!$user){

            return back()->with('status', "Email Tidak Ditemukan");

        }else{

            $user->remember_token = $token;
            $user->save();

            $status = Mail::to( $request->email )->send(new ResetPasswordMail(
                $request->email, $new_password , $reset_link
            ));

            return back()->with('status', "Link Reset password telah dikirim ke email Anda");

        }       
      

        

        // dd( $request->only('email') );

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        // $status = Password::sendResetLink(
        //     $request->only('email')
        // );

        


    //     return $status == Password::RESET_LINK_SENT
    //                 ? back()->with('status', __($status))
    //                 : back()->withInput($request->only('email'))
    //                         ->withErrors(['email' => __($status)]);
    }
}
