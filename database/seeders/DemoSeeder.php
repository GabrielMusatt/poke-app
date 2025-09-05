<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks temporarily
        // (needed to truncate tables that may be linked by foreign keys)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Empty (truncate) the "specs" and "users" tables
        // Truncate removes all rows and resets auto-increment IDs
        DB::table('specs')->truncate(); 
        DB::table('users')->truncate(); 

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Example user data (array of users)
       $users = [
            [
                'name'     => 'Gab',
                'email'    => 'gabriel@c-link.com',
                'password' => Hash::make('secret'), // Hash password before saving
                'city'     => 'sd',
                'state'    => 'Rom'
            ],
            [
                'name'     => 'Mr. Big',
                'email'    => 'mr-big@mr-olympia.com',
                'password' => Hash::make('secret'),
                'city'     => 'GF',
                'state'    => 'DA'
            ],
            [
                'name'     => 'Pikachu',
                'email'    => 'pika@pokemon.com',
                'password' => Hash::make('secret'),
                'city'     => 'Pokecit',
                'state'    => 'Pokemonia'
            ],
            [
                'name'     => 'Ala',
                'email'    => 'dsadas@fasfa.com',
                'password' => Hash::make('secret'),
                'city'     => 'Ct',
                'state'    => 'MA'
            ],
        ];


        //Insert each user into DB and store their IDs
        $ids = [];
        foreach ($users as $u) {
            $ids[] = DB::table('users')->insertGetId($u);
        }

        //Insert related "specs" records for each user
       DB::table('specs')->insert([
            [
                'user_id'     => $ids[0],
                'job'         => 'Best employer ever',
                'salary'      => 10000000,
                'description' => "What can I say? The best"
            ],
            [
                'user_id'     => $ids[1], 
                'job'         => 'Body Builder',
                'salary'      => 15000000,
                'description' => "Build bodies. He's cool."
            ],
            [
                'user_id'     => $ids[2], // Pikachu ID
                'job'         => 'Laying around, staying in a ball',
                'salary'      => 0,
                'description' => "Digital animal Slepping in his ball"
            ],
            [
                'user_id'     => $ids[3], 
                'job'         => 'Chicken',
                'salary'      => 1,
                'description' => "cod-co-dac pac-pac-pac"
            ],
        ]);
    }
}
