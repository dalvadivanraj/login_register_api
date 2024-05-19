<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Validator;
use Auth;

class Authcontroller extends Controller
{
    public function register(Request $request){
        $validater = validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);

        if($validater->fails()){
            $response = [
                'success' => false,
                'message' => $validater->errors()
            ];

            return response()->json($response, 400);
        }

        $input = $request->all();
        $user = User::create($input);

        $success['token'] = $user->createToken('myapp')->plainTextToken;
        $success['name'] = $user->name;

        $response = [
            'success' => true,
            'data' => $success,
            'message' => 'user register successfully'

        ];

        return response()->json($response, 200);

    } 

    public function login(Request $request){
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::user();
            $success['token'] = $user->createToken('myapp')->plainTextToken;
            $success['name'] = $user->name;
    
            $response = [
                'success' => true,
                'data' => $success,
                'message' => 'user login successfully'
    
            ];
    
            return response()->json($response, 200);

        }else{
            $response = [
                'success' => false,
                'message' => 'unauthorised'
            ];

            return response()->json($response);

        }
    }
}
