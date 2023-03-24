<?php 

namespace Electricbrands\PhpOffice365mailer;

use Electricbrands\PhpOffice365mailer\PostRequest;


class MailBody 
{

    private $msEndpointUrl = 'https://graph.microsoft.com/v1.0/';

    public $Subject, $Body, $AltBody;

    protected $contentType = "text";

    protected $fromEMail, $fromName;

    protected $replyTo = [];

    protected $toRecipients = [];

    protected $ccRecipients = [];

    protected $bccRecipients = [];

    protected $attachments = [];

    public $requestJsonBody = [];


    public function send( $mailDebug = false ) : void 
    {

        $this->requestJsonBody["message"]["subject"] = $this->Subject;
        $this->requestJsonBody["message"]["body"]["contentType"] = $this->contentType;
        $this->requestJsonBody["message"]["body"]["content"] = $this->Body;

        $this->requestJsonBody["message"]["from"]["emailAddress"]["address"] = $this->fromEMail;
        
        if( $this->fromName != "" ){

            $this->requestJsonBody["message"]["from"]["emailAddress"]["name"] = $this->fromName;

        }
        
        if( is_array( $this->replyTo[0] ) ){

            $this->requestJsonBody["message"]["replyTo"] = $this->replyTo;

        }

        if( is_array( $this->toRecipients[0] ) ){

            $this->requestJsonBody["message"]["toRecipients"] = $this->toRecipients;

            if( is_array( $this->ccRecipients[0] ) ){

                $this->requestJsonBody["message"]["ccRecipients"] = $this->ccRecipients;

            }

            if( is_array( $this->bccRecipients[0] ) ){

                $this->requestJsonBody["message"]["bccRecipients"] = $this->bccRecipients;

            }

            if( is_array( $this->attachments[0] ) ){

                $this->requestJsonBody["message"]["attachments"] = $this->attachments;

            }

            
            //Send E-Mail
            $endpoint = $this->msEndpointUrl . 'users/' . $this->fromEMail . '/sendMail'; 

            $request = new PostRequest( $endpoint, json_encode( $this->requestJsonBody ) );

            if( $mailDebug ){

                print $request->responseCode . "\r\n";
                print $request->responseBody . "\r\n";
                
            }
            

        }else{

            trigger_error("PhpOffice365mailer Error: Mail recipient is missing!", E_USER_NOTICE);

        }

    }

    public function isHTML( $is ) : void 
    {

        $this->contentType = "html";

    }

    public function setFrom( $email, $mailname = "") : void 
    {

        $this->fromEMail = $email;
        $this->fromName = $mailname;

    }

    public function addReplyTo( $email, $mailname = "") : void 
    {

        $this->replyTo[] = [

            "emailAddress" => [

                "address" => $email,
                "name" => $mailname

            ]

        ];

    }

    public function addAddress( $email, $mailname = "") : void 
    {

        $this->toRecipients[] = [

            "emailAddress" => [

                "address" => $email,
                "name" => $mailname

            ]

        ];

    }

    public function addCC( $email, $mailname = "") : void 
    {

        $this->ccRecipients[] = [

            "emailAddress" => [

                "address" => $email,
                "name" => $mailname

            ]

        ];

    }

    public function addBCC( $email, $mailname = "") : void 
    {

        $this->bccRecipients[] = [

            "emailAddress" => [

                "address" => $email,
                "name" => $mailname

            ]

        ];

    }

    public function addAttachment( $filePath, $fileName ) : void 
    {

        if( is_file( $filePath ) && $fileName != "" ){

            $mimeType = mime_content_type( $filePath );

            if( $mimeType ){

                $this->attachments[] = [

                    '@odata.type' => '#microsoft.graph.fileAttachment',
                    'Name' => $fileName,
                    'ContentBytes' => chunk_split( base64_encode( file_get_contents( $filePath ) ) ),
                    'contentType' => $mimeType, 
        
                ];

            }

        }

    }

}