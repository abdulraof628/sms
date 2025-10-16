<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

use function Laravel\Prompts\form;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new admin user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $responses = form()
            ->text('What is your name?', required: true, name: 'name')
            ->text('What is your email?', required: true, name: 'email')
            ->password(
                label: 'What is your password?',
                validate: ['password' => 'min:8'],
                name: 'password'
            )
            ->confirm('Do you accept the terms?')
            ->submit();
        
        User::create([
            'name' => $responses['name'],
            'email' => $responses['email'],
            'password' => $responses['password'],
            'is_new' => true,
        ])->assignRole('developer');
    }
}
