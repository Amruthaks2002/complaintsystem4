<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create roles
        $roles = [
            'admin',
            'department',
            'student',
            'cleaning',
            'librarian',
            'canteen',
            'warden',
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Create departments
        $library = Department::create(['name' => 'Library']);
        $canteen = Department::create(['name' => 'Canteen']);
        $cleaning = Department::create(['name' => 'Cleaning']);

        // Admin
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('admin');

        // Department Head
        $deptHead = User::create([
            'name' => 'Department Head',
            'email' => 'dept@example.com',
            'password' => Hash::make('password'),
        ]);
        $deptHead->assignRole('department');

        // Sample student
        $student = User::create([
            'name' => 'Student User',
            'email' => 'student@example.com',
            'password' => Hash::make('password'),
        ]);
        $student->assignRole('student');

        // Cleaning Staff
        $cleaningUser = User::create([
            'name' => 'Cleaning Staff',
            'email' => 'cleaning@example.com',
            'password' => Hash::make('password'),
            'department_id' => $cleaning->id,
        ]);
        $cleaningUser->assignRole('cleaning');

        // Librarian
        $librarianUser = User::create([
            'name' => 'Librarian',
            'email' => 'librarian@example.com',
            'password' => Hash::make('password'),
            'department_id' => $library->id,
        ]);
        $librarianUser->assignRole('librarian');

        // Canteen Head
        $canteenUser = User::create([
            'name' => 'Canteen Head',
            'email' => 'canteen@example.com',
            'password' => Hash::make('password'),
            'department_id' => $canteen->id,
        ]);
        $canteenUser->assignRole('canteen');

        // Warden
        // $wardenUser = User::create([
        //     'name' => 'Warden',
        //     'email' => 'warden@example.com',
        //     'password' => Hash::make('password'),
        // ]);
        // $wardenUser->assignRole('warden');

        // 🔁 Create 50 fake students
        for ($i = 0; $i < 50; $i++) {
            $student = User::create([
                'name' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'password' => Hash::make('password'),
            ]);
            $student->assignRole('student');
        }

        echo "✅ Database seeding completed successfully.\n";
    }
}
