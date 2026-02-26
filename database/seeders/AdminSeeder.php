<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        $admin = User::updateOrCreate(
            ['email' => 'admin@maroof.test'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password123'),
                'email_verified_at' => now(),
            ]
        );

        // Assign super_admin role
        $admin->assignRole('super_admin');

        echo "\n✅ Admin user created:\n";
        echo "📧 Email: admin@maroof.test\n";
        echo "🔑 Password: password123\n";
    }
}
