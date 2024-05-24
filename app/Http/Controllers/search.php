
<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


use App\Models\User;
use Illuminate\Http\Request;



class search extends Controller
{
    //public function __construct()
    //{
      //  $this->middleware('auth');
        
    //}

    public function search(Request $request)
    {
        dd($request);
        $searchTerm = $request->input('search');

        // Perform a "like" query to search for users
        $users = User::where('name', 'like', "%$searchTerm%")->get();

        return view('user.search', compact('users'));
    }
}