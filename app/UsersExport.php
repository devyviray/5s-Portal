<?php

namespace App;

use App\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class UsersExport implements FromView
{
    protected $users;

    public function __construct($users) {
        $this->users = $users;
    }

    public function view(): View
    {
        return view('user.export', ['users' => $this->users]);
    }
}