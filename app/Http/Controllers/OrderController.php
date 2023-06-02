<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use \PDF;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $authUser = auth()->user();
        $orders = Order::orderBy('created_at', 'DESC')->with(['user', 'order_items'])->where('user_id', '=', $authUser->id)->whereIn('status', ['Berhasil', 'Berhasil Diambil'])->paginate(8);
        // dd(json_decode($orders));
        return view('orders.list', compact('orders'));
    }

    public function getHistoryOrder()
    {
        $authUser = auth()->user();
        $orders = Order::orderBy('created_at', 'DESC')->with(['user', 'order_items'])->where('user_id', '=', $authUser->id)->whereIn('status', ['Checking Admin', 'Dibatalkan', 'Dibatalkan User'])->paginate(8);
        // dd(json_decode($orders));
        return view('history.list', compact('orders'));
    }

    public function getDetail(Request $request)
    {
        $order_id = $request->order_id;
        $orders = OrderItem::with(['product', 'order'])->where('order_id', '=', $order_id)->get();
        // dd(json_decode($orders));
        return view('orders.detail', compact('orders'));
    }

    public function getDetailHistory(Request $request) {
        $order_id = $request->order_id;
        $orders = OrderItem::with(['product', 'order'])->where('order_id', '=', $order_id)->get();
        // dd(json_decode($orders));
        return view('history.detail', compact('orders'));
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
        $cart_checked = $request->input('checkbox');

        // update latest cart
        if($request->quantity) {
            // dd($request->quantity);

            for ($i=0; $i < count($cart_checked); $i++) { 
                Cart::where('id', $cart_checked[$i])->update([
                    'quantity' => $request->quantity[$i]
                ]);
            }
        }

        // dd($request);
        // $auth_user = auth()->user();
        $carts = Cart::with(['user','product'])->whereIn('id', $cart_checked)->get();
        // dd($carts);
        $decoded = json_decode($carts, true);
        $result = array();

        foreach ($decoded as $value) {
            if(isset($result[$value["product_id"]])) {
                $result[$value["product_id"]]["quantity"] += $value["quantity"];
            } else {
                $result[$value["product_id"]] = $value;
            }
        }

        $data = array();
        $orders = array();

        // dd($result);

        foreach($result as $datas) {
            array_push($orders, [
                'user_id' => $datas['user_id'],
                'total' => $datas['quantity'] * $datas['product']['price'],
                'status' => 'Checking Admin',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }

        $total_price = array();
        foreach ($orders as $val) {
            if (!isset($total_price[$val['user_id']]))
                $total_price[$val['user_id']] = $val;
            else
                $total_price[$val['user_id']]['total'] += $val['total'];
        }
        $total_price = array_values($total_price); 

        Order::insert($total_price);
		$last_id = Order::all()->sortByDesc('created_at')->first();

        foreach($result as $datas) {
            array_push($data, [
                'user_id' => $datas['user_id'],
                'product_id' => $datas['product_id'],
                'product_name' => $datas['product']['name'],
                'product_image' => $datas['product']['image'],
                'quantity' => $datas['quantity'],
                'price' => $datas['product']['price'] ,
                'total' => $datas['quantity'] * $datas['product']['price'],
                'order_id' => $last_id->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }

        OrderItem::insert($data);
        Cart::destroy($cart_checked);

        return redirect('/status');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }

    public function generatePDF(Request $request)
    {
        // $users = User::get();
  
        // $data = [
        //     'title' => 'Welcome to ItSolutionStuff.com',
        //     'date' => date('m/d/Y'),
        //     'users' => $users
        // ]; 
        $order_id = $request->order_id;
        // dd($order_id);
        $orders = OrderItem::with(['product', 'order'])->where('order_id', '=', $order_id)->get();
        // dd(json_decode($orders));
        // return view('orders.detail', compact('orders'));

        $pdf = PDF::loadView('invoice', [
            'orders' => $orders
        ]);
     
        // return $pdf->download('invoice_apotek_lambanda.pdf');
        return $pdf->stream("invoice_apotek_lambanda_".$order_id.".pdf");
    }

    public function rejectOrderByCustomer(Request $request) {
        $order_id = $request->order_id;

        Order::find($order_id)->update([
            'status' => 'Dibatalkan User'
        ]);
        return redirect('/order');
    }
}
