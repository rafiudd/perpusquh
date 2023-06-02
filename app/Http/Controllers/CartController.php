<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $authUser = auth()->user();
        $carts = Cart::with(['user','product'])->where('user_id', '=', $authUser->id)->get();

        $decoded = json_decode($carts, true);
        $result = array();

        foreach ($decoded as $value) {
            if(isset($result[$value["product_id"]])) {
                $result[$value["product_id"]]["quantity"] += $value["quantity"];
            } else {
                $result[$value["product_id"]] = $value;
            }

        }

        return view('carts.list', compact('result'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $authUser = auth()->user();
        if($authUser == null) {
            return redirect('/login');
        }

        // dd($request->quantity);
        if($request->quantity == null) {
            $request->quantity = 1;
        }

        Cart::create([
            'user_id'     => $authUser->id,
            'product_id'  => $request->product_id,
            'quantity'    => $request->quantity
        ]);

        // return redirect('/');
        if($request->source == 'detail') {
            $redirect_page = "/product/".$request->product_id."";
            return redirect($redirect_page)->with(['success' => 'Berhasil memasukan ke keranjang']);
        }
        return redirect('/')->with(['success' => 'Berhasil memasukan ke keranjang']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cart $cart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        // dd($request);
        $auth_user = auth()->user();
        $cart_id = $request->id;

        $deleted = Cart::where('id', '=', $cart_id)->delete();
        return redirect('/cart');
    }
}
