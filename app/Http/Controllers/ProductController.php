<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Gallery;
use Illuminate\Support\Facades\DB;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

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
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'file'=>'required',
            'description' => 'required',
            'category_id' => 'required',
            'price' => 'required',
            'mass' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "message" => "File couldn't upload",
            ]);
        }

        $product = new Product();
        $product->name = $request->name;
        $product->mass = $request->mass;
        $product->price = $request->price;
        $product->description = $request->description;
        $product->category_id = $request->category_id;
        $product->store_id = auth()->user()->id;
        $gallery_id = '1';
        $file_tostore = '';

        if ($files = $request->file('file')) {
            //store file into document folder
            $file = $request->file('file')->store('storage/uploads', 'public');
            $file_tostore = $file;
            //store your file into database
            $document = new Gallery();
            $document->path = env('APP_URL') . '/' . $file;
            $document->name = $request->name;
            $document->save();
            $gallery_id = $document->id;
        }
        $product->gallery_id = $gallery_id;
        $product->save();

        return response()->json([
            "success" => true,
            "message" => "File successfully uploaded",
            "new_product" => $product,
            'path' => $document->path,
            'path2' => env('APP_URL') . '/' . $file_tostore
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

        return response()->json([
            'success' => true,
            'product' => $product,

        ]);
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
