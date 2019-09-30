<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

//begin
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/phpmailer/phpmailer/src/Exception.php';
require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../vendor/phpmailer/phpmailer/src/SMTP.php';
require '../vendor/autoload.php';

//end
class MailHandler
{ 
    public function SendMail($to,$subject,$body,$attachment=""){
        $mail = new PHPMailer(true);
        try {
			//Server settings
			$mail->CharSet = 'UTF-8'; 
            $mail->SMTPDebug = 2;                                       // Enable verbose debug output
            $mail->isSMTP();                                            // Set mailer to use SMTP
            $mail->Host       = 'smtp.gmail.com';  // Specify main and backup SMTP servers
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = 'hieptest12345@gmail.com';                     // SMTP username
            $mail->Password   = 'hieptest12345678';                               // SMTP password
            //$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption, `PHPMailer::ENCRYPTION_SMTPS` also accepted
            $mail->Port       = 587;                                    // TCP port to connect to
        
            //Recipients
            $mail->setFrom('hieptest12345@gmail.com', 'qlgx.net');
            $mail->addAddress($to);     // Add a recipient
            //$mail->addAddress('ellen@example.com');               // Name is optional
            //$mail->addReplyTo('info@example.com', 'Information');
            //$mail->addCC('cc@example.com');
            //$mail->addBCC('bcc@example.com');
        
            // Attachments
            if($attachment!="")
            {
            $mail->addAttachment("./".$attachment);         // Add attachments
            }
            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    =$body;
			//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
			$mail->SMTPDebug = false;
			$mail->do_debug = 0;
            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}
