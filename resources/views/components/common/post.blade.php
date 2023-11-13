<head>
    @vite('resources/scss/post.scss')
</head>

<div class="postContainer">
    <img src={{ asset('images/Logo.svg') }} alt="User Post Image" />
    <div class="postInfo">
        <div class="postHeader">
            <h3>Minh nguyen</h3>
            <span>Minhnguyen@123</span>
            <i></i>
            <span>2 Gio</span>
        </div>
        <div class="postContent">
            <p>
                I'm not convinced that using custom ChatGPT UI is either cheaper or faster than subscribing to ChatGPT
                Plus.
                Prove me wrong!
            </p>
        </div>
        <div class="postAction">
            <div class="action" id="likeAction">
                <x-bx-like class="icon" />
                <span>17</span>
            </div>
            <div class="action" id="commentAction">
                <x-far-comment class="icon" />
                <span>17</span>
            </div>
            <div class="action" id="shareAction">
                <x-heroicon-o-share class="icon" />
                <span>17</span>
            </div>
            <div class="action" id="favoriteAction">
                <x-heroicon-o-share class="icon" />
                <span>17</span>
            </div>
        </div>
    </div>
</div>
