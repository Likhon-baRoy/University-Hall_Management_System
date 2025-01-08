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
    $permissions = ['Slider', 'Our Team', 'Testimonials', 'Admins', 'Settings', 'Posts', 'Portfolio', 'Clients'];

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

    // Create Super Admin User
    Admin::create([
      'name' => 'Provider',
      'email' => 'provider@gmail.com',
      'cell' => '01532389132',
      'username' => 'provider',
      'password' => Hash::make('123'),
      'role_id' => $role->id,
    ]);

    // Create Existing Halls
    Hall::create([
      'name' => 'Mokbul Hossain Hall',
      'gender' => 'male',
      'location' => 'City Campus, Savar',
    ]);

    Hall::create([
      'name' => 'Fatema Hall',
      'gender' => 'female',
      'location' => 'City Campus, Savar',
    ]);

    Hall::create([
      'name' => 'Mona Hall',
      'gender' => 'female',
      'location' => 'Khagan Bazar, Birulia, Savar',
    ]);

    Hall::create([
      'name' => 'Fazlur Rahaman Hall',
      'gender' => 'male',
      'location' => 'Khagan Bazar, Birulia, Savar',
    ]);
  }
}
