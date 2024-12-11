<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Insurance;
use App\Models\Employee;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Issue;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UsersTableSeeder::class,
            CompaniesTableSeeder::class,
            EmployeesTableSeeder::class,
            CustomersTableSeeder::class,
            InsurancesTableSeeder::class,
            IssuesTableSeeder::class,
        ]);
    }
}
