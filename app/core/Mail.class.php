<?php

class Mail
{
    private $to;
    private $subject;
    private $message;

    public function __construct($to, $subject = null, $message= null)
    {
        $this->to = $to;
        $this->subject = (! empty($subject) ? $subject : "Qwarkz mailing"); //TODO
        $this->message = (! empty($message) ? $message : "test mail true");
    }

    public function send()
    {
        /*if(empty($to) || !filter_var($to, FILTER_VALIDATE_EMAIL)) {
            die('pd');
        } else {
            mail($this->to, $this->subject, $this->message);
        }*/

        $mail = new PHPMailer;

        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'ssl0.ovh.net';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'noreply@qwarkz.fr';                 // SMTP username
        $mail->Password = 'azertyuiop';                           // SMTP password
        $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 465;                                    // TCP port to connect to

        $mail->setFrom('noreply@qwarkz.fr', 'Qwarkz');
        $mail->addAddress($this->to);     // Add a recipient
        /*$mail->addReplyTo('info@example.com', 'Information');
        $mail->addCC('cc@example.com');
        $mail->addBCC('bcc@example.com');*/

        /*$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name*/
        $mail->isHTML(true);                                  // Set email format to HTML

        $mail->Subject = $this->subject;
        $mail->Body    = $this->message;
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        if(!$mail->send()) {
            $message = json_encode(['success' => false]);
        } else {
            $message = json_encode(['success' => true]);
        }
        echo $message;
    }
}