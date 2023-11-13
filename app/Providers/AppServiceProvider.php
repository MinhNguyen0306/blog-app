<?php

namespace App\Providers;

use App\View\Components\common\Button;
use App\View\Components\common\LeftContent;
use App\View\Components\common\Logo;
use App\View\Components\common\MidContent;
use App\View\Components\common\Post;
use App\View\Components\common\RightContent;
use App\View\Components\Forms\Input;
use App\View\Components\Forms\Select;
use App\View\Components\layouts\MainLayout;
use App\View\Components\layouts\NotHeaderLayout;
use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        Blade::component('main-layout', MainLayout::class);
        Blade::component('not-header-layout', NotHeaderLayout::class);
        Blade::component('button', Button::class);
        Blade::component('forms.input', Input::class);
        Blade::component('forms.select', Select::class);
        Blade::component('logo', Logo::class);
        Blade::component('left-content', LeftContent::class);
        Blade::component('mid-content', MidContent::class);
        Blade::component('right-content', RightContent::class);
        Blade::component('post', Post::class);
    }
}
