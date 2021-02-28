<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    //
    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        error_log(json_encode($request->toArray()));

        if ($validator->fails()) {
            error_log(json_encode($validator->errors()));
            return $this->errorResponse($validator->errors());
        }

        $user = User::where('email', $request->email)->first();
        // error_log(json_encode(Hash::check($request->password, $user->password)));
        if ($user == null || !Hash::check($request->password, $user->password)) {
            return $this->errorResponse('The provided credentials are incorrect.');
        }

        return $this->successResponse("Login successful", ['user' => $user->toArray(), 'token' => $user->createToken($request->device_name)->plainTextToken]);
    }

    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'c_password' => 'required|same:password',
            'device_name' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors());
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $user->videos()->create([]);

        $user->photos()->create([]);

        $user->documents()->create([]);

        // return ['token' => $user->createToken($request->device_name)->plainTextToken, 'user' => $user->toArray()];
        return $this->successResponse("Registration successful", ['user' => $user->toArray(), 'token' => $user->createToken($request->device_name)->plainTextToken]);
    }

    public function logout(Request $request)
    {
        error_log(json_encode(auth()->user()));
        if (Auth::user()) {
            $user = Auth::user();

            $user->tokens()->delete();

            return $this->successResponse('Successfully logged out');
        } else {
            return $this->failureResponse('Successfully logged out');
        }
    }

    public function getUser($id) {

        $user = User::find($id);
        return $this->successResponse("Success", $user);
    }
}
