<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class BookingTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $timestamp = Carbon::now();

        DB::table('booking_types')->insert([
            [
                'booking_type' => 'Standup Internal',
                'booking_type_key' => 'standup_internal',
                'booking_type_description' => 'standup internal',
                'status' => true,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'booking_type' => 'Standup With Client',
                'booking_type_key' => 'standup_with_client',
                'booking_type_description' => 'standup with client',
                'status' => true,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'booking_type' => 'Internal Meeting',
                'booking_type_key' => 'internal_meeting',
                'booking_type_description' => 'internal meeting',
                'status' => true,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'booking_type' => 'Client Call',
                'booking_type_key' => 'client_call',
                'booking_type_description' => 'client call',
                'status' => true,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'booking_type' => 'Other',
                'booking_type_key' => 'other',
                'booking_type_description' => 'other',
                'status' => true,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],

        ]);
    }

}
