<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Http\Controllers\BaseResponse;
use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

class CategoryController extends BaseResponse
{
    public function index()
    {
        $categories = Category::all();
        return $this->ok($categories, 'Categories retrieved');
    }
    public function store(CreateCategoryRequest $request){
        $category = Category::create($request->all());
        if($category){
            return $this->created($category, 'Category created');
        }else{
            return $this->error('Failed to create category');
        }
    }
    public function show($id){
        $category = Category::where('id',$id)->first();
        if($category){
            return $this->ok($category,'Category retrieved');
        }else{
            return $this->error('Category not found', 404);
        }
    }
    public function update(UpdateCategoryRequest $request, $id){
        $category = Category::where('id',$id)->first();
        if($category){
            $category->update($request->all());
            return $this->ok($category);
        }else{
            return $this->error('Category not found', 404);
        }
    }
    public function destroy($id){
        $category = Category::where('id',$id)->first();
        if($category){
            $category->delete();
            return $this->ok(null, 'Category deleted');
        }else{
            return $this->error('Category not found', 404);
        }
    }
}
