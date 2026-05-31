<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class ResetAdminPassword extends Command
{
    protected $signature = 'admin:reset-password';
    protected $description = 'Reset password akun admin Syamama Kitchen';

    public function handle(): int
    {
        $this->info('=== Reset Password Admin Syamama Kitchen ===');
        $this->newLine();

        // Show existing admins
        $admins = User::where('role', 'admin')->get(['id', 'name', 'email']);

        if ($admins->isEmpty()) {
            $this->error('Belum ada akun admin! Buat dulu dengan: php artisan admin:create');
            return Command::FAILURE;
        }

        $this->info('Daftar admin yang terdaftar:');
        $this->table(
            ['ID', 'Nama', 'Email'],
            $admins->toArray()
        );
        $this->newLine();

        $email = $this->ask('Masukkan email admin yang ingin direset');

        $user = User::where('email', $email)->where('role', 'admin')->first();

        if (!$user) {
            $this->error('Admin dengan email tersebut tidak ditemukan!');
            return Command::FAILURE;
        }

        $password = $this->secret('Masukkan password baru (minimal 6 karakter)');
        if (!$password || strlen($password) < 6) {
            $this->error('Password harus minimal 6 karakter!');
            return Command::FAILURE;
        }

        $confirmPassword = $this->secret('Konfirmasi password baru');
        if ($password !== $confirmPassword) {
            $this->error('Password tidak cocok!');
            return Command::FAILURE;
        }

        $user->update([
            'password' => Hash::make($password),
        ]);

        $this->newLine();
        $this->info("Password admin '{$user->name}' ({$user->email}) berhasil direset!");
        $this->info('Login di: ' . url('/syamama-panel'));

        return Command::SUCCESS;
    }
}
