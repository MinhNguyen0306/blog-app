<?php

namespace App\View\Components\common;

use App\Models\Follower;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FollowRequest extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public Follower $requestFollowing
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.common.follow-request');
    }
}
