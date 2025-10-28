<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Structure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class StructureController extends Controller
{
    /**
     * Display the structure organization page.
     */
    public function index(): View
    {
        // Real data with pagination
        $structures = Structure::with(['children', 'parent'])
            ->active()
            ->orderBy('level')
            ->orderBy('sort_order')
            ->paginate(10);

        $hierarchyTree = Structure::getHierarchyTree();

        // Statistics
        $totalPositions = Structure::active()->count();
        $organizationLevels = Structure::active()->max('level') ?? 0;
        $totalStaff = Structure::active()->where('level', '>', 2)->count();

        return view('admin.structure.index', compact(
            'structures',
            'hierarchyTree',
            'totalPositions',
            'organizationLevels',
            'totalStaff'
        ));
    }

    /**
     * Store a new structure position (clean, transactional)
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'position' => ['required', 'string', 'max:255'],
            'position_custom' => ['required_if:position,custom', 'nullable', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'nip' => ['nullable', 'string', 'max:20'],
            'rank' => ['nullable', 'string', 'max:255'],
            'level' => ['required', 'integer', 'min:1'],
            'parent_id' => ['nullable', 'integer', 'exists:structures,id'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'is_active' => ['nullable'],
            'is_plt' => ['nullable', 'boolean'],
            'plt_from_structure_id' => ['nullable', 'integer', 'exists:structures,id'],
            'plt_name' => ['nullable', 'string', 'max:255'],
            'plt_nip' => ['nullable', 'string', 'max:20'],
            'plt_rank' => ['nullable', 'string', 'max:255'],
            'plt_start_date' => ['nullable', 'date'],
            'plt_end_date' => ['nullable', 'date', 'after_or_equal:plt_start_date'],
            'plt_notes' => ['nullable', 'string']
        ]);

        $position = $this->resolvePosition($request);

        // Root level must not have parent
        if ((int) $validated['level'] === 1) {
            $validated['parent_id'] = null;
        }

        DB::beginTransaction();
        try {
            $sortOrder = $this->nextSortOrder((int) $validated['level']);
            $photoPath = $this->handlePhotoUpload($request, null);

            $data = [
                'position' => $position,
                'name' => $validated['name'],
                'nip' => $validated['nip'] ?? null,
                'rank' => $validated['rank'] ?? null,
                'level' => (int) $validated['level'],
                'parent_id' => $validated['parent_id'] ?? null,
                'sort_order' => $sortOrder,
                'photo' => $photoPath,
                'is_active' => $request->boolean('is_active', true),
                'is_plt' => $request->boolean('is_plt', false),
                'plt_from_structure_id' => $validated['plt_from_structure_id'] ?? null,
                'plt_name' => $validated['plt_name'] ?? null,
                'plt_nip' => $validated['plt_nip'] ?? null,
                'plt_rank' => $validated['plt_rank'] ?? null,
                'plt_start_date' => $validated['plt_start_date'] ?? null,
                'plt_end_date' => $validated['plt_end_date'] ?? null,
                'plt_notes' => $validated['plt_notes'] ?? null,
            ];

            $structure = Structure::create($data);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Struktur organisasi berhasil ditambahkan',
                'data' => $structure,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data.',
            ], 500);
        }
    }

    /**
     * Show a specific structure as JSON.
     */
    public function show(Structure $structure): JsonResponse
    {
        $structure->load('pltFromStructure');

        return response()->json([
            'success' => true,
            'data' => $structure,
        ]);
    }

    /**
     * Update structure (clean, transactional)
     */
    public function update(Request $request, Structure $structure): JsonResponse
    {
        $validated = $request->validate([
            'position' => ['required', 'string', 'max:255'],
            'position_custom' => ['required_if:position,custom', 'nullable', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'nip' => ['nullable', 'string', 'max:20'],
            'rank' => ['nullable', 'string', 'max:255'],
            'level' => ['required', 'integer', 'min:1'],
            'parent_id' => ['nullable', 'integer', 'exists:structures,id'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'is_active' => ['nullable'],
            'is_plt' => ['nullable', 'boolean'],
            'plt_from_structure_id' => ['nullable', 'integer', 'exists:structures,id'],
            'plt_name' => ['nullable', 'string', 'max:255'],
            'plt_nip' => ['nullable', 'string', 'max:20'],
            'plt_rank' => ['nullable', 'string', 'max:255'],
            'plt_start_date' => ['nullable', 'date'],
            'plt_end_date' => ['nullable', 'date', 'after_or_equal:plt_start_date'],
            'plt_notes' => ['nullable', 'string']
        ]);

        $position = $this->resolvePosition($request);

        // Root level must not have parent
        if ((int) $validated['level'] === 1) {
            $validated['parent_id'] = null;
        }

        DB::beginTransaction();
        try {
            $data = [
                'position' => $position,
                'name' => $validated['name'],
                'nip' => $validated['nip'] ?? null,
                'rank' => $validated['rank'] ?? null,
                'level' => (int) $validated['level'],
                'parent_id' => $validated['parent_id'] ?? null,
                'is_active' => $request->boolean('is_active', true),
                'is_plt' => $request->boolean('is_plt', false),
                'plt_from_structure_id' => $validated['plt_from_structure_id'] ?? null,
                'plt_name' => $validated['plt_name'] ?? null,
                'plt_nip' => $validated['plt_nip'] ?? null,
                'plt_rank' => $validated['plt_rank'] ?? null,
                'plt_start_date' => $validated['plt_start_date'] ?? null,
                'plt_end_date' => $validated['plt_end_date'] ?? null,
                'plt_notes' => $validated['plt_notes'] ?? null,
            ];

            // Replace photo if provided
            if ($request->hasFile('photo')) {
                $data['photo'] = $this->handlePhotoUpload($request, $structure->photo);
            }

            $structure->update($data);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Struktur organisasi berhasil diperbarui',
                'data' => $structure->fresh(),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui data.',
            ], 500);
        }
    }

    /**
     * Delete structure (guard if has children)
     */
    public function destroy(Structure $structure): JsonResponse
    {
        try {
            if ($structure->children()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus struktur yang memiliki bawahan',
                ], 400);
            }

            DB::beginTransaction();
            if ($structure->photo && Storage::disk('public')->exists($structure->photo)) {
                Storage::disk('public')->delete($structure->photo);
            }
            $structure->delete();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Struktur organisasi berhasil dihapus',
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    // Helpers
    private function resolvePosition(Request $request): string
    {
        $position = trim((string) $request->input('position'));
        if ($position === 'custom') {
            return trim((string) $request->input('position_custom', ''));
        }
        return $position;
    }

    private function handlePhotoUpload(Request $request, ?string $existingPath = null): ?string
    {
        if (!$request->hasFile('photo')) {
            return $existingPath; // keep existing if not uploading new
        }

        if ($existingPath && Storage::disk('public')->exists($existingPath)) {
            Storage::disk('public')->delete($existingPath);
        }

        $photo = $request->file('photo');
        $name = time() . '_' . preg_replace('/[^A-Za-z0-9_.-]/', '_', $photo->getClientOriginalName());
        return $photo->storeAs('structure_photos', $name, 'public');
    }

    private function nextSortOrder(int $level): int
    {
        $max = Structure::where('level', $level)->max('sort_order');
        return (int) (($max ?? 0) + 1);
    }
}
