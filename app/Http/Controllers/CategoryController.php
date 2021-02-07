<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $categories = Category::all();
        return response()->json([
            "success" => true,

            "categories" => $categories

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
        $user = Category::create([
            'name' => $request->name,

        ]);
        return response()->json([
            "success" => true,



        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {

        $category_id = request('id');
        $category_tobeDeleted = Category::find($category_id);
        $category_tobeDeleted->delete();

        return response()->json([
            "success" => true,



        ]);
    }



    public function getProducts_ByCategory($id)
    {


        $store_id = auth()->user()->id;
        $category_id = $id;
        $query = "SELECT  products.*, g.path,c.name as category_name
    FROM products
    INNER JOIN galleries g on products.gallery_id = g.id
    INNER JOIN categories c on products.category_id=c.id
    WHERE (products.store_id = $store_id AND products.category_id=$category_id)
    ORDER BY products.id";
        $products = DB::Select($query);

        return response()->json([
            "success" => true,
            'products' => $products



        ]);
    }
}
