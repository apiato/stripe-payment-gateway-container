<?php

namespace App\Containers\VendorSection\StripeStripe\Data\Repositories;

use App\Containers\VendorSection\StripeStripe\Models\StripeAccount;
use App\Ship\Parents\Repositories\Repository;

class StripeAccountRepository extends Repository
{
    public function model(): string
    {
        return StripeAccount::class;
    }
}
