<?php

namespace App\Repositories;

use App\Field;
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
}
