<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Проверяем, не существует ли уже админ
        $adminExists = User::where('email', 'admin@admin.com')->exists();

        if (!$adminExists) {
            // Создаем администратора
            User::create(['name' => 'Администратор', 'email' => 'admin@admin.com', 'password' => Hash::make('12345678'), 'role' => 'admin', 'email_verified_at' => now(),]);

            $this->command->info('Администратор создан:');
            $this->command->info('Email: admin@admin.com');
            $this->command->info('Password: 12345678');
            $this->command->warn('⚠️  НЕ ЗАБУДЬТЕ ИЗМЕНИТЬ ПАРОЛЬ ПОСЛЕ ПЕРВОГО ВХОДА!');
        } else {
            $this->command->info('Администратор уже существует');
        }

    }

}
