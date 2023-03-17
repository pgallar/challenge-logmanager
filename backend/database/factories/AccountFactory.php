<?php

namespace Database\Factories;

use App\Models\Account;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccountFactory extends Factory
{
    protected $model = Account::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'client_id' => $this->faker->uuid,
            'client_secret' => $this->faker->uuid,
            'short_name' => $this->faker->word,
        ];
    }
}
