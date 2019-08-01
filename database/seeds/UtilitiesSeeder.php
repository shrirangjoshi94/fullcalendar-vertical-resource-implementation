<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class UtilitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $timestamp = Carbon::now();

        DB::table('utilities')->insert([
            [
                'utility_name' => 'Led',
                'utility_description' => 'led lights',
                'status' => true,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'utility_name' => 'Projector',
                'utility_description' => 'projector',
                'status' => true,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'utility_name' => 'Speaker',
                'utility_description' => 'Speaker',
                'status' => true,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'utility_name' => 'Is computer',
                'utility_description' => 'Is computer',
                'status' => true,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]
        ]);
    }
}
