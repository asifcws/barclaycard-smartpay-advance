<?php

namespace Cws\BarclaycardSmartpayAdvance\Events;

use Cws\Payments\Models\Payment;

class PaymentEvent
{
    /**
     * @var Payment
     */
    public $payment;

    /**
     * @var string|null
     */
    public $redirectUrl;

    /**
     * PaymentCompletedEvent constructor.
     *
     * @param Payment $payment
     */
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
        $this->redirectUrl;
    }
}