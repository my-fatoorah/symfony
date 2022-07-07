<?php

namespace MyFatoorah\SymfonyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use MyFatoorah\Library\PaymentMyfatoorahApiV2;

class InvoiceController extends AbstractController {

    public $mfObj;

    public const VERSION = '2.0.0';

//-----------------------------------------------------------------------------------------------------------------------------------------

    /**
     * create MyFatoorah object
     */
    public function __construct(ContainerBagInterface $params) {
error_log('999999999999999999999999999999999999999999999999999999');
        $apiKey      = $params->get('myfatoorah.apiKey');
        $countryCode = $params->get('myfatoorah.countryCode');
        $isTest      = $params->get('myfatoorah.isTest');
        $this->mfObj = new PaymentMyfatoorahApiV2($apiKey, $countryCode, $isTest);
    }

//-----------------------------------------------------------------------------------------------------------------------------------------

    /**
     * Create MyFatoorah invoice
     *
     * @Route("/myfatoorah/create", name="myfatoorah_symfony_create")
     * 
     * @return JsonResponse
     */
    public function create(): JsonResponse {
        try {
            $paymentMethodId = 0; // 0 for MyFatoorah invoice or 1 for Knet in test mode

            $curlData = $this->getPayLoadData();
            $data     = $this->mfObj->getInvoiceURL($curlData, $paymentMethodId);

            $response = ['IsSuccess' => 'true', 'Message' => 'Invoice created successfully.', 'Data' => $data];
        } catch (\Exception $e) {
            $response = ['IsSuccess' => 'false', 'Message' => $e->getMessage()];
        }
        return $this->json($response);
    }

//-----------------------------------------------------------------------------------------------------------------------------------------

    /**
     * 
     * @param int|string $orderId
     * @return array
     */
    private function getPayLoadData($orderId = null) {
        $callbackURL = 'http:' . $this->generateUrl('myfatoorah_symfony_callback', [], UrlGeneratorInterface::NETWORK_PATH);

        return [
            'CustomerName'       => 'FName LName',
            'InvoiceValue'       => '10',
            'DisplayCurrencyIso' => 'KWD',
            'CustomerEmail'      => 'test@test.com',
            'CallBackUrl'        => $callbackURL,
            'ErrorUrl'           => $callbackURL,
            'MobileCountryCode'  => '+965',
            'CustomerMobile'     => '12345678',
            'Language'           => 'en',
            'CustomerReference'  => $orderId,
            'SourceInfo'         => 'Symfony ' . \Symfony\Component\HttpKernel\Kernel::VERSION . ' - MyFatoorah ' . self::VERSION
        ];
    }

//-----------------------------------------------------------------------------------------------------------------------------------------

    /**
     * Get MyFatoorah payment information
     * 
     * @Route("/myfatoorah/callback", name="myfatoorah_symfony_callback")
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function callback(Request $request): JsonResponse {
        //http://127.0.0.1:8000/myfatoorah/callback?paymentId=100202217186102325
        try {
            $paymentId = $request->query->get('paymentId');

            $data = $this->mfObj->getPaymentStatus($paymentId, 'PaymentId');
            if ($data->InvoiceStatus == 'Paid') {
                $msg = 'Invoice is paid.';
            } else if ($data->InvoiceStatus == 'Failed') {
                $msg = 'Invoice is not paid due to ' . $data->InvoiceError;
            } else if ($data->InvoiceStatus == 'Expired') {
                $msg = 'Invoice is expired.';
            }

            $response = ['IsSuccess' => 'true', 'Message' => $msg, 'Data' => $data];
        } catch (\Exception $e) {
            $response = ['IsSuccess' => 'false', 'Message' => $e->getMessage()];
        }
        return $this->json($response);
    }

//-----------------------------------------------------------------------------------------------------------------------------------------
}
