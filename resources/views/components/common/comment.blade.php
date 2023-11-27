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
            <span>{{ floor((strtotime(date('Y-m-d H:i:s')) - strtotime($comment->updated_at)) / 60 / 60 / 24) < 1
                ? (floor((strtotime(date('Y-m-d H:i:s')) - strtotime($comment->updated_at)) / 60 / 60) < 1
                    ? (floor((strtotime(date('Y-m-d H:i:s')) - strtotime($comment->updated_at)) / 60) < 1
                        ? strtotime(date('Y-m-d H:i:s')) - strtotime($comment->updated_at) . ' giây trước'
                        : floor((strtotime(date('Y-m-d H:i:s')) - strtotime($comment->updated_at)) / 60) . ' phút trước')
                    : floor((strtotime(date('Y-m-d H:i:s')) - strtotime($comment->updated_at)) / 60 / 60) . ' giờ trước')
                : floor((strtotime(date('Y-m-d H:i:s')) - strtotime($comment->updated_at)) / 60 / 60 / 24) . ' ngày trước' }}</span>
            <div class="tick"></div>
            <div class="action" id="likeAction">
                <i class="fa-regular fa-heart icon"></i>
                <span>17</span>
            </div>
            <div class="action" id="commentAction">
                <i class="fa-regular fa-comment icon"></i>
                <span>17</span>
            </div>
        </div>
    </div>
</div>
