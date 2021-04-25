<?php

namespace App\Containers\Vendor\Stripe\Actions;

use App\Containers\AppSection\Authentication\Tasks\GetAuthenticatedUserTask;
use App\Containers\Vendor\Payment\Tasks\AssignPaymentAccountToUserTask;
use App\Containers\Vendor\Stripe\Tasks\CreateStripeAccountTask;
use App\Containers\Vendor\Stripe\UI\API\Requests\CreateStripeAccountRequest;
use App\Ship\Parents\Actions\Action;

class CreateStripeAccountAction extends Action
{
	public function run(CreateStripeAccountRequest $request)
	{
		$user = app(GetAuthenticatedUserTask::class)->run();

		$sanitizedData = $request->sanitizeInput([
			'customer_id',
			'card_id',
			'card_funding',
			'card_last_digits',
			'card_fingerprint',
			'nickname',
		]);

		$account = app(CreateStripeAccountTask::class)->run($sanitizedData);

		return app(AssignPaymentAccountToUserTask::class)->run($account, $user, $sanitizedData['nickname']);
	}
}
