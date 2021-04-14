<?php

namespace App\Containers\VendorSection\Stripe\Tests\Unit;

use App\Containers\VendorSection\Payment\Tasks\AssignPaymentAccountToUserTask;
use App\Containers\VendorSection\Payment\Traits\MockablePaymentsTrait;
use App\Containers\VendorSection\Stripe\Models\StripeAccount;
use App\Containers\VendorSection\Stripe\Tests\TestCase;

/**
 * Class ChargeUserWithStripeTest
 *
 * @group stripe
 * @group unit
 *
 * @author  Mahmoud Zalt  <mahmoud@zalt.me>
 */
class ChargeUserWithStripeTest extends TestCase
{
	use MockablePaymentsTrait;

	public function testChargeUserWithStripe(): void
	{
		$this->mockPayments();
		$user = $this->getTestingUser();
		$stripeAccount = StripeAccount::factory()->create([
			'customer_id' => 'cus_8mBD5S1SoyD4zL',
		]);
		$amount = 100;
		app(AssignPaymentAccountToUserTask::class)->run($stripeAccount, $user, 'nickname');

		$account = $user->paymentAccounts->first();
		$transaction = $user->charge($account, $amount);

		self::assertEquals($transaction->gateway, 'Stripe');
	}
}
