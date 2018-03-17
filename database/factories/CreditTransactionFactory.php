<?php

use App\Payments\CreditTransaction;
use App\Payments\CreditTransactionType;
use App\Payments\Receipt;
use App\User;
use Faker\Generator as Faker;

$factory->define(CreditTransaction::class, function (Faker $faker) {
    return [
        'amount' => $faker->numberBetween(0, 100),
        'credit_transaction_type_id' => CreditTransactionType::all()->random()->id,
        'receipt_id' => factory(Receipt::class)->create()->id,
        'user_id' => factory(User::class)->create()->id
    ];
});
