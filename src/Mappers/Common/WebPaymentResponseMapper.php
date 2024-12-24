<?php

namespace Cws\BarclaycardSmartpayAdvance\Mappers\Common;

use Cws\BarclaycardSmartpayAdvance\Contracts\MapperService;
use Cws\BarclaycardSmartpayAdvance\Mappers\Mapper;
use Illuminate\Support\Arr;

class WebPaymentResponseMapper  extends Mapper
{
    /**
     * @param array $response
     * @return array
     */
    public function parse(array $response): array
    {
        $response = Arr::get($response, 'return');

        return [
            'acquirer' => $this->parseAcquirer($response),
            'auth_type' => $this->parseAuthType($response),
            'authentication_request' => $this->parseAuthenticationRequest($response),
            'authorisation_response' => $this->parseAuthorisationResponse($response),
            'bank' => $this->parseBank($response),
            'billing_address' => $this->parseBillingAddress($response),
            'browser' => $this->parseBrowser($response),
            'card' => $this->parseCard($response),
            'card_response' => $this->parseCardResponse($response),
            'cardholder_name' => $this->parseCardholderName($response),
            'config_params' => $this->parseConfigParams($response),
            'currency_code' => $this->parseCurrencyCode($response),
            'cv_response' => $this->parseCvResponse($response),
            'environment' => $this->parseEnvironment($response),
            'purchase_amount' => $this->parsePurchaseAmount($response),
            'purchase_description' => $this->parsePurchaseDescription($response),
            'redirect_url' => $this->parseRedirectUrl($response),
            'requester' => $this->parseRequester($response),
            'responder' => $this->parseResponder($response),
            'response' => $this->parseResponse($response),
            'scheme_reference_data' => $this->parseSchemeReferenceData($response),
            'status' => $this->parseStatus($response),
            'store_id' => $this->parseStoreId($response),
            'store_result_page' => $this->parseStoreResultPage($response),
            'total_amount' => $this->parseTotalAmount($response),
            'transaction_reference' => $this->parseTransactionReference($response),
            'valid_card_types' => $this->parseValidCardTypes($response),
            'valid_payment' => $this->parseValidPayment($response),
        ];
    }

    /**
     * @param array $response
     * @return array|null
     */
    protected function parseAcquirer(array $response): ?array
    {
        return [
            'id' => Arr::get($response, 'acquirer.ID'),
            'name' => Arr::get($response, 'acquirer.name'),
        ];
    }

    /**
     * @param array $response
     * @return string|null
     */
    protected function parseAuthType(array $response): ?string
    {
        return Arr::get($response, 'authType');
    }

    /**
     * @param array $response
     * @return string|null
     */
    protected function parseAuthenticationRequest(array $response): ?string
    {
        return Arr::get($response, 'authenticationRequest.challengePreference');
    }

    /**
     * @param array $response
     * @return array|null
     */
    protected function parseAuthorisationResponse(array $response): ?array
    {
        return [
            'auth_code' => Arr::get($response, 'authorisationResponse.authcode'),
            'host_response_code' => Arr::get($response, 'authorisationResponse.hostResponseCode'),
            'host_response_essage' => Arr::get($response, 'authorisationResponse.hostResponseMessage'),
            'merchant_no' => Arr::get($response, 'authorisationResponse.merchantNo'),
            'payment_method' => Arr::get($response, 'authorisationResponse.paymentMethod'),
            'is_soft_declined' => Arr::get($response, 'authorisationResponse.softDeclined'),
            'tid' => Arr::get($response, 'authorisationResponse.tid'),
        ];
    }

    /**
     * @param array $response
     * @return array|null
     */
    protected function parseBank(array $response): ?array
    {
        return [
            'id' => Arr::get($response, 'bank.ID'),
            'name' => Arr::get($response, 'bank.name'),
        ];
    }

    /**
     * @param array $response
     * @return array|null
     */
    protected function parseBillingAddress(array $response): ?array
    {
        return [
            'line_l' => Arr::get($response, 'billingAddress.line1'),
            'line_2' => Arr::get($response, 'billingAddress.line2'),
            'postcode' => Arr::get($response, 'billingAddress.postcode'),
        ];
    }

    /**
     * @param array $response
     * @return array|null
     */
    protected function parseBrowser(array $response): ?array
    {
        return [
            'accept' => Arr::get($response, 'browser.accept'),
            'is_java_enabled' => Arr::get($response, 'browser.javaEnabled'),
            'is_javascript_enabled' => Arr::get($response, 'browser.javaScriptEnabled'),
            'language' => Arr::get($response, 'browser.language'),
            'remote_address' => Arr::get($response, 'browser.remoteAddress'),
            'screen_color_depth' => Arr::get($response, 'browser.screenColorDepth'),
            'screen_resolution' => Arr::get($response, 'browser.screenResolution'),
            'timezone' => Arr::get($response, 'browser.timezone'),
            'user_agent' => Arr::get($response, 'browser.userAgent'),
        ];
    }

    /**
     * @param array $response
     * @return array|null
     */
    protected function parseCard(array $response): ?array
    {
        return [
            'source' => Arr::get($response, 'card.source'),
            'masked_pan' => Arr::get($response, 'card.maskedPAN'),
            'online_token' => Arr::get($response, 'card.onlineToken'),
            'expiry' => Arr::get($response, 'card.expiry'),
            'card_type' => Arr::get($response, 'card.cardType'),
            'storage_indicator' => Arr::get($response, 'card.storageIndicator'),
        ];
    }

    /**
     * @param array $response
     * @return string|null
     */
    protected function parseCardResponse(array $response): ?string
    {
        return Arr::get($response, 'response');
    }

    /**
     * @param array $response
     * @return string|null
     */
    protected function parseCardholderName(array $response): ?string
    {
        return Arr::get($response, 'cardholderName');
    }

    /**
     * @param array $response
     * @return array|null
     */
    protected function parseConfigParams(array $response): ?array
    {
        return [
            'is_display_basket_details' => Arr::get($response, 'configParams.displayBasketDetails'),
            'merchant_states' => Arr::get($response, 'configParams.merchantStates'),
            'store_id' => Arr::get($response, 'card.storeID'),
        ];
    }

    /**
     * @param array $response
     * @return string|null
     */
    protected function parseCurrencyCode(array $response): ?string
    {
        return Arr::get($response, 'currencyCode');
    }

    /**
     * @param array $response
     * @return array|null
     */
    protected function parseCvResponse(array $response): ?array
    {
        return [
            'raw_cv2' => Arr::get($response, 'cvResponse.rawCv2'),
            'raw_address' => Arr::get($response, 'cvResponse.rawAddress'),
            'raw_postcode' => Arr::get($response, 'cvResponse.rawPostcode'),
            'auth_entity' => Arr::get($response, 'cvResponse.authEntity'),
            'mapped_cv2' => Arr::get($response, 'cvResponse.mappedCV2'),
            'mapped_address' => Arr::get($response, 'cvResponse.mappedAddress'),
            'mapped_postcode' => Arr::get($response, 'cvResponse.mappedPostcode'),
            'result' => Arr::get($response, 'cvResponse.result'),
        ];
    }

    /**
     * @param array $response
     * @return string|null
     */
    protected function parseEnvironment(array $response): ?string
    {
        return Arr::get($response, 'environment');
    }

    /**
     * @param array $response
     * @return string|null
     */
    protected function parsePurchaseAmount(array $response): ?string
    {
        return (float) Arr::get($response, 'purchaseAmount');
    }

    /**
     * @param array $response
     * @return string|null
     */
    protected function parsePurchaseDescription(array $response): ?string
    {
        return Arr::get($response, 'purchaseDescription');
    }

    /**
     * @param array $response
     * @return string|null
     */
    protected function parseRedirectUrl(array $response): ?string
    {
        $redirectUrl = Arr::get($response, 'redirectURL');

        if (!$redirectUrl && Arr::get($response, 'card.storageIndicator') == 'subsequent') {
            $redirectUrl = Arr::get($response, 'storeResultPage');
        }

        return $redirectUrl;
    }

    /**
     * @param array $response
     * @return array|null
     */
    protected function parseRequester(array $response): ?array
    {
        return [
            'enterprise_id' => Arr::get($response, 'requester.enterpriseID'),
            'client_id' => Arr::get($response, 'requester.clientID'),
            'transaction_no' => Arr::get($response, 'requester.transNo'),
            'version' => Arr::get($response, 'requester.version'),
            'original_version' => Arr::get($response, 'requester.originalVersion'),
        ];
    }

    /**
     * @param array $response
     * @return array|null
     */
    protected function parseResponder(array $response): ?array
    {
        return [
            'name' => Arr::get($response, 'responder.name'),
            'version' => Arr::get($response, 'responder.version'),
            'release_date' => Arr::get($response, 'responder.releaseDate'),
            'id' => Arr::get($response, 'responder.id'),
        ];
    }

    /**
     * @param array $response
     * @return string|null
     */
    protected function parseResponse(array $response): ?string
    {
        return Arr::get($response, 'response');
    }

    /**
     * @param array $response
     * @return string|null
     */
    protected function parseSchemeReferenceData(array $response): ?string
    {
        return Arr::get($response, 'schemeReferenceData');
    }

    /**
     * @param array $response
     * @return string|null
     */
    protected function parseStatus(array $response): ?string
    {
        return Arr::get($response, 'status');
    }

    /**
     * @param array $response
     * @return string|null
     */
    protected function parseStoreId(array $response): ?string
    {
        return Arr::get($response, 'configParams.storeID');
    }

    /**
     * @param array $response
     * @return string|null
     */
    protected function parseStoreResultPage(array $response): ?string
    {
        return Arr::get($response, 'storeResultPage');
    }

    /**
     * @param array $response
     * @return string|null
     */
    protected function parseTotalAmount(array $response): ?string
    {
        return (float) Arr::get($response, 'totalAmount');
    }

    /**
     * @param array $response
     * @return string|null
     */
    protected function parseTransactionReference(array $response): ?string
    {
        return Arr::get($response, 'transactionReference');
    }

    /**
     * @param array $response
     * @return array|null
     */
    protected function parseValidCardTypes(array $response): ?array
    {
        return Arr::get($response, 'validCardTypes');
    }

    /**
     * @param array $response
     * @return string|null
     */
    protected function parseValidPayment(array $response): ?string
    {
        return Arr::get($response, 'validPayment');
    }
}