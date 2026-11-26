<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Menampilkan semua kategori milik user yang sedang login.
     */
    public function index(Request $request): JsonResponse
    {
        $categories = Category::where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json([
            'message' => 'Daftar kategori berhasil diambil',
            'data' => $categories,
        ], 200);
    }

    /**
     * Menyimpan kategori baru milik user yang sedang login.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $category = Category::create([
            'user_id' => $request->user()->id,
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        return response()->json([
            'message' => 'Kategori berhasil ditambahkan',
            'data' => $category,
        ], 201);
    }

    /**
     * Menampilkan detail satu kategori milik user.
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $category = Category::where('user_id', $request->user()->id)->find($id);

        if (!$category) {
            return response()->json(['message' => 'Kategori tidak ditemukan'], 404);
        }

        return response()->json([
            'message' => 'Detail kategori ditemukan',
            'data' => $category,
        ], 200);
    }

    /**
     * Memperbarui data kategori milik user.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $category = Category::where('user_id', $request->user()->id)->find($id);

        if (!$category) {
            return response()->json(['message' => 'Kategori tidak ditemukan'], 404);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $category->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? $category->description,
        ]);

        return response()->json([
            'message' => 'Kategori berhasil diperbarui',
            'data' => $category,
        ], 200);
    }

    /**
     * Menghapus kategori milik user.
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $category = Category::where('user_id', $request->user()->id)->find($id);

        if (!$category) {
            return response()->json(['message' => 'Kategori tidak ditemukan'], 404);
        }

        $category->delete();

        return response()->json([
            'message' => 'Kategori berhasil dihapus',
        ], 200);
    }
}