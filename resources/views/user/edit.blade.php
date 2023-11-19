<head>
    @vite(['resources/scss/edit-profile-page.scss'])
</head>

<x-layouts.main-layout>
    <div class="editContainer">
        <div class="editHeader">
            <div class="back" onclick="window.location='{{ route('users.get_profile_view') }}'">
                <x-bx-arrow-back class="icon" />
            </div>
            <div class="headerInfo">
                <h2>{{ Auth::user()->name }}</h2>
                <span>{{ Auth::user()->posts->count() }} bài đăng</span>
            </div>
        </div>


        <form class="editContent" action="{{ route('users.update', Auth::user()->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <div class="imageField">
                <label for="avatar-upload">
                    <img src={{ Auth::user()->avatar ? asset('images/' . Auth::user()->avatar) : asset('images/user.png') }}
                        alt="">
                    <div class="addImage">
                        <x-uni-camera-plus-o class="icon" />
                    </div>
                </label>
                <input type="file" id="avatar-upload" accept="image/*" name="avatar-upload" class="avatar-upload" />
            </div>

            <div class="editField">
                <label for="name">Ten</label>
                <input type="text" name="name" id="name" value={{ Auth::user()->name }} />
            </div>

            <div class="editField">
                <label for="bio">Tiểu sử</label>
                <textarea name="bio" id="bio">
                    {{ Auth::user()->bio ? Auth::user()->bio : '' }}
                </textarea>
            </div>

            <div class="editField">
                <label for="link">Liên kết</label>
                <input type="text" name="link" id="link"
                    value={{ Auth::user()->link ? Auth::user()->link : '' }} />
            </div>

            <div>
                <x-common.button type="submit">
                    Lưu thay đổi
                </x-common.button>
            </div>
        </form>
    </div>

    <script type="text/javascript">
        const avatar = document.querySelector('#avatar-upload');
        const handlePreUpload = (e) => {
            const file = e.target.files[0];
            console.log(file);
        }

        avatar.addEventListener('change', handlePreUpload);
    </script>
</x-layouts.main-layout>
