<?php

namespace Tests\Feature\Models;

use App\Models\Genre;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class GenreTest extends TestCase
{
    use DatabaseMigrations;

    public function testList()
    {
        factory(Genre::class, 1)->create();

        $genres = Genre::all();

        $this->assertCount(1, $genres);
    }

    public function testAttributes()
    {
        factory(Genre::class, 1)->create();

        $genreKeys = array_keys(Genre::all()->first()->getAttributes());

        $this->assertEqualsCanonicalizing(
            [
                'id',
                'name',
                'is_active',
                'created_at',
                'updated_at',
                'deleted_at'
            ],
            $genreKeys
        );
    }

    public function testUuidIsValid()
    {
        $genre = Genre::create([
            'name' => 'Aventura'
        ]);

        $pattern = '/^([0-9]|[a-f]){8}-([0-9]|[a-f]){4}-([0-9]|[a-f]){4}-([0-9]|[a-f]){4}-([0-9]|[a-f]){12}$/i';
        $uuidIsValid = (bool) preg_match($pattern, $genre->id, $match);

        $this->assertTrue($uuidIsValid);
    }

    public function testIsActiveIsTrue()
    {
        $genre = Genre::create([
            'name' => 'Aventura'
        ]);
        
        $genre->refresh();    

        $this->assertTrue($genre->is_active);
    }

    public function testIsActiveIsFalse()
    {
        $genre = Genre::create([
            'name' => 'Aventura',
            'is_active' => false
        ]);

        $genre->refresh();

        $this->assertFalse($genre->is_active);
    }

    public function testUpdate()
    {
        $genre = factory(Genre::class)->create([
            'name' => 'Aventura',
            'is_active' => false
        ])->first();
        
        $data = ['name' => 'Aventura_test', 'is_active' => true];

        $genre->update($data);

        $this->assertEquals($data['name'], $genre->name);
        $this->assertEquals($data['is_active'], $genre->is_active);
    }

    public function testDelete()
    {
        $genre = factory(Genre::class, 1)->create()->first();

        $genre->delete();

        $this->assertNotNull($genre->deleted_at);
    }
}