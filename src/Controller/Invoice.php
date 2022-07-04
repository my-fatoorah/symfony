<?php

namespace MyFatoorah\Symfony\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use MyFatoorah\Library\PaymentMyfatoorahApiV2;

class Invoice {

    public $mfObj;

    public const VERSION = '2.0.0';

//-----------------------------------------------------------------------------------------------------------------------------------------

    /**
     * create MyFatoorah object
     */
    public function __construct() {
//        $apiKey      = 'rLtt6JWvbUHDDhsZnfpAhpYk4dxYDQkbcPTyGaKp2TYqQgG7FGZ5Th_WD53Oq8Ebz6A53njUoo1w3pjU1D4vs_ZMqFiz_j0urb_BH9Oq9VZoKFoJEDAbRZepGcQanImyYrry7Kt6MnMdgfG5jn4HngWoRdKduNNyP4kzcp3mRv7x00ahkm9LAK7ZRieg7k1PDAnBIOG3EyVSJ5kK4WLMvYr7sCwHbHcu4A5WwelxYK0GMJy37bNAarSJDFQsJ2ZvJjvMDmfWwDVFEVe_5tOomfVNt6bOg9mexbGjMrnHBnKnZR1vQbBtQieDlQepzTZMuQrSuKn-t5XZM7V6fCW7oP-uXGX-sMOajeX65JOf6XVpk29DP6ro8WTAflCDANC193yof8-f5_EYY-3hXhJj7RBXmizDpneEQDSaSz5sFk0sV5qPcARJ9zGG73vuGFyenjPPmtDtXtpx35A-BVcOSBYVIWe9kndG3nclfefjKEuZ3m4jL9Gg1h2JBvmXSMYiZtp9MR5I6pvbvylU_PP5xJFSjVTIz7IQSjcVGO41npnwIxRXNRxFOdIUHn0tjQ-7LwvEcTXyPsHXcMD8WtgBh-wxR8aKX7WPSsT1O8d8reb2aR7K3rkV3K82K_0OgawImEpwSvp9MNKynEAJQS6ZHe_J_l77652xwPNxMRTMASk1ZsJL';
        $apiKey      = 'UZH8ufQHiix-srTtd5-KLguZnsv6Y5tQ1fLWMzpR3CrhxGBViRBtXKa3OuFhC561BjSdWzYZcBWhWerC6gDVvglUV4FbcJrvCboVhtZb2BHgRdmtANu2sc8ouvGWuoPJqpffOfkHoXqdO0tHICxsKGsfVHIUP80iyV-KdJLXoaC6ugdXJoSQpjZguTb2meJyaQeSgQdpKkIGqUk3b3y11QxiYHJDq5P8v3oqgkQ_EpsRTT3NyeAqmC89wwox5JTgZUNpngHaqy5_VvZMylrp-ni_SFCYX-MsO1VMeY1fn_bsMSnzqaBFRBoBdI1JvD03C2cMbIeZ0I53aSl0ZdaoN29uEqafiEkOSk-B5wTHch0Et--y42_5NUncbZxcf82pDuR5uglJyMdITMZY3BywtDQQYlM2QT_CNhYJCmAE-T_1VesgpvW9aP0NxCSqXpbVeNkYNtcjzU3ej9CwzMIpuhGhSwOL_B-lEU_ZxCtnO6Sq5-Xn6ECibsgdjm0Hok6qe0t2euTC8lhsbOExnMuyRu9rehdZc9TGEbEmsFtddDohmXKe3lEYnuzrM--Htu-uFwVhzbUFRXtzkSrUnbEUj7bxafZr7wDCSs-FSP9sShPAXv2s0VHuapqKlrwH1JJ-8OKYwxwYnoMJb0tVwq-abN-LUASBvm9_NVar4JQxkcqClAhp';
        $countryCode = 'KWT';
        $isTest      = true;

        $this->mfObj = new PaymentMyfatoorahApiV2($apiKey, $countryCode, $isTest);
    }

//-----------------------------------------------------------------------------------------------------------------------------------------

    /**
     * Create MyFatoorah invoice
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
        return new JsonResponse($response);
    }

//-----------------------------------------------------------------------------------------------------------------------------------------

    /**
     * 
     * @param int|string $orderId
     * @return array
     */
    private function getPayLoadData($orderId = null) {
        $callbackURL = null; //route('myfatoorah.callback');

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
     * @param Request $request
     * @return JsonResponse
     */
    public function callback(Request $request): JsonResponse {
        //http://127.0.0.1:8000/myfatoorah/callback?paymentId=100202217186102325
        try {
            $paymentId = $request->query->get('paymentId');
            $data      = $this->mfObj->getPaymentStatus($paymentId, 'PaymentId');

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
        return new JsonResponse($response);
    }

//-----------------------------------------------------------------------------------------------------------------------------------------
}
