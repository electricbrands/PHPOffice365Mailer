<?php 

namespace Electricbrands\PhpOffice365mailer;

use Electricbrands\PhpOffice365mailer\Token;
#use \GuzzleHttp\Client;


class PostRequest 
{

    private $token, $accessToken;

    private $endpointUrl, $bodyContent;

    public $responseCode, $responseBody, $responseHeader;


    function __construct( $endpointUrl, $bodyContent )
    {

        $this->endpointUrl = $endpointUrl;
        $this->bodyContent = $bodyContent;
        $this->token = new Token;
        $this->accessToken = $this->token->token->accessToken;
        #$this->sendRequestGuzzle();
        $this->sendRequestCurl();

    }

    private function sendRequestCurl() : void 
    {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->endpointUrl);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->bodyContent);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(

            'Authorization: Bearer ' . $this->accessToken,
            'Content-Type: application/json',
            'Content-Length: ' . strlen( $this->bodyContent )

        ));

        $response = curl_exec($ch);

        $this->responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $this->responseHeader = substr($response, 0, $header_size);
        $this->responseBody = substr($response, $header_size);
        curl_close($ch);

    }

    /*
    private function sendRequestGuzzle() : void
    {

        $guzzle = new Client;

        $response = $guzzle->post($this->endpointUrl, [

            'headers' => [

                'Authorization' => 'Bearer ' . $this->accessToken,
                'Content-Type' => 'application/json'

            ],
            'body' => $this->bodyContent

        ]);

        $this->responseCode = $response->getStatusCode();
        $this->responseBody = $response->getBody();

    }
    */


}