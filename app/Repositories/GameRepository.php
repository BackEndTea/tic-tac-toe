<?php

namespace App\Repositories;

use App\Game;
use App\User;
use App\Util\Constants;
use Illuminate\Database\Eloquent\Collection;

class GameRepository
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
        return Game::findOrFail($id);
    }

    /**
     * Returns all games from the database.
     *
     * @return Collection|Game[]
     */
    public function getAll()
    {
        return Game::get();
    }

    /**
     * Returns all games which have not yet started.
     *
     * @return Collection|Game[]
     */
    public function getStartingGames()
    {
        return Game::where('gamestate', Constants::GAME_STATE_NOT_YET_STARTED)->get();
    }

    /**
     * Returns all games which are curretnly being played.
     *
     * @return Collection|Game[]
     */
    public function getPlayingGames()
    {
        return Game::where('gamestate', Constants::GAME_STATE_PLAYING)->get();
    }

    /**
     * Returns all games which have finished.
     *
     * @return Collection|Game[]
     */
    public function getFinishedGames()
    {
        return Game::where('gamestate', Constants::GAME_STATE_FINISHED)->get();
    }

    /**
     * Retrieves the player 1 of the game.
     *
     * @param int id Game id
     *
     * @return User
     */
    public function getPlayerOne($id)
    {
        $game = $this->getById($id);

        return User::where('id', $game->player1id)->first();
    }

    /**
     * Retrieves the player 2 of the game.
     *
     * @param int id Game id
     *
     * @return User
     */
    public function getPlayerTwo($id)
    {
        $game = $this->getById($id);

        return User::where('id', $game->player2id)->first();
    }

    /**
     * Retrieves the both playersof the game.
     *
     * @param int id Game id
     *
     * @return Collection|User[]
     */
    public function getPlayers($id)
    {
        $game = $this->getById($id);

        return User::where('id', $game->player1id)
             ->orWhere('id', $game->player2id)->get();
    }

    /**
     * Retrieves the fields of a game.
     *
     * @param int id Game id
     *
     * @return Collection|Field[]
     */
    public function getFields($id)
    {
        return Game::find($id)->fields()->get();
    }

    /**
     * Creates a new game.
     *
     * @param array $attributes
     *
     * @return Game
     */
    public function create($attributes)
    {
        $game = Game::create($attributes);
        $fieldRepository = new FieldRepository();

        if ($attributes['gametype'] == Constants::GAME_TYPE_EXTREME) {
            $fieldRepository->createExtremeGame($game->gameid);
        } elseif ($attributes['gametype'] == Constants::GAME_TYPE_NORMAL) {
            $fieldRepository->createNormalGame($game->gameid);
        }

        return $game;
    }

    /**
     * Updates gamestate of specified game.
     *
     * @param int id gameid
     * @param int state new gamestate
     *
     * @return Game
     */
    public function setGameState($id, $state)
    {
        $game = Game::where('gameid', $id)->first();
        if ($state == Constants::GAME_STATE_FINISHED) {
            $game->finished_at = date('Y-m-d H:i:s');
        }
        $game->gamestate = $state;
        $game->save();

        return $game;
    }

    /**
     * Sets the second player for a game.
     *
     * @param int gameID ID of the game
     * @param int userID ID of the player 2
     *
     * @return Game
     */
    public function setPlayerTwo($gameID, $userID)
    {
        $game = Game::find($gameID);
        $game->player2id = $userID;
        $game->save();

        return $game;
    }

    /**
     * Sets the first players tag for a game.
     *
     * @param int gameID ID of the game
     * @param char userTag tag the player wants to use
     *
     * @return Game
     */
    public function setPlayerOneTag($gameID, $userTag)
    {
        $game = Game::find($gameID);
        $game->player1tag = $userTag;
        $game->save();

        return $game;
    }

    /**
     * Sets the second players tag for a game.
     *
     * @param int gameID ID of the game
     * @param char userTag tag the player wants to use
     *
     * @return Game
     */
    public function setPlayerTwoTag($gameID, $userTag)
    {
        $game = Game::find($gameID);
        $game->player2tag = $userTag;
        $game->save();

        return $game;
    }
}
