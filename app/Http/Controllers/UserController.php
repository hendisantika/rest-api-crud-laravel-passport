<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Validator;

class UserController extends Controller
{
    /**
     * User Register
     */
    public function register(Request $request)
    {
        // validate
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            // return response
            $response = [
                'success' => false,
                'message' => 'Validation Error.', $validator->errors(),
            ];
            return response()->json($response, 404);
        }

        // insert to DB
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $accessToken = $user->createToken('ApplicationName')->accessToken;

        // return response
        $response = [
            'success' => true,
            'message' => 'User registration successful',
            'accessToken' => $accessToken,
        ];
        return response()->json($response, 200);
    }
}
