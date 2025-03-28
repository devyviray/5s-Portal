<?php

namespace App;

use App\CompanyCategory;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CompanyCategoryExport implements FromView
{
    protected $list;

    public function __construct($list) {
        $this->list = $list;
    }

    public function view(): View
    {
        return view('company-area.export', ['list' => $this->list]);
    }
}