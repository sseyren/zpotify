<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\APIBaseController;
use Illuminate\Support\Facades\Storage;
use App\Track;
use JWTAuth;
use Validator;
use Image;

class TrackController extends APIBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tracks = Track::orderBy("id", "desc")->paginate(15, ["*"], "page");
        return $this->sendResponse($tracks);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "artist" => "required|string|max:255",
            "album" => "max:255",
            "name" => "required|string|max:255",
            "content" => "required|file|max:10000",
            "cover" => "file|max:1000",
        ]);

        if ($validator->fails()){
            return $this->sendError("Validation error.", $validator->errors(), 406);
        }

        if(!$request->file("content")->isValid()){
            return $this->sendError($error = "File error.", $code = 500);
        }

        $user = JWTAuth::parseToken()->authenticate();

        $track = new Track;
        $track->uploader = $user["username"];
        $track->artist = $request["artist"];
        $track->album = $request["album"];
        $track->name = $request["name"];
        ($request->file("cover") && $request->file("cover")->isValid()) ? $track->coverExist = true : $track->coverExist = false;
        $track->save();
        
        $request->content->storeAs("contents", $track->id . ".mp3");

        if ($request->hasFile("cover") && $request->file("cover")->isValid()){
            $image = Image::make($request->cover)->resize(300, 300)->save("storage/covers/". $track->id. ".jpg");
        }

        return $this->sendResponse($track, $code = 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Track $track)
    {
        return $this->sendResponse(array_merge($track->toArray(), ["randomTrack" => rand(1, Track::count())]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $track = Track::find($id);
        $authUser = JWTAuth::parseToken()->authenticate();
        if($track["uploader"] != $authUser["username"])
            return $this->sendError("Permission error.");
        
        $input = $request->all();
        $validator = Validator::make($input, [
            "artist" => "string|max:255",
            "album" => "max:255",
            "name" => "string|max:255",
            "cover" => "file|max:1000",
            "coverExist" => "boolean"
        ]);
        
        if ($validator->fails()){
            return $this->sendError("Validation error.", $validator->errors(), 406);
        }

        $updatingValues = [];
        if ($input){
            foreach (["artist", "album", "name", "coverExist"] as $value) {
                if(array_key_exists($value, $input))
                    $updatingValues[$value] = $input[$value];
            }
        }

        if($request->hasFile("cover") && $request->file("cover")->isValid()){
            $updatingValues["coverExist"] = true;
            $image = Image::make($request->cover)->resize(300, 300)->save("storage/covers/" .$id. ".jpg");
        }

        $result = $track->update($updatingValues);

        if ($result)
            return $this->sendResponse($track);

        return $this->sendError("Server error.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $track = Track::find($id);
        if(!$track)
            return $this->sendError("Track not found.");
        $authUser = JWTAuth::parseToken()->authenticate();
        if($track["uploader"] != $authUser["username"])
            return $this->sendError("Permission error.");

        if($track->delete()){
            Storage::delete([
                "contents/".$track->id.".mp3",
                "covers/".$track->id.".jpg"    
            ]);
            return $this->sendResponse($track);
        }
            
        return $this->sendError("Server error.");
    }

    public function search(Request $request){
        $results = new Track;
        if ($request->q){
            $results = Track::SearchByKeyword($request->q);
        }
        if($request->user){
            $results = $results->SearchByUser($request->user);
        }
        $results = $results->orderBy("id", "desc")->paginate(15, ["*"], "page");
        return $this->sendResponse($results->appends(["q" => $request->q, "user" => $request->user]));
    }
}
