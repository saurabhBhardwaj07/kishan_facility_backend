<?php

namespace App\Http\Controllers\Frontend;

use App\Models\User;
use App\Models\Product;
use App\Models\UserOrder;
use App\Models\OrderProduct;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = UserOrder::with(['user', 'address', 'orderProducts', 'orderProducts.product'])->latest()->get();

        $response = ["message" => "Order List", "data" => $orders];
        return response($response, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = User::with(['defaultAddress', 'products'])->where('id', Auth::id())->first();
        if ($user) {

            $user_products = $user->products;
            if ($user_products->count() == 0) {
                $response = ["message" => 'User does not has products in cart'];
                return response($response, 422);
            }

            $user_address = $user->defaultAddress;
            if ($user_address->count() == 0) {
                $response = ["message" => 'User does not has default address'];
                return response($response, 422);
            }

            $total_price = $user_products->sum('price');

            $user_order = UserOrder::create([
                'user_id' => Auth::id(),
                'user_address_id' => $user_address->id,
                'total_price' => $total_price,
            ]);

            if ($user_order) {

                foreach ($user_products as $user_product) {

                    $order_product = OrderProduct::create([
                        'order_id' => $user_order->id,
                        'product_id' => $user_product->product_id,
                        'quantity' => $user_product->quantity,
                        'price' => $user_product->price,
                    ]);

                    if($order_product){
                        //Subtracting the quantity from products
                        $product = Product::where('id', $user_product->product_id)->first();
                        $remaining_quantity = $product->quantity - $user_product->quantity;
                        $product->update(['quantity' => $remaining_quantity]);

                        //Deleting Product from cart
                        $user_product->delete();
                    }
                }
            }

            $order = UserOrder::with(['user', 'address', 'orderProducts', 'orderProducts.product'])->where('id', $user_order->id)->first();

            $response = ["message" => "Order Placed Successfully.", "data" => $order];
            return response($response, 200);
        } else {
            $response = ["message" => 'User does not exist'];
            return response($response, 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
