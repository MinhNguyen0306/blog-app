<head>
    @vite('resources/scss/post.scss')
</head>

<div class="postContainer" onclick="window.location='{{ route('posts.detail', $post->id) }}'">
    <img src={{ $post->user->avatar ? asset('images/' . $post->user->avatar) : asset('images/user.png') }}
        alt="User Post Image" onclick="window.location='{{ route('users.get_profile_view', $post->user->id) }}'" />

    <div class="postInfo">
        <div class="postHeader">
            <h3 onclick="window.location='{{ route('users.get_profile_view', $post->user->id) }}'">
                {{ $post->user->name }}
            </h3>
            <span>{{ $post->user->email }}</span>
            <i></i>
            <span>{{ $post->created_at }}</span>
        </div>
        <div class="postContent">
            <p class="title">
                {{ $post->title }}
            </p>
            <p class="content">
                {{ $post->content }}
                {{-- I'm not convinced that using custom ChatGPT UI is either cheaper or faster than subscribing to ChatGPT
                Plus.Prove me wrong! --}}
            </p>

            @if ($post->image)
                <img src="{{ asset('images/' . $post->image) }}" alt="">
            @endif
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
</div>
