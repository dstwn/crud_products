<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class Product extends Model
{
    use HasFactory, Uuids;
    
    protected $fillable = ['name', 'price', 'category_id', 'description', 'image'];
    protected $table = 'products';

    public function category(){
        return $this->belongsTo(Category::class);
    }
}
