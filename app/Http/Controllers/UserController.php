<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return Inertia::render('Users', [
            'users' => User::all(),
        ]);
    }
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
            'role' => ['required', 'in:admin,user'],
        ]);
         $user->name=$data['name'];
         $user->email=$data['email'];
         $user->role=$data['role'];
        $user->save();
        return redirect()->route('users');

        
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8'],
            'role' => ['required', 'in:admin,user']
        ]);
        $data['password'] = bcrypt($data['password']);
        User::create($data);
        return redirect()->route('users');
    }
    public function destroy(User $user)
    {    
       
        $user->delete();
        return redirect()->route('users');
    }
}
