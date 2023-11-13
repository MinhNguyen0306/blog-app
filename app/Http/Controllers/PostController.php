<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;

use App\Models\Post;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::all()->reject(function (Post $post) {
            return $post->isPublished === false;
        })->map(function (Post $post) {
            return $post;
        });

        return view('post.index', ['posts' => $posts]);
    }

    public function create()
    {
        return view('post.create');
    }

    public function edit(Post $post)
    {
        return view('post.edit', ['post' => $post]);
    }

    public function detail(Post $post)
    {
        if (!$post->isPublished) {
            return redirect()->back()->with([
                'fail' => "This post is unpublished"
            ]);
        }

        if (!$post->onlyMember) {
            return redirect()->back()->with([
                'fail' => "This post is for members only"
            ]);
        }

        return view('post.detail', ['post' => $post]);
    }

    public function store(PostRequest $postRequest)
    {
        dd($postRequest);
        $postRequest->validated();

        $post = new Post();
        $post->title = $postRequest->title;
        $post->content = $postRequest->content;

        try {
            $postSaved = Post::saved($post);

            if ($postSaved) {
                return redirect()->back()->with([
                    'success' => 'Tao post thanh cong'
                ]);
            }

            return redirect()->back()->with([
                'fail' => 'Tao post that bai'
            ]);
        } catch (Throwable $throw) {
            return redirect()->back()->with([
                'fail' => $throw->getMessage()
            ]);
        }
    }

    public function update(PostRequest $postRequest, $postId)
    {
        $postRequest->validated();

        try {
            $post = Post::findOrFail($postId);

            if (!$post) {
                return redirect()->back()->with([
                    "fail" => "Post not found with " . $postId
                ]);
            }

            $post->title = $postRequest->title;
            $post->cotnent = $postRequest->content;
            $postSaved = Post::saved($post);

            if ($postSaved) {
                return redirect()->back()->with([
                    "success" => "Update post success"
                ]);
            }

            return redirect()->back()->with([
                "fail" => "Update post failed"
            ]);
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with([
                "fail" => $exception->getMessage()
            ]);
        }
    }

    public function delete($postId)
    {
        try {
            $post = Post::findOrFail($postId);

            $postDeleted = $post->deleted();
            if ($postDeleted) {
                return redirect()->back()->with([
                    'success' => "Xoa post thanh cong"
                ]);
            }

            return redirect()->back()->with([
                "fail" => "Xoa post that bai"
            ]);
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with([
                "fail" => $exception->getMessage()
            ]);
        }
    }

    public function publishPost(Post $post)
    {
        $checkUpdate = $post->where('isPublished', true)
            ->update(['isPublished' => false])
            ->orWhere('isPublished', false)
            ->update(['isPublished' => true]);
        if ($checkUpdate) {
            return view('post.edit', [
                'fail' => "Update post failed"
            ]);
        }

        return view('post.edit', [
            'success' => "Update post success"
        ]);
    }
}
