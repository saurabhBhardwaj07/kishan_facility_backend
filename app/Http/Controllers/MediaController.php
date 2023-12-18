<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($type, $filename)
    {
        $file = storage_path('app/private_uploads/') . $type . '/' . $filename;

        $extension = pathinfo($file, PATHINFO_EXTENSION);

        if ($extension == 'pdf') {
            $content_type = 'application/' . $extension . '';
        } else {
            $content_type = 'image/' . $extension . '';
        }

        if (file_exists($file)) {

            $headers = [
                'Content-Type' => $content_type
            ];

            return response()->download($file, $filename, $headers, 'inline');
        } else {
            abort(403, 'Unauthorize access');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $type, $mediable_type, $mediable_id)
    {
        if ($request->file('file')) {

            $file = $request->file('file');
            $original_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $fileName = uniqid() . '.' . $extension;
            $path = $request->file->storeAs($type, $fileName, 'private_uploads');

            $media = Media::create([
                'type' => $type,
                'name' => $original_name,
                'path' => $fileName,
                'extension' => $extension,
                // 'title' => $request->title,
                // 'notes' => $request->notes,
                'mediable_type' => $mediable_type,
                'mediable_id' => $mediable_id,
            ]);

            return $media;
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
    public function update(Request $request, $type, Media $media)
    {
        if ($request->file('file')) {

            $old_file_path = $media->type . '/' . $media->path;

            $file = $request->file('file');
            $original_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $fileName = uniqid() . '.' . $extension;
            $path = $request->file->storeAs($type, $fileName, 'private_uploads');

            $media->update([
                'name' => $original_name,
                'path' => $fileName,
                'extension' => $extension,
            ]);

            if (Storage::disk('private_uploads')->exists($path) && Storage::disk('private_uploads')->exists($old_file_path)) {
                Storage::disk('private_uploads')->delete($old_file_path);
            }
        }

        // $media->update([
        //     'title' => $request->title,
        //     'notes' => $request->notes,
        // ]);
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
