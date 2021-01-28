<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use App\Models\Gallery;
use App\Models\Product;
class UserController extends Controller
{

public function getUser_ById(Request $request){
    $user=User::find($request->id);
 return response()->json([
     "success" => true,
     "message" => "User expotoken successfully uploaded",
     "user" => $user,

 ]);
}

    public function update_expotoken(Request $request){

        $user_id=auth()->user()->id;

           DB::table('users')
           ->where('id', $user_id)
           ->update([
           'expo_notifications'=>$request->expotoken,
           ]);
  $user=User::find($user_id);
 return response()->json([
     "success" => true,
     "message" => "User expotoken successfully uploaded",
     "user" => $user,

 ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::all();
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
    //store new user with  role of cashier in request ;
    public function store_cashier(Request $request)
    {//only store owner can create his cashier

        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required|min:6'
        ]);


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        $user_id=$user->id;
        $store_id=auth()->user()->id;

        //create new role of cashier
        $createRole = [ 'user_id' =>$user_id,
        'role' => 'cashier',
        'store_id' => $store_id,
        ];
$role = new Role($createRole);
$role->save();

        return response()->json($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update_image(Request $request)
    {
        //only allowed to update profile picture ..

        $user_id=auth()->user()->id;
        if ($files = $request->file('file')) {

            //store file into document folder
            $file = $request->file('file')->store('storage/uploads','public');

 $file22=$file;
            //store your file into database
            $document = new Gallery();
            $document->path = env('APP_URL').'/'.$file;
            $document->name = 'image';
            $document->save();
           $gallery_id= $document->id;
           DB::table('users')
           ->where('id', $user_id)
           ->update([
           'image_path'=>$document->path,
           ]);

 }else{
  return response()->json([
      "success" => false,
      "message"=>'no image found'
  ]);
 }
  $user=User::find($user_id);
 return response()->json([
     "success" => true,
     "message" => "User Image successfully uploaded",
     "user" => $user,
     'path'=>$document->path,

 ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    //delete user with its roles in tables ;
    public function destroy($id)
    {
         $user=User::find($id);

        $user->delete();

        return response()->json([
            'success'=>true,
            'data' => 'Member deleted'
        ]);


    }


public function getAllMembers(){//from store owner




 $store_id=auth()->user()->id;

        $query = "SELECT  users.id, users.name, users.email , r.role
                FROM users
                INNER JOIN roles r on users.id = r.user_id
                WHERE (role = 'customer' OR role='cashier') AND r.store_id=$store_id
                ORDER BY user_id";
        $members = DB::Select($query);

        return response()->json([
            'success' => true,
            'members'=>$members
        ]);

}


    public function getAllCustomers(){//from store owner being the signed in using it

        $store_id=auth()->user()->id;

        $query = "SELECT  users.id, users.name, users.email , r.role
                FROM users
                INNER JOIN roles r on users.id = r.user_id
                WHERE (role = 'customer') AND r.store_id=$store_id
                ORDER BY user_id";
        $customers = DB::Select($query);

        return response()->json([
            'success' => true,
            'customers'=>$customers
        ]);



    }
    public function getStores(){//for any login role




        //$store_id=auth()->user()->id;

               $query = "SELECT  users.id, users.name, users.email , r.role
                       FROM users
                       INNER JOIN roles r on users.id = r.user_id
                       WHERE (role = 'store' )
                       ORDER BY user_id";
               $members = DB::Select($query);

               return response()->json([
                   'success' => true,
                   'stores'=>$members
               ]);






       }


    public function getAllCashiers(){

        $store_id=auth()->user()->id;

        $query = "SELECT  users.id, users.name, users.email , r.role
                FROM users
                INNER JOIN roles r on users.id = r.user_id
                WHERE (role = 'cashier') AND r.store_id=$store_id
                ORDER BY user_id";
        $cashiers = DB::Select($query);

        return response()->json([
            'data' => 'Successfully fetched',
            'customers'=>$cashiers
        ]);



    }











}
