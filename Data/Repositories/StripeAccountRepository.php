<?php

namespace App\Containers\Vendor\Stripe\Data\Repositories;

use App\Containers\Vendor\Stripe\Models\StripeAccount;
use App\Ship\Parents\Repositories\Repository;

class StripeAccountRepository extends Repository
{
    public function model(): string
    {
        return StripeAccount::class;
    }
}
