<?php

namespace App\Containers\VendorSection\Stripe\Actions;

use Apiato\Core\Foundation\Facades\Apiato;
use App\Containers\AppSection\Authentication\Tasks\GetAuthenticatedUserTask;
use App\Containers\VendorSection\Payment\Tasks\AssignPaymentAccountToUserTask;
use App\Containers\VendorSection\Stripe\Tasks\CreateStripeAccountTask;
use App\Containers\VendorSection\Stripe\UI\API\Requests\CreateStripeAccountRequest;
use App\Ship\Parents\Actions\Action;

class CreateStripeAccountAction extends Action
{
	public function run(CreateStripeAccountRequest $data)
	{
		$user = Apiato::call(GetAuthenticatedUserTask::class);

		$sanitizedData = $data->sanitizeInput([
			'customer_id',
			'card_id',
			'card_funding',
			'card_last_digits',
			'card_fingerprint',
			'nickname',
		]);

		$account = Apiato::call(CreateStripeAccountTask::class, [$sanitizedData]);

		return Apiato::call(AssignPaymentAccountToUserTask::class, [$account, $user, $sanitizedData['nickname']]);
	}
}
