<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Crimeregister;
use Faker\Generator as Faker;

$factory->define(Crimeregister::class, function (Faker $faker) {
    return [
        'type' => $faker->randomElement(['शरीरा विरुद्ध', 'माला विरुद्ध', 'महिलांविरुद्ध', 'अपघात', 'इतर अपराध']),
        'registernumber' => $faker->numerify('######'),
        'date' => $faker->dateTimeBetween('-150 days', '+0 days'),
        'ppid' => $faker->randomElement([1, 2]),
        'psid' => 1,
    ];
});
