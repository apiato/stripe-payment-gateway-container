<?php

namespace App\Modules\Stripe\Tests\Unit;

use App\Modules\Payment\Tasks\AssignPaymentAccountToUserTask;
use App\Modules\Payment\Traits\MockablePaymentsTrait;
use App\Modules\Stripe\Models\StripeAccount;
use App\Modules\Stripe\Tests\TestCase;
use Illuminate\Support\Facades\App;

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
        App::make(AssignPaymentAccountToUserTask::class)->run($stripeAccount, $user, 'nickname');

        $account = $user->paymentAccounts->first();
        $transaction = $user->charge($account, $amount);

        self::assertEquals($transaction->gateway, 'Stripe');
    }
}
