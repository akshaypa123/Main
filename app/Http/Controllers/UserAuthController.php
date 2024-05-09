<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use Session;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
class UserAuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }
    public function customLogin(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required',
                'password' => 'required',
            ]);
    
            $credentials = $request->only('email', 'password');
    
            if (Auth::attempt($credentials)) {
                return redirect()->intended('dashboard')->withSuccess('Signed in');
            }
    
            return redirect("login")->withSuccess('Login details are not valid');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
            // You can also log the error or handle it in other ways as per your application's requirement
        }
    }
    
    public function registration()
    {
        return view('auth.register');
    }
public function customRegistration(Request $request)
{
    try {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);
        
        $data = $request->all();
        $check = $this->create($data);
        
        return redirect("dashboard")->withSuccess('You have signed-in');
    } catch (\Exception $e) {
        return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
        // You can also log the error or handle it in other ways as per your application's requirement
    }
}

public function create(array $data)
{
    try {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ]);
    } catch (\Exception $e) {
        // You can log the error or handle it in other ways as per your application's requirement
        throw new \Exception('Error creating user: ' . $e->getMessage());
    }
}

    public function dashboard()
    {
        if (Auth::check()) {
            return view('auth.dashboard');
        }
        return redirect("login")->withSuccess('You are not allowed to access');
    }
    public function signOut()
    {
        Session::flush();
        Auth::logout();
        return Redirect('login');
    }
}
