<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExcelFromViewExport implements FromView
{
    protected $export;
    protected $data;
    protected $template;

    public function __construct($template, $data)
    {
        $this->data = $data;
        $this->template = $template;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        return view($this->template, $this->data);
    }
}
