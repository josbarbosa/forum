<?php namespace App\Http\Controllers;

use App\User;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;

/**
 * Class UserNotificationsController
 * @package App\Http\Controllers
 */
class UserNotificationsController extends Controller
{
    /**
     * UserNotificationsController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @return DatabaseNotificationCollection
     */
    public function index(): DatabaseNotificationCollection
    {
        return auth()->user()->unreadNotifications;
    }

    /**
     * @param User $user
     * @param DatabaseNotification $notification
     */
    public function destroy(User $user, DatabaseNotification $notification): void
    {
        auth()->user()->notifications()->findOrFail($notification->id)->markAsRead();
    }
}
