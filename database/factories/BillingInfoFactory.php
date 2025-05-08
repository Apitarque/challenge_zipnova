<?php

namespace Database\Factories;

use App\Models\BillingInfo;
use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;

class BillingInfoFactory extends Factory
{
    protected $model = BillingInfo::class;

    public function definition(): array
    {
        return [
            'tax_id' => $this->faker->numerify('##-########-#'),
            'company_name' => $this->faker->company,
            'address_id' => Address::factory(),
        ];
    }
}
