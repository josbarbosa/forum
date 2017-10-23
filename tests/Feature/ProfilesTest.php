<?php namespace Tests\Feature;

use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * Class ProfilesTest
 * @package Tests\Feature
 */
class ProfilesTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    function a_user_has_a_profile(): void
    {
        $user = create(User::class);
        $this->get(route('show_profile', $user->name))
            ->assertSee($user->name);
    }

    /** @test */
    function profiles_display_all_threads_created_by_the_associated_user(): void
    {
        $user = create(User::class);

        $thread = create(Thread::class, 1, [
            'user_id' => $user->id,
        ]);

        $this->get(route('show_profile', $user->name))
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }
}
