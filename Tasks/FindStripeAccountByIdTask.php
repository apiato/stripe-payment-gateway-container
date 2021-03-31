<?php

namespace App\Modules\Stripe\Tasks;

use App\Modules\Stripe\Data\Repositories\StripeAccountRepository;
use App\Ship\Exceptions\NotFoundException;
use App\Ship\Parents\Tasks\Task;
use Exception;

class FindStripeAccountByIdTask extends Task
{
    protected StripeAccountRepository $repository;

    public function __construct(StripeAccountRepository $repository)
    {
        $this->repository = $repository;
    }

    public function run($id)
    {
        try {
            return $this->repository->find($id);
        } catch (Exception $exception) {
            throw new NotFoundException();
        }
    }
}
