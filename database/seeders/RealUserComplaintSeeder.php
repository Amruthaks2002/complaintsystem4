<?php

namespace Database\Seeders;

use App\Models\Complaint;
use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Seeder;

class RealUserComplaintSeeder extends Seeder
{
    public function run()
    {
        // Get all student user IDs
        $students = User::role('student')->pluck('id')->toArray();

        // Get all department IDs
        $departments = Department::pluck('id')->toArray();

        if (empty($students) || empty($departments)) {
            echo "❌ Error: Need students and departments in the database.\n";

            return;
        }

        // Optional: Delete old complaints if needed (be careful with foreign keys)
        // Complaint::truncate(); // Avoid if foreign keys exist in `complaint_responses`

        $total = 100000;
        $chunk = 500;

        for ($i = 0; $i < $total; $i += $chunk) {
            $data = [];

            for ($j = 0; $j < $chunk; $j++) {
                $data[] = [
                    'user_id' => fake()->randomElement($students),
                    'department_id' => fake()->randomElement($departments),
                    'title' => fake()->sentence(),
                    'description' => fake()->paragraphs(2, true),
                    'status' => fake()->randomElement(['pending', 'in_progress', 'resolved']),
                    'created_at' => fake()->dateTimeBetween('-60 days', 'now'),
                    'updated_at' => now(),
                ];
            }

            Complaint::insert($data);
            echo '✅ Inserted '.min($i + $chunk, $total)." complaints...\n";
        }

        echo "🎉 Successfully seeded 10,000 complaints!\n";
    }
}
