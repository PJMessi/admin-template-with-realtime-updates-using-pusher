<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if (!app()->environment(["production", "staging"])) {
            $this->call(UserSeeder::class);
            $this->call(DriverSeeder::class);
        }
    }
}
