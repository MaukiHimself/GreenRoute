<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;

class ProductController extends Controller
{
    //
    public function index(){
$products=Product::all();
        return view('products.index',['products'=>$products]);
    }
    public function create(){
        return view('products.create');
    }
    public function store(Request $request): RedirectResponse {
        $data = $request->validate([
            // Adjust validation rules for product fields, e.g.:
            'username' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        $newProduct = Product::create($data);
        return redirect()->route('product.index');
    }
    public function edit(Product $product){
        return view('products.edit',['product'=> $product]);
    }
    public function update(Product $product,Request $request):RedirectResponse{
     $data = $request->validate([
            // Adjust validation rules for product fields, e.g.:
            'username' => 'required|string',
            'password' => 'required|string|min:6',
        ]);
        $product->update($data);
        return redirect()->route('product.index')->with('success', 'Product Updated Successfully');
    }
    public function destroy(Product $product){
        $product->delete();
        return redirect()->route('product.index')->with('success','Product deleted Successfully');
    }
}