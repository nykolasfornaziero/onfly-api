<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $users = [
            ['name' => 'adminUser', 'email' => 'admin@onfly.com', 'password' => Hash::make('password')],
            ['name' => 'SimpleUser', 'email' => 'user@onfly.com', 'password' => Hash::make('password')],
        ];

        Permission::create(['name' => 'admin-travels']);
        Permission::create(['name' => 'create-travels']);

        $admin = Role::create(['name' => 'admin']);
        $colaborator = Role::create(['name' => 'colaborator']);

        $admin->givePermissionTo(['admin-travels', 'create-travels']);
        $colaborator->givePermissionTo('create-travels');

        foreach ($users as $user) {
            $user = User::create($user);
            if ($user->email == 'admin@onfly.com') {
                $user->assignRole('admin');
            } else {
                $user->assignRole('colaborator');
            }

        }
    }
}
