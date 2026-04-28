<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Role;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->word() . '_' . uniqid(),
            'description' => $this->faker->sentence(),
        ];
    }

    /**
     * Indicate that the role is an admin role.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function admin()
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'admin',
                'description' => 'Administrator role with full access',
            ];
        });
    }

    /**
     * Indicate that the role is a user role.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function user()
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'user',
                'description' => 'Regular user role with limited access',
            ];
        });
    }
}