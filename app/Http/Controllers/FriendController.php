<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class FriendController extends Controller
{
    
    public function search(Request $request)
    {
        $searchTerm = $request->input('search');
        $friends = User::where('name', 'like', "%$searchTerm%")->get();
        return response()->json(['friends' => $friends]);
    }


    
}
