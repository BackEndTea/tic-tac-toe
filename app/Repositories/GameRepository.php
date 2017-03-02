<?php

namespace App\Repositories;

use App\Game;
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
          * Creates a new game.
          *
          * @param array $attributes
          */
         public function create($attributes)
         {
             return Game::create($attributes);
             
             if($attributes['gametype'] ==Constants::GAME_TYPE_EXTREME) {

             }

         }

         /**
          * Updates gamestate of specified game.
          *
          * @param int id gameid
          *
          * @param int state new gamestate
          *
          * @return Game
          */
         public function setGameState($id, $state)
         {
             $game = Game::where('gameid', $id)->first();
             if ($state == Constants::GAME_STATE_FINISHED) {
                 $game->finished_at = date("Y-m-d H:i:s");
             }
             $game->gamestate = $state;
             $game->save();
             return $game;
         }
}
