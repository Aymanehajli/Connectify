<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PublicationRequest;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Notification;
use App\Models\Publication;
use Illuminate\Auth\Access\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public function __construct(){
        $this->middleware('auth');
     }

     public function like($id)
{
    DB::beginTransaction();
    try {
        Like::firstOrCreate(["publication_id" => $id, "user_id" => auth()->id()]);
        $post = Publication::findOrFail($id);
        $post->likes += 1;
        $post->save();
        DB::commit();
        Notification::create([
            "type" => "Like post",
            "user_id" => $post->user_id,
            "message" => auth()->user()->name . " liked your post",
            "url" => "#",
        ]);
        return response()->json(['success' => true, 'likes' => $post->likes, 'liked' => true]);
    } catch (\Throwable $th) {
        DB::rollBack();
        return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
    }
}

public function dislike(Request $request, $id)
{
   
        $like = Like::where(["publication_id" => $id, "user_id" => auth()->id()])->first();
        
            $like->delete();
            $post = Publication::findOrFail($id);
            $post->likes -= 1;
            $post->save();
            
            Notification::create([
                "type" => "remove like",
                "user_id" => $post->user_id,
                "message" => auth()->user()->name . " removed their like from your post",
                "url" => "#",
            ]);
            return response()->json(['success' => true, 'likes' => $post->likes, 'liked' => false]);
       
}

     
    public function index(Request $request)
    {
        //latest bach akhir haja hiya tban lwla
        
       
        $publications = Publication::where('user_id', auth()->id())
        ->orWhere('shared_by', auth()->id())
        ->orWhereDoesntHave('user', function ($query) {
            $query->where('id', auth()->id());
        })
        ->orderBy('created_at', 'desc')
        ->get();
    
      $user = Auth::user();
      
   
      return view('publication.index',compact('publications','user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('publication.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PublicationRequest $request)
    {
        
        $formfields = $request->validated();
        if ($request->hasFile('image')) {
            $formfields['image'] = $request->file('image')->store('publicationimage', 'public');
        }
    
        // Handle video upload
        if ($request->hasFile('video')) {
            $formfields['video'] = $request->file('video')->store('publicationvideo', 'public');
        }
        $formfields['user_id']=Auth::id();
        Publication::create($formfields);
        return to_route('publication.index')->with('success','Votre compte est bien crée') ;
    }

    /**
     * Display the specified resource.
     */
    public function show(Publication $publication)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Publication $publication)
    {
       //baqi gates w policies

        return view('publication.edit',compact('publication'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Publication $publication)
    {
        
        //baqi gates w policies

    $publication->update([
        'titre' => $request->input('titre'),
        'body' => $request->input('body'),
        'image' => $request->file('image')->store('publicationimage','public'),
    ]);
    return redirect()->route('publication.index')->with('success', 'Publication modifiée avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Publication $publication)
    {
        //
        $publication->delete(); 
        return to_route('publication.index')->with('success','Votre compte est bien crée') ;
    }
    public function comments($id)
    {
        $publication = Publication::with('comments.user')->findOrFail($id);
        $comments = $publication->comments->map(function ($comment) {
            return [
                'user_image' => asset('storage/' . $comment->user->image),
                'user_name' => $comment->user->name,
                'comment' => $comment->body
            ];
        });
    
        return response()->json(['success' => true, 'comments' => $comments]);
    }
    
    public function addComment(Request $request, $id)
    {
        $publication = Publication::findOrFail($id);
    
        $comment = new Comment();
        $comment->user_id = Auth::id();
        $comment->publication_id = $id;
        $comment->comment = $request->comment;
        $comment->save();
    
        return response()->json([
            'success' => true,
            'comment' => [
                'user_image' => asset('storage/' . Auth::user()->image),
                'user_name' => Auth::user()->name,
                'comment' => $comment->comment
            ]
        ]);
    }




    public function share($id)
    {
        $originalPublication = Publication::findOrFail($id);

        $sharedPublication = Publication::create([
            'user_id' => auth()->id(),
            'shared_by' => $originalPublication->user_id,
            'titre' => $originalPublication->titre,
            'body' => $originalPublication->body,
            'image' => $originalPublication->image,
            'video' => $originalPublication->video,
        ]);

        Notification::create([
            "type" => "share post",
            "user_id" => $originalPublication->user_id,
            "message" => auth()->user()->name . " shared your post",
            "url" => "#",
        ]);

        return redirect()->back()->with('success', 'Publication partagée avec succès.');
    }
}
