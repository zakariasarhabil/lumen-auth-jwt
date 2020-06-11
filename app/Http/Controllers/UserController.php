<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Exception;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function registre(Request $request)
    {


        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);



        try {

            $user = new User;
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->password = Hash::make($request->password);




                if ( $user->save()) {
                    $code = 200;
                    $output = [
                        'user' => $user,
                        'code' => $code ,
                        'message' => 'User created...'
                    ];
                }
                else {
                    $code = 500;
                    $output = [
                        'user' => $user,
                        'code' => $code ,
                        'message' => 'User not created...'
                    ];
                }
                return response()->json($output,$code );

        } catch (Exception $e)
                {
                    dd($e->getMessage());
                }




    }


    public function login(Request $request)
    {


        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $input =  $request->only('email', 'password');
        // dd($input);

        if ( ! $Authorized = Auth::attempt($input)) {

            $code = 401;
            $output = [

                'code' => $code ,
                'message' => 'User is not authorirzed'
            ];

        } else {

            $code = 201;
            $token = $this->respondWithToken($Authorized);
           $output = [

               'code' => $code ,
               'message' => 'User  login ...',
               'token' => $token
           ];
        }

        return response()->json($output, $code);
    }
}
