<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\CourseSeeder;
use Database\Seeders\UserListSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\CourseModuleSeeder;
use Database\Seeders\CourseSectionSeeder;
use Database\Seeders\CourseMaterialSeeder;
use Database\Seeders\PermissionRoleSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            PermissionRoleSeeder::class,
            UserListSeeder::class,
            UserSeeder::class,
            CourseSeeder::class,
            CourseModuleSeeder::class,
            CourseSectionSeeder::class,
            CourseMaterialSeeder::class,
        ]);
    }
}
