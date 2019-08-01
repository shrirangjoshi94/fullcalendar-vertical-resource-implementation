<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $timestamp = Carbon::now();

        DB::table('users')->insert([
            [
                'username' => 'test-user-1',
                'email' => 'test-user-1@mailinator.com',
                'role_id' => 1,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'username' => 'pittest',
                'email' => 'pittest@easternenterprise.com',
                'role_id' => 1,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]
        ]);
    }
}
