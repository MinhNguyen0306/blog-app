<?php

namespace App\View\Components\common;

use App\Models\Comment as ModelsComment;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Comment extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public ModelsComment $comment
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.common.comment');
    }
}
