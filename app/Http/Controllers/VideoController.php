<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use JD\Cloudder\Facades\Cloudder;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        try {

            $videos = auth()->user()->videos;
        } catch (\Throwable $th) {
            //throw $th;
            return $this->errorResponse("No videos found");
        }

        return $this->successResponse("Success", $videos);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'videos' => 'required|array',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors());
        }

        $videoUrls = collect([]);
        try {
            //code...
            $files = collect([]);
            foreach ($request->file('videos') as $video) {

                error_log(json_encode($video->getRealPath()));
                Cloudder::uploadVideo($video->getRealPath(), null);
                $uploadedFile = Cloudder::getResult();

                auth()->user()->videos()->create([
                    'name' => $video->getClientOriginalName(),
                    'url' => $uploadedFile['url'],
                ]);

                $files->push($uploadedFile);
            }

            error_log(json_encode($files));
        } catch (\Throwable $th) {
            //throw $th;
            return $this->errorResponse($th->getMessage());

        }

        return $this->successResponse("Success");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function show(Video $video)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Video $video)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function destroy(Video $video)
    {
        //
    }
}
