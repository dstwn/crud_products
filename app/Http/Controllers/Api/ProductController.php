<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Http\Controllers\BaseResponse;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends BaseResponse
{
    public function index()
    {
        $products = Product::with('category')->get();
        return $this->ok($products,'Products retrieved');
    }
    public function store(CreateProductRequest $request){
        if($request->hasFile('image')){
            $image = $request->file('image');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/images');
            $image->move($destinationPath, $name);
            $request->merge(['image' => url('/public/images',$name)]);
        }
        $product = Product::create($request->all());
        if($product){
            return $this->created($product,'Product created');
        }else{
            return $this->error('Failed to create product');
        }
    }
    public function show($id){
        $product = Product::where('id',$id)->with('category')->first();
        if($product){
            return $this->ok($product,'Product retrieved');
        }else{
            return $this->error('Product not found', 404);
        }
    }
    public function update(UpdateProductRequest $request, $id){
        $product = Product::where('id',$id)->first();
        if($product){
            if($request->hasFile('image')){
                $image = $request->file('image');
                $name = time().'.'.$image->getClientOriginalExtension();
                $destinationPath = public_path('/images');
                $image->move($destinationPath, $name);
                $request->merge(['image' => url('/public/images',$name)]);
            }
            $product->update($request->all());
            return $this->ok($product,'Product updated');
        }else{
            return $this->error('Product not found', 404);
        }
    }
    public function destroy($id){
        $product = Product::where('id',$id)->first();
        if($product){
            $product->delete();
            return $this->ok(null, 'Product deleted');
        }else{
            return $this->error('Product not found', 404);
        }
    }
}
