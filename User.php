<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    protected static function boot()
    {
        parent::boot();

        static::created(function($user) {
            $user->mine()->create([
//                'user_id' => $user->id,
                'type' => 1,
            ]);
        });


        static::created(function($user) {
            $user->resource()->create([
                'black_coal' => 100,
                'brown_coal' => 100,
                'money'=> 10000
            ]);
        });
    }

//Mine::create([
//'user_id' => $data['name'],
//'type' => 1,
//]);

//Resource::create([
//'black_coal' => 100,
//'brown_coal' => 100,
//'money'=> 10000
//]);
    public function resource()
    {
       return $this->hasOne(Resource::class);
    }
    public function worker()
    {
        return $this->hasMany(Worker::class);
    }
    public function mine()
    {
        return $this->hasMany(Mine::class);
    }

    public function alliance()
    {
        return $this->belongsTo(alliance::class);
    }


}
