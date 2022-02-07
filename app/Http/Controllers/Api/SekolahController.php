<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sekolah;
use Illuminate\Http\Request;

class SekolahController extends Controller
{

    public function index()
    {
        $sekolah = Sekolah::with('user')->get();

        return response()->json(
            [
                'status' => 'success',
                'message' => 'berhasil mengambil data sekolah',
                'data' => $sekolah
            ],
        );
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $data['user_id'] = $request->user()->id;

        $sekolah = Sekolah::create($data);

        return response()->json(
            [
                'status' => 'success',
                'message' => 'berhasil menambahkan data sekolah',
                'data' => $sekolah
            ],
        );
    }

    public function update($id, Request $request)
    {
        $sekolah = Sekolah::find($id);

        if (!$sekolah) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'data sekolah tidak ditemukan'
                ],
                404
            );
        }

        $sekolah->update($request->all());

        return response()->json(
            [
                'status' => 'success',
                'message' => 'berhasil mengubah data sekolah',
                'data' => $sekolah
            ],
        );
    }

    public function destroy($id)
    {
        $sekolah = Sekolah::find($id);

        if (!$sekolah) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'data sekolah tidak ditemukan'
                ],
                404
            );
        }

        $sekolah->delete();

        return response()->json(
            [
                'status' => 'success',
                'message' => 'berhasil menghapus data sekolah'
            ],
        );
    }
}
