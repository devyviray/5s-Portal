<?php

namespace App;

use App\Checklist;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ChecklistExport implements FromView
{
    protected $list;

    public function __construct($list) {
        $this->list = $list;
    }

    public function view(): View
    {
        return view('checklist.export', ['list' => $this->list]);
    }
}