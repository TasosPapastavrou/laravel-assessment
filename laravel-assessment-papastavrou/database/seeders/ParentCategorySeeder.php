<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ParentCategory; 
use App\Models\Category; 

class ParentCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        ParentCategory::create([
            'title' => "Gamming", 
        ]);

        ParentCategory::create([
            'title' => "Finance", 
        ]);
  
    }
}
