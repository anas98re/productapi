<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class comment extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'user_id',
        'user_name',
        'comment'

    ];

    public function users()
    {
        return $this->belongsTo('App\Models\User','user_id','id');
    }

    public function products()
    {
        return $this->belongsTo('App\Models\product','product_id','id');
    }
}
