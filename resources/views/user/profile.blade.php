<head>
    @vite(['resources/scss/profile-page.scss'])
</head>

<x-layouts.main-layout>
    <div class="profileContainer">
        <div class="profileHeader">
            <div class="back">
                <x-bx-arrow-back class="icon" />
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
                <div>
                    <x-common.button type='button' onclick="window.location='{{ route('users.get_edit_form') }}'">
                        Chỉnh sửa profile
                    </x-common.button>
                </div>
            </div>

            <div class="info">
                <h2 class="infoName">
                    {{ $user->name }}
                </h2>
                <span class="infoEmail">
                    {{ $user->email }}
                </span>
                <span class="registerDate">
                    <x-akar-schedule class="icon" /> Tham gia thang
                    {{ explode('-', $user->created_at->toDateString())[1] }} nam
                    {{ explode('-', $user->created_at->toDateString())[0] }}
                </span>
                <div class="interaction">
                    <div>
                        <span class="followNumber">{{ $user->follwings->count() }}</span>
                        <span>Đang theo dõi</span>
                    </div>
                    <div>
                        <span class="followNumber">{{ $user->followers->count() }}</span>
                        <span>Người theo dõi</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="tabBar">
            <div class="tabItem">
                <a href="#" class="tabLink">
                    <div class="tabBox {{ Route::current('users.profile') ? 'active' : '' }}">
                        <span class="tabTitle">
                            Bài đăng
                        </span>
                    </div>
                </a>
            </div>
            <div class="tabItem">
                <a href="#" class="tabLink">
                    <div class="tabBox">
                        <span class="tabTitle">
                            Phản hồi
                        </span>
                    </div>
                </a>
            </div>
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

        </div>
    </div>
</x-layouts.main-layout>
