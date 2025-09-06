<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // Cross-DB safe way to handle FKs
        Schema::disableForeignKeyConstraints();

        // TRUNCATE is not supported the same way on SQLite, so handle both cases:
        if (DB::getDriverName() === 'sqlite') {
            DB::table('specs')->delete();
            DB::table('users')->delete();
            // reset autoincrement counters for SQLite
            DB::statement('DELETE FROM sqlite_sequence WHERE name IN ("specs","users")');
        } else {
            DB::table('specs')->truncate();
            DB::table('users')->truncate();
        }

        Schema::enableForeignKeyConstraints();

        // seed data
        $users = [
            ['name' => 'Gab',     'email' => 'gabriel@c-link.com',    'password' => Hash::make('secret'), 'city' => 'sd',      'state' => 'Rom'],
            ['name' => 'Mr. Big', 'email' => 'mr-big@mr-olympia.com', 'password' => Hash::make('secret'), 'city' => 'GF',      'state' => 'DA'],
            ['name' => 'Pikachu', 'email' => 'pika@pokemon.com',      'password' => Hash::make('secret'), 'city' => 'Pokecit', 'state' => 'Pokemonia'],
        ];

        DB::table('users')->insert($users);

        // build specs using inserted user IDs
        $userIds = DB::table('users')->pluck('id', 'email');
        $specs = [
            ['user_id' => $userIds['gabriel@c-link.com'],    'job' => 'Dev',     'salary' => 1234, 'description' => '...'],
            ['user_id' => $userIds['mr-big@mr-olympia.com'], 'job' => 'Coach',   'salary' => 2345, 'description' => '...'],
            ['user_id' => $userIds['pika@pokemon.com'],      'job' => 'Mascot',  'salary' => 3456, 'description' => '...'],
        ];

        DB::table('specs')->insert($specs);
    }
}
