<?php

namespace Database\Factories;

use App\Models\Meme;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;


class MemeFactory extends Factory
{
    public function definition(): array
    {
        $status = fake()->randomElement([
            Meme::STATUS_PUBLISHED,
            Meme::STATUS_PUBLISHED,
            Meme::STATUS_PUBLISHED,
            Meme::STATUS_PENDING,
            Meme::STATUS_REJECTED,
        ]);

        return [
            'user_id' => User::query()->inRandomOrder()->value('id') ?? User::factory(),
            'title' => mb_convert_case(fake()->words(4, true), MB_CASE_TITLE),
            'category' => fake()->word(),
            'image' => 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQNWZT5txgxyMLNrVynYhw0KwCepmirDwiu9g&s
            '.fake()->numberBetween(1, 200).'/800/600',
            'media_type' => Meme::MEDIA_IMAGE,
            'status' => $status,
            'rejection_reason' => $status === Meme::STATUS_REJECTED ? fake()->sentence() : null,
            'published_at' => $status === Meme::STATUS_PUBLISHED ? fake()->dateTimeBetween('-30 days', 'now') : null,
        ];
    }
}