<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PhotoController extends Controller
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
            //code...
            $photos = auth()->user()->photos->getMedia('photos');

            $photoUrls = collect([]);

            foreach($photos as $photo) {
                $photoUrls->push($photo->getFullUrl()."");
            }

            error_log(json_encode($photoUrls));
        } catch (\Throwable $th) {
            //throw $th;
            return $this->errorResponse("No Photos found");
        }

        return $this->successResponse("Success", $photoUrls);
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
        error_log(json_encode($_FILES));
        error_log(json_encode($request->file('photos')));
        $validator = Validator::make($request->all(), [
            'photos' => 'required|array',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors());
        }

        try {
            //code...
            $photos = auth()->user()->photos->addAllMediaFromRequest('photos')->each(function ($fileAdder) {
                $fileAdder->toMediaCollection('photos');
            });


        } catch (\Throwable $th) {
            //throw $th;
            return $this->errorResponse($th->getMessage());
        }

        return $this->successResponse("Success");


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function show(Photo $photo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Photo $photo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Photo  $photo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Photo $photo)
    {
        //
    }
}
