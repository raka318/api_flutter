<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Watchlist;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WatchlistController extends Controller
{
    /**
     * Menampilkan seluruh daftar watchlist milik user yang sedang login.
     */
    public function index(Request $request): JsonResponse
    {
        // Ambil data watchlist khusus milik user yang sedang login, urutkan dari terbaru
        $watchlists = Watchlist::where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar watchlist berhasil diambil',
            'data' => $watchlists,
        ], 200);
    }

    /**
     * Menyimpan film Studio Ghibli ke dalam daftar watchlist user.
     */
    public function store(Request $request): JsonResponse
    {
        // Validasi semua input dari Flutter langsung di controller
        $validator = Validator::make($request->all(), [
            'film_id' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'image' => 'required|string',
            'director' => 'required|string|max:255',
            'rt_score' => 'required|string|max:20',
            'personal_note' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data film tidak lengkap atau tidak valid',
                'errors' => $validator->errors(),
            ], 422);
        }

        $userId = $request->user()->id;

        // Cek apakah film dengan film_id ini sudah pernah disimpan oleh user yang sama
        $alreadyExists = Watchlist::where('user_id', $userId)
            ->where('film_id', $request->film_id)
            ->exists();

        if ($alreadyExists) {
            return response()->json([
                'success' => false,
                'message' => 'Film ini sudah ada di dalam Watchlist kamu',
            ], 409);
        }

        // Simpan data baru ke tabel watchlists
        $watchlist = Watchlist::create([
            'user_id' => $userId,
            'film_id' => $request->film_id,
            'title' => $request->title,
            'image' => $request->image,
            'director' => $request->director,
            'rt_score' => $request->rt_score,
            'personal_note' => $request->personal_note,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Film berhasil ditambahkan ke Watchlist',
            'data' => $watchlist,
        ], 201);
    }

    /**
     * Memperbarui catatan pribadi (personal_note) pada item watchlist milik user.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        // Validasi input catatan pribadi
        $validator = Validator::make($request->all(), [
            'personal_note' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi catatan gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Cari item watchlist berdasarkan ID dan pastikan milik user yang login
        $watchlist = Watchlist::where('user_id', $request->user()->id)
            ->where('id', $id)
            ->first();

        if (!$watchlist) {
            return response()->json([
                'success' => false,
                'message' => 'Data watchlist tidak ditemukan',
            ], 404);
        }

        // Perbarui kolom personal_note
        $watchlist->update([
            'personal_note' => $request->personal_note,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Catatan film berhasil diperbarui',
            'data' => $watchlist,
        ], 200);
    }

    /**
     * Menghapus film dari daftar watchlist milik user.
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        // Cari item watchlist berdasarkan ID dan pastikan milik user yang login
        $watchlist = Watchlist::where('user_id', $request->user()->id)
            ->where('id', $id)
            ->first();

        if (!$watchlist) {
            return response()->json([
                'success' => false,
                'message' => 'Data watchlist tidak ditemukan',
            ], 404);
        }

        // Hapus data dari database
        $watchlist->delete();

        return response()->json([
            'success' => true,
            'message' => 'Film berhasil dihapus dari Watchlist',
        ], 200);
    }
}