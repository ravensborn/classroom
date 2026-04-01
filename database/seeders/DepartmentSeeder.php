<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = ['بیرکاری', 'کۆمپیوتەر', 'فیزیک'];

        foreach ($departments as $name) {
            Department::create(['name' => $name]);
        }
    }
}
