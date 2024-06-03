<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\GenericUse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SettingsController extends Controller
{
    public function index()
    { 
        
        $user = Auth::user();
        $currentLocale = App::getLocale();

        return view('settings.index', compact('user','currentLocale'));
    }
    public function updateProfileimage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:png,jpg,svg,jpeg,jfif'
        ]);
        
        DB::table('users')
    ->where('id', Auth::id())
    ->update([
        'image'=> $request->file('image')->store('userprofile','public'),
        
    ]);
       
        return redirect()->route('settings.index')->with('status', 'Profile picture updated successfully.');
    }


    public function switchLanguage(Request $request)
    {
        $language = $request->input('language');
        $languages = ['en', 'fr', 'es','ar']; // Add more languages as needed

        if (in_array($language, $languages)) {
            Session::put('app_locale', $language);
            App::setLocale($language);
        }
        
        $user = Auth::user();
        $currentLocale = App::getLocale();

        return view('settings.index', compact('user','currentLocale'));
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
       
       
        return redirect()->route('settings.index')->with('success', 'Account updated successfully.');
        
    }

    public function updateAccount(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->old_password, $user->password)) {
            return back()->with('status', 'Old password does not match our records.');
        }
        
        DB::table('users')
    ->where('id', Auth::id())
    ->update([
        'password' => Hash::make($request->password),
        
    ]);
       
        return back()->with('status', 'Account updated successfully.');
    }
    

    
    

   
}
