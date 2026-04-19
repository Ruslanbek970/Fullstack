<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Meme;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->call(RolePermissionSeeder::class);

        User::where('email', 'test@example.com')->first()?->assignRole('member');

        $this->call(DemoRoleUsersSeeder::class);

        $users = User::query()->get();
        Meme::factory(100)->recycle($users)->create();

        foreach (Meme::query()->where('status', Meme::STATUS_PUBLISHED)->inRandomOrder()->limit(20)->get() as $meme) {
            foreach (range(1, random_int(0, 3)) as $_) {
                Comment::query()->create([
                    'meme_id' => $meme->id,
                    'user_id' => $users->random()->id,
                    'body' => fake()->sentences(random_int(1, 2), true),
                    'is_hidden' => false,
                ]);
            }
        }
    }
}
