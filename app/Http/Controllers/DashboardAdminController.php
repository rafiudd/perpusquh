<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\Notification;
use Hash;

class DashboardAdminController extends Controller
{
    public function index()
    {
        // $notification = Notification::where('is_read', '=', '0')->orderBy('updated_at', 'DESC')->get()->unique('product_id');;

        // dd(count($notification));
        return view('admin.dashboard');
    }  

    public function orders(Request $request)
    {
        // $orders = Order::where('status', '=', 'Checking Admin')->orderBy('created_at', 'DESC')->with(['user', 'order_items'])->get();
        $orders = Order::whereIn('status', ['Checking Admin', 'Berhasil', 'Dibatalkan', 'Dibatalkan User'])->orderBy('updated_at', 'DESC')->with(['user', 'order_items'])->paginate(8);

        // dd(json_decode($orders));
        // dd( request()->all() );
        // dd($status);

        return view('admin.orders.list', compact('orders'));
    }

    public function searchOrder(Request $request)
	{
		// menangkap data pencarian
		$keyword = $request->keyword;
        $status = $request->get('status');


		$users = User::orderBy('created_at', 'DESC')->where('role', '=', 'customer')->where('name', 'LIKE', "%".$keyword."%")->get();
        $user_id = array();

        foreach ($users as $user) {
            array_push($user_id, $user->id);
        }

        // dd($user_id);

        $orders;
        if($status != null) {
            $orders = Order::join('users', 'orders.user_id', '=', 'users.id')->join('order_items', 'order_items.order_id', '=', 'order_items.id')->where('status', '=', $status)->whereIn('orders.user_id', $user_id)->paginate(20);
            // $orders = Order::pagination(8)->with(['user', 'order_items'])->where('status', '=', $status)->whereIn('user_id', $user_id);
        } else {
            $orders = Order::whereIn('status', ['Checking Admin', 'Berhasil', 'Dibatalkan', 'Dibatalkan User'])->orderBy('updated_at', 'DESC')->with(['user', 'order_items'])->whereIn('orders.user_id', $user_id)->paginate(8);
            // $orders = Order::join('users', 'orders.user_id', '=', 'users.id')->join('order_items', 'order_items.order_id', '=', 'order_items.id')->whereIn('user_id', $user_id)->whereIn('status', ['Checking Admin', 'Berhasil', 'Dibatalkan', 'Dibatalkan User'])->pagination(8);
        }

        return view('admin.orders.list', compact('orders'));
	}

    public function approveOrder(Request $request) {
        $order_id = $request->order_id;

        // update quantity product
        $order_items = OrderItem::with(['product', 'order'])->where('order_id', '=', $order_id)->get();
        foreach ($order_items as $item) {
            Product::find($item->product_id)->update([
                'stock' => $item['product']['stock'] - $item->quantity
            ]);

            if(($item['product']['stock'] - $item->quantity) < 50) {
                // dd($item['product']['stock'] - $item->quantity);
                Notification::create([
                    'product_id' => $item->product_id,
                    'product_name' => $item->product_name,
                    'product_stock'=> $item['product']['stock'] - $item->quantity,
                    'is_read'=> 0,
                ]);
            }
        }

        Order::find($order_id)->update([
            'status' => 'Berhasil'
        ]);
        return redirect('/dashboard/order-management');
    }

    public function rejectOrder(Request $request) {
        $order_id = $request->order_id;

        Order::find($order_id)->update([
            'status' => 'Dibatalkan'
        ]);
        return redirect('/dashboard/order-management');
    }

    public function takenOrder(Request $request) {
        $order_id = $request->order_id;

        Order::find($order_id)->update([
            'status' => 'Berhasil Diambil'
        ]);
        return redirect('/dashboard/order-management');
    }

    public function deleteOrder(Request $request) {
        $order_id = $request->order_id;

        Order::find($order_id)->delete();
        return redirect('/dashboard/order-management');
    }

    public function getDetailOrder(Request $request)
    {
        $order_id = $request->order_id;
        $orders = OrderItem::with(['product', 'order'])->where('order_id', '=', $order_id)->get();
        // dd(json_decode($orders));
        return view('admin.orders.detail', compact('orders'));
    }

    public function getProducts()
    {
        $products = Product::paginate(8);
        $expired_dates = array();
        $status = array();

        foreach ($products as $product) {
            // dd($product);
            array_push($expired_dates, $product->expired_date);
        }

        $today = date("Y-m-d H:i:s");

        foreach ($expired_dates as $expired_date) {
            // dd($today, $expired_date);

            if($expired_date > $today) {
                array_push($status, "Belum Expired");
            } else {
                array_push($status, "Expired");
            }
        }

        // dd($status);


        return view('admin.products.list', compact('products', 'status'));
    }

    public function createProduct() {
        return view('admin.products.create');
    }

    public function storeProduct(Request $request) {
        $input = $request->all();

        $check = Product::where('code', '=', $request['code'])->first();
        if($check) {
            return redirect("/dashboard/product-management/create")->with(['product_validation' => 'The credentials do not match our records']);
        }
  
        if ($image = $request->file('image')) {
            $destinationPath = 'images/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['image'] = "/images/$profileImage";
        }
    
        Product::create($input);
     
        return redirect('/dashboard/product-management');
    }

    public function deleteProduct(Request $request) {
        $product_id = $request->product_id;

        Product::find($product_id)->delete();
        return redirect('/dashboard/product-management');
    }

    public function editProduct(Request $request) {
        $product_id = $request->product_id;
        // dd($product_id);
        $product = Product::where('id', '=', $product_id)->first();
        // dd($product);

        return view('admin.products.edit', compact('product'));
    }

    public function updateProduct(Request $request)
    {
        // $request->validate([
        //     'name' => 'required',
        //     'detail' => 'required'
        // ]);
        $product_id = $request->product_id;
        $input = $request->all();
        // dd($input);
        if ($image = $request->file('image')) {
            $destinationPath = 'images/';
            $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
            $image->move($destinationPath, $profileImage);
            $input['image'] = "/images/$profileImage";
        }else{
            unset($input['image']);
        }
        $updated = Product::find($product_id)->update($input);

        // dd($request);
        if ($request->stock > 50) {
            Notification::where('product_id', '=', $product_id)->update([
                'is_read' => 1
            ]);
        }
        // $updated = $product->update($input);
        // dd($updated);
    
        return redirect("/dashboard/product-management");
    }

    public function searchProduct(Request $request)
	{
		// menangkap data pencarian
		$keyword = $request->keyword;
		$expired_date = $request->expired_date;
        // dd($expired_date);
        $expired_dates = array();
        $status = array();

        if($keyword) {
            $products = Product::where('name', 'LIKE', "%".$keyword."%")->paginate(8);
            foreach ($products as $product) {
                // dd($product);
                array_push($expired_dates, $product->expired_date);
            }
        }

        if($expired_date) {
            $products = Product::whereDate('expired_date','=',$expired_date)->paginate(8);
            foreach ($products as $product) {
                // dd($product);
                array_push($expired_dates, $product->expired_date);
            }
        }
        
        $today = date("Y-m-d H:i:s");

        foreach ($expired_dates as $expired_date) {
            // dd($today, $expired_date);

            if($expired_date > $today) {
                array_push($status, "Belum Expired");
            } else {
                array_push($status, "Expired");
            }
        }

        if(!$keyword && !$expired_date) {
            $products = Product::paginate(8);

            foreach ($products as $product) {
                // dd($product);
                array_push($expired_dates, $product->expired_date);
            }
    
            $today = date("Y-m-d H:i:s");
    
            foreach ($expired_dates as $expired_date) {
                // dd($today, $expired_date);
    
                if($expired_date > $today) {
                    array_push($status, "Belum Expired");
                } else {
                    array_push($status, "Expired");
                }
            }
            return view('admin.products.list', compact('products', 'status'));
        }
 
        return view('admin.products.list', compact('products', 'status'));
	}

    public function getUsers()
    {
        $users = User::paginate(8);
        return view('admin.users.list', compact('users'));
    }

    public function createUser() {
        return view('admin.users.create');
    }

    public function storeUser(Request $request) {
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        User::create($input);
     
        return redirect('/dashboard/user-management');
    }

    public function deleteUser(Request $request) {
        $user_id = $request->user_id;

        User::find($user_id)->delete();
        return redirect('/dashboard/user-management');
    }

    public function editUser(Request $request) {
        $user_id = $request->user_id;
        $user = User::where('id', '=', $user_id)->first();

        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request)
    {
        $user_id = $request->user_id;
        $input = $request->all();

        $updated = User::find($user_id)->update($input);

        return redirect("/dashboard/user-management");
    }

    public function searchUser(Request $request)
	{
		// menangkap data pencarian
		$keyword = $request->keyword;
		$users = User::where('name', 'LIKE', "%".$keyword."%")->paginate(8);
 
        return view('admin.users.list', compact('users'));
	}

    public function orderHistory(Request $request) {
        // dd("sda");
        $orders = Order::whereIn('status', ['Berhasil Diambil'])->orderBy('updated_at', 'DESC')->with(['user', 'order_items'])->paginate(8);

        return view('admin.history.list', compact('orders'));
    }

    public function getDetailHistory(Request $request) {
        $order_id = $request->order_id;
        $orders = OrderItem::with(['product', 'order'])->where('order_id', '=', $order_id)->get();
        // dd(json_decode($orders));
        return view('admin.history.detail', compact('orders'));
    }


    public function searchHistory(Request $request)
	{
		// menangkap data pencarian
		$keyword = $request->keyword;
        $status = $request->get('status');


		$users = User::orderBy('created_at', 'DESC')->where('name', 'LIKE', "%".$keyword."%")->get();
        $user_id = array();

        foreach ($users as $user) {
            array_push($user_id, $user->id);
        }

        $orders;
        // dd($user_id);
        if($status != null) {
            $orders = Order::with(['user', 'order_items'])->where('status', '=', $status)->whereIn('user_id', $user_id)->paginate(8);
        } else {
            $orders = Order::with(['user', 'order_items'])->whereIn('user_id', $user_id)->whereIn('status', ['Berhasil Diambil'])->orderBy('created_at', 'DESC')->paginate(8);
        }

        return view('admin.history.list', compact('orders'));
	}
}
