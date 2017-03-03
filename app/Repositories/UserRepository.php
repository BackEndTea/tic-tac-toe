<?php

namespace App\Repositories;

use App\Game;
use App\User;
use App\Util\Constants;
use Illuminate\Database\Eloquent\Collection;

class UserRepository
{
    /**
     * Return Game with given id from database.
     *
     * @param $id
     *
     * @return mixed
     */
    public function getById($id)
    {
        return User::findOrFail($id);
    }

    /**
     * Returns all games from the database.
     *
     * @return Collection|User[]
     */
    public function getAll()
    {
        return User::get();
    }

    /**
     * Returns all games the user is part of.
     *
     * @param int id user ID
     *
     * @return Collection|Game[]
     */
    public function getAllGames($id)
    {
        return Game::where('player1id', $id)->orWhere('player2id', $id)->get();
    }

    /**
     * Returns all games the user is part of that have not yet started.
     *
     * @param int id user ID
     *
     * @return Collection|Game[]
     */
    public function getStartingGames($id)
    {
        return Game::where(function ($query) use ($id) {
            $query->where('player1id', $id)
                ->where('gamestate', Constants::GAME_STATE_NOT_YET_STARTED);
        })->orWhere('player2id', $id)
            ->where('gamestate', Constants::GAME_STATE_NOT_YET_STARTED)
            ->get();
    }

    /**
     * Returns all games the user is part of that are curretnly being played.
     *
     * @param int id user ID
     *
     * @return Collection|Game[]
     */
    public function getPlayingGames($id)
    {
        return Game::where(function ($query) use ($id) {
            $query->where('player1id', $id)
                ->where('gamestate', Constants::GAME_STATE_PLAYING);
        })->orWhere('player2id', $id)
            ->where('gamestate', Constants::GAME_STATE_PLAYING)
            ->get();
    }

    /**
     * Returns all games the user is part of that have finished.
     *
     * @param int id user ID
     *
     * @return Collection|Game[]
     */
    public function getFinishedGames($id)
    {
        return Game::where(function ($query) use ($id) {
            $query->where('player1id', $id)
                ->where('gamestate', Constants::GAME_STATE_FINISHED);
        })->orWhere('player2id', $id)
            ->where('gamestate', Constants::GAME_STATE_FINISHED)
            ->get();
    }

    /**
     * Creates a new User.
     *
     * @param array $attributes
     *
     * @return User
     */
    public function create($attributes)
    {
        return User::create($attributes);
    }
}
