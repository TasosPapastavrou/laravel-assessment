<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Comment;
use App\Models\Tag;
use App\Models\Category;
use App\Models\User;

class Post extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $table = "posts";

    protected $fillable = [
        'title','content','author', 'slug'
    ];
    
    public function comments(){
        return $this->hasMany(Comment::class);
    }  

    public function user(){
        return $this->belongsTo(User::class);
    } 

    public function tags(){
        return $this->belongsToMany(Tag::class, 'post_tag','post_id','tag_id');
    } 

    public function categories(){
        return $this->belongsToMany(Category::class, 'post_category','post_id','category_id');
    } 

    public function postDetailsChange($updatePostValues,$oldPost){

        if($updatePostValues['title'] != $oldPost->title || $updatePostValues['content'] != $oldPost->content)
            return true;
        else
            return false;        

    }

    public function scopeFilterByIdAndSlug(Builder $query,$data): void
    {
        $query->where('id', '=', $data['post_id'])
                ->where('slug', '=', $data['slug']);
    }

    public function scopeFilterByAuthorAndTagsAndCategory(Builder $query,$author,$tagsName,$categoriesName): void
    {
         $query->when($author ?? null, function($query) use ($author) {
                    $query->where('author', '=', $author);
                })->when($tagsName ?? null, function($query) use ($tagsName) {
                    $query->whereHas('tags', function (Builder $query) use ($tagsName) {
                        $query->whereIn('title', $tagsName);
                    });
                })->when($categoriesName ?? null, function($query) use ($categoriesName) {
                    $query->whereHas('categories', function (Builder $query) use ($categoriesName) {
                        $query->whereIn('title', $categoriesName);
                    });
                });
        
        
    }


}
