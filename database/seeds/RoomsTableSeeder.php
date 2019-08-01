<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class RoomsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $timestamp = Carbon::now();

        DB::table('rooms')->insert([
            [
                'room_name' => 'Amsterdam',
                'status' => true,
                'maximum_capacity' => 10,
                'room_description' => 'amsterdam room',
                'created_by' => 1,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'room_name' => 'Koln',
                'status' => true,
                'maximum_capacity' => 20,
                'room_description' => 'koln room',
                'created_by' => 1,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'room_name' => 'Eindhoven',
                'status' => true,
                'maximum_capacity' => 3,
                'room_description' => 'Eindhoven room',
                'created_by' => 1,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'room_name' => 'Training Room',
                'status' => false,
                'maximum_capacity' => 5,
                'room_description' => 'training room',
                'created_by' => 1,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]
        ]);
    }
}
