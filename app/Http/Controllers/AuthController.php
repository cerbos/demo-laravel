<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    private string $appToken = "appToken";

    public function register(Request $request) {
        $user = User::create([
            "email" => $request->json()->get('email'),
            "password" => bcrypt($request->json()->get('password')),
            "name" => $request->json()->get('name'),
            "roles" => $request->json()->get('roles'),
            "region" => $request->json()->get('region'),
            "department" => $request->json()->get('department'),
        ]);

        return response()->json([
            "email" => $user->email,
            "token" => $user->createToken($this->appToken)->plainTextToken,
        ], 201);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request) {
        $user = User::where([
            'email' => $request->json()->get('email')
        ])->first();

        if(!$user || !Hash::check($request->json()->get('password'), $user->password)) {
            return response()->json(null, 401);
        }

        return response()->json([
            "email" => $user->email,
            "token" => $user->createToken($this->appToken)->plainTextToken,
        ], 201);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();
        return response()->json();
    }
}
