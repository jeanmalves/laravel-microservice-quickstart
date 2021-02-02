<?php

namespace Tests\Feature\Models;

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use DatabaseMigrations;

    public function testList()
    {
        factory(Category::class, 1)->create();

        $categories = Category::all();

        $this->assertCount(1, $categories);
        
    }

    public function testAttributes()
    {
        factory(Category::class, 1)->create();

        $categoryKeys = array_keys(Category::all()->first()->getAttributes());

        $this->assertEqualsCanonicalizing(
            [
                'id',
                'name',
                'description',
                'is_active',
                'created_at',
                'updated_at',
                'deleted_at'
            ],
            $categoryKeys
        );
    }

    public function testDescriptionAttributeIsNull()
    {
        $category = Category::create([
            'name' => 'Ficção'
        ]);
        
        $this->assertNull($category->description);

    }

    public function testIsActiveIsTrue()
    {
        $category = Category::create([
            'name' => 'Ficção'
        ]);
        
        $category->refresh();    

        $this->assertTrue($category->is_active);
    }

    public function testIsActiveIsFalse()
    {
        $category = Category::create([
            'name' => 'Ficção',
            'is_active' => false
        ]); 

        $this->assertFalse($category->is_active);
    }

    public function testUpdate()
    {
        $category = factory(Category::class)->create([
            'name' => 'name_test',
            'description' => 'description_test',
            'is_active' => false
        ])->first();
        
        $data = [
            'name' => 'name_update_test',
            'description' => 'description_update_test',
            'is_active' => true
        ];

        $category->update($data);

        foreach($data as $key => $value) {
            $this->assertEquals($value, $category->{$key});
        }
    }
}
