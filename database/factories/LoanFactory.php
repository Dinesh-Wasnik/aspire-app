<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Loan;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Loan::class, function (Faker $faker) {
    return [
        'id' => $faker->unique()->randomDigit,
        'user_id' => User::inRandomOrder()->first()->id,
        'amount' => 100,
        'remaining_amount' => 100,
        'term' => 3, // password
        'installment' => (100/3)
    ];
});