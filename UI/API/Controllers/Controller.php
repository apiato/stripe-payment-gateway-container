<?php

namespace App\Containers\VendorSection\Stripe\UI\API\Controllers;

use Apiato\Core\Foundation\Facades\Apiato;
use App\Containers\VendorSection\Stripe\Actions\CreateStripeAccountAction;
use App\Containers\VendorSection\Stripe\Actions\UpdateStripeAccountAction;
use App\Containers\VendorSection\Stripe\UI\API\Requests\CreateStripeAccountRequest;
use App\Containers\VendorSection\Stripe\UI\API\Requests\UpdateStripeAccountRequest;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

class Controller extends ApiController
{
	public function createStripeAccount(CreateStripeAccountRequest $request): JsonResponse
	{
		$stripeAccount = Apiato::call(CreateStripeAccountAction::class, [$request]);

		return $this->accepted([
			'message' => 'Stripe account created successfully.',
			'stripe_account_id' => $stripeAccount->id,
		]);
	}

	public function updateStripeAccount(UpdateStripeAccountRequest $request): JsonResponse
	{
		$stripeAccount = Apiato::call(UpdateStripeAccountAction::class, [$request]);

		return $this->accepted([
			'message' => 'Stripe account updated successfully.',
			'stripe_account_id' => $stripeAccount->id,
		]);
	}
}
