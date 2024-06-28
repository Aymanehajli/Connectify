<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PublicationRequest;
use App\Models\Comment;
use App\Models\Friend;
use App\Models\Like;
use App\Models\Notification;
use App\Models\Publication;
use App\Models\User;
use Illuminate\Auth\Access\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
            "message" => "<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-person-hearts' viewBox='0 0 16 16'>
            <path fill-rule='evenodd' d='M11.5 1.246c.832-.855 2.913.642 0 2.566-2.913-1.924-.832-3.421 0-2.566M9 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0m-9 8c0 1 1 1 1 1h10s1 0 1-1-1-4-6-4-6 3-6 4m13.5-8.09c1.387-1.425 4.855 1.07 0 4.277-4.854-3.207-1.387-5.702 0-4.276ZM15 2.165c.555-.57 1.942.428 0 1.711-1.942-1.283-.555-2.281 0-1.71Z'/>
            </svg>" .auth()->user()->name . " liked your post",
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

        $activeUsers =  $this->getFriendsOnline($user->id);

       
     
      return view('publication.index',compact('publications','user','friendSuggestions','friends','activeUsers'));
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

    private function getFriendsOnline($userId)
    {
        
        $friendRequests = Friend::where(function ($query) use ($userId) {
            $query->where('user_id', $userId)
                  ->orWhere('friend_id', $userId);
        })->where('status', 'accepted')->get();

        
        $friendIds = $friendRequests->map(function ($friendRequest) use ($userId) {
            return $friendRequest->user_id == $userId ? $friendRequest->friend_id : $friendRequest->user_id;
        });

        return User::whereIn('id', $friendIds)
                ->where('active_status', 1) // Add the condition for active status
                ->get();
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
        return view('publication.edit',compact('publication'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Publication $publication)
    {
        $newData = [
            'titre' => $request->input('titre'),
            'body' => $request->input('body'),
        ];
        if ($request->hasFile('image')) {
            $newData['image'] = $request->file('image')->store('publicationimage', 'public');
        } else {
            $newData['image'] = $publication->image; // Retain the existing image path if no new image is uploaded
        }


        if ($request->hasFile('video')) {
            $newData['video'] = $request->file('video')->store('publicationvideo', 'public');
        } else {
            $newData['video'] = $publication->video; // Retain the existing video path if no new video is uploaded
        }
    
// Update the publication with the new data
$publication->update($newData);
    return redirect()->route('publication.index')->with('success', 'Publication modifiée avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Publication $publication)
    {
        //
        $publication->delete(); 
        return to_route('publication.index')->with('success','Votre publication est bien supprimée ') ;
    }
    public function comments($id)
    {
        try {
            // Fetch the publication
            $publication = Publication::findOrFail($id);
            if (!$publication) {
                throw new \Exception("Publication not found");
            }
    
            // Fetch the comments for the publication
            $comments = $publication->comments()->with('user')->paginate(5);
            
            if ($comments->isEmpty()) {
                throw new \Exception("No comments yet !!");
            }
    
            
            // Map the comments to include necessary user information
            $mappedComments = $comments->map(function ($comment) {
                return [
                    'user_image' => asset('storage/' . $comment->user->image),
                    'user_name' => $comment->user->name,
                    'comment' => $comment->comment
                ];
            });
    
            Log::info('Comments fetched:', $mappedComments->toArray());
            

            return response()->json([
                'success' => true,
                'comments' => $mappedComments,
                'next_page_url' => $comments->nextPageUrl() // Provide the URL for the next page of comments
            ]);
        } catch (\Exception $e) {
            Log::error('Error in fetching comments:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
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

    public function getActiveUsers()
    {
        $activeUsers = User::where('active_status', 1)->get();

        return view('publication.index', compact('activeUsers'));
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

    // Return JSON response for AJAX request
    return response()->json([
        'success' => true,
        'message' => 'Publication partagée avec succès.',
    ]);
}

}
