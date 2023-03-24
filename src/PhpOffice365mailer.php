<?php 

namespace Electricbrands\PhpOffice365mailer;

use Electricbrands\PhpOffice365mailer\Token;
use Electricbrands\PhpOffice365mailer\MailBody;

class PhpOffice365mailer extends MailBody
{

    private $token, $accessToken;

    
    
    function __construct()
    {
        
        if( $this->setEnvironment() ){

            $this->token = new Token;
            $this->accessToken = $this->token->token->accessToken;

        }

    }

    public function tokenInfo() : void 
    {

        print "Token: " . substr( $this->accessToken, 0, 100 ) . "...\r\n";
        print "Token was issued at: " . date("d.m.Y H:i:s", $this->token->token->generated ) . "\r\n";
        print "Token will expire at: " . date("d.m.Y H:i:s", $this->token->token->expiration ) . "\r\n";

    }

    private function setEnvironment() : bool 
    {

        if( !extension_loaded('curl') ){
            
            trigger_error("PhpOffice365mailer Error: PHP Curl extension missing!", E_USER_NOTICE);

            return false;

        }

        if( $this->testEnvironment() === false ){

            if( $this->testEnvironment() === false ){

                trigger_error("PhpOffice365mailer Error: Environment vars missing!", E_USER_NOTICE);

                return false;

            }

        }

        if( is_writable( __DIR__ . '/../files' ) === false ){

            trigger_error("PhpOffice365mailer Error: files directory not writable!", E_USER_NOTICE);

            return false;

        }

        return true;

    }

    private function testEnvironment() : bool 
    {

        if( !isset( $_ENV["MS_TENANT_ID"] ) || !isset( $_ENV["MS_CLIENT_ID"] ) || !isset( $_ENV["MS_CLIENT_SECRET"] ) ){

            return false;

        }

        return true;

    }

}