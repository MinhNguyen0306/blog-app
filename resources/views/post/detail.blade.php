<head>
    @vite(['resources/scss/post-detail-page.scss', 'resources/js/app.js', 'resources/scss/comment-box.scss'])
</head>

<script type="module">
    const url = window.location.href
    const postId = url.split('/')[4];
    const postComment = document.querySelector('.postComment');
    const voidClass = document.querySelector('.void');

    window.Echo.channel('post.' + postId)
        .listen('NewComment', (comment) => {
            console.log(comment);
            const userAvatar = comment.user.avatar;
            const userName = comment.user.name;
            const userEmail = comment.user.email;
            const userId = comment.user.id;
            const commentContainer = `<div class="commentContainer">
                                <img src=${userAvatar ? `http://127.0.0.1:8000/images/${userAvatar}` : "http://127.0.0.1:8000/images/user.png"}
                                    alt="User comment Image" onclick="window.location='http://127.0.0.1:8000/account/profile/${userId}'" />

                                <div class="commentInfo">
                                    <div class="commentHeader">
                                        <h3 onclick="window.location='http://127.0.0.1:8000/account/profile/${userId}'">
                                            ${userName}
                                        </h3>
                                        <span>${userEmail}</span>
                                    </div>
                                    <div class="commentContent">
                                        <p class="content">
                                            ${comment.content}
                                        </p>
                                    </div>

                                    <div class="commentAction">
                                        <span>${comment.created_at}</span>
                                        <i></i>
                                        <div class="action" id="likeAction">
                                            <i class="fa-solid fa-arrow-left icon"></i>
                                            <span>17</span>
                                        </div>
                                        <div class="action" id="commentAction">
                                            <i class="fa-regular fa-comment icon"></i>
                                            <span>17</span>
                                        </div>
                                    </div>
                                </div>
                            </div>`
            if (voidClass) {
                voidClass.remove();
            }
            postComment.innerHTML = postComment.innerHTML + commentContainer;
        });
</script>

<x-layouts.main-layout>

    <div class="postContainer">
        <div class="postHeader">
            <div class="back" onclick="window.location='{{ url()->previous() }}'">
                <i class="fa-solid fa-arrow-left" class="icon"></i>
            </div>
            <div class="headerInfo">
                <h2>Bài đăng</h2>
            </div>
        </div>

        <div class="postContent">
            <div class="headerContent">
                <div class="userContent"
                    onclick="window.location='{{ route('users.get_profile_view', $post->user->id) }}'">
                    <img src={{ $post->user->avatar ? asset('images/' . $post->user->avatar) : asset('images/user.png') }}
                        alt="User Post Image" />
                    <div class="userInfo">
                        <h3>
                            {{ $post->user->name }}
                        </h3>
                        <span>{{ floor((strtotime(date('Y-m-d H:i:s')) - strtotime($post->updated_at)) / 60 / 60 / 24) < 1
                            ? (floor((strtotime(date('Y-m-d H:i:s')) - strtotime($post->updated_at)) / 60 / 60) < 1
                                ? (floor((strtotime(date('Y-m-d H:i:s')) - strtotime($post->updated_at)) / 60) < 1
                                    ? strtotime(date('Y-m-d H:i:s')) - strtotime($post->updated_at) . ' giây trước'
                                    : floor((strtotime(date('Y-m-d H:i:s')) - strtotime($post->updated_at)) / 60) . ' phút trước')
                                : floor((strtotime(date('Y-m-d H:i:s')) - strtotime($post->updated_at)) / 60 / 60) . ' giờ trước')
                            : floor((strtotime(date('Y-m-d H:i:s')) - strtotime($post->updated_at)) / 60 / 60 / 24) . ' ngày trước' }}</span>
                    </div>
                </div>
                @if ($post->user->id === Auth::user()->id)
                    <div>
                        <x-common.button type='button'
                            onclick="window.location='{{ route('users.get_profile_view', ['userId' => Auth::user()->id]) }}'">
                            Xem profile
                        </x-common.button>
                    </div>
                @else
                    @if ($post->user->followers->where('from_user_id', '=', Auth::user()->id)->where('sending_status', '=', 'pending')->first())
                        <form
                            action="{{ route('users.cancel_sending_following', ['fromUserId' => Auth::user()->id, 'toUserId' => $post->user->id]) }}"
                            method="POST">
                            @csrf
                            <x-common.button type='submit'>
                                Hủy yêu cầu
                            </x-common.button>
                        </form>
                    @elseif($post->user->followers->where('from_user_id', '=', Auth::user()->id)->where('sending_status', '=', 'accepted')->first())
                        <form
                            action="{{ route('users.following', ['fromUserId' => Auth::user()->id, 'toUserId' => $post->user->id]) }}"
                            method="POST">
                            @csrf
                            <x-common.button type='submit'>
                                Đang theo dõi
                            </x-common.button>
                        </form>
                    @else
                        <form
                            action="{{ route('users.following', ['fromUserId' => Auth::user()->id, 'toUserId' => $post->user->id]) }}"
                            method="POST">
                            @csrf
                            <x-common.button type='submit'>
                                Theo dõi
                            </x-common.button>
                        </form>
                    @endif
                @endif
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
                    <i class="fa-regular fa-heart icon"></i>
                    <span>{{ $post->likes->count() }}</span>
                </div>
                <div class="action" id="commentAction">
                    <i class="fa-regular fa-comment icon"></i>
                    <span>{{ $post->comments->count() }}</span>
                </div>
                <div class="action" id="shareAction">
                    <i class="fa-regular fa-share-from-square icon"></i>
                    <span>{{ $post->shares->count() }}</span>
                </div>
                <div class="action" id="favoriteAction">
                    <i class="fa-regular fa-bookmark icon"></i>
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
            <input type="text" id="content" name="content" class="content" placeholder="Viết bình luận..."
                autocomplete="off" />
            <div class="commentAction">
                <div class="action">
                    <div class="iconAction">
                        <label for="image-upload">
                            <i class="fa-regular fa-image icon"></i>
                        </label>
                        <input type="file" id="image-upload" name="image-upload" accept="image/*">
                    </div>
                </div>

                <div class="submit">
                    <x-common.button type="submit">
                        Trả lời
                    </x-common.button>
                </div>
            </div>
        </div>
    </form>
</x-layouts.main-layout>
