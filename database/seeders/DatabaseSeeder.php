<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info(' -> create default user');

        /** @var User $user */
        $user = User::factory()->create();
        $token = $user->createToken('foobar');
        $this->command->info(' -> default user token: ' . $token->plainTextToken);
    }
}
