<head>
    @vite(['resources/scss/post-detail-page.scss'])
</head>

<x-layouts.main-layout>
    <div class="postContainer">
        <div class="postHeader">
            <div class="back">
                <x-bx-arrow-back class="icon" />
            </div>
            <div class="headerInfo">
                <h2>Bai dang</h2>
            </div>
        </div>


        <div class="postContent">
            <div class="headerContent">
                <div class="userContent">
                    <img src={{ $post->user->avatar ? asset('images/' . $post->user->avatar) : asset('images/user.png') }}
                        alt="User Post Image"
                        onclick="window.location='{{ route('users.get_profile_view', $post->user->id) }}'" />
                    <div class="userInfo">
                        <h3 onclick="window.location='{{ route('users.get_profile_view', $post->user->id) }}'">
                            {{ $post->user->name }}
                        </h3>
                        <span>{{ $post->created_at }}</span>
                    </div>
                </div>
                <div>
                    <x-common.button type='button'>
                        Theo doi
                    </x-common.button>
                </div>
            </div>

            <div class="mainContent">
                <p class="title">
                    {{ $post->title }}
                </p>
                <p class="content">
                    {{ $post->content }}
                </p>
            </div>

            <div class="postAction">
                <div class="action" id="likeAction">
                    <x-bx-like class="icon" />
                    <span>17</span>
                </div>
                <div class="action" id="commentAction">
                    <x-far-comment class="icon" />
                    <span>17</span>
                </div>
                <div class="action" id="shareAction">
                    <x-heroicon-o-share class="icon" />
                    <span>17</span>
                </div>
                <div class="action" id="favoriteAction">
                    <x-monoicon-favorite class="icon" />
                </div>
            </div>
        </div>

        <div class="postComment">
            @if ($post->comments && $post->comments->count() > 0)
                @foreach ($post->comments as $comment)
                    <x-common.comment :comment='$comment' />
                @endforeach
            @else
                <span class="void">
                    Chưa có bình luận nào
                </span>
            @endif
        </div>
    </div>

    <form action="{{ route('comments.create', ['userId' => Auth::user()->id, 'postId' => $post->id]) }}" method="POST"
        enctype="multipart/form-data" class="commentForm">
        @csrf

        <img src={{ $post->user->avatar ? asset('images/' . $post->user->avatar) : asset('images/user.png') }}
            alt="User Post Image" onclick="window.location='{{ route('users.get_profile_view', $post->user->id) }}'" />

        <div class="commentPost">
            <input type="text" id="content" name="content" class="content" placeholder="Viet binh luan..."
                autocomplete="off" />
            <div class="commentAction">
                <div class="action">
                    <div class="iconAction">
                        <label for="image-upload">
                            <x-bi-image-fill class="icon" />
                        </label>
                        <input type="file" id="image-upload" name="image-upload" accept="image/*">
                    </div>
                </div>

                <div class="submit">
                    <x-common.button type="submit">
                        Tra loi
                    </x-common.button>
                </div>
            </div>
        </div>
    </form>
</x-layouts.main-layout>
