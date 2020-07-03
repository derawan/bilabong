<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ApplicationConfigSeeder::class);
        $this->create_master_data_reference();
        $this->call(AdminSeeder::class);
        $this->call(UserSeeder::class);

    }

    public function create_master_data_reference()
    {
        $this->call(CountryTableSeeder::class);
        $this->call(ProvinceTableSeeder::class);
        $this->call(CityTableSeeder::class);
        $this->call(EntityCategoryTypeSeeder::class);
        $this->call(CategoryTableSeeder::class);
    }
}
