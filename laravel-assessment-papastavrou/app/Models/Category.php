<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Post;
use App\Models\ParentCategory;

class Category extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $table = "categories";

    protected $fillable = [
        'title' 
    ];

    public function parentCategory(){
        return $this->belongsTo(ParentCategory::class);
    } 

    public function posts(){
        return $this->belongsToMany(Post::class, 'post_category','category_id','post_id');
    }

}
