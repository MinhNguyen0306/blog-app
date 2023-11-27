<head>
    @vite(['resources/scss/app.scss', 'resources/scss/payment-choose.scss'])
</head>

<div class="paymentChooseContainer">
    <div class="package">
        <div class="item">
            <h2 class="title">
                Gói Silver / 70000vnd
            </h2>
            <ul class="features">
                <li class="feature">
                    Xem bất kì post nào
                </li>
                <li class="feature">
                    Đặc quyền member
                </li>
            </ul>
            <form action="{{ route('payment.vnpay_payment') }}" method="POST" style="width: 100%">
                @csrf
                <input type="hidden" value="silver" name="payment_package">
                <x-common.button type="submit" name="redirect">
                    Chọn
                </x-common.button>
            </form>
        </div>

        <div class="item">
            <h2 class="title">
                Gói Gold / 100000vnd
            </h2>
            <ul class="features">
                <li class="feature">
                    Đặc quyền của gói Silver
                </li>
                <li class="feature">
                    Không quảng cáo
                </li>
            </ul>
            <form action="{{ route('payment.vnpay_payment') }}" method="POST" style="width: 100%">
                @csrf
                <input type="hidden" value="gold" name="payment_package">
                <x-common.button type="submit" name="redirect">
                    Chọn
                </x-common.button>
            </form>
        </div>
    </div>
</div>
