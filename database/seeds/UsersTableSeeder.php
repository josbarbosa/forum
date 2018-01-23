<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class, 20)->create();
        factory(User::class, 1)->create([
            'name'     => 'admin',
            'email'    => 'admin@localhost.wip',
            'password' => bcrypt('aaaa'),
        ]);
    }
}
