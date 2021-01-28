<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    //

    //Register method only for store ,role routed in frontend ;
    public function register_store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);



        $user_id = $user->id;
        //$store_id=auth()->user()->id;unneeded store id here same as user id

        //create new role of cashier
        $createRole = [
            'user_id' => $user_id,
            'role' => 'store',
            'store_id' => $user_id,
        ];
        $role = new Role($createRole);
        $role->save();




        return response()->json([
            'user' => $user,
            'data' => 'New Store Created'

        ]);
    }

    //login
    public function login(Request $request)
    {
        /*$validation=$request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required'
        ]);*/

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([

                'success' => false,
                'message' => 'email does not exist'

            ]);
        }




        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user_id = Auth::user()->id;
            $user = User::find($user_id);


            $userRole = $user->role()->first();

            if ($userRole) {
                $this->scope = $userRole->role;
            }

            $token = $user->createToken($user->email . '-' . now(), [$this->scope]);
            //add join for galleries table to get user image



            return response()->json([
                'success' => true,
                'token' => $token->accessToken,
                'user' => $user,
                'user_role' => $userRole
            ]);
        } else { //in case email or password wrong
            return response()->json([

                'success' => false,
                'message' => 'wrong password'

            ]);
        }
    }


    //register_customer
    public function register_customer(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);



        $user_id = $user->id;
        //$store_id=auth()->user()->id;unneeded store id here same as user id

        //create new role of cashier
        $store_id = $request->store_id;
        $createRole = [
            'user_id' => $user_id,
            'role' => 'customer',
            'store_id' => $store_id,
        ];
        $role = new Role($createRole);
        $role->save();




        return response()->json([
            'success' => true,
            'user' => $user,
            'data' => 'New Customer Created'

        ]);
    }
}
