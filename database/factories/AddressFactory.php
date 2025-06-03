<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
   protected $model = Address::class;

   private $indianCities = [
    ['city' => 'Mumbai', 'state' => 'Maharashtra'],
    ['city' => 'Delhi', 'state' => 'Delhi'],
    ['city' => 'Banglore', 'state' => 'Karnataka'],
    ['city' => 'Hydrabad', 'state' => 'Telangana'],
    ['city' => 'Ahmdabad', 'state' => 'Gujarat'],
    ['city' => 'Chennai', 'state' => 'Tamil Nadu'],
    ['city' => 'Kotkata', 'state' => 'West Bengal'],
    ['city' => 'Pune', 'state' => 'Maharashtra'],
    ['city' => 'Jaipur', 'state' => 'Rajsthan'],
    ['city' => 'Surat', 'state' => 'Gujarat'],
    ['city' => 'Lucknow', 'state' => 'Uttar Pradesh'],
    ['city' => 'Kanpur', 'state' => 'Uttar Pradesh'],
    ['city' => 'Nagpur', 'state' => 'Maharashtra'],
    ['city' => 'Indore', 'state' => 'Madhya Pradesh'],
    ['city' => 'Bhopal', 'state' => 'Madhya Pradesh'],
    ['city' => 'Visakhapatnam', 'state' => 'Andhra Pradesh'],
    ['city' => 'Vadodara', 'state' => 'Gujarat'],
    ['city' => 'Coimbtore', 'state' => 'Tamil Nadu'],
    ['city' => 'Rajkot', 'state' => 'Gujarat'],
    ['city' => 'Kochi', 'state' => 'Kerala'],
   ];

    public function definition()
    {
        $location = $this->faker->randomElement($this->indianCities);
        return [
            'user_id' => User::factory(),
            'address' => $this->faker->streetAddress() . ', ' . $this->faker->secondaryAddress(),
            'city' => $location['city'],
            'state' => $location['state'],
            'type' => $this->faker->randomElement(['Home', 'Office']),
        ];
    }

    public function forCity($city, $state){
        return $this->state(function (array $attributes) use ($city, $state){
            return [
                'city' => $city,
                'state' => $state,
            ];
        });
    }

    public function home(){
        return $this->state(function (array $attributes){
            return [
                'type' => 'Home',
            ];
        });
    }

    public function office(){
        return $this->state(function (array $attributes){
            return [
                'type' => 'Office',
            ];
        });
    }
}