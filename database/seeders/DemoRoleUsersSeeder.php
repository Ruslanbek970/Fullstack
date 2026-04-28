<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoRoleUsersSeeder extends Seeder
{
    
    public function run(): void
    {
        $accounts = [
            ['name' => 'Super Admin Demo', 'email' => 'super@demo.local', 'role' => 'super-admin'],
            ['name' => 'Admin Demo', 'email' => 'admin@demo.local', 'role' => 'admin'],
            ['name' => 'Moderator Demo', 'email' => 'mod@demo.local', 'role' => 'moderator'],
            ['name' => 'Member Demo', 'email' => 'member@demo.local', 'role' => 'member'],
        ];

        foreach ($accounts as $row) {
            $user = User::updateOrCreate(
                ['email' => $row['email']],
                [
                    'name' => $row['name'],
                    'password' => Hash::make('password'),
                ]
            );
            $user->syncRoles([$row['role']]);
        }
    }
}
