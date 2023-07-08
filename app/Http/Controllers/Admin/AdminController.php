<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class AdminController extends Controller
{   
    public function index(Request $request){
        $validator = Validator::make($request->all(), [
            'token' => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'fail',
                'message' => $validator->messages()
            ]);
        }
        
        $token = $request->token;
        $tokenDB = User::where('token', $token)->count();
        
        if($tokenDB > 0){
            $key = env('APP_KEY');
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            $decodeArray = (array) $decoded;
            if($decodeArray['extime'] > time()){
                $admin = User::get();
                $data = array();
                foreach($admin as $adm){
                    $data = array(
                        'id' => $adm->id,
                        'name' => $adm->name,
                        'email' => $adm->email,
                    );
                }
                return response()->json([
                    'status' => 'success',
                    'message' => 'this data success to get here.',
                    'data' => $data,
                ]);
            }else{
                return response()->json([
                    'status' => 'fail',
                    'message' => 'sorry, this data fail to delete.'
                ]);
            }
        }else{
            return response()->json([
                'status' => 'fail',
                'message' => 'sorry, this data token not valid.'
            ]);
        }
    }

    public function registerAdmin(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required',
            'token' => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'fail',
                'message' => $validator->messages()
            ]);
        }
        if(User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ])){
            return response()->json([
                'status' => 'success',
                'message' => 'successfully save data.'
            ]);
        }
    }

    public function authenticatedAdmin(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'fail',
                'message' => $validator->messages()
            ]);
        }

        $cek = User::where('email', $request->email)->count();
        $admin = User::where('email', $request->email)->get();
        $password = $request->password;
        if($cek > 0){
            foreach($admin as $admin){
                if(Hash::check($password, $admin->password)){
                    $appKey = env('APP_KEY');
                    $data = [
                        'extime' => time()+(60*120),
                        'id' => $admin->id,
                    ];
                    $generateJWT = JWT::encode($data, $appKey, 'HS256');
                    User::where('id', $admin->id)->update([
                        'token' => $generateJWT,
                    ]);

                    return response()->json([
                        'status' => 'success',
                        'message' => 'success authenticated.',
                        'token' => $generateJWT,
                    ]);
                }else{
                    return response()->json([
                        'status' => 'fail',
                        'message' => 'sorry, this password not found.'
                    ]);
                }
            }
        }else{
            return response()->json([
                'status' => 'fail',
                'message' => 'sorry, this data email not register.'
            ]);
        }
    }

    public function destroy(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'token' => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 'fail',
                'message' => $validator->messages()
            ]);
        }
        
        $token = $request->token;
        $tokenDB = User::where('token', $token)->count();
        
        if($tokenDB > 0){
            $key = env('APP_KEY');
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            $decodeArray = (array) $decoded;
            if($decodeArray['extime'] > time()){
                if(User::where('id', $request->id)->delete()){
                    return response()->json([
                        'status' => 'success',
                        'message' => 'data successfully to delete.'
                    ]);
                }
            }else{
                return response()->json([
                    'status' => 'fail',
                    'message' => 'sorry, this data fail to delete.'
                ]);
            }
        }else{
            return response()->json([
                'status' => 'fail',
                'message' => 'sorry, this data token not valid.'
            ]);
        }
    }

}
