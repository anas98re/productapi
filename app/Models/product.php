<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class product extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'pro_name',
        'views',
        'price',
        // 'current_price',
        // 'pro_image',
        'title',
         'url',
        'pro_expiration_Date',
        'pro_Category',
        'pro_phone',
        'pro_quantity'
        // 'pro_disCount1',
        // 'pro_disCount2'
    ];
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    protected $hidden=['created_at','updated_at','pivot'];
    public $timestamps = true;


    // protected $dates = ['expired_at'];
    public function users()
    {
        return $this->belongsTo('App\Models\User','user_id','id');
    }

    public function discounts()
    {
        return $this->hasMany('App\Models\discount','product_id','id')->orderBy('date');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\comment','product_id','id');
    }
    public function likes()
{
return $this->hasMany('App\Models\like', 'product_id', 'id');
}


public $withCount=['comments','likes'];


// public function likes()
// {
// return $this->belongsToMany('App\Models\User', 'likes');
// }

    // public function category() {
    //     return $this->belongsTo('App\Models\Category');
    // }

    // public function reviews() {
    //     return $this->hasManyThrough('App\Models\Review', 'App\Product');
    // }
    // $category->reviews();
}
