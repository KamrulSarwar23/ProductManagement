<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Intervention\Image\Laravel\Facades\Image;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $products = Product::query();

        if ($request->searchQuery != '') {
            $products = Product::where('name', 'Like', '%'.$request->searchQuery.'%');
        }

        $products = $products->latest()->paginate(10);

        return response()->json([
            'products' =>  $products
        ], 200);

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
            $request->validate([
                'name' => 'required',
                'description' => 'required',
                'price' => 'integer',
                'quantity' => 'integer',
            ]);

            $product = new Product();
            $product->name = $request->name;
            $product->description = $request->description;
            $product->type = $request->type;
            $product->price = $request->price;
            $product->quantity = $request->quantity;
       
       if ($request->image != "") {

            $strpos = strpos($request->image, ';');
            $sub  = substr($request->image, 0, $strpos);
            $ex = explode('/', $sub)[1];
            $name = time() . "." . $ex;
            $img = Image::read($request->image)->resize(200,200);
            $upload_path = public_path('/upload/');   
            $img->save($upload_path . $name);
            $product->image = $name;

        }else{
            $product->image = "no-image.png";
        }   

        $product->save();

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(String $id)
    {
        $product = Product::findOrFail($id);

        return response()->json([
            'product' =>  $product
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'integer',
            'quantity' => 'integer',
        ]);

        $product = Product::findOrFail($id);
        $product->name = $request->name;
        $product->description = $request->description;
        $product->type = $request->type;
        $product->price = $request->price;
        $product->quantity = $request->quantity;
        
   
   if ($request->image != $product->image) {

        $strpos = strpos($request->image, ';');
        $sub  = substr($request->image, 0, $strpos);
        $ex = explode('/', $sub)[1];
        $name = time() . "." . $ex; 
        $img = Image::read($request->image)->resize(200,200);
        $upload_path = public_path() . "/upload/";

        $image = $upload_path . $product->image;

        if (file_exists($image)) {
            @unlink($image);
        }
        
        $img->save($upload_path . $name);

        $product->image = $name;

    }else{
        $product->image = $product->image;
    }

    $product->save();
    
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
    }
}
