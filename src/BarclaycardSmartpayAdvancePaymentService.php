<?php

namespace Cws\BarclaycardSmartpayAdvance;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response;

class BarclaycardSmartpayAdvancePaymentService
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

    /**
     * @param array $payment
     * @return string
     */
    public function charge(array $payment): string
    {
        $payment['callback_url'] = 'https://eaf3-3-9-124-172.ngrok-free.app/payment/handle';
        $options['address']['line_1'] = 'Logic House';
        $options['address']['postcode'] = 'GU51 3SB';
        $payment['options'] = $options;
        $auditTags = [];
        $response = $this->barclaycardSmartpayAdvance->beginWebPayment($payment, $auditTags);
        $redirectUrl = Arr::get($response, 'redirect_url');

        return $redirectUrl;
    }

    /**
     * @param array $payment
     * @param Request $request
     * @return Response|string
     */
    public function handle(array $payment, Request $request): Response
    {
        return '';
    }
}