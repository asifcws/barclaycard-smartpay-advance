<?php

namespace Cws\BarclaycardSmartpayAdvance;
use Cws\Payments\Contracts\SubscribeContract;
use Cws\Payments\Events\SubscribeEvent;
use Cws\Payments\Models\Payment;
use Illuminate\Support\Arr;

class BarclaySmartpayAdvanceSubscriptionService implements SubscribeContract
{
    /**
     * @var BarclaycardSmartpayAdvance
     */
    protected BarclaycardSmartpayAdvance $barclaycardSmartpayAdvance;

    /**
     * @param BarclaycardSmartpayAdvance $barclaycardSmartpayAdvance
     */
    public function __construct(BarclaycardSmartpayAdvance $barclaycardSmartpayAdvance)
    {
        $this->barclaycardSmartpayAdvance = $barclaycardSmartpayAdvance;
    }

    public function debit(Payment $payment): bool
    {
        $request = $payment->toArray();
        $response = $this->barclaycardSmartpayAdvance->beginWebPayment($request);
        $isPaid = $this->handlePaymentResponseSetIsPaid($response);
        $payment->transaction_id = Arr::get($response, 'transaction_reference');
        $payment->is_paid = $isPaid;
        $payment->response = $response;
        $payment->save();

        event($event = new SubscribeEvent($payment));

        return $isPaid;
    }

    /**
     * @param array $response
     * @return bool
     */
    protected function handlePaymentResponseSetIsPaid(array $response): bool
    {
        $authType = Arr::get($response, 'auth_type');

        if ($authType == 'AuthOnly') {
            return Arr::get($response, 'status') == 'AUTHORISED';
        } elseif ($authType == 'AuthAndSettle') {
            return Arr::get($response, 'status') == 'CAPTURED';
        }

        return false;
    }
}