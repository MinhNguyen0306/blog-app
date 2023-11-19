<head>
    @vite('resources/scss/comment-box.scss')
</head>

<div class="commentContainer">
    <img src={{ $comment->user->avatar ? asset('images/' . $comment->user->avatar) : asset('images/user.png') }}
        alt="User comment Image" onclick="window.location='{{ route('users.get_profile_view', $comment->user->id) }}'" />

    <div class="commentInfo">
        <div class="commentHeader">
            <h3 onclick="window.location='{{ route('users.get_profile_view', $comment->user->id) }}'">
                {{ $comment->user->name }}
            </h3>
            <span>{{ $comment->user->email }}</span>
        </div>
        <div class="commentContent">
            <p class="content">
                {{ $comment->content }}
            </p>

            @if ($comment->image)
                <img src="{{ asset('images/' . $comment->image) }}" alt="">
            @endif
        </div>

        <div class="commentAction">
            <span>{{ $comment->created_at }}</span>
            <i></i>
            <div class="action" id="likeAction">
                <x-bx-like class="icon" />
                <span>17</span>
            </div>
            <div class="action" id="commentAction">
                <x-far-comment class="icon" />
                <span>17</span>
            </div>
        </div>
    </div>
</div>
