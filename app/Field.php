<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    /**
    * The table associated with the model.
    *
    * @var string
    */
   protected $table = 'field';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'fieldid';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
}
