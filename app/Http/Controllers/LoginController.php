<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Session\session;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;


class LoginController extends Controller
{

    public function Home(){


        return view('home');
    } 

    public function show(){


        return view('login.show');
    }
    
    public function login(Request $request) :RedirectResponse{
        
        $email =$request->email;
        $password=$request->password;
        
        $values = ['email' => $email,'password' => $password ];
        //kaykhdm par defaut ela table user 
        //khas tbdl mn config auth w users-table w ndero smiya dyl table dyalna
        
         if(Auth::attempt($values)&& Auth::user()->deleted_at === null){
            //connected
            //$request-> session()->regenerate();
            
           Auth::login(Auth::user(), true);
           $user = User::find(auth()->id()); // Get the user by ID
        $user->toggleActiveStatus(); // Toggle the active status

            return to_route('publication.index')->with('success','Vous étes bien connecté '.$email.".");

         }else{
            //shi haja ghalat
            throw ValidationException::withMessages([
                'email' => 'Email ou mot de passe incorrect!',
            ])->errorBag('login')->redirectTo('/loginform');
         }     
    }

    public function logout(Request $request){
        $user = User::find(auth()->id()); // Get the user by ID
        $user->toggleActiveStatus(); // Toggle the active status

        Auth::logout();

        $request->session()->flush();

        

        return to_route('loginshow');

    }
    protected function credentials(Request $request)
    {
        return array_merge($request->only($this->username(), 'password'), ['deleted_at' => null]);
    }
}
