<?php

namespace App\Http\Controllers\Frontend;

use Exception;
use App\Models\User;
use App\Models\Media;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\MediaController;

class UserController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request)
    {
        $user = User::with('image')->where('id', Auth::id())->first();

        $user->update([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'email' => $request->email,
        ]);

        if ($user && $request->file('file')) {
            try {
                DB::beginTransaction();

                $media = null;
                // If Image is there then fetching media
                if ($user->image) {
                    $media = Media::find($user->image->id);
                }

                // Instantiate MediaController
                $media_controller = new MediaController;

                // Access method in MediaController
                //If media is there then updating else creating a new media
                if ($media) {
                    $media_controller->update($request, 'users', $media);
                } else {
                    $media_controller->store($request, 'users', 'App\Models\User', $user->id);
                }

                DB::commit();
            } catch (Exception $ex) {
                Log::error("User Image Update :" . $ex->getMessage());
                DB::rollBack();
            }
        }

        $user->refresh();

        $response = ['message' => "Profile Updated Successfully.", 'user' => $user];
        return response()->json($response, 200);
    }
}
