<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    /**
    * The table associated with the model.
    *
    * @var string
    */
   protected $table = 'games';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'gameid';

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

    public function users()
    {
        return $this->belongsTo('App\User', 'id', 'player1id')
            ->orwhere('id', $this->player2id);
    }

    public function field()
    {
        return $this->hasOne('App\Field', 'gameid', 'gameid');
    }
}
