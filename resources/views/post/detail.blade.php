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

                                        ${comment.image && `<img src='http://127.0.0.1:8000/images/${comment.image} alt="" />`}
                                    </div>

                                    <div class="commentAction">
                                        <span>${comment.created_at}</span>
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
            <div class="back">
                <x-bx-arrow-back class="icon" />
            </div>
            <div class="headerInfo">
                <h2>Bai dang</h2>
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
                        <span>{{ $post->created_at }}</span>
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
                            action="{{ route('users.cancel_following', ['fromUserId' => Auth::user()->id, 'toUserId' => $post->user->id]) }}"
                            method="POST">
                            @csrf
                            <x-common.button type='submit'>
                                Hủy yêu cầu
                            </x-common.button>
                        </form>
                    @elseif($post->user->followers->where('from_user_id', '=', Auth::user()->id)->where('sending_status', '=', 'accepted')->first())
                        <form
                            action="{{ route('users.cancel_following', ['fromUserId' => Auth::user()->id, 'toUserId' => $post->user->id]) }}"
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
