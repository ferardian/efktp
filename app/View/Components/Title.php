<?php

namespace App\View\Components;

use Closure;
use App\Models\Setting;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class Title extends Component
{
    /**
     * Create a new component instance.
     */
    public $setting;
    public function __construct()
    {
        $this->setting = new Setting();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.title', ['data' => $this->setting->first()]);
    }
}
