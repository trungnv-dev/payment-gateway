<?php

namespace App\Console\Commands;

use App\Enums\UserRole;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;

class AuthenticationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'authentication';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Authentication when run command!';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $email = $this->ask("Authentication [Email]");
        $password = $this->secret("Authentication [Password]");

        if (Auth::attempt(['email' => $email, 'password' => $password]) && Auth::user()->role == UserRole::ADMIN) {
            $this->alert("Hi, ". Auth::user()->name);

            return true;
        } else {
            $this->error('Unauthentication!');

            return false;
        }
    }
}
