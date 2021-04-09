<?php

namespace App\Containers\VendorSection\Stripe\Tasks;

use App\Containers\VendorSection\StripePayment\Contracts\ChargeableInterface;
use App\Containers\VendorSection\StripePayment\Contracts\PaymentChargerInterface;
use App\Containers\VendorSection\StripePayment\Models\AbstractPaymentAccount;
use App\Containers\VendorSection\StripePayment\Models\PaymentTransaction;
use App\Containers\VendorSection\Stripe\Exceptions\StripeAccountNotFoundException;
use App\Containers\VendorSection\Stripe\Exceptions\StripeApiErrorException;
use App\Ship\Parents\Tasks\Task;
use Cartalyst\Stripe\Stripe;
use Exception;
use Illuminate\Support\Facades\Config;

class ChargeWithStripeTask extends Task implements PaymentChargerInterface
{
    private Stripe $stripe;

    public function __construct(Stripe $stripe)
    {
        $this->stripe = $stripe->make(Config::get('settings.stripe.secret'), Config::get('settings.stripe.version'));
    }

    public function charge(ChargeableInterface $user, AbstractPaymentAccount $account, $amount, $currency = 'USD'): PaymentTransaction
    {
        // NOTE: you should not call this function directly. Instead use the Payment Gateway in the Payment container.
        // Or even better to use the charge function in the ChargeableTrait.

        $valid = $account->checkIfPaymentDataIsSet(['customer_id', 'card_id', 'card_funding', 'card_last_digits', 'card_fingerprint']);

        if ($valid == false) {
            throw new StripeAccountNotFoundException('We could not find your credit card information.
            For security reasons, we do not store your credit card information on our server.
            So please login to our Web App and enter your credit card information directly into Stripe,
            then try to purchase the credits again.
            Thanks.');
        }

        try {
            $response = $this->stripe->charges()->create([
                'customer' => $account->customer_id,
                'currency' => $currency,
                'amount' => $amount,
            ]);

        } catch (Exception $e) {
            throw (new StripeApiErrorException('Stripe API error (chargeCustomer)'))->debug($e->getMessage(), true);
        }

        if ($response['status'] != 'succeeded') {
            throw new StripeApiErrorException('Stripe response status not succeeded (chargeCustomer)');
        }

        if ($response['paid'] !== true) {
            throw new StripeApiErrorException('Payment was not set to PAID.');
        }

        // this data will be stored on the pivot table (user credits)
        $transaction = new PaymentTransaction([
            'transaction_id' => $response['id'],
            'status' => $response['status'],
            'is_successful' => true,
            'data' => $response,
        ]);

        return $transaction;
    }
}
