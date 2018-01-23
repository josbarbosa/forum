<?php

use App\Reply;
use App\User;
use Illuminate\Database\Seeder;

class FavoritesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $numberOfFavorites = 50;
        for ($i = 0; $i < $numberOfFavorites; $i++) {
            /** @var User $user */
            $user = User::inRandomOrder()->first();
            auth()->login($user);

            /** @var Reply $reply */
            $reply = Reply::inRandomOrder()->first();

            $reply->favorite();

            auth()->logout();
        }
    }
}
