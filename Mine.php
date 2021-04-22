<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mine extends Model
{
    protected $fillable = ['id','user_id','name','type'];

    protected static function boot()
    {
        parent::boot();

        static::created(function($mine) {
            for($i=1;$i<4;$i++)
            {
                $mine->mineImprovement()->create([
//                    'mine_id'=>$mine->id,
                    'improvement_id'=>$i,
                    'improvement_level_id'=>0
                ]);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function worker()
    {
        return $this->hasMany(Worker::class);
    }
    public function mineImprovement()
    {
        return $this->hasMany(MineImprovement::class);
    }
}
