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
        $this->call(MstAtkTableSeeder::class);
        $this->call(MstBarangTableSeeder::class);
        $this->call(SubBidangTableSeeder::class);
        $this->call(PermissionTableSeeder::class);

    }

}