<head>
    @vite(['resources/scss/home-page.scss'])
</head>

<x-layouts.main-layout>
    <div class="homeContainer">
        <div class="homeHeader">
            <div class="left">
                <span>Tất cả</span>
            </div>
            <div class="right">
                Đang theo dõi
            </div>
        </div>

        <form action="" class="postForm" method="POST">
            <img src={{ asset('images/Logo.svg') }} alt="User Image" />
            <div class="postFormContent">
                <input type="text" placeholder="Viet gi do di nao" />
                <div class="attachAction">
                    <div class="iconAction">
                        <x-heroicon-o-home class="icon" />
                    </div>
                    <div class="iconAction">
                        <x-heroicon-o-home class="icon" />
                    </div>
                    <div class="iconAction">
                        <x-heroicon-o-home class="icon" />
                    </div>
                    <div class="submit">
                        <span>0/100</span>
                        <x-common.button>
                            Dang
                        </x-common.button>
                    </div>
                </div>
            </div>
        </form>

        <div class="homeContent">
            <x-common.post />
            <x-common.post />
            <x-common.post />
            <x-common.post />
            <x-common.post />
            <x-common.post />
        </div>
    </div>
</x-layouts.main-layout>
