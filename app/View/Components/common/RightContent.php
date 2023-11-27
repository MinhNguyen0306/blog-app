<?php

namespace App\View\Components\common;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class RightContent extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public Collection $users
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.common.right-content');
    }
}
