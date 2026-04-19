<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'meme.view',
            'meme.create',
            'meme.edit',
            'meme.delete',
            'meme.publish',
            'meme.reject',

            'comment.create',
            'comment.moderate',
            'comment.delete',

            'like.toggle',

            'user.view',
            'user.ban',

            'analytics.view',

            'role.manage',
            'permission.manage',
        ];

        foreach ($permissions as $name) {
            Permission::firstOrCreate(['name' => $name]);
        }

        $superAdmin = Role::firstOrCreate(['name' => 'super-admin']);
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $moderator = Role::firstOrCreate(['name' => 'moderator']);
        $member = Role::firstOrCreate(['name' => 'member']);

        $superAdmin->syncPermissions(Permission::all());

        $admin->syncPermissions([
            'meme.view',
            'meme.create',
            'meme.edit',
            'meme.delete',
            'meme.publish',
            'meme.reject',
            'comment.create',
            'comment.moderate',
            'comment.delete',
            'like.toggle',
            'user.view',
            'user.ban',
            'analytics.view',
        ]);

        $moderator->syncPermissions([
            'meme.view',
            'meme.publish',
            'meme.reject',
            'comment.create',
            'comment.moderate',
            'comment.delete',
            'like.toggle',
            'user.view',
        ]);

        $member->syncPermissions([
            'meme.view',
            'meme.create',
            'meme.edit',
            'meme.delete',
            'comment.create',
            'like.toggle',
        ]);

        $guard = config('auth.defaults.guard', 'web');
        $legacyUserRole = Role::where('name', 'user')->where('guard_name', $guard)->first();
        if ($legacyUserRole) {
            foreach (User::role('user')->get() as $account) {
                $account->assignRole($member);
                $account->removeRole('user');
            }
            $legacyUserRole->delete();
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        }

    }
}
