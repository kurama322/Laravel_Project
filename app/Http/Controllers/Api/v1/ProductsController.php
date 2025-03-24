<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Products\CreateRequest;
use App\Http\Requests\Admin\Products\EditRequest;
use App\Http\Requests\Api\v1\ProductEditRequest;
use App\Http\Resources\v1\ProductResource;
use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryContract;
use Illuminate\Http\Request;

class ProductsController extends Controller
{

    public function __construct(protected ProductRepositoryContract $repository)
    {
    $this->authorizeResource(Product::class, 'product');
    }

    public function index(Request $request)
    {
        return ProductResource::collection(
          $this->repository->paginate($request, false)
        );
    }


    public function store(CreateRequest $request)
    {
        if ($product = $this->repository->store($request))
        {
            return new ProductResource($product);
        }

        return response()->json([
            'status' => 'error',
            'data' => 'Something went wrong'], 400);
    }


    public function show(Product $product)
    {
        $product->load(['categories', 'images']);

        return new ProductResource($product);
    }


    public function update(ProductEditRequest $request, Product $product)
    {
        if ($this->repository->update($product, $request)) {

        return new ProductResource($product);
    }

        return response()->json([
        'status' => 'error',
        'data' => 'Something went wrong'], 400);
    }


    public function destroy(string $id)
    {
        //
    }
}
