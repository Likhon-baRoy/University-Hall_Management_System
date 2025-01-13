<?php

namespace Database\Seeders;

use App\Models\StudentVerification;
use App\Models\TeacherVerification;
use App\Models\StaffVerification;
use Illuminate\Database\Seeder;

class VerificationTableSeeder extends Seeder
{
  public function run(): void
  {
    // Clear existing records
    StudentVerification::truncate();
    TeacherVerification::truncate();
    StaffVerification::truncate();

    // Seed Students
    $students = [
      [
        'user_id' => '181472543',
        'email' => 'likhonhere007@gmail.com',
        'department' => 'cse',
        'gender' => 'male',
        'is_registered' => false
      ],
      [
        'user_id' => '181472544',
        'email' => 'lidansuidan007@gmail.com',
        'department' => 'cse',
        'gender' => 'female',
        'is_registered' => false
      ],
      [
        'user_id' => '181472545',
        'email' => 'lidansuidan009@gmail.com',
        'department' => 'eee',
        'gender' => 'female',
        'is_registered' => false
      ]
    ];

    // Seed Teachers
    $teachers = [
      [
        'user_id' => '171472543',
        'email' => 'likhonhere007@proton.me',
        'department' => 'cse',
        'gender' => 'male',
        'is_registered' => false
      ],
      [
        'user_id' => '171472544',
        'email' => 'lidansuidan00@gmail.com',
        'department' => 'cse',
        'gender' => 'female',
        'is_registered' => false
      ]
    ];

    // Seed Staff
    $staff = [
      [
        'user_id' => '201472545',
        'email' => 'lidansuidan09@gmail.com',
        'department' => 'eee',
        'gender' => 'male',
        'is_registered' => false
      ],
      [
        'user_id' => '201472546',
        'email' => 'mrinoman00@gmail.com',
        'department' => 'cse',
        'gender' => 'female',
        'is_registered' => false
      ]
    ];

    // Insert the data
    StudentVerification::insert($students);
    TeacherVerification::insert($teachers);
    StaffVerification::insert($staff);
  }
}
