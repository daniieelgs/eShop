<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    function create(CategoryRequest $req){

        $category = new Category();

        $category->name = $req->input('name');

        $category->save();

        return redirect("/product/category/".$category->id);
    }

    function update(CategoryRequest $req, $id){

        $category = Category::find($id);

        if($category == null) abort(404, 'Category not found');

        $category->name = $req->input('name');

        $category->save();

        return redirect("/product/category/".$category->id);
    }

    function delete($id){

        $category = Category::find($id);

        if($category == null) abort(404, 'Category not found');

        $category->delete();

        return back();

    }
}
