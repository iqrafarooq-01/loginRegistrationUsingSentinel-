<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class RegisterController extends Controller
{
    public function register() {
        return view('security.register');
    }

    public function registerUser(Request $request) {

        $user = Sentinel::registerAndActivate($request->all());
        echo 'user registered'; 
        return redirect('/');
        dd($user);
    
    } 
}
