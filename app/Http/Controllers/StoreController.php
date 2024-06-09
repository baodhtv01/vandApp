<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public const PER_PAGE = 10;
    private $model;

    public function __construct(Store $model)
    {
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $stores = $this->model->where('user_id', auth()->id())
            ->paginate(self::PER_PAGE);

        return response()->json($stores);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $validatedData['user_id'] = auth()->id();

        try {
            $store = $this->model->create($validatedData);
            return response()->json($store, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create record', 'details' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $store = $this->model->with('products')
                ->where('user_id', auth()->id())
                ->where('id', $id)
                ->firstOrFail();

            return response()->json($store);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Record not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        try {
            $store = $this->model->where('id', $id)
                ->where('user_id', auth()->id())
                ->firstOrFail();

            $store->update($validatedData);
            return response()->json($store);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Record not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $store = $this->model->where('id', $id)
                ->where('user_id', auth()->id())
                ->firstOrFail();

            $store->delete();
            return response()->json(['message' => 'Store deleted successfully']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Record not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Search for a name
     */
    public function search(Request $request): JsonResponse
    {
        $name = $request->input('name');
        try {

            $stores = $this->model->with('products')
                ->where('user_id', auth()->id())
                ->where('name', 'like', "%$name%")
                ->paginate(self::PER_PAGE);

            if ($stores->isEmpty()) {
                return response()->json(['message' => 'No records found'], 404);
            }

            return response()->json($stores);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
