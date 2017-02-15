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
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Area::class, function(Faker\Generator $faker){
	return [
		'name' => $faker->city,
		'parent' => $faker->boolean ? $faker->numberBetween(1,20) : null
	];
});

$factory->define(App\Donor::class, function(Faker\Generator $faker){
	$reg_no = $faker->numberBetween(1,28);
	$gender = $faker->randomElement(['male','female']);
	$reg_type = $faker->randomElement(['qa', 'qg', 'qpm']);
	$reg_batch = $faker->numberBetween(1,400);
	$reg_batch_suffix = $faker->boolean ? $faker->randomLetter() : null;

	// form the registration number
	$reg_complete = null;

	if($reg_type != 'qa')
	{
		if($reg_type == 'qg' && $gender == 'female')
		{
			$reg_complete .= 'F-';
		} 
		else if ($reg_type == 'qpm') 
		{
			$reg_complete .= 'M-';
		}
		$reg_complete .= $reg_no;
		$reg_complete .= '/' . $reg_batch;
		if(!is_null($reg_batch_suffix))
			$reg_complete .= '/' . strtoupper($reg_batch_suffix);
	}

	$available = $faker->boolean;



	return [
		'name' => $faker->name,
		'gender' => $gender,
		'religion' => $faker->randomElement(['islam', 'hindu', 'buddhist', 'christian']),
		'dob' => $faker->dateTimeBetween('-50 years','-19 years'),
		'reg_type' => $reg_type,
		'reg_no' => $reg_no,
		'reg_batch' => $reg_batch,
		'reg_batch_suffix' => $reg_batch_suffix,
		'reg_complete' => $reg_complete,
		'phone_primary' => '0' . $faker->numberBetween(1111111111,1999999999),
		'phone_secondary' => $faker->boolean ? '0' . $faker->numberBetween(1111111111,1999999999) . ',' . '0' . $faker->numberBetween(1111111111,1999999999) : null,
		'phone_request' => $faker->boolean ? '0' . $faker->numberBetween(1111111111,1999999999) : null,
		'can_donate' => $faker->boolean,
		'available' => $available,
		'unavailable_till' => !$available ? $faker->dateTimeBetween('+2 days' , '+2 years') : null,
		'ref_name' => $faker->name,
		'ref_phone' => $faker->boolean ? '0' . $faker->numberBetween(1111111111,1999999999) : null,
		'blood_group' => $faker->randomElement(['A+', 'A-', 'AB+', 'AB-', 'O+', 'O-']),
		'area_id' => $faker->numberBetween(1,20),
		'address' => $faker->address
	];
});