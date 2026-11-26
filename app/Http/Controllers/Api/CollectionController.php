<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    /**
     * Menampilkan daftar item koleksi milik user yang sedang login.
     */
    public function index(Request $request): JsonResponse
    {
        // Gunakan $request->user()->id agar sesuai dengan token Sanctum
        $query = Collection::with('category')
            ->where('user_id', $request->user()->id);

        // Filter berdasarkan category_id jika dipilih pada dropdown Flutter
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->query('category_id'));
        }

        $collections = $query->latest()->get();

        return response()->json([
            'message' => 'Daftar koleksi berhasil diambil',
            'data' => $collections,
        ], 200);
    }

    /**
     * Menyimpan item koleksi baru milik user ke kategori tertentu.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'integer'],
            'title' => ['required', 'string', 'max:255'],
            'note' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'rating' => ['nullable', 'string', 'max:20'],
            'image_url' => ['nullable', 'string', 'max:500'],
        ]);

        // Pastikan kategori yang dipilih adalah milik user yang sedang login
        $category = Category::where('user_id', $request->user()->id)
            ->find($validated['category_id']);

        if (!$category) {
            return response()->json([
                'message' => 'Kategori tidak valid atau bukan milik Anda',
            ], 422);
        }

        $collection = Collection::create([
            'user_id' => $request->user()->id,
            'category_id' => $category->id,
            'title' => $validated['title'],
            'note' => $validated['note'] ?? $validated['notes'] ?? null,
            'rating' => $validated['rating'] ?? null,
            'image_url' => $validated['image_url'] ?? null,
        ]);

        $collection->load('category');

        return response()->json([
            'message' => 'Item koleksi berhasil ditambahkan',
            'data' => $collection,
        ], 201);
    }

    /**
     * Menampilkan detail satu item koleksi milik user.
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $collection = Collection::with('category')
            ->where('user_id', $request->user()->id)
            ->find($id);

        if (!$collection) {
            return response()->json(['message' => 'Item koleksi tidak ditemukan'], 404);
        }

        return response()->json([
            'message' => 'Detail koleksi ditemukan',
            'data' => $collection,
        ], 200);
    }

    /**
     * Memperbarui item koleksi milik user.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $collection = Collection::where('user_id', $request->user()->id)->find($id);

        if (!$collection) {
            return response()->json(['message' => 'Item koleksi tidak ditemukan'], 404);
        }

        $validated = $request->validate([
            'category_id' => ['required', 'integer'],
            'title' => ['required', 'string', 'max:255'],
            'note' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'rating' => ['nullable', 'string', 'max:20'],
            'image_url' => ['nullable', 'string', 'max:500'],
        ]);

        $category = Category::where('user_id', $request->user()->id)
            ->find($validated['category_id']);

        if (!$category) {
            return response()->json([
                'message' => 'Kategori tidak valid atau bukan milik Anda',
            ], 422);
        }

        $collection->update([
            'category_id' => $category->id,
            'title' => $validated['title'],
            'note' => $validated['note'] ?? $validated['notes'] ?? $collection->note,
            'rating' => $validated['rating'] ?? $collection->rating,
            'image_url' => $validated['image_url'] ?? $collection->image_url,
        ]);

        $collection->load('category');

        return response()->json([
            'message' => 'Item koleksi berhasil diperbarui',
            'data' => $collection,
        ], 200);
    }

    /**
     * Menghapus item koleksi milik user.
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $collection = Collection::where('user_id', $request->user()->id)->find($id);

        if (!$collection) {
            return response()->json(['message' => 'Item koleksi tidak ditemukan'], 404);
        }

        $collection->delete();

        return response()->json([
            'message' => 'Item koleksi berhasil dihapus',
        ], 200);
    }
}