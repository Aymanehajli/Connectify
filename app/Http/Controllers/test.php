<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Block;
use App\Models\Friend;
use App\Models\User;
use Illuminate\Http\Request;

class test extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['create', 'store']]);    }
    
    

    //show all users
    public function index (){

        $users = User::paginate(10);
        
        return view('user.users',['users' => $users]);
    }
 

    private function getFriends($userId)
    {
        
        $friendRequests = Friend::where(function ($query) use ($userId) {
            $query->where('user_id', $userId)
                  ->orWhere('friend_id', $userId);
        })->where('status', 'accepted')->get();

        
        $friendIds = $friendRequests->map(function ($friendRequest) use ($userId) {
            return $friendRequest->user_id == $userId ? $friendRequest->friend_id : $friendRequest->user_id;
        });

        return User::whereIn('id', $friendIds)->get();
    }


    //show by id
    public function show(User $user)
    {

        
        $Auser = auth()->user();
        $user1 = User::find($user->id);
        $auth1 = User::find($Auser->id);

        $friendSuggestions = User::where('id', '!=', auth()->id())
        ->whereNotIn('id', function ($query) {
            $query->select('friend_id')
                  ->from('friends')
                  ->where('user_id', auth()->id())
                  ->where('status', 'accepted'); // Vous pouvez ajouter d'autres conditions si nécessaire
        })
        ->inRandomOrder()
        ->take(5)
        ->get();

        $friends = $this->getFriends($user->id);

        $isBlocked = $auth1->isBlockedBy($user1) || $user1->hasBlocked($auth1);

        return view('user.show', compact('user','auth1','isBlocked','friendSuggestions','friends'));
    }

     //create formulaire
    public function create()
    {
        return view('user.create');
    }
    public function settings ()

    {
        return view('user.settings');
    }
    public function formul(){
        return view('hello');
    }


    //store formulaire
    public function store (UserRequest $request)
    {
        //$name=$request->name;
        //$password=$request->password;
        //$email=$request->email;
       
        //$token = $request->session()->token();
        

        $formfields=$request->validated();

        //recupere filename bach maybqach temporaire
        $formfields['image']=$request->file('image')->store('userprofile','public');
        
        //*******validation
        //  $request->validate([
         //   'name' => 'required',
          //  'password' => 'required|confirmed',
          //]);
       
          
          //************cryptage du password
           // $password=$request->password;
           // dd(Hash::make($password));
       
           User::create($formfields);

        //insertion
        //User::create([
        //    'name' => $name,
         //   'email' => $email,
          //  'password' => $password,
        //]);
        
        return view('login.show');
    }


    //supprimer un compte
    public function destroy(Request $request){
        $user = User::find(auth()->id());
        $user->toggleActiveStatus();
        Auth::logout();
        $request->session()->flush();

        $user->delete();   
    
       return to_route('loginshow');
    }


     /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        

        return view('user.edit',compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        

    $user->update([
        'name' => $request->input('name'),
        'email' => $request->input('email'),
        'password' => $request->input('password'),
        'image' => $request->file('image')->store('userprofile','public'),
    ]);
    return redirect()->route('user.index')->with('success', 'user modifiée avec succès');
    }

    
    
    public function getActiveUsers()
    {
        $activeUsers = User::where('active_status', 1)->get();

        return view('user.active', compact('activeUsers'));
    }

    

}
