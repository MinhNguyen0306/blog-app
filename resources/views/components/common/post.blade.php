<head>
    @vite('resources/scss/post.scss')

    <script>
        function getProfileView(event) {
            event.stopPropagation();
            const userId = {!! $post->user->id !!}
            window.location = `http://127.0.0.1:8000/users/account/profile/${userId}`
        }

        function sharePost(event) {
            event.stopPropagation();
            return confirm('Share bài post này?')
        }

        function likePost(event) {
            event.stopPropagation();
            const likeNumber = document.querySelector('#likeNumber');
            console.log(likeNumber.innerHTML);
        }
    </script>
</head>

<div class="postContainer" onclick="window.location='{{ route('posts.detail', $post->id) }}'">
    <img class="userImage"
        src={{ $post->user->avatar ? asset('images/' . $post->user->avatar) : asset('images/user.png') }}
        alt="User Post Image" onclick="getProfileView(event)" />

    <div class="postInfo">
        <div class="postHeader">
            <h3 onclick="getProfileView(event)">
                {{ $post->user->name }}
            </h3>
            <span>{{ $post->user->email }}</span>
            <i></i>
            <span>
                {{ floor((strtotime(date('Y-m-d H:i:s')) - strtotime($post->updated_at)) / 60 / 60 / 24) < 1
                    ? (floor((strtotime(date('Y-m-d H:i:s')) - strtotime($post->updated_at)) / 60 / 60) < 1
                        ? (floor((strtotime(date('Y-m-d H:i:s')) - strtotime($post->updated_at)) / 60) < 1
                            ? strtotime(date('Y-m-d H:i:s')) - strtotime($post->updated_at) . ' giây trước'
                            : floor((strtotime(date('Y-m-d H:i:s')) - strtotime($post->updated_at)) / 60) . ' phút trước')
                        : floor((strtotime(date('Y-m-d H:i:s')) - strtotime($post->updated_at)) / 60 / 60) . ' giờ trước')
                    : floor((strtotime(date('Y-m-d H:i:s')) - strtotime($post->updated_at)) / 60 / 60 / 24) . ' ngày trước' }}</span>
        </div>
        <div class="postContent">
            <p class="title">
                {{ $post->title }}
            </p>
            <p class="content">
                {{ $post->content }}
            </p>

            @if ($post->image)
                <img src="{{ asset('images/' . $post->image) }}" alt="" class="postImage">
            @endif
        </div>

        <div class="postAction">
            <form class="action {{ $post->likes->where('user_id', Auth::user()->id) ? 'hasLike' : '' }}" id="likeAction"
                onclick="likePost(event)"
                action="{{ route('posts.like_post', ['userId' => Auth::user()->id, 'postId' => $post->id]) }}"
                method="POST">
                @csrf
                <label for="likeNumber"><x-bx-like class="icon" /></label>
                <button type="submit" id="likeNumber"
                    class="btnAction">{{ $post->likes->where('has_like', true)->count() }}</button>
            </form>
            <div class="action" id="commentAction">
                <x-far-comment class="icon" />
                <span>{{ $post->comments->count() }}</span>
            </div>

            <form class="action" id="shareAction"
                action="{{ route('posts.share', ['postId' => $post->id, 'userId' => $post->user->id]) }}"
                method="POST" onclick="sharePost(event)">
                @csrf
                <label for="btnShare"><x-heroicon-o-share class="icon" /></label>
                <button type="submit" id="btnShare" class="btnAction">{{ $post->shares->count() }}</button>
            </form>
            <div class="action" id="favoriteAction">
                <x-monoicon-favorite class="icon" />
            </div>
        </div>
    </div>
</div>
