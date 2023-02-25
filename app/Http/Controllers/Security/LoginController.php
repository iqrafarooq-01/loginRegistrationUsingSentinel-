<?php

namespace App\Http\Controllers\Security;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Illuminate\Support\Facades\Validator;

use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use Cartalyst\Sentinel\Checkpoints\NotActivatedException;


class LoginController extends Controller
{
    public function login()
    {
        return view('security.login');
    }

    public function postlogin(Request $request)
    {
        Sentinel::disableCheckpoints();
        $errorMsgs = [
            'email.required'     =>  'Please provide email id',
            'email.email'        =>  'The email must be a valid email',
            'password.required'  =>  'Password is required',
        ];
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required'
        ], $errorMsgs);

        if ($validator->fails()) {
            $returnData = array(
                'status'     =>  'error',
                'message'    =>  'Please review fields',
                'errors'     =>   $validator->errors()->all()
            ); // validation fail return data in Json format   
            return response()->json($returnData, 500);
        }

        //Remember Check is On 
        if ($request->remember == 'on') {
            try {
                $user = Sentinel::authenticateAndRemember($request->all());
            } catch (ThrottlingException $e) {
                $delay = $e->getDelay();
                $returnData = array(
                    'status'     =>  'error',
                    'message'    =>  'Please review',
                    'errors'     =>   ["you are banned for $delay seconds..."]
                );
                return response()->json($returnData, 500);
            } catch (NotActivatedException $e) {
                $returnData = array(
                    'status'     =>  'error',
                    'message'    =>  'Please review',
                    'errors'     =>   ["Please activate your account."]
                );
                return response()->json($returnData, 500);
            }
        } else {
            try {
                $user = Sentinel::authenticateAndRemember($request->all());
            } catch (ThrottlingException $e) {
                $delay = $e->getDelay();
                $returnData = array(
                    'status'     =>  'error',
                    'message'    =>  'Please review',
                    'errors'     =>   ["you are banned for $delay seconds..."]
                );
                return response()->json($returnData, 500);
            } catch (NotActivatedException $e) {
                $returnData = array(
                    'status'     =>  'error',
                    'message'    =>  'Please review',
                    'errors'     =>   ["Please activate your account."]
                );
                return response()->json($returnData, 500);
            }
        }

        //User has logedIn
        if (Sentinel::check()) {
            redirect( url('/') ); //redirect to dasboard
            echo 'Success';
        } else {
            $returnData = array(
                'status'     =>  'error',
                'message'    =>  'Please review',
                'errors'     =>   ["Credentials mismatched."]
            );
            return response()->json($returnData, 500);
        }
    }



    //Logout method
    public function logout() {
        Sentinel::logout();
        return redirect( url('/login') );
    }
}
