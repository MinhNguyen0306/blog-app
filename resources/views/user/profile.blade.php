<head>
    @vite(['resources/scss/profile-page.scss', 'resources/scss/post.scss'])
</head>

<x-layouts.main-layout>
    <div class="profileContainer">
        <div class="profileHeader">
            <div class="back" onclick="window.location='{{ url()->previous() }}'">
                <i class="fa-solid fa-arrow-left icon"></i>
            </div>
            <div class="headerInfo">
                <h2>{{ $user->name }}</h2>
                <span>{{ $user->posts->count() }} bài đăng</span>
            </div>
        </div>

        <div class="profileInfo">
            <div class="infoTop">
                <img src={{ $user->avatar ? asset($user->avatar) : asset('images/user.png') }} alt=""
                    class="avatar">
                @if ($user->id === Auth::user()->id)
                    <div>
                        <x-common.button type='button' onclick="window.location='{{ route('users.get_edit_form') }}'">
                            Chỉnh sửa profile
                        </x-common.button>
                    </div>
                @else
                    @if ($user->followers->where('from_user_id', '=', Auth::user()->id)->where('sending_status', '=', 'pending')->first())
                        <form
                            action="{{ route('users.cancel_sending_following', ['fromUserId' => Auth::user()->id, 'toUserId' => $user->id]) }}"
                            method="POST">
                            @csrf
                            <x-common.button type='submit'>
                                Hủy yêu cầu
                            </x-common.button>
                        </form>
                    @elseif($user->followers->where('from_user_id', '=', Auth::user()->id)->where('sending_status', '=', 'accepted')->first())
                        <form
                            action="{{ route('users.following', ['fromUserId' => Auth::user()->id, 'toUserId' => $user->id]) }}"
                            method="POST">
                            @csrf
                            <x-common.button type='submit'>
                                Đang theo dõi
                            </x-common.button>
                        </form>
                    @else
                        <form
                            action="{{ route('users.following', ['fromUserId' => Auth::user()->id, 'toUserId' => $user->id]) }}"
                            method="POST">
                            @csrf
                            <x-common.button type='submit'>
                                Theo dõi
                            </x-common.button>
                        </form>
                    @endif
                @endif
            </div>

            <div class="info">
                <h2 class="infoName">
                    {{ $user->name }}
                </h2>
                <span class="infoEmail">
                    {{ $user->email }}
                </span>
                <div class="registerDate">
                    <i class="fa-regular fa-calendar-days icon"></i>
                    <span> Tham gia tháng {{ explode('-', $user->created_at->toDateString())[1] }} năm
                        {{ explode('-', $user->created_at->toDateString())[0] }}
                    </span>
                </div>
                <div class="interaction">
                    <div>
                        <span
                            class="followNumber">{{ $user->follwings->where('sending_status', '=', 'accepted')->count() }}</span>
                        <span>Đang theo dõi</span>
                    </div>
                    <div>
                        <span
                            class="followNumber">{{ $user->followers->where('sending_status', '=', 'accepted')->count() }}</span>
                        <span>Người theo dõi</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="tabBar">
            <div class="tabItem current">
                <a href="#" class="tabLink">
                    <div class="tabBox active">
                        <span class="tabTitle">
                            Bài đăng
                        </span>
                    </div>
                </a>
            </div>
            @if ($user->id === Auth::user()->id)
                <div class="tabItem">
                    <a href="{{ route('users.get_request_followers', $user->id) }}" class="tabLink">
                        <div class="tabBox">
                            <span class="tabTitle">
                                Yêu cầu theo dõi
                            </span>
                        </div>
                    </a>
                </div>
            @endif
            <div class="tabItem">
                <a href="#" class="tabLink">
                    <div class="tabBox">
                        <span class="tabTitle">
                            Album
                        </span>
                    </div>
                </a>
            </div>
            <div class="tabItem">
                <a href="#" class="tabLink">
                    <div class="tabBox">
                        <span class="tabTitle">
                            Bài đăng được chia sẻ
                        </span>
                    </div>
                </a>
            </div>
        </div>

        <div class="content">
            @if ($user->posts && $user->posts->count() > 0)
                @foreach ($user->posts as $post)
                    <div class="postContainer" onclick="window.location='{{ route('posts.detail', $post->id) }}'">
                        <div class="postInfoLimit">
                            <p class="postTitle">
                                {{ $post->title }}
                            </p>
                            <h5 class="lastChange">
                                {{ floor((strtotime(date('Y-m-d H:i:s')) - strtotime($post->updated_at)) / 60 / 60 / 24) < 1
                                    ? (floor((strtotime(date('Y-m-d H:i:s')) - strtotime($post->updated_at)) / 60 / 60) < 1
                                        ? (floor((strtotime(date('Y-m-d H:i:s')) - strtotime($post->updated_at)) / 60) < 1
                                            ? strtotime(date('Y-m-d H:i:s')) - strtotime($post->updated_at) . ' giây trước'
                                            : floor((strtotime(date('Y-m-d H:i:s')) - strtotime($post->updated_at)) / 60) . ' phút trước')
                                        : floor((strtotime(date('Y-m-d H:i:s')) - strtotime($post->updated_at)) / 60 / 60) . ' giờ trước')
                                    : floor((strtotime(date('Y-m-d H:i:s')) - strtotime($post->updated_at)) / 60 / 60 / 24) . ' ngày trước' }}
                            </h5>
                            <p class="desc">
                                {{ $post->content }}
                            </p>
                        </div>
                    </div>
                @endforeach
            @else
                <span>Không có yêu cầu nào</span>
            @endif
        </div>
    </div>
</x-layouts.main-layout>
