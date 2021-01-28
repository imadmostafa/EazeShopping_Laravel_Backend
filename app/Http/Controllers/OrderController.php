<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Role;
class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    //
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
        //add order from customer when requesting order ;
        $request->validate([
            'amount' => 'required',
            'latitude' => 'required',
            'longitude' => 'required'
        ]);

        $customer_id=auth()->user()->id;
        $order = Order::create([
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
            'amount' => $request->amount,
            'store_id' => $request->store_id,
            'customer_id'=>$customer_id,
            'isDelivered' => 0,

        ]);


        return response()->json([
            "success" => true,
            "message" => "Order successfully added",
            "order"=>$order
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //assign who is the cashier to deliver the request ;
        $order_id=$request->order_id;

        DB::table('orders')
        ->where('id', $order_id)
        ->update([
        'cashier_id' => $request->cashier_id,
        ]);

        $order=Order::find($order_id);
        return response()->json([
            "success" => true,
            "message" => "Order successfully updated",
            "order"=>$order
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }

    public function getall_orders_cashier(Request $request){

        $user_id=auth()->user()->id;
        $rolerow=Role::where('user_id',$user_id)->get()->first();
        $store_id=$rolerow->store_id;

        $query = "SELECT  orders.*, u.name as customer_name,u.expo_notifications,u.image_path
        FROM orders
        INNER JOIN users u on u.id = orders.customer_id
        WHERE (orders.store_id = $store_id)
        ORDER BY orders.id";
        $orders = DB::Select($query);

        return response()->json([
            'success' => true,
            'orders'=>$orders

        ]);






    }

















}
