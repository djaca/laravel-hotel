<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        factory(\App\RoomType::class, 4)->create()->each(function ($type) {
            factory(\App\Room::class, 2)->create(['type_id' => $type->id]);
        });

        \App\Room::find(1)->update(['available' => false]);

        factory(\App\Reservation::class)->create([
            'start_date' => now()->subDays(2)->toDateString(),
            'end_date' => now()->toDateString(),
            'created_at' => now()->subDays(3),
            'updated_at' => now()->subDays(3),
        ])->rooms()->attach(8);

        factory(\App\Reservation::class)->create([
            'start_date' => now()->toDateString(),
            'end_date'   => now()->addDays(3)->toDateString(),
            'created_at' => now()->subDays(3),
            'updated_at' => now()->subDays(3),
        ])->rooms()->attach(1);

        factory(\App\Reservation::class)->create([
            'start_date' => now()->toDateString(),
            'end_date'   => now()->addDays(3)->toDateString(),
        ])->rooms()->attach([2, 3, 4, 5]);

        factory(\App\Reservation::class)->create([
            'start_date' => now()->toDateString(),
            'end_date'   => now()->addDays(3)->toDateString(),
        ])->rooms()->attach([6, 7]);

        factory(\App\Guest::class, 8)->create();
    }
}
