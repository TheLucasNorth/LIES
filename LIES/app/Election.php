<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Election extends Model
{
    public function votes() {
        return $this->hasMany('App\Vote');
    }

    public function candidates() {
        return $this->hasMany('App\Candidate');
    }

    protected $dates = ['voting_start', 'voting_end'];
}
