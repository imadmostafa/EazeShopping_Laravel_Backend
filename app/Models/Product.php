<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [

        'name',
        'description',
        'price',
        "mass",
        "store_id",
        "category_id",
        "gallery_id"


    ];



public function image(){
    return $this->hasOne(Gallery::class,'gallery_id');
}








}
