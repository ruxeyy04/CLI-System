<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class UpdateUserPasswordCommand extends Command
{
    protected $signature = 'user:update-password
                            {identifier : Username or email address of the user}
                            {--password= : New password (prompted if omitted)}';

    protected $description = 'Update a user password by matching username or email (no strength rules)';

    public function handle(): int
    {
        $identifier = trim((string) $this->argument('identifier'));

        if ($identifier === '') {
            $this->error('Identifier cannot be empty.');

            return self::FAILURE;
        }

        $user = User::query()
            ->where('email', $identifier)
            ->orWhere('username', $identifier)
            ->first();

        if (!$user) {
            $this->error("No user found with username or email: {$identifier}");

            return self::FAILURE;
        }

        $password = (string) ($this->option('password') ?? $this->secret('Enter new password'));

        if ($password === '') {
            $this->error('Password cannot be empty.');

            return self::FAILURE;
        }

        $user->password = $password;
        $user->save();

        $this->info('Password updated successfully.');
        $this->line("User: {$user->fname} {$user->lname} ({$user->username} / {$user->email})");

        return self::SUCCESS;
    }
}
