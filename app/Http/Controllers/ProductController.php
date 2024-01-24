<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();

        return view("", compact("products"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $product = Product::create($request->all());

        if($product){

            return redirect()->back()->with("status","Berhasil Menambahkan Product");
        }
        return redirect()->back()->with("error","Gagal Menambahkan Product");
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $product->update($request->all());

        if($product){
            return redirect()->back()->with("status","Berhasil Update Product");
        }
        return redirect()->back()->with("error","Gagal Update Product");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        if($product){
            return redirect()->back()->with("status","Berhasil Hapus Product");
        }
        return redirect()->back()->with("error","Gagal Hapus Product");
    }
}
