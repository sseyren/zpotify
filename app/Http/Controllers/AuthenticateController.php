<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\APIBaseController;
use App\User;

class AuthenticateController extends APIBaseController
{
    public function authenticate(Request $request)
    {
        // grab credentials from the request
        $credentials = $request->only('username', 'password');

        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return $this->sendError("invalid_credentials", ['error' => 'invalid_credentials'], 401);
                //return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return $this->sendError("could_not_create_token", ['error' => 'could_not_create_token'], 500);
            //return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // all good so return the token
        return $this->sendResponse(["user" => User::username($request["username"])->get(), "token" => compact("token")], "OK");
        //return response()->json(compact('token'));
    }
}
