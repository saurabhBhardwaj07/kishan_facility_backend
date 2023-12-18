<?php

namespace App\Http\Controllers\Frontend;

use App\Models\UserAddress;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserAddressRequest;

class UserAddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_addresses = UserAddress::where('user_id', Auth::id())->latest()->get();

        $response = ["message" => "User Addresses", "data" => $user_addresses];
        return response($response, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserAddressRequest $request)
    {
        $user_address = UserAddress::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'address' => $request->address,
            'country' => $request->country,
            'state' => $request->state,
            'city' => $request->city,
            'pincode' => $request->pincode,
            'is_default' => $request->is_default,
        ]);

        if ($request->is_default == 1) {
            UserAddress::where('user_id', Auth::id())->whereNot('id', $user_address->id)->update(['is_default' => '0']);
        }

        $response = ['message' => "Address Created Successfully.", 'data' => $user_address];
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
    public function update(UserAddressRequest $request, UserAddress $user_address)
    {
        $user_address->update([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'address' => $request->address,
            'country' => $request->country,
            'state' => $request->state,
            'city' => $request->city,
            'pincode' => $request->pincode,
            'is_default' => $request->is_default,
        ]);

        if ($request->is_default == 1) {
            UserAddress::where('user_id', Auth::id())->whereNot('id', $user_address->id)->update(['is_default' => '0']);
        }

        $response = ['message' => "Address Successfully.", 'data' => $user_address];
        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserAddress $user_address)
    {
        //Deleting User Address
        $user_address->delete();

        $response = ['message' => "Address Deleted Successfully.", 'data' => $user_address];
        return response()->json($response, 200);
    }
}
