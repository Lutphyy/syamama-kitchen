<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateAdmin extends Command
{
    protected $signature = 'admin:create';
    protected $description = 'Buat akun admin baru untuk Syamama Kitchen';

    public function handle(): int
    {
        $this->info('=== Buat Akun Admin Syamama Kitchen ===');
        $this->newLine();

        $name = $this->ask('Masukkan nama admin');
        if (!$name) {
            $this->error('Nama tidak boleh kosong!');
            return Command::FAILURE;
        }

        $email = $this->ask('Masukkan email admin');
        $validator = Validator::make(['email' => $email], [
            'email' => 'required|email|unique:users,email',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return Command::FAILURE;
        }

        $password = $this->secret('Masukkan password (minimal 6 karakter)');
        if (!$password || strlen($password) < 6) {
            $this->error('Password harus minimal 6 karakter!');
            return Command::FAILURE;
        }

        $confirmPassword = $this->secret('Konfirmasi password');
        if ($password !== $confirmPassword) {
            $this->error('Password tidak cocok!');
            return Command::FAILURE;
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => 'admin',
        ]);

        $this->newLine();
        $this->info('Admin berhasil dibuat!');
        $this->table(
            ['Nama', 'Email', 'Role'],
            [[$user->name, $user->email, $user->role]]
        );
        $this->newLine();
        $this->info('Login di: ' . url('/syamama-panel'));

        return Command::SUCCESS;
    }
}
