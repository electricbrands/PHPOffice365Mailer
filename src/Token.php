<?php 

namespace Electricbrands\PhpOffice365mailer;

# use \GuzzleHttp\Client;

class Token 
{

    private $tokenUrl;

    private $tokenFileName = __DIR__ . '/../files/token.php';

    private $tokenRegExp = '/^\/\*(.*)\*\//m'; 

    private $tokenGenTries = 0;

    public $token;


    function __construct()
    {
        
        $this->tokenUrl = 'https://login.microsoftonline.com/' . $_ENV["MS_TENANT_ID"] . '/oauth2/v2.0/token';

        if(!is_file( $this->tokenFileName )){

            $this->generateToken();

        }

        $this->getSavedTokenVals();
       

    }

    private function getSavedTokenVals() : void 
    {
        
        $content = file_get_contents( $this->tokenFileName );
        
        if( preg_match( $this->tokenRegExp, $content, $matches ) ){

            $this->token = json_decode( $matches[1] );

            if( (int)time() > (int)$this->token->expiration ){

                $this->generateToken();
                $this->tokenGenTries++;

                if( $this->tokenGenTries < 4 ){

                    $this->getSavedTokenVals();

                }else{

                    trigger_error("PhpOffice365mailer Error: token generation error!", E_USER_NOTICE);

                }

            }

        }else{

            trigger_error("PhpOffice365mailer Error: token error in files/token.php!", E_USER_NOTICE);

        }

    }

    private function generateToken() : void 
    {

        $jsonInput = "<?php\r\n/*" . json_encode( $this->getCurlToken() ) . "*/\r\n";
        file_put_contents( $this->tokenFileName, $jsonInput );

    }

    private function getCurlToken() : array 
    {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->tokenUrl);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'client_id=' . $_ENV["MS_CLIENT_ID"] . '&client_secret=' . $_ENV["MS_CLIENT_SECRET"] . '&scope=https://graph.microsoft.com/.default&grant_type=client_credentials');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(

            'Content-Type: application/x-www-form-urlencoded'

        ));

        $response = curl_exec($ch);
        $responseBody = json_decode( $response );
        curl_close($ch);

        return [

            "accessToken" => $responseBody->access_token,
            "generated" => time(),
            "expiration" => (string)( (int)time() + ((int)$responseBody->expires_in - 300) )

        ];

    }

    /*
    private function getGuzzleToken() : array 
    {

        $guzzle = new Client;

        $response = json_decode($guzzle->post($this->tokenUrl, [
            'form_params' => [
                'client_id' => $_ENV["MS_CLIENT_ID"],
                'client_secret' => $_ENV["MS_CLIENT_SECRET"],
                'scope' => 'https://graph.microsoft.com/.default',
                'grant_type' => 'client_credentials',
            ],
        ])->getBody()->getContents());

        return [

            "accessToken" => $response->access_token,
            "generated" => time(),
            "expiration" => (string)( (int)time() + ((int)$response->expires_in - 300) )

        ];

    }
    */


}