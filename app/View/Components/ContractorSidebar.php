<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ContractorSidebar extends Component
{
    public $title;
    public $backUrl;

    /**
     * Create a new component instance.
     */
    public function __construct($title = null, $backUrl = null)
    {
        $this->title = $title;
        $this->backUrl = $backUrl;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('layouts.contractor-sidebar');
    }
}
