<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;

class ParentCategory extends Model
{
    use HasFactory;


    protected $primaryKey = 'id';

    protected $table = "parent_categories";

    protected $fillable = [
        'title'
    ];
    
    public function categories(){
        return $this->hasMany(Category::class);
    }

}
