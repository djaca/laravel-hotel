<?php

namespace Tests\Unit;

use App\RoomType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RoomTypeTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_has_all_fields()
    {
        $attributes = [
            'name'        => $this->faker->words(2, true),
            'description' => $this->faker->sentence,
            'capacity'    => 2,
            'price'       => $this->faker->randomFloat(2)
        ];

        $type = factory(RoomType::class)->create($attributes);

        $this->assertEquals($attributes['name'], $type->name);
        $this->assertEquals($attributes['description'], $type->description);
        $this->assertEquals($attributes['capacity'], $type->capacity);
        $this->assertEquals($attributes['price'], $type->price);
    }
}
