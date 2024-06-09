<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Store;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public const PER_PAGE = 10;
    private $model;

    public function __construct(Product $model)
    {
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $products = $this->model->with('store')
            ->whereHas('store', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->paginate(self::PER_PAGE);

        return response()->json($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'store_id' => 'required|exists:stores,id',
        ]);

        try {
            $product = $this->model->with('store')
                ->wherehas('store', function ($query) use ($validatedData) {
                    $query->where('user_id', auth()->id());
                })
                ->firstOrFail();
            $product = $product->create($validatedData);
            return response()->json($product, 201);
        }catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create record', 'details' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $product = $this->model->with('store')
                ->wherehas('store', function ($query) {
                    $query->where('user_id', auth()->id());
                })
                ->where('id', $id)
                ->firstOrFail();

            return response()->json($product);
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
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'price' => 'sometimes|numeric',
            'store_id' => 'required|exists:stores,id',
        ]);

        try {
            $product = $this->model->where('id', $id)
                ->whereHas('store', function ($query) {
                    $query->where('user_id', auth()->id());
                })
                ->firstOrFail();

            $product->update($validatedData);
            return response()->json($product);
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
            $product = $this->model->where('id', $id)
                ->whereHas('store', function ($query) {
                    $query->where('user_id', auth()->id());
                })
                ->firstOrFail();

            $product->delete();
            return response()->json(['message' => 'Product deleted successfully']);
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
            $products = $this->model
                ->with('store')
                ->whereHas('store', function ($query) {
                    $query->where('user_id', auth()->id());
                })
                ->where('name', 'like', "%$name%")
                ->paginate(self::PER_PAGE);

            if ($products->isEmpty()) {
                return response()->json(['message' => 'No records found'], 404);
            }

            return response()->json($products);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
