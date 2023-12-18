<?php

namespace App\Http\Controllers\Frontend;

use App\Models\CropCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\CropCategoryRequest;

class CropCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $crop_categories = CropCategory::with(['crops', 'crops.image'])->latest()->get();

        $response = ["message" => "Crop Categories", "data" => $crop_categories];
        return response($response, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CropCategoryRequest $request)
    {
        $crop_category = CropCategory::create($request->all());

        $response = ['message' => "Crop Category Created Successfully.", 'data' => $crop_category];
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
    public function update(CropCategoryRequest $request, CropCategory $crop_category)
    {
        $crop_category->update($request->all());

        $response = ['message' => "Crop Category Updated Successfully.", 'data' => $crop_category];
        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(CropCategory $crop_category)
    {
        //Deleting Crop Category
        $crop_category->delete();

        $response = ['message' => "Crop Category Deleted Successfully.", 'data' => $crop_category];
        return response()->json($response, 200);
    }
}
