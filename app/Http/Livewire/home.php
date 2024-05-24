<?php

namespace App\Http\Livewire;

use App\Models\Like;
use App\Models\Publication;
use Illuminate\Console\View\Components\Component;
use Illuminate\Support\Facades\DB;

class Home extends Component
{
    public function like($id)
    {
        DB::beginTransaction();
        try {
            Like::firstOrCreate(["publication_id" => $id, "user_id" => auth()->id()]);
            $post = Publication::findOrFail($id);
            $post->likes += 1;
            $post->save();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function dislike($id)
    {
        DB::beginTransaction();
        try {
            $like = Like::where(["publication_id" => $id, "user_id" => auth()->id()])->first();
            $like->delete();
            $post = Publication::findOrFail($id);
            $post->likes -= 1;
            $post->save();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}