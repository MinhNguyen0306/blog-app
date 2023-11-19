<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function getCommentOfPost(Post $post)
    {
        $comments = $post->comments;
        return view('post.detail', ["comments" => $comments]);
    }

    public function create(Request $request, $userId, $postId)
    {
        $request->validate([
            'content' => 'required', 'min:5',
        ]);

        if (!$userId || !$postId) {
            toastr()->error("Require user ID and post Id");
            return redirect()->back();
        }

        $request->merge(['user_id' => $userId, 'post_id' => $postId]);

        $createdComment = Comment::create($request->all());

        if ($createdComment) toastr()->success("Da binh luan");
        else toastr()->error("Binh luan that bai");

        return redirect()->back();
    }
}
