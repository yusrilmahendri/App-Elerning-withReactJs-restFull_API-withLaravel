<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;
use Illuminate\Htt\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Models\User;

class AdminController extends Controller
{
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

        $token = $request->token;
        $tokenDB = User::where('token', $token)->count();
        if($tokenDB > 0){
            $key = env('APP_KEY');
            $decoded = JWT::decode($token, $key, ['HS256']);
            $decodeArray = (array) $decoded;
            if($decodeArray['extime'] > time()){
                if(User::create([
                    'nama' => $request->nama,
                    'email' => $request->email,
                    'password' => encrypt($request->password),
                ])){
                    return response()->json([
                        'status' => 'success',
                        'message' => 'successfully save data.'
                    ]);
                }
            }else{
                return response()->json([
                    'status' => 'fail',
                    'message' => 'sorry, this data fail to save.'
                ]);
            }
        }else{
            return response()->json([
                'status' => 'fail',
                'message' => 'sorry, this data token not valid.'
            ]);
        }
    }

    public function authenticatedAdmin(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|unique:users',
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
        if($cek > 0){
            foreach($admin as $admin){
                if($request->password == decrypt($admin->password)){
                    $key = env('APP_KEY');
                    $data = [
                        'extime' => time()+(60*120),
                        'id' => $admin->id,
                        ''
                    ];
                    $generateJWT = JWT::encode($data, $key);

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
}
