<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Image;
use File;
use Illuminate\Support\Facades\Validator;
use App\Barang;
use App\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Faker\Generator as Faker; 

class BarangController extends Controller
{
    //
    public function show($id){
        $user = User::find($id);//id nya adalah miliki user, karena untuk melihat barang apa saja yang dimiliki oleh user

        
        $barang = DB::table('barangs')->where('user_id', '=', $user->id)->get();

        if(!is_null($barang)){
            return response([
                'message' => 'Retrieve Product Success',
                'data' => $barang
            ], 200);
        }

        return response([
            'message' => 'Product Not Found',
            'data' => null
        ],404);
        // return $barang;
    }

    public function destroy($id){
        $barang = Barang::find($id);

        if(is_null($barang)){
            return response([
                'message' => 'Product Not Found',
                'data' => null
            ],404);
        }

        if($barang->delete()){
            return response([
                'message' => 'Delete Product Success',
                'data' => $barang,
            ],200);
        }

        return response([
            'message' => 'Delete Product Failed',
            'data' => null,
        ],400);
    }

    public function update(Request $request, $id){
        $barang = Barang::find($id);

        if(is_null($barang)){
            return response([
                'message' => 'Product Not Found',
                'data' => null
            ],404);
        }

        $updateData = $request->all();

        $validate = Validator::make($updateData, [
            'nama_barang' => 'required|min:6|max:20',
            'harga' => 'required|numeric',
            'ukuran' => 'required|numeric',
            'deskripsi' => 'required|max:20',
            'stok' => 'required|numeric',
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()],400);

        $barang->nama_barang = $updateData['nama_barang'];
        $barang->harga = $updateData['harga'];
        $barang->ukuran = $updateData['ukuran'];
        $barang->deskripsi = $updateData['deskripsi'];
        $barang->stok = $updateData['stok'];

        if($barang->save()){
            return response([
                'message' => 'Update Product Success',
                'data' => $barang,
            ],200);
        }
        return response([
            'message' => 'Update Product Failed',
            'data' => null,
        ],400);
    }



    public function store(Request $request, $id){
        $user = User::find($id);//store id nya adalah id user
        $data = $request->all();

        $data['user_id'] = $user->id;
        $validate = Validator::make($data, [
            'nama_barang' => 'required|min:6|max:20',
            'harga' => 'required|numeric',
            'ukuran' => 'required|numeric',
            'deskripsi' => 'required|max:20',
            'stok' => 'required|numeric',
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $barang = Barang::create($data);

        if($user->barangs()->save($barang)){
            return response([
                'message' => 'Berhasil tambah barang',
                'data' => $barang,
            ],200);
        }
        
        return response()->json(['message'=>'Error Occured','data'=>null],400);
        
    }

    public function generateImage(Faker $faker){
        return [
            'image' => $faker->image('public/storage/images', 640, 480, null, false)
        ];
    }

}
