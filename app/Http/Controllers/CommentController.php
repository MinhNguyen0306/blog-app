<?php

namespace App\Http\Controllers;

use App\Events\NewComment;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Services\FCMService;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    protected $fcmService;

    public function __construct(FCMService $fcmService)
    {
        $this->fcmService = $fcmService;
    }

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

        if ($createdComment) {
            toastr()->success("Da binh luan");
            broadcast(new NewComment($createdComment))->toOthers();
            $this->fcmService->sendNotification($createdComment, $userId);
        } else {
            toastr()->error("Binh luan that bai");
        }

        return redirect()->back();
    }
}
