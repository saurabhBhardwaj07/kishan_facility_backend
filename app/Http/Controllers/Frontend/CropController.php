<?php

namespace App\Http\Controllers\Frontend;

use Exception;
use App\Models\Crop;
use App\Models\Media;
use App\Http\Requests\CropRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MediaController;

class CropController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $crops = Crop::with('cropCategory')->latest()->get();

        $response = ["message" => "Crops List", "data" => $crops];
        return response($response, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CropRequest $request)
    {
        $crop = Crop::create($request->all());

        if ($crop) {
            try {
                DB::beginTransaction();
                // Instantiate MediaController
                $media_controller = new MediaController;
                // Access method in MediaController
                $media_controller->store($request, 'crops', 'App\Models\Crop', $crop->id);

                DB::commit();
            } catch (Exception $ex) {
                Log::error("Crop Image Store :" . $ex->getMessage());
                DB::rollBack();
            }
        }

        $crop->refresh();

        $response = ['message' => "Crop Created Successfully.", 'data' => $crop];
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
    public function update(CropRequest $request, Crop $crop)
    {
        $crop->update($request->all());

        if ($crop && $request->file('file')) {
            try {
                DB::beginTransaction();

                $media = null;
                // If Image is there then fetching media
                if ($crop->image) {
                    $media = Media::find($crop->image->id);
                }

                // Instantiate MediaController
                $media_controller = new MediaController;

                // Access method in MediaController
                //If media is there then updating else creating a new media
                if ($media) {
                    $media_controller->update($request, 'crop', $media);
                } else {
                    $media_controller->store($request, 'crop', 'App\Models\Crop', $crop->id);
                }

                DB::commit();
            } catch (Exception $ex) {
                Log::error("Crop Image Update :" . $ex->getMessage());
                DB::rollBack();
            }
        }

        $response = ['message' => "Crop Updated Successfully.", 'data' => $crop];
        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Crop $crop)
    {
        //Deleting Crop Image
        if ($crop->image) {
            $crop->image->delete();
        }

        //Deleting Crop
        $crop->delete();

        $response = ['message' => "Crop Deleted Successfully.", 'data' => $crop];
        return response()->json($response, 200);
    }
}
