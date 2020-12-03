<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Barang;
use App\User;
use App\Cart;

class CartController extends Controller
{
    public function add($idUser, $idBarang){
        $user = User::find($idUser);
        $barang = Barang::find($idBarang);

        $cartItem['barang_id'] = $barang->id;
        $cartItem['user_id'] = $user->id;
        $cartItem['jumlah_item'] = 1;
        $cartItem['subtotal'] = $barang->harga;

        $cart = Cart::create($cartItem);

        return response([
            'message' => 'added to cart',
            'data' => $cart,
        ], 200);

    }
}
