<?php namespace App\Http\Controllers;

use App\Activity;
use App\User;
use Illuminate\View\View;

/**
 * Class ProfilesController
 * @package App\Http\Controllers
 */
class ProfilesController extends Controller
{
    /**
     * @param User $user
     * @return View
     */
    public function show(User $user): View
    {
        return view('profiles.show', [
            'profileUser' => $user,
            'activities'  => Activity::feed($user),
        ]);
    }
}
