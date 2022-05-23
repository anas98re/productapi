<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class like extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'user_id', 'product_id',
    ];
    // protected $dates = [
    //     'created_at',
    //     'updated_at',
    //     'deleted_at'
    // ];
    // protected $hidden=['created_at','updated_at','pivot'];
    // public $timestamps = true;

    public function users()
    {
        return $this->belongsTo('App\Models\User','user_id','id');
    }

    public function products()
    {
        return $this->belongsTo('App\Models\product','product_id','id');
    }
}
