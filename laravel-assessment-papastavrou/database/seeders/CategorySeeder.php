<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;  

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $newPost = new Category();
        $newPost->title = "PlayStation"; 
        $newPost->parent_categories_id = 1; 
        $newPost->save(); 

        $newPost = new Category();
        $newPost->title = "Xbox"; 
        $newPost->parent_categories_id = 1; 
        $newPost->save(); 


        $newPost = new Category();
        $newPost->title = "Crysis"; 
        $newPost->parent_categories_id = 2; 
        $newPost->save(); 
    }
}
