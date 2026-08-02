<?php

namespace App\Http\Controllers\Dashboard\Materials;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Materials\MaterialRequest;
use App\Models\Material;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * @param MaterialRequest $request
     * @return JsonResponse
     */
    public function store(MaterialRequest $request): JsonResponse
    {
        $data = $request->only(['name', 'description', 'quantity', 'status']);

        $material = Material::create($data);

        if ($request->expectsJson()) {
            return response()->json([
                'id' => $material->id,
                'name' => $material->name,
                'message' => trans('dashboard.material_added_successfully')
            ]);
        }

        return $material;
    }

    /**
     * @return JsonResponse
     */
    public function getMaterial(): JsonResponse
    {
        return response()->json([
            'data' => Material::select('id', 'name')->get(),
            'message' => __('dashboard.success'), 'code' => 200
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getMaterialByIds(Request $request): JsonResponse
    {
        $materials = Material::whereIn('id', $request->ids)->get();

        return response()->json($materials);
    }

    /**
     * @param Material $material
     * @return JsonResponse
     */
    public function destroy(Material $material): JsonResponse
    {
        $material->delete();

        return response()->json([
            'message' => trans('dashboard.material_delete_successfully')
        ]);
    }
}
