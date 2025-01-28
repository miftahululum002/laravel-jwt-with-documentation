<?php

namespace App\Http\Controllers;

use App\Libraries\ResponseLibrary;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:api', ['except' => ['login', 'register']]);
    // }

    /**
     * Login
     * 
     * Logging in user.
     * 
     * Digunakan untuk user login ke dalam sistem
     * @unauthenticated
     * 
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email'     => 'required|string|email',
            'password'  => 'required|string',
        ]);
        if ($validate->fails()) {
            return ResponseLibrary::errorResponse('Form input not valid', $validate->errors(), 422);
        }
        $credentials = $request->only('email', 'password');
        try {
            $token = Auth::guard('api')->attempt($credentials);
            if (!$token) {
                return ResponseLibrary::unauthorizeResponse('Credential login Anda tidak valid');
            }
            $user = Auth::guard('api')->user();
            $res = [
                'user'          => $user,
                'authorization' => [
                    'token' => $token,
                    'type'  => 'bearer',
                ]
            ];
            return ResponseLibrary::successResponse('Success', $res);
        } catch (\Exception $e) {
            return ResponseLibrary::internalErrorResponse('Internal server error', $e->getMessage());
        }
    }

    /**
     * Register
     * 
     * Register new user.
     * 
     * Digunakan untuk register user ke dalam sistem
     * @unauthenticated
     * 
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users',
            'password'  => 'required|string|min:6',
        ]);
        if ($validate->fails()) {
            return ResponseLibrary::errorResponse('Form input not valid', $validate->errors(), 422);
        }
        $data = $validate->validated();
        $passwordOri = $data['password'];
        $data['password'] = bcrypt($data['password']);
        try {
            $user = User::create($data);
            $credentials = [
                'email'     => $data['email'],
                'password'  => $passwordOri
            ];
            $token = Auth::guard('api')->attempt($credentials);
            $user->created_by = auth('api')->user()->id;
            $user->save();
            $data = [
                'user' => $user,
                'authorization' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ];
            return ResponseLibrary::successResponse('Register successfully', $data);
        } catch (\Exception $e) {
            return ResponseLibrary::internalErrorResponse('Internal server error', $e->getMessage());
        }
    }

    /**
     * Logout
     * 
     * Logout new user.
     * 
     * Digunakan untuk logout sistem
     * 
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        try {
            Auth::guard('api')->logout();
            return ResponseLibrary::successResponse('Successfully logged out');
        } catch (\Exception $e) {
            return ResponseLibrary::internalErrorResponse('Internal server error', $e->getMessage());
        }
    }

    /**
     * Refresh
     * 
     * Refresh token.
     * 
     * Digunakan untuk refresh token
     * 
     * @return \Illuminate\Http\Response
     */
    public function refresh()
    {
        try {
            $data = [
                'user' => Auth::guard('api')->user(),
                'authorization' => [
                    'token' => Auth::guard('api')->refresh(),
                    'type' => 'bearer',
                ]
            ];
            return ResponseLibrary::successResponse('Success', $data);
        } catch (\Exception $e) {
            return ResponseLibrary::internalErrorResponse('Internal server error', $e->getMessage());
        }
    }

    public function me(Request $request)
    {
        $user = $request->user();
        return ResponseLibrary::successResponse('Success', $user);
    }
}
