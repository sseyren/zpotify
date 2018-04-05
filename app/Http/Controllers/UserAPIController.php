<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\APIBaseController as APIBaseController;
use App\User;
use Validator;
use Image;
use JWTAuth;

class UserAPIController extends APIBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::paginate(15, ["*"], "page");
        return $this->sendResponse($users, "OK");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'username' => 'required|string|max:255|unique:users|regex:/^[\w-]*$/',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if($validator->fails()){
            return $this->sendError("Validation error.", $validator->errors(), 406);
        }

        $user = User::create([
            "username" => $input["username"],
            "name" => $input["name"],
            "email" => $input["email"],
            "password" => bcrypt($input["password"]),
        ]);

        return $this->sendResponse($user, "User created.", 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($username)
    {
        $user = User::username($username)->get()->toArray();
        $uploadCount = ["uploadCount" => \App\Track::searchByUser($username)->count()];
        return $this->sendResponse(array_merge($user[0], $uploadCount));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $username)
    {
        $authUser = JWTAuth::parseToken()->authenticate();
        if($username != $authUser["username"])
            return $this->sendError("Permission error.");

        $user = User::username($username);
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'string|max:255',
            'email' => 'string|email|max:255|unique:users',
            'password' => 'string|min:6',
            'pp' => 'file|max:1000',
            'ppExist' => 'boolean'
        ]);
        
        if ($validator->fails()){
            return $this->sendError("Validation error.", $validator->errors(), 406);
        }

        $updatingValues = [];
        if ($input){
            if(array_key_exists("password", $input))
                $updatingValues["password"] = bcrypt($input["password"]);
            foreach (["name", "email", "ppExist"] as $value) {
                if(array_key_exists($value, $input))
                    $updatingValues[$value] = $input[$value];
            }
        }

        if($request->hasFile("pp") && $request->file("pp")->isValid()){
            $updatingValues["ppExist"] = true;
            $image = Image::make($request->pp)->resize(300, 300)->save("storage/users/" .$username. ".jpg");
        }

        $result = $user->update($updatingValues);

        if ($result){
            return $this->sendResponse($user);
        }

        return $this->sendError("Server error.");
    }
}
