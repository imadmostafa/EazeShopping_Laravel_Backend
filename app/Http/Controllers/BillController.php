<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BillController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() //request assumed from cashier
    {
        //get all bills with customer names , for the specific store id;
        $user_id = auth()->user()->id;
        $rolerow = Role::where('user_id', $user_id)->get()->first();
        $store_id = $rolerow->store_id;

        $query = "SELECT  bills.*,u.name as customer_name,u.image_path
    FROM bills
    INNER JOIN roles r on bills.customer_id = r.user_id
    INNER JOIN users u on u.id=r.user_id
    WHERE (r.store_id = $store_id AND bills.isDone='0')
    ORDER BY bills.id";
        $bills = DB::Select($query);
        return response()->json([
            'success' => true,
            'bills' => $bills

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

        $user_id = auth()->user()->id;
        $rolerow = Role::where('user_id', $user_id)->get()->first();
        $store_id = $rolerow->store_id;
        $bill = Bill::create([ //so isdone only accepts 0 or 1 input ;
            'bill_amount' => $request->bill_amount,
            'isDone' => $request->isDone,
            'customer_id' => $user_id,
        ]);
        return response()->json([
            'success' => true,
            'bill' => $bill

        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Bill  $bill
     * @return \Illuminate\Http\Response
     */
    public function show(Bill $bill)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Bill  $bill
     * @return \Illuminate\Http\Response
     */
    public function edit(Bill $bill)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bill  $bill
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bill $bill)
    {
        //set isDone to equal to 1 , approving the request
        $id = $request->id;
        $bill = DB::table('bills')
            ->where('id', $id)
            ->update(['isDone' => 1]);
        return response()->json([
            'success' => true,
            'bill' => $bill

        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bill  $bill
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bill $bill)
    {
        //
    }

    public function getBills_Done_Cashier()
    {
    }


    public function getBills_Done_Store()
    {
    }



    public function getBillsDone() //request assumed from cashier
    {
        //get all bills with customer names , for the specific store id;
        $user_id = auth()->user()->id;
        $rolerow = Role::where('user_id', $user_id)->get()->first();
        $store_id = $rolerow->store_id;

        $query = "SELECT  bills.*,u.name as customer_name
    FROM bills
    INNER JOIN roles r on bills.customer_id = r.user_id
    INNER JOIN users u on u.id=r.user_id
    WHERE (r.store_id = $store_id AND bills.isDone='1')
    ORDER BY bills.id";
        $bills = DB::Select($query);
        return response()->json([
            'success' => true,
            'bills' => $bills

        ]);
    }
}
