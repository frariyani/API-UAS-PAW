<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Image;
use App\Barang;
use App\User;

class FeedController extends Controller
{
    public function show($id){
        $user = User::find($id);

        $barang = DB::table('barangs')->where('user_id', '!=', $user->id)->get();
        \Log::info($barang);
        if(!is_null($barang)){
            return response([
                'message' => 'Barang ketemu',
                'data' => $barang
            ], 200);
        }

        return response([
            'message' => 'Barang tidak ketemu',
            'data' => null
        ], 404);
    }

    public function showDetail($id){
        $barang = Barang::find($id);

        $barangDetail = DB::table('barangs')->where('id', '=', $barang->id)->get();

        if(!is_null($barangDetail)){
            return response([
                'message' => 'Barang ketemu detailssss',
                'data' => $barangDetail
            ], 200);
        }
        return response([
            'message' => 'Barang tidak ketemu',
            'data' => null
        ], 404);
    }
}
