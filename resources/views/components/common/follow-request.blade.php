@vite('resources/scss/follow-request.scss')

<div class="box">
    <div class="userInfo">
        <img src={{ $requestFollowing->fromUser->avatar ? asset('images/' . $requestFollowing->fromUser->avatar) : asset('images/user.png') }}
            alt="User Image"
            onclick="window.location='{{ route('users.get_profile_view', $requestFollowing->fromUser->id) }}'" />
        <div class="info">
            <h3 onclick="window.location='{{ route('users.get_profile_view', $requestFollowing->fromUser->id) }}'">
                {{ $requestFollowing->fromUser->name }}
            </h3>
            <span>
                {{ floor((strtotime(date('Y-m-d H:i:s')) - strtotime($requestFollowing->updated_at)) / 60 / 60 / 24) < 1
                    ? (floor((strtotime(date('Y-m-d H:i:s')) - strtotime($requestFollowing->updated_at)) / 60 / 60) < 1
                        ? (floor((strtotime(date('Y-m-d H:i:s')) - strtotime($requestFollowing->updated_at)) / 60) < 1
                            ? strtotime(date('Y-m-d H:i:s')) - strtotime($requestFollowing->updated_at) . ' giây trước'
                            : floor((strtotime(date('Y-m-d H:i:s')) - strtotime($requestFollowing->updated_at)) / 60) . ' phút trước')
                        : floor((strtotime(date('Y-m-d H:i:s')) - strtotime($requestFollowing->updated_at)) / 60 / 60) . ' giờ trước')
                    : floor((strtotime(date('Y-m-d H:i:s')) - strtotime($requestFollowing->updated_at)) / 60 / 60 / 24) . ' ngày trước' }}
            </span>
        </div>
    </div>

    <div class="handleAction">
        <form
            action="{{ route('users.accept_following', ['fromUserId' => $requestFollowing->from_user_id, 'toUserId' => $requestFollowing->to_user_id]) }}"
            method="POST">
            @csrf
            <x-common.button type='submit'>
                Chấp nhận
            </x-common.button>
        </form>

        <form
            action="{{ route('users.reject_following', ['fromUserId' => $requestFollowing->from_user_id, 'toUserId' => $requestFollowing->to_user_id]) }}"
            method="POST">
            @csrf
            <x-common.button type='submit' style="background-color:crimson; color: white">
                Từ chối
            </x-common.button>
        </form>
    </div>
</div>
