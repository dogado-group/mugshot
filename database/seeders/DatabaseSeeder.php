<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info(' -> create default user');

        /** @var User $user */
        $user = \App\Models\User::factory()->create();
        $token = $user->createToken('foobar');
        $this->command->info(' -> default user token: ' . $token->plainTextToken);
    }
}
