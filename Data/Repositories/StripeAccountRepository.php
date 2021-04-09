<?php

namespace App\Containers\VendorSection\Stripe\Data\Repositories;

use App\Containers\VendorSection\Stripe\Models\StripeAccount;
use App\Ship\Parents\Repositories\Repository;

class StripeAccountRepository extends Repository
{
    public function model(): string
    {
        return StripeAccount::class;
    }
}
