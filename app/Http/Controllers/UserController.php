<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Exception;

class UserController extends Controller
{

    public function show( Request $request){
        try{
            $user = User::find(request("id"));
            if(!$user){
                throw new Exception(json_encode("This user does not exist..!"),400);
            }          
            return Helper::document("users",[
                'name' => $user->name,
                'email' => $user->email,
                "password" => $user->password,
            ],
            $user->id
            );
        }catch(\Exception $ex){
            return response()->json(["errors" => json_decode($ex->getMessage())],($ex->getCode() == 0?400:$ex->getCode()) );
        } 
    }

    public function store(Request $request){
        try{
            $validator = Validator::make($request->json()->all(), [
                'data.attributes.name' => "required|string",
                'data.attributes.email' => "required|email|unique:users,email",
                "data.attributes.password" => "required|string"
            ]);
           
            if ($validator->fails()) {
                throw new Exception(json_encode($validator->errors()),422);
            }
            $user = User::create([
                'name' => $request->json("data.attributes.name"),
                'email' => $request->json("data.attributes.email"),
                "password" => Hash::make($request->json("data.attributes.password")),
            ]);
            return Helper::document("users",[
                'name' => $user->name,
                'email' => $user->email,
                "password" => $user->password,
            ],
            $user->id
        );
        }catch(\Exception $ex){
            return response()->json(["errors" => json_decode($ex->getMessage())],($ex->getCode() == 0?400:$ex->getCode()) );
        }
    }

    public function update(Request $request){
        try{           
            $validator = Validator::make($request->json()->all(), [
                'data.attributes.name' => "sometimes|required|string",
                'data.attributes.email' => ["sometimes","required","email",
                                        Rule::unique("users","email")->ignore($user->id,"id"),
                                        "unique:users,email"],
                "data.attributes.password" => "sometimes|required|string"
            ]);
          
            if ($validator->fails()) {
                throw new Exception(json_encode($validator->errors()),422);
            }
            $user = User::find(request("id"));
            if(!$user){
                throw new Exception(json_encode("This user does not exist..!"),400);
            } 

            if($request->json("data.attributes.name")){
                $user->name = $request->json("data.attributes.name");
            }
            if($request->json("data.attributes.email")){
                $user->email = $request->json("data.attributes.email");
            }
            if($request->json("data.attributes.password")){
                $user->password = Hash::make($request->json("data.attributes.password"));
            }
            $user->save();
            return Helper::document("users",[
                'name' => $user->name,
                'email' => $user->email,
                "password" => $user->password,
            ],
            $user->id
            );
        }catch(\Exception $ex){
            return response()->json(["errors" => json_decode($ex->getMessage())],($ex->getCode() == 0?400:$ex->getCode()) );
        }
    }

   public function destory( Request $request){
    try{           
        $user = User::find(request("id"));
            if(!$user){
                throw new Exception(json_encode("This user does not exist..!"),400);
            } 
        $regreso = $user;
        $user->delete();
        return Helper::document("users",[
            'name' => $regreso->name,
            'email' => $regreso->email,
            "password" => $regreso->password,
        ],
        $regreso->id
        );
    }catch(\Exception $ex){
        return response()->json(["errors" => json_decode($ex->getMessage())],($ex->getCode() == 0?400:$ex->getCode()) );
    }
   }
}
