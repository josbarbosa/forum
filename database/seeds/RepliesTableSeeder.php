<?php

use App\Reply;
use App\User;
use App\Thread;
use Illuminate\Database\Seeder;

class RepliesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $numberOfReplies = 50;
        for ($i = 0; $i < $numberOfReplies; $i++) {
            $user = User::inRandomOrder()->first();
            auth()->login($user);

            $thread = Thread::inRandomOrder()->first();

            factory(Reply::class)->create([
                'user_id'   => auth()->id(),
                'thread_id' => $thread->id,
            ]);
            auth()->logout();
        }
    }
}
