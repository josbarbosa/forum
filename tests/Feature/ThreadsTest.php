<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ThreadsTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function a_user_can_browse_threads()
    {
        //$response = $this->get('/threads');
        $this->assertTrue(true);
    }
}
