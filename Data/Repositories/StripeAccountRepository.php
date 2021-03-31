<?php

namespace App\Modules\Stripe\Data\Repositories;

use App\Modules\Stripe\Models\StripeAccount;
use App\Ship\Parents\Repositories\Repository;

class StripeAccountRepository extends Repository
{
    public function model(): string
    {
        return StripeAccount::class;
    }
}
