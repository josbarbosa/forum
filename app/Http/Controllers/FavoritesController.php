<?php namespace App\Http\Controllers;

use App\Reply;

/**
 * Class FavoritesController
 * @package App\Http\Controllers
 */
class FavoritesController extends Controller
{

    /**
     * FavoritesController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @param Reply $reply
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Reply $reply)
    {
        $reply->favorite();

        return back();
    }
}
