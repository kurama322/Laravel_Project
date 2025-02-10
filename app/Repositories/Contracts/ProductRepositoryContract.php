<?php

namespace App\Repositories\Contracts;

use App\Http\Requests\Admin\Products\CreateRequest;
use App\Http\Requests\Admin\Products\EditRequest;
use App\Models\Product;

interface ProductRepositoryContract
{

    public function store(CreateRequest $request):Product|false;

    public function update(Product $product, EditRequest $request):bool;

}
