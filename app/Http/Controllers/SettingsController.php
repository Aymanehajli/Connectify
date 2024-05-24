<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\GenericUse;

class SettingsController extends Controller
{
    public function index()
    { 
        
        $user = Auth::user();
        return view('settings.index', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
        ]);
        

        DB::table('users')
        ->where('id', Auth::id())
        ->update([
            'name' => $request->username,
            'email' => $request->email,
        ]);
        $statusMessage = 'Profile updated successfully.';
       
        return back()->with('status', $statusMessage);
        
    }

    public function updateAccount(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        DB::table('users')
    ->where('id', Auth::id())
    ->update([
        'password' => Hash::make($request->password),
        
    ]);
       
        return back()->with('status', 'Account updated successfully.');
    }

    
    

   
}
