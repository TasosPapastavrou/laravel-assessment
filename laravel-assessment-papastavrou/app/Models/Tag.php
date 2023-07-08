<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Post;

class Tag extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $table = "tags";

    protected $fillable = [
        'title' 
    ]; 
    
    public function post(){
        return $this->belongsToMany(Post::class, 'post_tag','tag_id','post_id');
    }  

}
