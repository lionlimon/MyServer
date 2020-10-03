<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;


class RegisterController extends BaseController {
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request) {

        $validator = Validator::make($request->input(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
        
        if (User::where('email', '=', $request->input('email'))->count() <= 0) {
            
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors());       
            }
    
            $input = $request->input();
            $input['password'] = bcrypt($input['password']);

            $user = User::create($input);
        
            $success['token'] =  $user->createToken('MyApp')->accessToken;
            $success['name'] =  $user->name;

            return $this->sendResponse($success, 'Пользователь зарегистрирован.');
        } else {
            return $this->sendError('Пользователь уже существует', ['email' => 'Похоже, пользователь с таким email уже существует']);
        }

        
    }
}