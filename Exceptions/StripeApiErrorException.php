<?php

namespace App\Containers\Vendor\Stripe\Exceptions;

use App\Ship\Parents\Exceptions\Exception;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class StripeApiErrorException extends Exception
{
    protected $code = SymfonyResponse::HTTP_EXPECTATION_FAILED;
    protected $message = 'Stripe API error.';
}
