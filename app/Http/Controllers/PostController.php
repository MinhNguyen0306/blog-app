<?php

namespace App\Http\Controllers;

use App\Events\LikeEvent;
use App\Http\Requests\PostRequest;
use App\Models\Category;
use App\Models\LikePost;
use App\Models\Post;
use App\Models\Share;
use App\Models\User;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SebastianBergmann\Type\FalseType;
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

        $users = User::where('id', '!=', Auth::user()->id)->get();

        $categories = Category::all();

        return view('home', [
            'users' => $users,
            'posts' => $posts,
            'shares' => $shares,
            'categories' => $categories,
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

    public function update(PostRequest $request, $postId)
    {
        $request->validated();

        try {
            $post = Post::findOrFail($postId);

            $post->title = $request->title;
            $post->content = $request->content;
            $post->category_id = $request->category_id;
            $post->user_id = $request->user_id;

            $postSaved = $post->save();

            if ($postSaved) {
                toastr()->success("Cap nhat thanh cong");
            } else {
                toastr()->error("Cap nhat that bai");
            }
        } catch (ModelNotFoundException $ex) {
            toastr()->error($ex->getMessage());
        } finally {
            return redirect()->back();
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

    public function share($userId, $postId)
    {
        try {
            $user = User::findOrFail($userId);

            $share = $user->shares()
                ->where('post_id', $postId)
                ->first();

            if ($share) {
                if ($share->isPublished === false) {
                    $share->isPublished = true;
                    $updatedShare = $share->save();
                    if ($updatedShare) toastr()->success("Publish share");
                    else toastr()->error('Error publish share');
                    return redirect()->back();
                }

                toastr()->error('Ban da chia se post nay roi!');
                return redirect()->back();
            }

            $freshShare = new Share();
            $freshShare->post_id = $postId;
            $freshShare->user_id = $userId;
            $freshShare->isPublished = true;
            $freshShare->shareContent = "";
            $createdShare = $freshShare->save();

            if ($createdShare) {
                toastr()->success("Share post thanh cong");
            } else {
                toastr()->error("Share post that bai");
            }
        } catch (ModelNotFoundException $ex) {
            toastr()->error("Resource not found exception with " . $userId);
        } finally {
            return redirect()->back();
        }
    }

    public function likePost($userId, $postId)
    {
        try {
            $user = User::findOrFail($userId);

            $checkLike = $user->likes
                ->where('post_id', $postId)
                ->first();

            if (!$checkLike) {
                $createdLikePost = LikePost::create([
                    'user_id' => $userId,
                    'post_id' => $postId,
                    'has_like' => true
                ]);

                if (!$createdLikePost) toastr('Server Error');
            } else {
                $checkLike->update(['has_like' => false]);
            }
        } catch (ModelNotFoundException $e) {
            toastr($e->getMessage());
        } finally {
            return redirect()->back();
        }
    }
}
