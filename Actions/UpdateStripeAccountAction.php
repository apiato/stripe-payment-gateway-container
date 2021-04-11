<?php

namespace App\Containers\VendorSection\Stripe\Actions;

use Apiato\Core\Foundation\Facades\Apiato;
use App\Containers\AppSection\Authentication\Tasks\GetAuthenticatedUserTask;
use App\Containers\VendorSection\Payment\Tasks\CheckIfPaymentAccountBelongsToUserTask;
use App\Containers\VendorSection\Stripe\Models\StripeAccount;
use App\Containers\VendorSection\Stripe\Tasks\FindStripeAccountByIdTask;
use App\Containers\VendorSection\Stripe\Tasks\UpdateStripeAccountTask;
use App\Containers\VendorSection\Stripe\UI\API\Requests\UpdateStripeAccountRequest;
use App\Ship\Parents\Actions\Action;

class UpdateStripeAccountAction extends Action
{
	public function run(UpdateStripeAccountRequest $data): StripeAccount
	{
		$user = Apiato::call(GetAuthenticatedUserTask::class);

		// check, if this account does - in fact - belong to our user
		$account = Apiato::call(FindStripeAccountByIdTask::class, [$data->id]);
		$paymentAccount = $account->paymentAccount;
		Apiato::call(CheckIfPaymentAccountBelongsToUserTask::class, [$user, $paymentAccount]);

		// we own this account - so it is safe to update it
		$sanitizedData = $data->sanitizeInput([
			'customer_id',
			'card_id',
			'card_funding',
			'card_last_digits',
			'card_fingerprint',
		]);

		return Apiato::call(UpdateStripeAccountTask::class, [$account, $sanitizedData]);
	}
}
