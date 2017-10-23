<?php namespace App\Http\Controllers;

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
            'threads'     => $user->threads()->paginate(getItemsPerPage('profiles')),
        ]);
    }
}
