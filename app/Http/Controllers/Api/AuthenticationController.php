<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseResponse;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;

class AuthenticationController extends BaseResponse
{
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return $this->error('Akun tidak ditemukan', 422, ['email' => ['Akun tidak ditemukan']]);
        }elseif (! Hash::check($request->input('password'), $user->password)) {
            return $this->error('Password Salah', 422, ['password' => ['Password salah']]);
        }
        $user->tokens()->where('name', 'authToken')->delete();
        $token = $user->createToken('authToken')->plainTextToken;
        $userAuth = User::where('email', $request->email)->first();
        return $this->ok(['token' => $token,'user' => $userAuth], 'Login success', 200);
    }
    public function register(RegisterRequest $request)
    {
        try{
            \DB::beginTransaction();
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => \Hash::make($request->password),
            ]);
            $token = $user->createToken('authToken')->plainTextToken;
            \DB::commit();
            return $this->created(['token' => $token,'user' => $user], 'Register success', 201);
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->error($e->getMessage(), 500);
        }
        \DB::commit();
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->ok(null, 'Logout success', 200);
    }
    public function user(){
        $user = User::where('id', auth()->user()->id)->first();
        return $this->ok($user);
       
    }
}
