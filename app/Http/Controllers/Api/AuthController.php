<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    public function register(Request $request){
        $registrationData = $request->all();
        $validate = Validator::make($registrationData, [
            'nama_user' => 'required|min:6',
            'alamat' => 'required|min:6',
            'no_hp' => 'required|regex:/^08[0-9]{8,10}/',
            'email' => 'required|email:rfc,dns|unique:users',
            'password' => 'required|min:6|string|regex:/[a-z]/|regex:/[0-9]/'
        ]);

        //$request->foto_user = public_path('/images/FUu1GKmipUF2CBp5pWIf.jpg'); 


        if($validate->fails())
            return response(['message' => $validate->errors()],400);

        $registrationData['password'] = Hash::make($request->password);
        $user = User::create($registrationData);
        //User::create($request->getAttributes())->sendEmailVerificationNotification();
        return response([
            'message' => 'Register Success',
            'user' => $user,
        ],200);

        $detail = 'heuheueheueh';
        Maill::to('frs.ariyani@gmail.com')->send(new VerificationEmail($detail));

        // try{
        //     $detail = [
        //         'body' => $request->nama_user,
        //     ];
            
        // }catch(Exception $e){
            
        // }
    
    }

    public function login(Request $request){
        $loginData = $request->all();
        $validate = Validator::make($loginData, [
            'email' => 'required|email:rfc,dns',
            'password' => 'required'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()],400);
        
        if(!Auth::attempt($loginData))
            return response(['message' => 'Invalid Credentials'],401);

        $user = Auth::user();
        $token = $user->createToken('Authentication Token')->accessToken;

        return response([
            'message' => 'Authenticated',
            'user' => $user,
            'token_type' => 'Bearer',
            'access_token' => $token
        ]);
    }

    public function logout(Request $request){
        $request->user()->token()->revoke();
        return response([
            'message' => 'Logged out'
        ]);
    }
}
