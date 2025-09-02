<?php

namespace App\View\Components;

use Illuminate\View\Component;

class MultipleImagesUpload extends Component
{
    public $name;
    public $label;
    public $value;

    public function __construct($name, $label, $value = null)
    {
        $this->name = $name;
        $this->label = $label;
        $this->value = $value;
    }

    public function render()
    {
        return view('components.multiple-images-upload');
    }
}
