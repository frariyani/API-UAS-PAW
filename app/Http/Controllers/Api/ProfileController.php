<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use Auth;
use Image;
use File;
use App\User;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function show($id){
        $user = User::find($id);

        if(!is_null($user)){
            return response([
                'message' => 'Retrieve User Success',
                'data' => $user
            ], 200);
        }

        return response([
            'message' => 'User Not Found',
            'data' => null,
        ], 404);

    }

    public function update(Request $request, $id){
        $user = User::find($id);

        if(is_null($user)){
            return response([
                'message' => 'User Not found',
                'data' => null,
            ], 404);
        }


        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'nama_user' => 'required|min:6',
            'alamat' => 'required|min:6',
            'no_hp' => 'required|regex:/^08[0-9]{8,10}/',
            // 'email' => 'required|email:rfc,dns',
            // 'image' => $fileName
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $user->nama_user = $updateData['nama_user'];
        $user->alamat = $updateData['alamat'];
        $user->no_hp = $updateData['no_hp'];
        // $user->image = $fileName;

        if($user->save()){
            return response([
                'message' => 'Update User Success',
                'data' => $user,
            ], 200);
        }
        return response([
            'message' => 'Update User Failed',
            'data' => null,
        ],);



    }

    public function upload(Request $request, $id){ 
        $user = User::find($id);

        if(isset($request->foto_user)){
            \Log::info($request->all());
            $exploded = explode(',', $request->foto_user);

            $decoded = base64_decode($exploded[1]);

            if(str_contains($exploded[0], 'jpeg'))
                $extension = 'jpg';
            else
                $extension = 'png';

            
            $random = Str::random(20);
            
            $fileName = $random.'.'.$extension;

            $path = public_path().'/'.$fileName;

            file_put_contents($path, $decoded);

        }

        $user->foto_user = $fileName;

        if(is_null($user)){
            return response([
                'message' => 'User not found',
                'data' => null
            ], 404);
        }

        if($user->save()){
            return response([
                'message' => 'Update avatar success',
                'data' => $user,
            ],200);
        }
        return response([
            'message' => 'Update profile failed',
            'data' => null,
        ], 400);


    }






}
