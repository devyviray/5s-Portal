<?php

namespace App;

use App\Faq;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class FaqsExport implements FromView
{
    protected $list;

    public function __construct($list) {
        $this->list = $list;
    }

    public function view(): View
    {
        return view('faq.export', ['list' => $this->list]);
    }
}