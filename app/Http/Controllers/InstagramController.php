<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\User;
use Illuminate\Support\Facades\Input;


class InstagramController extends Controller
{
    //
    public function redirectToInstagramProvider()
    {
        return Socialite::with('instagram')->scopes([
            "public_content"])->redirect();
    }
    /**
     * Obtain the user information from Facebook.
     *
     * @return void
     */
    public function handleProviderInstagramCallback()
    {

        $error  = Input::get('error') ;

        if ($error === 'access_denied'){
            return redirect('/');
        }

        $insta = Socialite::driver('instagram')->user();

        $details = [
            "access_token" => $insta->token
        ];

        if(Auth::user()->instagram){
            Auth::user()->instagram()->update($details);
        }else{
            Auth::user()->instagram()->create($details);
        }
        return redirect('/');
    }
}
