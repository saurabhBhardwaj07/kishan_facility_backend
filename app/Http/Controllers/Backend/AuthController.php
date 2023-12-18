<?php

namespace App\Http\Controllers\Backend;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Rules\IsValidPassword;
use App\Rules\MatchOldPassword;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:users',
            'phone' => 'nullable|digits:10|numeric|unique:users',
            'password' => ['required', 'string', new IsValidPassword(), 'confirmed'],
        ]);

        if ($validator->fails()) {
            return response(["message" => $validator->errors()->first()], 422);
        }

        $user_role = Role::where('name', Role::USER)->where('status', 1)->first();

        $request['password'] = Hash::make(trim($request['password']));
        $request['role_id'] = $user_role->id;

        $user = User::create($request->toArray());
        $token = $user->createToken('Personal Access Token', ['user'])->accessToken;
        $response = ["message" => "Acoount Created Successfully", "user" => $user, "token" => $token];

        return response($response, 200);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => ['required', 'string', new IsValidPassword()],
        ]);

        if ($validator->fails()) {
            return response(["message" => $validator->errors()->first()], 422);
        }

        $user = User::with('image')->where('email', $request->email)->first();

        if ($user) {
            $admin_role = Role::where('name', Role::ADMIN)->where('status', 1)->first();
            $user_role = Role::where('name', Role::USER)->where('status', 1)->first();

            if ($user->role_id == $user_role->id) {
                //Saving User's Device Token Everytime he logins from any device
                $user['device_token'] = $request->device_token;
                $user->save();
            }

            if (Hash::check(trim($request->password), $user->password)) {
                if ($user->role_id == $admin_role->id) {
                    $token = $user->createToken('Personal Access Token', ['admin'])->accessToken;
                } else {
                    $token = $user->createToken('Personal Access Token', ['user'])->accessToken;
                }

                $response = ["message" => "Login Successful", "user" => $user, "token" => $token];
                return response($response, 200);
            } else {
                $response = ["message" => "Incorrect Password"];
                return response($response, 422);
            }
        } else {
            $response = ["message" => 'User does not exist'];
            return response($response, 422);
        }
    }

    public function googleLogin(Request $request)
    {
        $full_name = explode(" ", $request->display_name);

        if (count($full_name) > 2) {
            $first_name = $full_name[0];
            $middle_name = $full_name[1];
            $last_name = $full_name[2];
        } else {
            $first_name = $full_name[0];
            $middle_name = null;
            $last_name = $full_name[1];
        }

        $user = User::with('image')->where('email', $request->email)->first();

        $admin_role = Role::where('name', Role::ADMIN)->where('status', 1)->first();
        $user_role = Role::where('name', Role::USER)->where('status', 1)->first();


        if (!$user) {
            $user = User::create([
                'role_id' => $user_role->id,
                'first_name' => $first_name,
                'middle_name' => $middle_name,
                'last_name' => $last_name,
                'phone' => $request->phone_no,
                'google_id' => $request->google_id,
                'email' => $request->email,
            ]);
        }

        if ($user->role_id == $user_role->id) {
            //Saving User's Device Token Everytime he logins from any device
            $user['device_token'] = $request->device_token;
            $user->save();
        }

        if ($user->role_id == $admin_role->id) {
            $token = $user->createToken('Personal Access Token', ['admin'])->accessToken;
        } else {
            $token = $user->createToken('Personal Access Token', ['user'])->accessToken;
        }

        $response = ["message" => "Login Successful", "user" => $user, "token" => $token];
        return response($response, 200);
    }

    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();
        $response = ['message' => 'You have been successfully Logged out!'];
        return response($response, 200);
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => ['required', new IsValidPassword(), new MatchOldPassword],
            'new_password' => ['required', new IsValidPassword()],
            'new_confirm_password' => ['required',  new IsValidPassword(), 'same:new_password'],
        ]);

        if ($validator->fails()) {
            return response(["message" => $validator->errors()->first()], 422);
        }

        $user = User::where('id', Auth::user()->id)->update(['password' => Hash::make(trim($request->new_password))]);

        return response(["status" => 200, "message" => "Password Changed Successfully",]);
    }
}
