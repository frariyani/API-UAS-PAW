<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Mail;
use App\Mail\VerificationEmail;
use Carbon\Carbon;


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

        try{
            $detail = [
                'body' => $request->nama_user,
                'id' => $user->id,
            ];
            Mail::to($request->email)->send(new VerificationEmail($detail));
            return 'Item successfully created';
        }catch(Exception $e){
            //return redirect()->route('welcome.index')->with('success','Item successfully created but email was not sent');
        }


        //User::create($request->getAttributes())->sendEmailVerificationNotification();
        return response([
            'message' => 'Register Success',
            'user' => $user,
        ],200);
    
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

        if(is_null($user->email_verified_at)){
            return response(['message' => 'Email belum diverifikasi'], 400);
        }else{
            $token = $user->createToken('Authentication Token')->accessToken;
        }
        

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

    public function verify($id){
        $user = User::findOrFail($id);

        if(is_null($user->email_verified_at)){
            $user->email_verified_at = Carbon::now()->format('Y-m-d H:i:s');
            if($user->save()){
                return response([
                    'message' => 'Berhasil diverifikasi'
                ], 200);
            }else{
                return response([
                    'message' => 'Tidak berhasil'
                ], 400);
            }
        }else{
            return response([
                'message' => 'Email sudah diverifikasi'
            ], 400);
        }
    }
}
