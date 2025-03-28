<?php

namespace App;

use App\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class UsersExport implements FromView
{
    protected $users, $filter;

    public function __construct($users, $filter) {
        $this->users = $users;
        $this->filter = $filter;
    }

    public function view(): View
    {
        return view('user.export', ['users' => $this->users, 'filter' => $this->filter]);
    }
}