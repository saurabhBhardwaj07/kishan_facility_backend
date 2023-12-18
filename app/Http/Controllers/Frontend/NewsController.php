<?php

namespace App\Http\Controllers\Frontend;

use Exception;
use App\Models\News;
use App\Models\Media;
use App\Http\Requests\NewsRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MediaController;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $news = News::with('image')->latest()->get();

        $response = ["message" => "News List", "data" => $news];
        return response($response, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NewsRequest $request)
    {
        $news = News::create($request->all());

        if ($news) {
            try {
                DB::beginTransaction();
                // Instantiate MediaController
                $media_controller = new MediaController;
                // Access method in MediaController
                $media_controller->store($request, 'news', 'App\Models\News', $news->id);

                DB::commit();
            } catch (Exception $ex) {
                Log::error("News Image Store :" . $ex->getMessage());
                DB::rollBack();
            }
        }

        $response = ['message' => "News Created Successfully.", 'data' => $news];
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
    public function update(NewsRequest $request, News $news)
    {
        $news->update($request->all());

        if ($news && $request->file('file')) {
            try {
                DB::beginTransaction();

                $media = null;
                // If Image is there then fetching media
                if ($news->image) {
                    $media = Media::find($news->image->id);
                }

                // Instantiate MediaController
                $media_controller = new MediaController;

                // Access method in MediaController
                //If media is there then updating else creating a new media
                if ($media) {
                    $media_controller->update($request, 'news', $media);
                } else {
                    $media_controller->store($request, 'news', 'App\Models\News', $news->id);
                }

                DB::commit();
            } catch (Exception $ex) {
                Log::error("News Image Update :" . $ex->getMessage());
                DB::rollBack();
            }
        }

        $news->refresh();

        $response = ['message' => "News Updated Successfully.", 'data' => $news];
        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(News $news)
    {
        //Deleting News Image
        if($news->image){
            $news->image->delete();
        }

        //Deleting News
        $news->delete();

        $response = ['message' => "News Deleted Successfully.", 'data' => $news];
        return response()->json($response, 200);
    }
}
