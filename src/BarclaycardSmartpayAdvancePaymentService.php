<?php

namespace Cws\BarclaycardSmartpayAdvance;

use Cws\BarclaycardSmartpayAdvance\Events\PaymentEvent;
use Cws\Payments\Contracts\PaymentContract;
use Cws\Payments\Models\Payment;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response;

class BarclaycardSmartpayAdvancePaymentService implements PaymentContract
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
     * @param Payment $payment
     * @return string
     * @throws Exception
     */
    public function charge(Payment $payment): string
    {
        $request = $this->buildPaymentRequest($payment);
        $response = $this->barclaycardSmartpayAdvance->beginWebPayment($request);
        $payment->transaction_id = Arr::get($response, 'transaction_reference');
        $payment->options = array_merge($payment->options, ['begin_web_payment_response' => $response]);
        $payment->save();

        return Arr::get($response, 'redirect_url');
    }

    /**
     * @param Payment $payment
     * @param Request $request
     * @return Response|string
     * @throws Exception
     */
    public function handle(Payment $payment, Request $request): Response|string
    {
        $response = $this->barclaycardSmartpayAdvance->getWebPayment(
            $this->buildGetWebPaymentRequest($payment)
        );

        $payment->is_paid = $this->handlePaymentResponseSetIsPaid($response);
        $payment->response = $response;
        $payment->save();

        event($event = new PaymentEvent($payment));

        if (!$event->redirectUrl) {
            throw new Exception('No redirect URL specified for Payment #'.optional($event->payment)->id);
        }

        return redirect($event->redirectUrl);
    }

    /**
     * @param Payment $payment
     * @return array
     */
    protected function buildPaymentRequest(Payment $payment): array
    {
        $request = $payment->toArray();
        $request['callback_url'] = route('payments.callback', ['payment' => $payment]);

        if(config('barclaycard-smartpay-advance.callback_host')) {
            $url = rtrim(config('barclaycard-smartpay-advance.callback_host'),"/");
            $request['callback_url'] = url($url).route('payments.callback', ['payment' => $payment], false);
        }

        return $request;
    }

    /**
     * @param Payment $payment
     * @return array
     */
    protected function buildGetWebPaymentRequest(Payment $payment): array
    {
        $options = $payment->options;
        $storeId = Arr::get($options, 'begin_web_payment_response.store_id');
        $transactionNo = $payment->reference;
        $transactionReference = $payment->transaction_id;

        return [
            'store_id' => $storeId,
            'transaction_no' => $transactionNo,
            'transaction_reference' => $transactionReference,
        ];
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