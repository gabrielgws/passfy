<?php

declare(strict_types=1);

namespace App\View\Components;

use Illuminate\View\Component;

class PublicLayout extends Component
{
    public function __construct(
        public ?string $title = null
    ) {}

    public function render()
    {
        return view('layouts.public');
    }
}
