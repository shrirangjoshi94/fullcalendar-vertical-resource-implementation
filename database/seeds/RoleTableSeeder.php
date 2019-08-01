<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $timestamp = Carbon::now();
        DB::table('roles')->insert([
            [
                'display_name' => 'Admin',
                'slug' => 'admin',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'display_name' => 'Project Manager',
                'slug' => 'project_manager',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'display_name' => 'Developer',
                'slug' => 'developer',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'display_name' => 'Other Users',
                'slug' => 'other_user',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]
        ]);
    }
}
