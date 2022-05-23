<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens,
     HasFactory,
     Notifiable;

    protected $table='users';
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone'
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function products()
    {
        return $this->hasMany('App\Models\product','user_id','id');
    }
    public function comments()
    {
        return $this->hasMany('App\Models\comment','user_id','id')->orderBy('date');
    }

    public function likes()
    {
        return $this->hasMany('App\Models\like','user_id','id');
    }

//     public function likes()
// {
// return $this->belongsToMany('App\Models\like', 'likes', 'user_id', 'product_id');
// }

}
