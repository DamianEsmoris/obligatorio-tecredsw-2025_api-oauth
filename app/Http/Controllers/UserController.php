<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Lcobucci\JWT\Parser;

use function PHPUnit\Framework\isNull;

class UserController extends Controller
{
    public function GetAll(Request $request) {
        if (Cache::has('users')) {
            $users = Cache::get('users');
            return $users;
        }
        $users = User::all();
        Cache::put('users', $users, 180);
        return $users;
    }

    public function Get(Request $request, int $id) {
        if (Cache::has('users')) {
            $users = Cache::get('users');
            return $users->find($id);
        }
        return User::findOrFail($id);
    }

    public function Register(Request $request){

        $validation = Validator::make($request->all(),[
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed'
        ]);

        if($validation->fails())
            return response($validation->errors(), 401);

        return $this -> createUser($request);

    }

    public function PasswordRecovery(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'email' => 'required|exists:users,email',
            'password' => 'required|confirmed'
        ]);

        if ($validation->fails())
            return response()->json($validation->errors(), 401);

        $user = User::where('email', $request->post("email"))->first();
        $user->password = Hash::make($request->post("password"));
        $user->save();
        return $user;
    }

    private function createUser($request){
        $user = new User();
        $user -> name = $request -> post("name");
        $user -> email = $request -> post("email");
        $user -> password = Hash::make($request -> post("password"));
        $user -> save();
        Cache::forget('users');
        return $user;
    }

    public function ValidateToken(Request $request){
        return auth('api')->user();
    }

    public function Logout(Request $request){
        $request->user()->token()->revoke();
        return ['message' => 'Token Revoked'];
    }


}
