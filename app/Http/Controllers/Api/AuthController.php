<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\Api\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:users,username',
            'first_name' => 'required',
            'last_name' => 'nullable',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'c_password' => 'required|same:password',
            'role_id' => 'required|exists:roles,name'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        $token = Auth::guard('api')->attempt(['email' => $request->email, 'password' => $request->password]);

        $success['token'] =  $token;
        $success['user'] =  $user;
        $success['role'] = $user->roles[0]->name;

        return $this->sendResponse($success, 'User register successfully.');
    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $token = Auth::guard('api')->attempt(['email' => $request->email, 'password' => $request->password]);
        // return $this->sendResponse($token, 'User login successfully.');
        if ($token) {
            $user = Auth::guard('api')->user();
            $success['token'] =  $token;
            $success['user'] =  $user;
            $success['role'] = $user->roles[0]->name;

            return $this->sendResponse($success, 'User login successfully.');
        } else {
            return $this->sendError('Email/Password is invalid.', []);
        }
    }
}
