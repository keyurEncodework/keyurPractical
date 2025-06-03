<?php

namespace Database\Factories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->firstName(),
            'email' => $this->faker->unique()->safeEmail(),
            'mobile_number' => $this->faker->unique()->numerify('##########'),
            'birth_date' => $this->faker->dateTimeBetween('-60 years', '-18 years')->format('Y-m-d'),
        ];
    }

    public function withAge($minAge, $maxAge){
        return $this->state(function(array $attributes) use ($minAge, $maxAge){
            $age = $this->faker->numberBetween($minAge, $maxAge);
            return ['birth_date' => Carbon::now()->subYears($age)->subDays(rand(0,365))->format('Y-m-d')];
        });
    }

    public function fromCity($city, $state){
        return $this->afterCreating(function (User $user) use ($city, $state){
            $user->addresses()->create([
                'address' => $this->faker->streetAddress(),
                'pin_code' => $this->faker->numerify('######'),
                'city' => $city,
                'state' => $state,
                'type' => $this->faker->randomElement(['Home', 'Office']),

            ]);
        });
    }
}
