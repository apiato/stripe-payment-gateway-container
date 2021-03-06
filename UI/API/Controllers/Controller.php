<?php

namespace App\Containers\Vendor\Stripe\UI\API\Controllers;

use App\Containers\Vendor\Stripe\Actions\CreateStripeAccountAction;
use App\Containers\Vendor\Stripe\Actions\UpdateStripeAccountAction;
use App\Containers\Vendor\Stripe\UI\API\Requests\CreateStripeAccountRequest;
use App\Containers\Vendor\Stripe\UI\API\Requests\UpdateStripeAccountRequest;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

class Controller extends ApiController
{
	public function createStripeAccount(CreateStripeAccountRequest $request): JsonResponse
	{
		$stripeAccount = app(CreateStripeAccountAction::class)->run($request);

		return $this->accepted([
			'message' => 'Stripe account created successfully.',
			'stripe_account_id' => $stripeAccount->id,
		]);
	}

	public function updateStripeAccount(UpdateStripeAccountRequest $request): JsonResponse
	{
		$stripeAccount = app(UpdateStripeAccountAction::class)->run($request);

		return $this->accepted([
			'message' => 'Stripe account updated successfully.',
			'stripe_account_id' => $stripeAccount->id,
		]);
	}
}
