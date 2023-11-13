<?php

namespace App\Http\Controllers;

use App\Models\Post;

class CommentController extends Controller
{
    public function getCommentOfPost(Post $post)
    {
        $comments = $post->comments;
        return view('post.detail', ["comments" => $comments]);
    }
}
