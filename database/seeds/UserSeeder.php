<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')
            ->insert([
                'name' => 'Ariyani',
                'email' => 'bangjago@admin.com',
                'password' => '$2b$10$4Intp2hjK8MsHEPYlGN0gOgVtL9nr0fkCfAy97oJkx18w5hopMymm',
                'created_at' => Carbon\Carbon::now(),
                'updated_at' => Carbon\Carbon::now()
            ]);
    }
}
