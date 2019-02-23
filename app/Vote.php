<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    public function elections() {
        return $this->belongsTo('App\Election');
    }

    public function voter() {
        return $this->belongsTo('App\User', 'votingCode', 'votingCode');
    }

    protected $guarded = [];
}
