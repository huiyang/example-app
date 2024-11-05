<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;

class Example extends Model
{
    use \Spatie\Activitylog\Traits\LogsActivity;

    //
    protected $table = 'example_models';

    protected $guarded = [];
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly($this->logs ?? [])
            ->logUnguarded()->logOnlyDirty()->dontSubmitEmptyLogs()->logExcept(['updated_at']);
    }
}
