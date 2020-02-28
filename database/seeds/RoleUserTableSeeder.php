<?php

use App\Role;
use Illuminate\Database\Seeder;

class RoleUserTableSeeder extends Seeder
{
    public function run()
    {
        Role::findOrFail(1)->users()->sync(1);
        Role::findOrFail(2)->users()->sync(range(2,11));
    }
}
