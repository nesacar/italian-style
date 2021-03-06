<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\EditUserRequest;
use App\User;
use Illuminate\Http\Request;
use File;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function index(){
        $users = User::select('id', 'name', 'email', 'role_id', 'created_at')->orderBy('created_at', 'DESC')->paginate(3);
        return response()->json([
            'users' => $users
        ]);
    }

    public function store(CreateUserRequest $request){
        $user = User::create($request->except('password', 'image'));
        if(request('password')){
            $user->password = bcrypt(request('password'));
            $user->update();
        }
        if(request('image')){
            User::base64UploadImage($user->id, request('image'));
        }
        return response()->json([
            'user' => $user
        ]);
    }

    public function show($id){
        $user = User::find($id);
        return response()->json([
            'user' => $user
        ]);
    }

    public function update(EditUserRequest $request, $id){
        $user = User::find($id);
        $user->update($request->except('password', 'image'));
        if(request('password')){
            $user->password = bcrypt(request('password'));
            $user->update();
        }
        return response()->json([
            'user' => $user
        ]);
    }

    public function destroy($id){
        $user = User::find($id);
        if(!empty($user->image)) File::delete($user->image);
        User::destroy($user->id);
        return response()->json([
            'message' => 'deleted'
        ]);
    }

    public function uploadImage($id){
        $image = User::base64UploadImage($id, request('image'));
        return response()->json([
            'image' => $image
        ]);
    }

    public function changePassword(ChangePasswordRequest $request){
        $user = $request->user();
        if (Hash::check(request('oldpassword'), $user->password)) {
            $user->password = bcrypt(request('password'));
            $user->update();

            if(request('image')){
                User::base64UploadImage($user->id, request('image'));
            }

            return response()->json([
                'message' => 'done'
            ]);
        }else{
            return response()->json([
                'errors' => [
                    'oldpassword' => [
                        0 => 'the old password mismatch'
                    ]
                ]
            ], 422);
        }
    }

    public function logout(Request $request){
        $user = $request->user();
        User::setLogoutUser($user);
        return response()->json([
            'message' => 'logout user'
        ]);
    }

    public function getUsers(Request $request){
        $user = $request->user();
        $login = User::select('id', 'email', 'role_id', 'name', 'image')->where('block', 0)->where('online', 1)->where('id', '<>', $user->id)->get();
        $logout = User::select('id', 'email', 'role_id', 'name', 'image')->where('block', 0)->where('online', 0)->where('id', '<>', $user->id)->get();
        return response()->json([
            'loginUsers' => $login,
            'logoutUsers' => $logout
        ]);
    }
}
