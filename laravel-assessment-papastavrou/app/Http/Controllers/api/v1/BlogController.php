<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use App\Models\User;
use App\Models\Post;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Tag;
use App\Mail\CommentNotification;
use Illuminate\Support\Facades\Mail;

class BlogController extends Controller
{

    private $user;

    function __construct() {        
        $this->user = Auth::guard('api')->user();    
    }
 
    public function getCategories(){

        $categories = Category::all();
        return response()->json(['categories'=>$categories],200);

    }

    public function getUserComments(){

        $user = $this->user;
        $comments = $user->comments;
        
        return response()->json(['user_comments'=>$comments],200);
    
    }

    public function getUserPosts(){

        $user = $this->user;
        $posts = $user->posts;

        return response()->json(['user_posts'=>$posts],200);

    }

    public function addComment(Request $request,$postId){

        $commentBody = $request->validate([
            'content' => 'required',
        ]);

        $userId = $this->user->id;
        $input = $request->input();
        $comment_content = $input['content'];
        $findPost = Post::find($postId); 

        if(empty($findPost))
            return response()->json(['message'=>'doesnt exist post with this id'],422);

        $newComment = new Comment();
        $newComment->content = $comment_content;
        $newComment->post_id = $postId;
        $newComment->user_id = $userId;
        $newComment->save(); 


        $post = $newComment->post;
        $authorEmail = $post->user->email; 
         
        Mail::to($authorEmail)->send(new CommentNotification($comment_content));

        return response()->json(['message'=>'successfull add new comment'],201);

    }

    public function deletePost($postId){ 

        $findPost = Post::find($postId); 

        if(empty($findPost))
            return response()->json(['message'=>'doesnt exist post with this id'],422);

        // $deletePost = $this->user->posts()->where('id','=',$postId)->delete();
        $findPost->delete();


        return response()->json(['message'=>'successfull post delete'],202);

    } 

    public function postUpdate(Request $request,$postId){

        $findPost = Post::find($postId); 

        if(empty($findPost))
            return response()->json(['message'=>'doesnt exist post with this id'],422);

        $input = $request->input();
        // $post = $this->user->posts()->where('id','=',$postId)->first();
        $isUpdate = $findPost->postDetailsChange($input,$findPost);
        $user = $this->user;
        
        $title = $input['title'];
        $content = $input['content'];
        $author = $input['author'];
        $slug = $input['slug'];
        $userId = $user->id;

        if($isUpdate){
            $tagUpdate = Tag::where('title','=','edited')->first(); 
            $tagId = $tagUpdate->id;
            $findPost->tags()->attach([$tagId]);
        }

        $findPost->title = $title;
        $findPost->content = $content;
        $findPost->author = $author;
        $findPost->slug = $slug;
        $findPost->user_id = $userId;
        $findPost->save(); 

        return response()->json(['message'=> 'successfull post update'],200);

    }

    public function addPost(Request $request){

        $addPost = $request->validate([
            'title' => 'required',
            'content' => 'required',
            'slug' => 'required', 
        ]);

        $input = $request->input();
        $user = $this->user;

        $title = $input['title'];
        $content = $input['content'];
        $author = $user->name;
        $slug = $input['slug'];
        $userId = $user->id;

        $newPost = new Post();
        $newPost->title = $title;
        $newPost->content = $content;
        $newPost->author = $author;
        $newPost->slug = $slug;
        $newPost->user_id = $userId;
        $newPost->save(); 

        $tagUpdate = Tag::where('title','=','new')->first(); 
        $tagId = $tagUpdate->id;
        $newPost->tags()->attach([$tagId]);

        return response()->json(['message'=> 'successfull add new post'],201);

    }


    public function getPost(Request $request){

        $getPost = $request->validate([
            'post_id' => 'required', 
            'slug' => 'required', 
        ]);

        $input = $request->input();
        $post = $this->user->posts()->filterByIdAndSlug($input)->with('tags')->first();

        return response()->json(['post'=>$post],200);
        
    }


    public function getPosts(Request $request){

        $input = $request->input();

        $author = $request->get('author');
        $tags = $request->get('tags');
        $categories = $request->get('categories');

        $posts = Post::filterByAuthorAndTagsAndCategory($author,$tags,$categories)->with('tags')->with('categories')->get();

        return response()->json(['posts'=>$posts],200);
        
    }

}
