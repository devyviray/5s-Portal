<?php

namespace App;

use App\CompanyCategory;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CompanyCategoryExport implements FromView
{
    protected $list, $filter;

    public function __construct($list, $filter = null) {
        $this->list = $list;
        $this->filter = $filter;
    }

    public function view(): View
    {
        return view('company-area.export', ['list' => $this->list, 'filter' => $this->filter]);
    }
}