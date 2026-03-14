<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class MakeSuperAdmin extends Command
{
    protected $signature   = 'chunkiq:superadmin {email}';
    protected $description = 'Grant super admin access to a user by email';

    public function handle(): int
    {
        $email = $this->argument('email');
        $user  = User::where('email', $email)->first();

        if (!$user) {
            $this->error("No user found with email: {$email}");
            return self::FAILURE;
        }

        $user->update(['is_super_admin' => true]);
        $this->info("✓ {$user->name} ({$email}) is now a super admin.");

        return self::SUCCESS;
    }
}
