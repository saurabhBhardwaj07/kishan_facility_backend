<?php

namespace App\Http\Controllers\Frontend;

use Exception;
use App\Models\Media;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Controllers\MediaController;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::with(['image', 'productCategory'])->latest()->get();

        $response = ["message" => "Product List", "data" => $products];
        return response($response, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        $product = Product::create($request->all());

        if ($product) {
            try {
                DB::beginTransaction();
                // Instantiate MediaController
                $media_controller = new MediaController;
                // Access method in MediaController
                $media_controller->store($request, 'products', 'App\Models\Product', $product->id);

                DB::commit();
            } catch (Exception $ex) {
                Log::error("Products Image Store :" . $ex->getMessage());
                DB::rollBack();
            }
        }

        $product->refresh();

        $response = ['message' => "Product Created Successfully.", 'data' => $product];
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
    public function update(ProductRequest $request, Product $product)
    {
        $product->update($request->all());

        if ($product && $request->file('file')) {
            try {
                DB::beginTransaction();

                $media = null;
                // If Image is there then fetching media
                if ($product->image) {
                    $media = Media::find($product->image->id);
                }

                // Instantiate MediaController
                $media_controller = new MediaController;

                // Access method in MediaController
                //If media is there then updating else creating a new media
                if ($media) {
                    $media_controller->update($request, 'product', $media);
                } else {
                    $media_controller->store($request, 'product', 'App\Models\Product', $product->id);
                }

                DB::commit();
            } catch (Exception $ex) {
                Log::error("Product Image Update :" . $ex->getMessage());
                DB::rollBack();
            }
        }

        $response = ['message' => "Product Updated Successfully.", 'data' => $product];
        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //Deleting Product Image
        if ($product->image) {
            $product->image->delete();
        }

        //Deleting product
        $product->delete();

        $response = ['message' => "Product Deleted Successfully.", 'data' => $product];
        return response()->json($response, 200);
    }
}
