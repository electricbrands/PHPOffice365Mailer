<?php 

use Electricbrands\PhpOffice365mailer\PhpOffice365mailer;
# use \Dotenv\Dotenv;

require( __DIR__ . '/../vendor/autoload.php' );

/* if you are using dotenv
$dotenv = Dotenv::createImmutable( __DIR__ . '/..' );
$dotenv->load();
 */

/* In case that you don't have dotenv installed */
$_ENV["MS_TENANT_ID"] = "Enter your tenant id"; 
$_ENV["MS_CLIENT_ID"] = "Enter your client id"; 
$_ENV["MS_CLIENT_SECRET"] = "Enter your client secret";

$mail = new PhpOffice365mailer();

# View JWT Informations
# $mailer->tokenInfo();

# Send Mail
$mail->setFrom('from@example.com', 'Mailer');
$mail->addAddress('joe@example.net', 'Joe User');     //Add a recipient
$mail->addAddress('ellen@example.com');               //Name is optional
$mail->addReplyTo('info@example.com', 'Information');
$mail->addCC('cc@example.com');
$mail->addBCC('bcc@example.com');

# Content
$mail->isHTML(true); //Set email format to HTML
$mail->Subject = 'this is a mail from ms graph just for you';
$mail->Body    = '<html>This is a html <b>mail</b> body for <i>you</i></html>';

# Add attachment
$mail->addAttachment( __DIR__ . '/testpdf.pdf', 'yourtestpdf.pdf' );

# Send
$mail->send();

# Or send and debug
# $mail->send( true );
 