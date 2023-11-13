<head>
    @vite(['resources/scss/right-content.scss'])
</head>

<div class="rightContainer">
    <div class="rightHeader">
        <div class="searchBox">
            <input type="text" placeholder="Tim kiem" />
            <x-zondicon-search class="icon" />
        </div>
    </div>

    <div class="rightContent">
        <div class="registerPremium">
            <h2>Dang ky goi Premiun</h2>
            <p>Đăng ký để mở khóa các tính năng mới và nếu đủ điều kiện, bạn sẽ được nhận một khoản chia sẻ doanh thu từ
                quảng cáo.</p>
            <x-common.button>
                Dang ky
            </x-common.button>
        </div>

        <div class="trend">

        </div>

        <div class="hintFollow">
            <h2>Goi y theo doi</h2>
            <ul class="listHint">
                <li class="userContainer">
                    <div class="userInfo">
                        <img src={{ asset('images/Logo.svg') }} alt="user image" />
                        <div class="userName">
                            <h3>Minh nguyen</h3>
                            <span>Minhnguyen@gmail.com</span>
                        </div>
                    </div>
                    <x-common.button>
                        Follow
                    </x-common.button>
                </li>
            </ul>
        </div>
    </div>

</div>