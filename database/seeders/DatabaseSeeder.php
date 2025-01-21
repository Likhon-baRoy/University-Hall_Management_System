<?php

namespace Database\Seeders;

use App\Models\Hall;
use App\Models\Admin;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
  public function run(): void
  {
    // Create Permissions
    $permissions = ['Slider', 'Admins', 'Settings', 'problems', 'Applications', 'Notices', 'Transaction', 'Meals', 'Fields'];

    foreach ($permissions as $permission) {
      Permission::create([
        'name' => $permission,
        'slug' => \Str::slug($permission),
      ]);
    }

    // Create Super Admin Role
    $role = Role::create([
      'name' => 'Super Admin',
      'slug' => 'super-admin',
      'permissions' => json_encode($permissions), // Assign all permissions directly
    ]);

    // After creating Super Admin role, add these roles
    $userTypes = ['student', 'teacher', 'staff', 'admin', 'editor', 'author'];

    foreach ($userTypes as $type) {
      Role::create([
        'name' => ucfirst($type), // Capitalizes first letter
        'slug' => $type,
        'permissions' => json_encode([]), // Start with no permissions
      ]);
    }

    // Create Super Admin User
    Admin::create([
      'user_id' => '123456789',
      'name' => 'Provider',
      'email' => 'provider@gmail.com',
      'cell' => '01532389132',
      'username' => 'provider',
      'password' => Hash::make('123'),
      'gender' => 'male',
      'user_type' => 'sadmin',
      'status' => true,
      'hall' => '',
      'room' => '',
      'seat' => '',
      'role_id' => $role->id,
    ]);

    // Create Halls
    $halls = [
      [
        'name' => 'Mokbul Hossain Hall',
        'gender' => 'male',
        'location' => 'City Campus, Savar',
      ],
      [
        'name' => 'Fatema Hall',
        'gender' => 'female',
        'location' => 'City Campus, Savar',
      ],
      [
        'name' => 'Mona Hall',
        'gender' => 'female',
        'location' => 'Khagan Bazar, Birulia, Savar',
      ],
      [
        'name' => 'Fazlur Rahaman Hall',
        'gender' => 'male',
        'location' => 'Khagan Bazar, Birulia, Savar',
      ],
    ];

    foreach ($halls as $hall) {
      Hall::create($hall);
    }

    // Run the verification seeder
    $this->call(VerificationTableSeeder::class);

  }
}
