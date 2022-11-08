<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class Category extends Model
{
    use HasFactory, Uuids;
    protected $fillable = ['name'];
    protected $table = 'categories';
    
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
