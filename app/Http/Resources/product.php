<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class product extends JsonResource
{

    public function toArray($request)
    {
       // return parent::toArray($request);
       return [ //the things that send it to mobil
        'id'=>$this->id,
        'views'=>$this->views,

        'pro_name'=>$this->pro_name,
        'user_id'=>$this->user_id,
        // 'pro_image'=>$this->pro_image,
        'title'=>$this->title,
         'url'=>$this->url,
        'pro_expiration_Date'=>$this->pro_expiration_Date,
        'pro_Category'=>$this->pro_Category,
        'pro_phone'=>$this->pro_phone,
        'pro_quantity'=>$this->pro_quantity,
        'price'=>$this->price,
        'discounts'=>$this->discounts,
        'countOfLikes'=>$this->countOfLikes,
        //  'comments'=>$this->comments,
        // 'current_price'=>$this->current_price,
        // 'list_discounts'=>$this->list_discounts->array(),
        // 'pro_quantity'=>$this->pro_quantity,
        // 'pro_disCount1'=>$this->pro_disCount1,
        // 'pro_disCount2'=>$this->pro_disCount2,
        'created_at'=>$this->created_at->format('d/m/y'),
        'updated_at'=>$this->updated_at->format('d/m/y'),
        //كل واحدة ترسل نفسها
       ];

    }
}
