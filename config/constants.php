<?php

return [
    'roles' => [
        'department_member' => ['id' => '5','level' => '1', 'label' => 'Department Member'],
        'process_owner' => ['id' => '4','level' => '2', 'label' => 'Area Owner'],
        'inspector' => ['id' => '3','level' => '3', 'label' => 'Inspector'],
        'administrator' => ['id' => '2','level' => '4', 'label' => 'Administrator'],
        'it' => ['id' => '1','level' => '5', 'label' => 'It'],
        'department_head' => ['id' => '6','level' => '6', 'label' => 'Department Head'],
        'bu_head' => ['id' => '7','level' => '7', 'label' => 'BU Head'],
        'group_president' => ['id' => '8','level' => '8', 'label' => 'Group President'],
    ],
    'status' => [
        'pending' => 1,
        'for_checking' => 2,
        'validated' => 3,
        'final' => 4
    ]
];