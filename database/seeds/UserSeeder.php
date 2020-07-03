<?php

use App\Models\Member;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = factory(\App\Models\Member::class, 150)->create();
    }
}
