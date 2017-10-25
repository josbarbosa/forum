<?php namespace App\Policies;

use App\Reply;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Class ReplyPolicy
 * @package App\Policies
 */
class ReplyPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @param Reply $reply
     * @return bool
     */
    public function update(User $user, Reply $reply): bool
    {
        return $reply->user_id == $user->id;
    }

}
