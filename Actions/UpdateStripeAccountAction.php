<?php

namespace App\Containers\Vendor\Stripe\Actions;

use App\Containers\AppSection\Authentication\Tasks\GetAuthenticatedUserTask;
use App\Containers\Vendor\Payment\Tasks\CheckIfPaymentAccountBelongsToUserTask;
use App\Containers\Vendor\Stripe\Models\StripeAccount;
use App\Containers\Vendor\Stripe\Tasks\FindStripeAccountByIdTask;
use App\Containers\Vendor\Stripe\Tasks\UpdateStripeAccountTask;
use App\Containers\Vendor\Stripe\UI\API\Requests\UpdateStripeAccountRequest;
use App\Ship\Parents\Actions\Action;

class UpdateStripeAccountAction extends Action
{
	public function run(UpdateStripeAccountRequest $request): StripeAccount
	{
		$user = app(GetAuthenticatedUserTask::class)->run();

		// check, if this account does - in fact - belong to our user
		$account = app(FindStripeAccountByIdTask::class)->run($request->id);
		$paymentAccount = $account->paymentAccount;
		app(CheckIfPaymentAccountBelongsToUserTask::class)->run($user, $paymentAccount);

		// we own this account - so it is safe to update it
		$sanitizedData = $request->sanitizeInput([
			'customer_id',
			'card_id',
			'card_funding',
			'card_last_digits',
			'card_fingerprint',
		]);

		return app(UpdateStripeAccountTask::class)->run($account, $sanitizedData);
	}
}
