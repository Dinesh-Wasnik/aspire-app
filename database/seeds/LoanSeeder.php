<?php

use Illuminate\Database\Seeder;

class LoanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('loans')->insert([
                [
                    'id' => '1',
                    'user_id' => '1',
                    'amount' => 10,
                    'term' => '3',
                    'is_approved' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => '2',
                    'user_id' => '2',
                    'amount' => 20,
                    'term' => '3',
                    'is_approved' => 0,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => '3',
                    'user_id' => '2',
                    'amount' => 200,
                    'term' => '9',
                    'is_approved' => null,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]                
            ]);
    }
}
