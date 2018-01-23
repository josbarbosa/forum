<?php

use App\User;
use App\Thread;
use Illuminate\Database\Seeder;

class ThreadsSubscriptionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $numberOfThreadSubs = 50;
        for ($i = 0; $i < $numberOfThreadSubs; $i++) {
            /** @var User $user */
            $user = User::inRandomOrder()->first();
            auth()->login($user);

            /** @var Thread $thread */
            $thread = Thread::inRandomOrder()->first();

            $thread->subscribe();

            auth()->logout();
        }
    }
}
