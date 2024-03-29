<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\SubscribeRequest;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductType;
use App\Models\Subscribe;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class IndexController extends Controller
{

    public function subscribe(SubscribeRequest $request) {

        $data = $request->validated();

        Subscribe::query()->create($data);

        return response()->json(['msg' => 'Вы успешно подписались на рассылку']);

    }

    /**
     * @param object $images
     * @param string $path
     * @return void
     */
    public function setImageUrl(object $images, string $path) : void {

        foreach ($images as $image) {

            $image[$path] = asset(Storage::url($image[$path]));

        }

    }


    /**
     * @return ProductCollection
     */
    public function products() : ProductCollection {

//        $products = Product::paginate(6);

        $products = Product::all();

        $this->setImageUrl($products, 'preview_image_path');

        $this->setImageUrl($products, 'size_image_path');

        foreach ($products as $product) {

            $this->setImageUrl($product->images, 'image_path');

            if(isset($product->type->name)) $product['type_id'] = $product->type->name;

        }

//        return new ProductCollection(Cache::remember('products', 60*60*24, function () use ($products) {
//            return $products;
//        }));

        return new ProductCollection($products);

    }

    /**
     * @param Product $product
     * @return ProductResource
     */
    public function product(Product $product) : ProductResource
    {

        $images = [];

        foreach ($product->images as $image) {

            $image['image_path'] = asset(Storage::url($image['image_path']));

            $images[] = $image['image_path'];

        }

        $product['preview_image_path'] = asset(Storage::url($product['preview_image_path']));

        $product['size_image_path'] = asset(Storage::url($product['size_image_path']));

        $product['images'] = $images;

//        $this->setImageUrl($product->images, 'image_path');

//        return new ProductResource(Cache::remember('product' . $product->id, 60*60*24, function () use ($product) {
//            return $product;
//        }));

        return new ProductResource($product);

    }

    /**
     * @param Product $product
     * @return ProductCollection
     */
    public function popularProducts(Product $product) : ProductCollection
    {

        $popularProducts = $product->popularProducts()->get();

        $products = [];

        foreach ($popularProducts as $popularProduct) {

            $products[] = $popularProduct->popularProduct;

        }

        foreach ($products as $product) {

            $product['preview_image_path'] = asset(Storage::url($product['preview_image_path']));

            $product['size_image_path'] = asset(Storage::url($product['size_image_path']));

        }

//        $product['preview_image_path'] = asset(Storage::url($product['preview_image_path']));

//        $product['size_image_path'] = asset(Storage::url($product['size_image_path']));

//        $this->setImageUrl($product->images, 'image_path');


        return new ProductCollection($products);

    }

    public function categories() {

        return new CategoryCollection(Cache::remember('categories', 60*60*24, function () {

            return ProductCategory::all();

        }));

    }

    public function category(ProductCategory $category) {

        return new CategoryResource(Cache::remember('category', 60*60*24, function () use($category) {

            return $category;

        }));

    }

}
