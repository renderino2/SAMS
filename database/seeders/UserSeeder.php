<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Default password for all seeded users
        $defaultPassword = Hash::make('password123');

        // Student Assistant Users
        User::create([
            'name' => 'John Doe',
            'full_name' => 'John Doe',
            'student_id_number' => '2021-0001',
            'email' => 'student.assistant@example.com',
            'email_verified_at' => now(),
            'password' => $defaultPassword,
            'password_hash' => $defaultPassword,
            'role' => 'Student Assistant',
            'office' => 'Registrar\'s Office',
            'contact' => '09123456789',
            'gender' => 'male',
            'active' => 1,
            'remember_token' => Str::random(10),
        ]);

        User::create([
            'name' => 'Jane Smith',
            'full_name' => 'Jane Smith',
            'student_id_number' => '2021-0002',
            'email' => 'jane.smith@example.com',
            'email_verified_at' => now(),
            'password' => $defaultPassword,
            'password_hash' => $defaultPassword,
            'role' => 'Student Assistant',
            'office' => 'Dean\'s Office',
            'contact' => '09123456790',
            'gender' => 'female',
            'active' => 1,
            'remember_token' => Str::random(10),
        ]);

        User::create([
            'name' => 'Michael Johnson',
            'full_name' => 'Michael Johnson',
            'student_id_number' => '2021-0003',
            'email' => 'michael.johnson@example.com',
            'email_verified_at' => now(),
            'password' => $defaultPassword,
            'password_hash' => $defaultPassword,
            'role' => 'Student Assistant',
            'office' => 'Library',
            'contact' => '09123456791',
            'gender' => 'male',
            'active' => 1,
            'remember_token' => Str::random(10),
        ]);

        // Office Head Users
        User::create([
            'name' => 'Dr. Maria Garcia',
            'full_name' => 'Dr. Maria Garcia',
            'email' => 'office.head@example.com',
            'email_verified_at' => now(),
            'password' => $defaultPassword,
            'password_hash' => $defaultPassword,
            'role' => 'Office Head',
            'office' => 'Registrar\'s Office',
            'contact' => '09123456792',
            'gender' => 'female',
            'active' => 1,
            'remember_token' => Str::random(10),
        ]);

        User::create([
            'name' => 'Prof. Robert Wilson',
            'full_name' => 'Prof. Robert Wilson',
            'email' => 'robert.wilson@example.com',
            'email_verified_at' => now(),
            'password' => $defaultPassword,
            'password_hash' => $defaultPassword,
            'role' => 'Office Head',
            'office' => 'Dean\'s Office',
            'contact' => '09123456793',
            'gender' => 'male',
            'active' => 1,
            'remember_token' => Str::random(10),
        ]);

        // HR Users
        User::create([
            'name' => 'Sarah Martinez',
            'full_name' => 'Sarah Martinez',
            'email' => 'hr@example.com',
            'email_verified_at' => now(),
            'password' => $defaultPassword,
            'password_hash' => $defaultPassword,
            'role' => 'HR',
            'office' => 'Human Resources',
            'contact' => '09123456794',
            'gender' => 'female',
            'active' => 1,
            'remember_token' => Str::random(10),
        ]);

        User::create([
            'name' => 'David Brown',
            'full_name' => 'David Brown',
            'email' => 'david.brown@example.com',
            'email_verified_at' => now(),
            'password' => $defaultPassword,
            'password_hash' => $defaultPassword,
            'role' => 'HR',
            'office' => 'Human Resources',
            'contact' => '09123456795',
            'gender' => 'male',
            'active' => 1,
            'remember_token' => Str::random(10),
        ]);
    }
}
