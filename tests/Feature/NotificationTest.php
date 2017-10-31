<?php namespace Tests\Feature;

use App\Thread;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Notifications\DatabaseNotification;
use Tests\TestCase;

/**
 * Class NotificationTest
 * @package Tests\Feature
 */
class NotificationTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->signIn();
    }

    /** @test */
    function a_notification_is_prepared_when_a_subscribed_thread_receives_a_new_reply_from_another_user(): void
    {
        $thread = create(Thread::class);

        $thread->subscribe();

        $this->assertCount(0, auth()->user()->notifications);

        /** Then each time a new reply is left */
        $thread->addReply([
            'user_id' => auth()->id(),
            'body'    => 'Some reply here',
        ]);

        $this->assertCount(0, auth()->user()->fresh()->notifications);

        $thread->addReply([
            'user_id' => create(User::class)->id,
            'body'    => 'Some reply here.',
        ]);

        $this->assertCount(1, auth()->user()->fresh()->notifications);
    }

    /** @test */
    function a_user_can_fetch_their_unread_notifications(): void
    {
        create(DatabaseNotification::class);

        $response = $this->getJson(route('show_notifications_profile', [
            'user' => auth()->user()->name,
        ]))->json();

        $this->assertCount(1, $response);
    }

    /** @test */
    function a_user_can_mark_a_notification_as_read(): void
    {
        create(DatabaseNotification::class);

        tap(auth()->user(), function ($user) {
            $this->assertCount(1, $user->unreadNotifications);

            $notificationId = $user->unreadNotifications->first()->id;

            $this->delete(route('notification_profile', [
                'user'         => $user->name,
                'notification' => $notificationId,
            ]));

            $this->assertCount(0, $user->fresh()->unreadNotifications);
        });
    }
}
