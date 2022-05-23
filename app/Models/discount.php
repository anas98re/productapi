<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class discount extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'discount_percentage',
        'date'
    ];

    public function products()
    {
        return $this->belongsTo('App\Models\product','product_id','id');
    }

}
