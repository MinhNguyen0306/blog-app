<head>
    @vite('resources/scss/post.scss')

    <script>
        // function getProfileView(event) {
        //     event.stopPropagation();
        //     const userId = {!! $share->user->id !!}
        //     window.location = `http://127.0.0.1:8000/users/account/profile/${userId}`
        // }

        // function sharePost(event) {
        //     event.stopPropagation();
        //     return confirm('Share bài post này?')
        // }

        // function likePost(event) {
        //     event.stopPropagation();
        //     const likeNumber = document.querySelector('#likeNumber');
        //     console.log(likeNumber.innerHTML);
        // }
    </script>
</head>

<div class="postContainer" onclick="window.location='{{ route('posts.detail', $share->post->id) }}'">
    <img class="userImage"
        src={{ $share->post->user->avatar ? asset('images/' . $post->user->avatar) : asset('images/user.png') }}
        alt="User Post Image" onclick="getProfileView(event)" />

    <div class="postInfo">
        <div class="postHeader">
            <h3 onclick="getProfileView(event)">
                {{ $share->post->user->name }}
            </h3>
            <span>{{ $share->post->user->email }}</span>
            <i></i>
            <span>
                {{ floor((strtotime(date('Y-m-d H:i:s')) - strtotime($share->updated_at)) / 60 / 60 / 24) < 1
                    ? (floor((strtotime(date('Y-m-d H:i:s')) - strtotime($share->updated_at)) / 60 / 60) < 1
                        ? (floor((strtotime(date('Y-m-d H:i:s')) - strtotime($share->updated_at)) / 60) < 1
                            ? strtotime(date('Y-m-d H:i:s')) - strtotime($share->updated_at) . ' giây trước'
                            : floor((strtotime(date('Y-m-d H:i:s')) - strtotime($share->updated_at)) / 60) . ' phút trước')
                        : floor((strtotime(date('Y-m-d H:i:s')) - strtotime($share->updated_at)) / 60 / 60) . ' giờ trước')
                    : floor((strtotime(date('Y-m-d H:i:s')) - strtotime($share->updated_at)) / 60 / 60 / 24) . ' ngày trước' }}</span>
        </div>

        <div style="border: 1px solid gray; border-radius: 25px">
            <x-common.post :post="$share->post" />
        </div>

    </div>
</div>
