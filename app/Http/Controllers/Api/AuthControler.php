<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthControler extends Controller
{

    public function index()
    {
        $user = User::with('sekolah')->get();
        return response()->json(
            [
                'status' => 'success',
                'message' => 'berhasil mengambil data user',
                'data' => $user
            ],
        );
    }

    public function Register(Request $request)
    {
        // gambil data dari form
        $data = $request->all();

        // validasi form
        $val = Validator::make(
            $data,
            [
                'name' => 'required|string',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:8',
            ]
        );

        if ($val->fails()) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => $val->errors()
                ],
                422
            );
        }


        // encrypsi passowrd
        $data['password'] = Hash::make($request->password);

        // nambah data ke table user
        $user = User::create($data);


        // kirim respon ke forntend
        return response()->json(
            [
                'status' => 'success',
                'message' => 'berhasil nambah user',
                'data' => $user
            ],
            200
        );
    }

    public function Login(Request $request)
    {
        // validasi form
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        $user = User::where("email", $request->email)->first();


        if (!$user || Hash::check($request->password, $user->passowrd)) {
            return response()->json(
                [
                    "massage" => "user tidak ditemukan",
                ],
                400
            );
        }

        // membuat token
        $token = $user->createToken('token')->plainTextToken;

        return response()->json(
            [
                'status' => 'success',
                'message' => 'berhasil login',
                'data' => [
                    'user' => $user,
                    'token' => $token
                ]
            ],
            200
        );
    }

    public function destroy(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();
        return response()->json(
            [
                "massage" => "berhasil logout"
            ],
            200
        );
    }

    public function update(Request $request)
    {
        $val = Validator::make(
            $request->all(),
            [
                'name' => 'required|string',
                'email' => 'required|email',
                'password' => 'required|min:8',
            ]
        );

        if ($val->fails()) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => $val->errors()
                ],
                422
            );
        }

        $data = $request->all();
        $data['password'] = Hash::make($request->password);
        $user = $request->user();
        $user->update($data);
        return response()->json(
            [
                "massage" => "berhasil update",
                "data" => $user
            ],
            200
        );
    }
}
