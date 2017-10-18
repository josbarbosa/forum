<?php namespace Tests\Feature;

use App\Thread;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ThreadsTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function a_user_can_view_all_threads()
    {
        $thread = factory(Thread::class)->create();
        $response = $this->get('/threads');

        //Test if page return OK status
        $response->assertStatus(200);
        //Test if the thread title is in the page
        $response->assertSee($thread->title);
    }

    /** @test */
    public function a_user_can_read_a_single_thread()
    {
        $thread = factory(Thread::class)->create();

        //Test if user can access thread
        $response = $this->get('/threads/'.$thread->id);
        $response->assertStatus(200);
        //$response->assertSee('the page you are looking for could not be found.');
        $this->assertEquals(2, $thread->id);
    }
}
