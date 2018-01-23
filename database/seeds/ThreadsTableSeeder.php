<?php

use App\Channel;
use App\Thread;
use App\User;
use Illuminate\Database\Seeder;

class ThreadsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $numberOfThreads = 50;
        for ($i = 0; $i < $numberOfThreads; $i++) {
            $user = User::inRandomOrder()->first();
            auth()->login($user);

            $channel = Channel::inRandomOrder()->first();

            factory(Thread::class)->create([
                'user_id'    => auth()->id(),
                'channel_id' => $channel->id,
            ]);

            auth()->logout();
        }
    }
}
