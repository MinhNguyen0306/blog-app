<x-layouts.main-layout>
    <div class="notificationContainer">
        <div class="postHeader">
            <div class="titlePage">
                <div class="back" onclick="window.location='{{ url()->previous() }}'">
                    <i class="fa-solid fa-arrow-left" class="icon"></i>
                </div>
                <div class="headerInfo">
                    <h2>Thông báo</h2>
                </div>
            </div>
            <div class="notiSetting">
                <x-common.button type="button" onclick="startFCM()">
                    @if (Auth::user()->device_token)
                        Tắt thông báo
                    @else
                        Bật thông báo
                    @endif
                </x-common.button>
            </div>
        </div>

        <div class="notificationContent">
            @if (Auth::user()->notifications && Auth::user()->notifications->count() > 0)
                {{-- @foreach ($post->comments as $comment)
                    <x-common.comment :comment='$comment' />
                @endforeach --}}
            @else
                <span class="void">
                    Chưa có thông báo nào
                </span>
            @endif
        </div>
    </div>
</x-layouts.main-layout>

<script src="https://www.gstatic.com/firebasejs/8.3.2/firebase.js"></script>

<head>
    @vite(['resources/scss/notification-page.scss', 'resources/js/app.js'])
    <script>
        const notiSetting = document.querySelector(".notiSetting");
        const btnTurnSetting = notiSetting.childNodes[4]
        // FCM Notification
        const firebaseConfig = {
            apiKey: "AIzaSyAdM4qSbQC1ebmOJ77jTADPnNjN_2Y6kbw",
            authDomain: "blog-app-laravel.firebaseapp.com",
            projectId: "blog-app-laravel",
            storageBucket: "blog-app-laravel.appspot.com",
            messagingSenderId: "560468941993",
            appId: "1:560468941993:web:52733e57a2cbfe143a5db8",
            measurementId: "G-E4HEP83JYX"
        };

        // Initialize Firebase
        const app = firebase.initializeApp(firebaseConfig);
        const messaging = firebase.messaging(app);

        function startFCM() {
            messaging.requestPermission()
                .then(() => {
                    return messaging.getToken();
                })
                .then((response) => {
                    const resp = fetch('{{ route('store.token') }}', {
                        method: "POST",
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content'),
                            "Content-Type": "application/json",
                        },
                        body: JSON.stringify({
                            token: response
                        })
                    })

                    resp
                        .then((response) => response.json())
                        .then((response) => {
                            console.log(response);
                            if (response.success) {
                                alert('Token has stored')
                                btnTurnSetting.innerText = "Tắt thông báo"
                            } else if (response.offSuccess) {
                                alert('Token has deleted')
                                btnTurnSetting.innerText = "Bật thông báo"
                            } else if (response.offFail) {
                                alert('Token delete failed')
                            } else {
                                alert('Token not stored');
                            }
                        })
                        .catch((error) => {
                            alert(error)
                        })
                })
                .catch(function(error) {
                    alert(error)
                })
        }

        messaging.onMessage(function(payload) {
            const title = payload.notification.title;
            const options = {
                body: payload.notification.body,
                icon: payload.notification.icon,
            };
            new Notification(title, options);
        });
    </script>
</head>
