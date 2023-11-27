<?php

namespace App\View\Components\common;

use App\Models\Share as ModelsShare;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Share extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public ModelsShare $share
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.share');
    }
}
