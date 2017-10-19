<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name'           => $faker->name,
        'email'          => $faker->unique()->safeEmail,
        'password'       => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Game::class, function (Faker\Generator $faker) {
    return [
        'player1id'      => $faker->numberBetween($min = 1, $max = 5),
        'player1tag'     => $faker->randomLetter,
        'gametype'       => App\Util\Constants::GAME_TYPE_NORMAL,
    ];
});

$factory->define(App\Field::class, function (Faker\Generator $faker) {
    return [
        'gameid'        => $faker->numberBetween($min = 1, $max = 5),
        'position1'     => App\Util\Constants::GAME_INPUT_NONE,
        'position2'     => App\Util\Constants::GAME_INPUT_NONE,
        'position3'     => App\Util\Constants::GAME_INPUT_NONE,
        'position4'     => App\Util\Constants::GAME_INPUT_NONE,
        'position5'     => App\Util\Constants::GAME_INPUT_NONE,
        'position6'     => App\Util\Constants::GAME_INPUT_NONE,
        'position7'     => App\Util\Constants::GAME_INPUT_NONE,
        'position8'     => App\Util\Constants::GAME_INPUT_NONE,
        'position9'     => App\Util\Constants::GAME_INPUT_NONE,
    ];
});
