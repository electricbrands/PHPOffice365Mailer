# PHPOffice365mailer
Send emails via Office365 using MS Graph API

## Installation
1. Via [Composer](https://getcomposer.org/):
```bash
composer require electricbrands/php-office365mailer
```

2. Setup [MS Account](https://learn.microsoft.com/en-us/exchange/client-developer/legacy-protocols/how-to-authenticate-an-imap-pop-smtp-application-by-using-oauth)

3. dotenv vars:
```php
MS_TENANT_ID="your tenant id" 
MS_CLIENT_ID="your client id" 
MS_CLIENT_SECRET="your client secret"
```

4. Make sure that the files directory is writable for the webserver

## Example 
```php
<?php 

use Electricbrands\PhpOffice365mailer\PhpOffice365mailer;
# use \Dotenv\Dotenv;

require( __DIR__ . '/vendor/autoload.php' );

/* if you are using dotenv
$dotenv = Dotenv::createImmutable( __DIR__ );
$dotenv->load();
 */

/* In case that you don't have dotenv installed */
$_ENV["MS_TENANT_ID"] = "Enter your tenant id"; 
$_ENV["MS_CLIENT_ID"] = "Enter your client id"; 
$_ENV["MS_CLIENT_SECRET"] = "Enter your client secret";

$mail = new PhpOffice365mailer();

# View JWT Informations
# $mail->tokenInfo();

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
```

## Links 
 - Microsoft [user: sendMail](https://learn.microsoft.com/en-us/graph/api/user-sendmail?view=graph-rest-1.0&tabs=http)
 - Microsoft [message resource type](https://learn.microsoft.com/en-us/graph/api/resources/message?view=graph-rest-1.0)