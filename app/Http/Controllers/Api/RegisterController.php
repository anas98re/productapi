<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Models\User;
//use Validator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
class RegisterController extends BaseController
{
    public function register(Request $request){
        $Validator = Validator::make($request->all(),[
            'name' => 'required',   
            'phone' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'C_password' => 'required|same:password',
        ]);
        if($Validator->fails())
        {
            return $this->sendError('please validate error', $Validator->errors());
        }

        $input=$request->all();
        $input['password']=Hash::make($input['password']);//تشفير الباسسورد
        $user=User::create($input);
        $success['token']=$user->createToken('anas')->accessToken;// رح ياخد البااسسورد تبعك ويشفرها ويرسلها لك
        $success['name']=$user->name;
        $success['phone']=$user->phone;
        $success['email']=$user->email;
        return $this->sendResponse( $success,'user Registerd successullly ');
    }





    public function login(Request $request){

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password]))//ـاكد أنه وضع باسسورد وايميل
        {
            // $user = $this->auth->user();
            //$user = auth()->user();
           $user=Auth::user(); //اذا صحيح اعطي token جديد واعطي الاسم
            $success['token']=$user->createToken('anas')->accessToken;
            $success['name']=$user->name;
            $success['phone']=$user->phone;
        $success['email']=$user->email;
            return $this->sendResponse( $success,'user Login successullly ');

        }
        else{//قي حالة الفشل اعطي ايررور وتحقق من معلوماتك
            return $this->sendError('please cheackyour Auth', ['error'=>'Unauthorised']);
            //     if('password'!=$request->password){
        //     return $this->sendError('please cheackyour Auth', ['error'=>'Unauthorised, password is false']);}
        //    else if('email'!=$request->email)
        //     return $this->sendError('please cheackyour Auth', ['error'=>'Unauthorised, email is false ']);
        //     else
        //     return $this->sendError('please cheackyour Auth', ['error'=>'Unauthorised, false ']);
        }


    }
}
