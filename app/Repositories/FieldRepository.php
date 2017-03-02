<?php

namespace App\Repositories;

use App\Field;
use App\Util\Constants;
use Illuminate\Database\Eloquent\Collection;

class FieldRepository
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
        return Field::findOrFail($id);
    }

     /**
      * Returns all games from the database.
      *
      * @return Collection|Field[]
      */
     public function getAll()
     {
         return Field::get();
     }

     /**
      * Creates a normal game in the database.
      *
      * @param int gameID Game ID to which this field is linked
      *
      * @return Field
      */
     public function createNormalGame($gameID)
     {
         return Field::create(
            [
                'gameid'        => $gameID,
                'position1'     => Constants::GAME_INPUT_NONE,
                'position2'     => Constants::GAME_INPUT_NONE,
                'position3'     => Constants::GAME_INPUT_NONE,
                'position4'     => Constants::GAME_INPUT_NONE,
                'position5'     => Constants::GAME_INPUT_NONE,
                'position6'     => Constants::GAME_INPUT_NONE,
                'position7'     => Constants::GAME_INPUT_NONE,
                'position8'     => Constants::GAME_INPUT_NONE,
                'position9'     => Constants::GAME_INPUT_NONE,
            ]
         );
     }

     /**
      * Creates an Extreme game in the databse.
      *
      * @param int gameID Game ID to which this field is linked
      *
      * @return Field
      */
     public function createExtremeGame($gameID)
     {
         $field = Field::create(
             [
                 'gameid'       => $gameID,
                 'position1'    => Constants::GAME_INPUT_FIELD,
                 'position2'    => Constants::GAME_INPUT_FIELD,
                 'position3'    => Constants::GAME_INPUT_FIELD,
                 'position4'    => Constants::GAME_INPUT_FIELD,
                 'position5'    => Constants::GAME_INPUT_FIELD,
                 'position6'    => Constants::GAME_INPUT_FIELD,
                 'position7'    => Constants::GAME_INPUT_FIELD,
                 'position8'    => Constants::GAME_INPUT_FIELD,
                 'position9'    => Constants::GAME_INPUT_FIELD,
             ]
         );

         for ($i = 0; $i <= 9; $i++) {
             $this->createInnerFields($field->gameid, $i);
         }

         return $field;
     }

    /**
     * Creaets inner fields for extreme game in the database.
     *
     * @param int parentID parent ID to which this field is linked
     * @param int placement placement of the inner field in the large field
     *
     * @return void
     */
    private function createInnerFields($parentID, $placement)
    {
        Field::create(
             [
                 'position1'    => Constants::GAME_INPUT_NONE,
                 'position1'    => Constants::GAME_INPUT_NONE,
                 'position1'    => Constants::GAME_INPUT_NONE,
                 'position1'    => Constants::GAME_INPUT_NONE,
                 'position1'    => Constants::GAME_INPUT_NONE,
                 'position1'    => Constants::GAME_INPUT_NONE,
                 'position1'    => Constants::GAME_INPUT_NONE,
                 'position1'    => Constants::GAME_INPUT_NONE,
                 'position1'    => Constants::GAME_INPUT_NONE,
                 'parentid'     => $parentID,
                 'placement'    => $placement,
             ]
         );
    }
}
