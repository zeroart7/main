<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $primarykey = 'product_id';

    public function category(){
        return $this->belongsto(Category::class,'category_id');
    }
}
