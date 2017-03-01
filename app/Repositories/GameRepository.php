<?php

namespace App\Repositories;

use App\Game;

class GameRepository
{
    /**
     * Return Game with given id from database
     *
     * @param $id
     *
     * @return mixed
     *
    */
    public function getById($id)
    {
        return Game::findOrFail($id);
    }

    /**
     * Returns all games from the database
     *
     * @return Collection|Game[]
     *
     */
     public function getAll()
     {
         return Game::get();
     }

     /**
      * Returns all games which have not yet started
      *
      * @return Collection|Game[]
      *
      */
      public function getStartingGames()
      {
          return Game::where('gamestate', 0)->get();
      }

      /**
       * Returns all games which are curretnly being played
       *
       * @return Collection|Game[]
       *
       */
       public function getPlayingGames()
       {
           return Game::where('gamestate', 1)->get();
       }

       /**
        * Returns all games which have finished
        *
        * @return Collection|Game[]
        *
        */
        public function getFinishedGames()
        {
            return Game::where('gamestate', 2)->get();
        }

}
