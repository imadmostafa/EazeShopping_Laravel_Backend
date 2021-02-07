<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Gallery;
use Illuminate\Support\Facades\DB;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$products = Product::all();

        // $store_id=auth()->user()->id;
        $query = "SELECT  products.*, g.path,c.name as category_name,u.name as store_name
    FROM products
    INNER JOIN galleries g on products.gallery_id = g.id
    INNER JOIN categories c on products.category_id=c.id
    INNER JOIN users u on products.store_id=u.id
    ORDER BY products.id";
        $products = DB::Select($query);
        return response()->json([
            'success' => true,
            'products' => $products
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //one request with formdata image(file) + data of product
        //save image->get its created ID -> create the project
        $product = new Product();


        $product->name = $request->name;
        $product->mass = $request->mass;
        $product->price = $request->price;
        $product->description = $request->description;
        $product->category_id = $request->category_id;
        $product->store_id = auth()->user()->id;
        $gallery_id = '1';
        $file22 = 's';
        if ($files = $request->file('file')) {

            //store file into document folder
            $file = $request->file('file')->store('storage/uploads', 'public');

            $file22 = $file;
            //store your file into database
            $document = new Gallery();
            $document->path = env('APP_URL') . '/' . $file;
            $document->name = $request->name;
            $document->save();
            $gallery_id = $document->id;
        }
        $product->gallery_id = $gallery_id;
        //$server_url=env('APP_URL');
        $product->save();
        return response()->json([
            "success" => true,
            "message" => "File successfully uploaded",
            "new_product" => $product,
            'path' => $document->path,
            'path2' => env('APP_URL') . '/' . $file22
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //update already existign product with image change
        $product_id = $request->id;
        // $product = Product::find($product_id);
        $gallery_id = '1';
        $file22 = 's';
        if ($files = $request->file('file')) {

            //store file into document folder
            $file = $request->file('file')->store('storage/uploads', 'public');

            $file22 = $file;
            //store your file into database
            $document = new Gallery();
            $document->path = env('APP_URL') . '/' . $file;
            $document->name = $request->name;
            $document->save();

            $gallery_id = $document->id;
            DB::table('products')
                ->where('id', $product_id)
                ->update([
                    'name' => $request->name,
                    'description' => $request->description,
                    'gallery_id' => $gallery_id,
                    'store_id' => auth()->user()->id,
                    'category_id' => $request->category_id,
                    'price' => $request->price,
                    'mass' => $request->mass,
                ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => 'no image found'
            ]);
        }
        $product = Product::find($product_id);
        return response()->json([
            "success" => true,
            "message" => "File successfully updated",
            "new_product" => $product,
            'path' => $document->path,
            'path2' => env('APP_URL') . '/' . $file22
        ]);
    } //end of update method

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {

        $product_id = request('id');
        $product_tobeDeleted = Product::find($product_id);
        $product_tobeDeleted->delete();


        return response()->json([
            'success' => true,
            'message' => 'deleted successfully'

        ]);
    }


    public function getproductbyname_customer($name)
    {


        //$name=$request->name;//name of product
        //$store_id=auth()->user()->id;
        //$count = Product::where('name', $name)->where('store_id',$store_id)->get()->first();
        //return response()->download(public_path('storage\uploads\j74SPP4ljcifeIquWn075sxoMumyVl7jFS7Tdikt.png'));
        $name = request('name');
        $user_id = auth()->user()->id;
        $rolerow = Role::where('user_id', $user_id)->get()->first();
        $store_id = $rolerow->store_id;


        $query = "SELECT  products.*, g.path,c.name as category_name
FROM products
INNER JOIN galleries g on products.gallery_id = g.id
INNER JOIN categories c on products.category_id=c.id
WHERE (products.store_id = $store_id AND products.name='$name')
ORDER BY products.id";
        $product = DB::Select($query);
        //commas important if putting string in raw query , if number no proble

        return response()->json([
            'success' => true,
            'product' => $product,

        ]);
        //C:\react\laravel_Final\SmartGroceryBackend\public\storage\uploads\j74SPP4ljcifeIquWn075sxoMumyVl7jFS7Tdikt.png



    }


    public function getAllProductsImages()
    {
        //get all products with corresponding images in one json file return to frontend;

        $store_id = auth()->user()->id;
        $query = "SELECT  products.*, g.path,c.name as category_name
FROM products
INNER JOIN galleries g on products.gallery_id = g.id
INNER JOIN categories c on products.category_id=c.id
WHERE (products.store_id = $store_id)
ORDER BY products.id";
        $products = DB::Select($query);

        return response()->json([
            'success' => true,
            'products' => $products

        ]);
    }





    public function getAllProductsImages_Customer()
    {
        //get all products with corresponding images in one json file return to frontend;

        $user_id = auth()->user()->id;
        $rolerow = Role::where('user_id', $user_id)->get()->first();
        $store_id = $rolerow->store_id;

        $query = "SELECT  products.*, g.path,c.name as category_name
    FROM products
    INNER JOIN galleries g on products.gallery_id = g.id
    INNER JOIN categories c on products.category_id=c.id
    WHERE (products.store_id = $store_id)
    ORDER BY products.id";
        $products = DB::Select($query);

        return response()->json([
            'success' => true,
            'products' => $products

        ]);
    }


    public function getProducts_Images_User()
    {
        // $products = Auth::user()->products()->get();
        $user_id = auth()->user()->id;
        $user = User::find($user_id);


        $products = $user->products()->get();
        return response()->json([
            'success' => true,
            'products' => $products

        ]);
    }
}
