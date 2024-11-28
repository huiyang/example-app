<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Related extends Model
{
    //
    protected $guarded = [];

    public function examples()
    {
        return $this->belongsToMany(Example::class)->withPivot(['note']);
    }
}
