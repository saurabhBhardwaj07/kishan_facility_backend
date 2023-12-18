<?php

namespace App\Http\Controllers\Frontend;

use App\Models\UserProduct;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserProductRequest;

class UserProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_products = UserProduct::with(['product', 'product.image', 'product.productCategory'])->where('user_id', Auth::id())->latest()->get();

        $response = ["message" => "Cart List", "data" => $user_products];
        return response($response, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserProductRequest $request)
    {
        $user_product = UserProduct::where([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id
        ])->first();

        if ($user_product) {
            $user_product->update([
                'quantity' => $user_product->quantity + $request->quantity,
                'price' => $user_product->price + $request->price,
            ]);
            $message = "Cart Updated Successfully.";
        } else {
            $user_product = UserProduct::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'price' => $request->price,
            ]);
            $message = "Product Added to cart Successfully.";
        }

        $response = ['message' => $message, 'data' => $user_product];
        return response()->json($response, 200);
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
    public function update(UserProductRequest $request, UserProduct $user_product)
    {
        $user_product->update([
            'quantity' => $request->quantity,
            'price' => $request->price,
        ]);

        $response = ['message' => "Cart Updated Successfully.", 'data' => $user_product];
        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserProduct $user_product)
    {
        //Deleting user product
        $user_product->delete();

        $response = ['message' => "Product Removed from cart Successfully.", 'data' => $user_product];
        return response()->json($response, 200);
    }
}
