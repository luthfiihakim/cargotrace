<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            ['Admin CargoTrace', 'admin@cargotrace.test', UserRole::Admin, null],
            ['Operator CargoTrace', 'operator@cargotrace.test', UserRole::Operator, null],
            ['Customer One', 'customer1@cargotrace.test', UserRole::Customer, 'PT Nusantara Export'],
            ['Customer Two', 'customer2@cargotrace.test', UserRole::Customer, 'CV Samudra Trading'],
        ];

        foreach ($accounts as [$name, $email, $role, $company]) {
            $user = User::firstOrNew(['email' => $email]);

            $user->forceFill([
                'name' => $name,
                'password' => 'password',
                'role' => $role,
                'company_name' => $company,
                'email_verified_at' => now(),
            ])->save();
        }
    }
}