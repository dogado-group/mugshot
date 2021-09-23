<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CreateUser extends Command
{
    protected $signature = 'mugshot:createuser {--name=} {--email=}';

    protected $description = 'Create a user with API Key';

    public function handle()
    {
        $name = $this->option('name');
        $email = $this->option('email');

        if (empty($name)) {
            $name = $this->ask('[Mugshot] Please enter your name');
        }
        if (empty($email)) {
            $email = $this->ask('[Mugshot] Please enter your email address');
        }

        if (!$this->confirm("[Mugshot] Do you want to create $name", false)) {
            return 0;
        }

        $this->info("[Mugshot] Creating account for ${name}");

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Str::random(32),
            'email_verified_at' => now()
        ]);
        $token = $user->createToken('API Access');

        $this->info('[Mugshot] You can now use this account:');
        $this->line('Name:  ' . $user->name);
        $this->line('Email: ' . $user->email);
        $this->line('Token: ' . $token->plainTextToken);

        return 0;
    }
}
