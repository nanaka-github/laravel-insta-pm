<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category; //this represents the categories table

class CategorySeeder extends Seeder
{

    private $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        //make sure there is no DUPLICATE name inthe categories table
        $categories = [
            [
                'name' => 'OOP Programming',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Database Administration',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Laravel Framework',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'TestCategory1',
                'created_at' => now(),
                'updated_at' => now()
            ]
            // you can add any name of categories  as mach as you can
        ];

        $this->category->insert($categories); // insert($categories) = line26

    }
}
