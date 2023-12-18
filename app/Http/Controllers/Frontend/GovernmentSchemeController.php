<?php

namespace App\Http\Controllers\Frontend;

use App\Models\GovernmentScheme;
use App\Http\Controllers\Controller;
use App\Http\Requests\GovernmentSchemeRequest;

class GovernmentSchemeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $government_schemes = GovernmentScheme::latest()->get();

        $response = ["message" => "Government Schemes", "data" => $government_schemes];
        return response($response, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GovernmentSchemeRequest $request)
    {
        $government_scheme = GovernmentScheme::create($request->all());

        $response = ['message' => "Government Scheme Created Successfully.", 'data' => $government_scheme];
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
    public function update(GovernmentSchemeRequest $request, GovernmentScheme $government_scheme)
    {
        $government_scheme->update($request->all());

        $response = ['message' => "Government Scheme Updated Successfully.", 'data' => $government_scheme];
        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(GovernmentScheme $government_scheme)
    {
        //Deleting Government Scheme
        $government_scheme->delete();

        $response = ['message' => "Government Scheme Deleted Successfully.", 'data' => $government_scheme];
        return response()->json($response, 200);
    }
}
