<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Category;
use App\Models\Post;
use App\Models\Share;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class PostController extends Controller
{
    public function home()
    {
        $posts = Post::all()->reject(function (Post $post) {
            return $post->isPublished === false;
        })->map(function (Post $post) {
            return $post;
        });

        $shares = Share::all()->reject(function (Share $share) {
            return $share->isPublished === false;
        })->map(function (Share $share) {
            return $share;
        });


        $categories = Category::all();

        return view('home', [
            'posts' => $posts,
            'shares' => $shares,
            'categories' => $categories
        ]);
    }

    public function create()
    {
        return view('post.create');
    }

    public function edit(Post $post)
    {
        return view('post.edit', ['post' => $post]);
    }

    public function detail($postId)
    {
        // if (!$post->isPublished) {
        //     return redirect()->back()->with([
        //         'fail' => "This post is unpublished"
        //     ]);
        // }

        // if (!$post->onlyMember) {
        //     return redirect()->back()->with([
        //         'fail' => "This post is for members only"
        //     ]);
        // }
        $post = Post::findOrFail($postId);

        return view('post.detail', ['post' => $post]);
    }

    public function store(PostRequest $request)
    {
        $request->validated();

        $newFile = '';
        if ($request->has('image-upload')) {
            $file = $request->file('image-upload');
            $fileName = $file->hashName();
            $newFile = time() . '-' . $fileName;
            $file->move(public_path('/images'), $newFile);
        }

        $request->merge(['image' => $newFile]);

        $postSaved = Post::create($request->all());

        if ($postSaved) {
            toastr()->success('Dang bai thanh cong');
        } else {
            toastr()->error("Dang bai that bai");
        }

        return redirect()->back();
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
