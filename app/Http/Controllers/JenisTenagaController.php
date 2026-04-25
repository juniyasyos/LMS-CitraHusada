<?php

namespace App\Http\Controllers;

use App\Models\JenisTenaga;
use Illuminate\Http\Request;

class JenisTenagaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // return all jenis tenaga entries
        try {
            $data = JenisTenaga::all();
            return response()->json([
                'success' => true,
                'message' => 'Data jenis tenaga berhasil diambil',
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data jenis tenaga',
                'data' => null
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'jenis_tenaga' => 'required|string|max:255',
                'deskripsi' => 'nullable|string',
            ]);

            $jenisTenaga = JenisTenaga::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Jenis tenaga berhasil dibuat',
                'data' => $jenisTenaga
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'data' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat jenis tenaga',
                'data' => null
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(JenisTenaga $jenisTenaga)
    {
        try {
            return response()->json([
                'success' => true,
                'message' => 'Detail jenis tenaga berhasil diambil',
                'data' => $jenisTenaga
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil detail jenis tenaga',
                'data' => null
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JenisTenaga $jenisTenaga)
    {
        try {
            $validated = $request->validate([
                'jenis_tenaga' => 'required|string|max:255',
                'deskripsi' => 'nullable|string',
            ]);

            $jenisTenaga->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Jenis tenaga berhasil diperbarui',
                'data' => $jenisTenaga
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'data' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui jenis tenaga',
                'data' => null
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JenisTenaga $jenisTenaga)
    {
        try {
            $jenisTenaga->delete();

            return response()->json([
                'success' => true,
                'message' => 'Jenis tenaga berhasil dihapus',
                'data' => null
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus jenis tenaga',
                'data' => null
            ], 500);
        }
    }
}
