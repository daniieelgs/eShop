<?php

namespace App\Http\Controllers;

use App\Http\Requests\OpinionRequest;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\ProductShoppingRequest;
use App\Models\Category;
use App\Models\Opinion;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{

    function index(){

        $products = Product::inRandomOrder()->take(10)->get();

        return $this->productView('index', ["products" => $products]);
    }

    function seeAll(){

        $products = Product::all();

        return $this->productView('seeProducts', ['products' => $products]);

    }

    function seeAllCategory($categoryId){
        $products = Product::where('category_id', $categoryId)->get();

        $category = Category::find($categoryId);

        if($category == null) return redirect('/product');

        return $this->productView('seeProducts', ['categoryProduct' => $category, 'products' => $products]);

    }

    function show($id){

        $product = Product::find($id);

        if($product == null) abort(404, 'Product not found');

        $otherProducs = Product::where('category_id', $product->category_id)->whereNot('id', $product->id)->get()->toArray();

        $opinions = $product->opinions->reverse();

        shuffle($otherProducs);

        return $this->productView('seeProduct', ['product' => $product, 'categoryProduct' => $product->category, 'otherProducts' => array_slice($otherProducs, 0, 4), 'opinions' => $opinions]);

    }

    function createOpinion(OpinionRequest $req, $id){
        
        $opinion = new Opinion();

        $opinion->text = $req->input('text') ?? "";
        $opinion->calification = $req->input('calification') ?? 0;
        $opinion->user_id = Auth::user()->id;
        $opinion->product_id = $id;

        $opinion->save();
        
        return back();

    }

    function deleteOpinion($id){

        $opinion = Opinion::find($id);

        if(Auth::user()->role != config('app.admin_role') && $opinion->user->id != Auth::user()->id) abort(403, 'Not authorized');

        $opinion->delete();

        return back();

    }

    function create(Request $req){

        $product = new Product();

        $req->validate([ //Condicions per acceptar el contingut Request. Si no compleix, és rebutja
            'img' => 'required|mimes:png,jpg,jpeg|max:2048',
        ]);

        $img = time().'_'.$req->file('img')->getClientOriginalName();

        $req->file('img')->storeAs('uploads', $img, 'public');

        $product->name = $req->input('name');
        $product->img_url = '/storage/uploads/'.$img;
        $product->description = $req->input('description');
        $product->category_id = $req->input('category');
        $product->price = $req->input('price');
        $product->stock = intval($req->input('stock'));

        $product->save();

        return back();

    }

    function update(ProductRequest $req, $id){

        $product = Product::find($id);

        if($product == null) abort(404, 'Product not found');

        Storage::disk('public')->delete($product->img_url);

        $img = time().'_'.$req->file('img')->getClientOriginalName();

        $req->file('img')->storeAs('uploads', $img, 'public');

        $product->name = $req->input('name');
        $product->img_url = '/storage/uploads/'.$img;
        $product->description = $req->input('description');
        $product->category_id = $req->input('category');
        $product->price = $req->input('price');
        $product->stock = intval($req->input('stock'));

        $product->save();

        return back();
    }

    function delete($id){

        $product = Product::find($id);

        Storage::disk('public')->delete($product->img_url);

        if($product == null) abort(404, 'Product not found');

        $product->delete();

        return back();

    }

    private function productView($view, $params = []){
        $user = null;
        $admin = false;
        $shoppingCount = null;

        if(Auth::check()){
            $user = Auth::user();
            $admin = $user->role === config('app.admin_role');
            $shoppingCount = count($user->shoppings->toArray());
        }

        return view($view, array_merge(['user' => $user, 'admin' => $admin, 'categories' => Category::all(), 'shoppingCount' => $shoppingCount], $params));
    }
}
