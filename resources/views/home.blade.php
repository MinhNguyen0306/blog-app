<head>
    @vite(['resources/scss/home-page.scss', 'resources/scss/select.scss'])
</head>

<x-layouts.main-layout>
    <div class="homeContainer">
        <div class="homeHeader">
            <div class="tabItem">
                <a href="{{ route('home') }}" class="tabLink">
                    <div class="tabBox {{ Route::current('home') ? 'active' : '' }}">
                        <span class="tabTitle">
                            Dành cho bạn
                        </span>
                    </div>
                </a>
            </div>

            <div class="tabItem">
                <a href="#" class="tabLink">
                    <div class="tabBox">
                        <span class="tabTitle">
                            Đang theo dõi
                        </span>
                    </div>
                </a>
            </div>
        </div>

        <form action="{{ route('posts.store') }}" class="postForm" method="POST" enctype="multipart/form-data">
            @csrf

            <input type="text" name="user_id" id="user_id" value={{ Auth::user()->id }} hidden readonly />

            <img src={{ Auth::user()->avatar ? asset('images/' . Auth::user()->avatar) : asset('images/Logo.svg') }}
                alt="User Image" />

            <div class="postFormContent">
                <input type="text" id="title" name="title" placeholder="Tiêu đề bài đăng" />
                @error('title')
                    <span class="errorMessage">
                        {{ $message }}
                    </span>
                @enderror

                <input type="text" id="content" name="content" placeholder="Nội dung bài đăng" autocomplete="off">
                @error('content')
                    <span class="errorMessage">
                        {{ $message }}
                    </span>
                @enderror

                <div class="attachAction">
                    <div class="action">
                        <div class="iconAction">
                            <label for="image-upload">
                                <x-bi-image-fill class="icon" />
                            </label>
                            <input type="file" id="image-upload" name="image-upload" accept="image/*">
                        </div>
                        <div class="iconAction">
                            <x-akar-schedule class="icon" />
                        </div>

                        <select name="category_id" id="category_id" class="selectContainer">
                            <option value="" selected disabled hidden>Chọn chủ đề</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" class="selectItem">
                                    {{ $category->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <span class="errorMessage">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="submit">
                        <x-common.button type="submit">
                            Đăng bài
                        </x-common.button>
                    </div>
                </div>
            </div>
        </form>

        <div class="homeContent">
            @foreach ($posts as $post)
                <x-common.post :post="$post" />
            @endforeach
        </div>
    </div>
</x-layouts.main-layout>
