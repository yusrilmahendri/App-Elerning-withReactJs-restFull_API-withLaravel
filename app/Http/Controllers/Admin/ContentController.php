<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Content\ContentCollection;
use Illuminate\Http\Request;
use App\Models\Content;
use App\Models\User;
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;
use Illuminate\Support\Facades\Validator;

class ContentController extends Controller
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
                $contents = Content::paginate(6);
                
                return new ContentCollection($contents);
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
    public function createdContent(Request $request){
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'link_thumbnail' => 'required',
            'link_video' => 'required',
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
                if(Content::create([
                    'title' =>  $request->title,
                    'description' =>  $request->description,
                    'link_thumbnail' =>  $request->link_thumbnail,
                    'link_video' =>  $request->link_video,
                ])){
                    return response()->json([
                        'status' => 'Success',
                        'message' => 'Conglrations this content success to save',
                    ]);
                }
            }else{
                return response()->json([
                    'status' => 'false',
                    'message' => 'failed to save data content'
                ]); 
            }
        }else{
            return response()->json([
                'status' => 'falsel',
                'message' => 'failed to save data content'
            ]); 
        }   
    }

    public function updatedContent(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'title' => 'required| unique:contents,title,'.$request->id.',id',
            'description' => 'required',
            'link_thumbnail' => 'required',
            'link_video' => 'required',
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
                if(Content::where('id', $request->id)->update([
                    'title' =>  $request->title,
                    'description' =>  $request->description,
                    'link_thumbnail' =>  $request->link_thumbnail,
                    'link_video' =>  $request->link_video,
                ])){
                    return response()->json([
                        'status' => 'Success',
                        'message' => 'Conglrations this content success to updated',
                    ]);
                }
            }else{
                return response()->json([
                    'status' => 'false',
                    'message' => 'failed to save data content'
                ]); 
            }
        }else{
            return response()->json([
                'status' => 'falsel',
                'message' => 'failed to save data content'
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
                if(Content::where('id', $request->id)->delete()){
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
