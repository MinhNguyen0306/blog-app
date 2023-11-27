<head>
    @vite(['resources/scss/app.scss', 'resources/scss/payment-choose.scss'])
</head>

<div class="paymentChooseContainer">
    <div style="display: flex; flex-direction: column; row-gap: 2rem;">
        @if (request()->get('vnp_ResponseCode') == 24 ||
                request()->get('vnp_ResponseCode') == 10 ||
                request()->get('vnp_ResponseCode') == 11)
            <span style="font-size: xx-large">Giao dich khong thanh cong</span>
        @else
            <span style="font-size: xx-large">Giao dich thanh cong</span>
        @endif

        <div>
            <x-common.button type='button' onclick="window.location='{{ route('home') }}'">
                Ve trang chu
            </x-common.button>
        </div>
    </div>
</div>
