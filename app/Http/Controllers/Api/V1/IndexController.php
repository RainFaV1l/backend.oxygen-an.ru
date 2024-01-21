<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class IndexController extends Controller
{

    public function setImageUrl(object $images, string $path) : void {

        foreach ($images as $image) {

            $image[$path] = asset(Storage::url($image[$path]));

        }

    }


    /**
     * @return ProductCollection
     */
    public function products() : ProductCollection {

        $products = Product::paginate(1);

        $this->setImageUrl($products, 'preview_image_path');

        $this->setImageUrl($products, 'size_image_path');

        foreach ($products as $product) {
            $this->setImageUrl($product->images, 'image_path');
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

        $product['preview_image_path'] = asset(Storage::url($product['preview_image_path']));

        $product['size_image_path'] = asset(Storage::url($product['size_image_path']));

        $this->setImageUrl($product->images, 'image_path');

        return new ProductResource(Cache::remember('product' . $product->id, 60*60*24, function () use ($product) {
            return $product;
        }));

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
