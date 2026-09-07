<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        if (!auth()->attempt($credentials)) {
            return back()->withErrors([
                'email' => 'The provided details are incorrect.',
            ]);
        }
        $request->session()->regenerate();
        return redirect()->route('home');
    }
    public function logout(Request $request){
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
    public function updateProfile(Request $request){
        $data=$request->validate([
         'name'=>['required','string','max:255'],
         'email'=>['required','email','unique:users,email,'.auth()->id()],
          'current_password'=>['nullable','current_password'],
         'password'=>['nullable','string','min:8','confirmed'],
        ]);
        $user=auth()->user();
        $user->name=$data['name'];
        $user->email=$data['email'];

        if(!empty($data['password'])){
            if(empty($data['current_password'])){
              return back()->withErrors([
               'current_password'=>'Current Password is required to change your password',
              ]);
            }
            $user->password=bcrypt($data['password']);
        }
        $user->save();
        return redirect()->route('profile');
    }
}
