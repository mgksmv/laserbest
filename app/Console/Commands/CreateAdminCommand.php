<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class CreateAdminCommand extends Command
{
    protected $signature = 'app:create-admin {email} {name} {password}';

    protected $description = 'Creates an admin user.';

    public function handle(): void
    {
        $email = $this->argument('email');
        $firstName = $this->argument('name');
        $password = $this->argument('password');

        $emailValidator = Validator::make(compact('email'), [
            'email' => 'email',
        ]);

        if ($emailValidator->fails()) {
            $this->error('Provided Email is not a correct Email address.');
            return;
        }

        User::query()->create([
            'email' => $email,
            'name' => $firstName,
            'password' => $password,
        ]);

        $this->info('Admin is created successfully.');
    }
}
