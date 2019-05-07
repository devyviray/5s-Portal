<?php

namespace Database\Seeds;

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
         * Role Types
         *
         */
        $RoleItems = [
            [
                'name'        => 'IT',
                'slug'        => 'it',
                'description' => 'It Role',
                'level'       => 4,
            ],
            [
                'name'        => 'Administrator',
                'slug'        => 'administrator',
                'description' => 'Administrator Role',
                'level'       => 3,
            ],
            [
                'name'        => 'Inspector',
                'slug'        => 'inspector',
                'description' => 'Inspector Role',
                'level'       => 2,
            ],
            [
                'name'        => 'Area owner',
                'slug'        => 'area owner',
                'description' => 'Area owner Role',
                'level'       => 1,
            ],
        ];

        /*
         * Add Role Items
         *
         */
        foreach ($RoleItems as $RoleItem) {
            $newRoleItem = config('roles.models.role')::where('slug', '=', $RoleItem['slug'])->first();
            if ($newRoleItem === null) {
                $newRoleItem = config('roles.models.role')::create([
                    'name'          => $RoleItem['name'],
                    'slug'          => $RoleItem['slug'],
                    'description'   => $RoleItem['description'],
                    'level'         => $RoleItem['level'],
                ]);
            }
        }
    }
}
