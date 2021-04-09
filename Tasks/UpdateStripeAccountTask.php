<?php

namespace App\Containers\VendorSection\Stripe\Tasks;

use App\Containers\VendorSection\Stripe\Data\Repositories\StripeAccountRepository;
use App\Containers\VendorSection\Stripe\Models\StripeAccount;
use App\Ship\Exceptions\UpdateResourceFailedException;
use App\Ship\Parents\Tasks\Task;
use Exception;

class UpdateStripeAccountTask extends Task
{
    protected StripeAccountRepository $repository;

    public function __construct(StripeAccountRepository $repository)
    {
        $this->repository = $repository;
    }

    public function run(StripeAccount $account, array $data)
    {
        try {
            return $this->repository->update($data, $account->id);
        } catch (Exception $exception) {
            throw new UpdateResourceFailedException();
        }
    }
}
