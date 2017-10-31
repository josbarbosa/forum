<?php namespace Tests\Feature;

use App\Reply;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * Class FavoritesTest
 * @package Tests\Feature
 */
class FavoritesTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    function guests_can_not_favorite_anything(): void
    {
        $this->post('replies/1/favorites')
            ->assertRedirect('/login');
    }

    /** @test */
    function an_authenticated_user_can_favorite_any_reply(): void
    {
        $this->signIn();

        /**
         * If I post to a "favorite" endpoint
         * /replies/id/favorites
         */

        $reply = create(Reply::class);

        $this->post(route('favorite_reply', $reply->id));

        /** It should be recorded in the database */
        $this->assertCount(1, $reply->favorites);
        $this->assertInstanceOf(Reply::class, $reply->favorites()->first()->favorited);
    }

    /** @test */
    function an_authenticated_user_may_only_favorite_a_reply_once(): void
    {
        $this->signIn();

        $reply = create(Reply::class);

        try {
            $this->post(route('favorite_reply', $reply->id));
            $this->post(route('favorite_reply', $reply->id));
        } catch (\Exception $e) {
            $this->fail('Did not expect to insert the same record set twice');
        }

        $this->assertCount(1, $reply->favorites);
    }

    /** @test */
    function an_authenticated_user_can_unfavorite_a_reply(): void
    {
        $this->signIn();

        $reply = create(Reply::class);

        $this->post(route('favorite_reply', $reply->id));

        $this->assertCount(1, $reply->favorites);

        $this->delete(route('favorite_delete_reply', $reply->id));

        $this->assertCount(0, $reply->fresh()->favorites);
    }
}
