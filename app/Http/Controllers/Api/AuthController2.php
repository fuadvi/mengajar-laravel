<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController2 extends Controller
{
    public function Register(Request $request)
    {
        // nampung semua data dari form
        $data = $request->all();

        // aturan validasi form
        $val = Validator::make(
            $data,
            [
                "name" => "required|string",
                "email" => "required|email|unique:users",
                "password" => "required|string|min:8",
            ]
        );

        // jika validasi gagal
        if ($val->fails()) {
            return response()->json([
                "status" => "error",
                "message" => $val->errors()
            ], 400);
        }

        // encripsi password
        $data['password'] = Hash::make($data['password']);


        // menambah data ke table user
        $user = User::create($data);

        // mengirim response berhasil tambah user baru
        return response()->json([
            "status" => "success",
            "message" => "User berhasil ditambahkan",
            "data" => $user
        ], 200);
    }

    public function Login(Request $request)
    {
        $data = $request->all();

        // aturan validasi form
        $val = Validator::make(
            $data,
            [
                "email" => "required|email",
                "password" => "required|string|min:8",
            ]
        );

        // jika validasi gagal
        if ($val->fails()) {
            return response()->json([
                "status" => "error",
                "message" => $val->errors()
            ], 400);
        }

        // cek email yang di input ada di database atau tidak
        $user = User::where('email', $request->email)->first();

        // jika email tidak ada di database
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(
                [
                    "status" => "error",
                    "message" => "Email atau password salah"
                ],
                400
            );
        }

        $token = $user->createToken('token')->plainTextToken;

        return response()->json(
            [
                "status" => "success",
                "message" => "Login berhasil",
                "data" => [
                    "user" => $user,
                    "token" => $token
                ]
            ],
            200
        );
    }
}
