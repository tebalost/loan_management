<?php
/**
 * PHPMailer multiple files upload and send example
 */

require_once "phpmailer/PHPMailer.php";
require_once "phpmailer/SMTP.php";
require_once "phpmailer/Exception.php";

//Import the PHPMailer class into the global namespace
use PHPMailer\PHPMailer\PHPMailer;


class Mailer
{
    private $mail = null;

    function __construct()
    {
        $this->mail = new PHPMailer;
        $this->mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
        $this->mail->isSMTP();                                            // Send using SMTP
        $this->mail->Host = 'pulamaliboho-fs.com';                    // Set the SMTP server to send through
        $this->mail->SMTPAuth = true;                                   // Enable SMTP authentication
        $this->mail->Username = 'service@pulamaliboho-fs.com';                     // SMTP username
        $this->mail->Password = '=l8PL5DwN{AZ';                               // SMTP password
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $this->mail->Port = 587;
        $this->mail->SMTPDebug = 0;
        $this->mail->isHTML(true);
        $this->mail->setFrom('service@pulamaliboho-fs.com', 'Pulamaliboho Financial Services');
        echo "Object created";
    }

    private function attatchFiles()
    {

        for ($i = 0; $i < count($_FILES['userfile']['name']); $i++) {
            $ext = PHPMailer::mb_pathinfo($_FILES['userfile']['tmp_name'], PATHINFO_EXTENSION);
            $filename = $_FILES['userfile']['name'] . $ext;
            $uploadfile = $_FILES['userfile']['tmp_name'];
            // Attach the uploaded file
            $this->mail->addAttachment($uploadfile, $filename);
        }
    }

    function sendEmail($emails, $attachment = "NO")
    {
        foreach ($emails as $value)
            $this->mail->addBCC($value);


        // attach a file if it there
        if ($attachment == "yes")
            $this->attatchFiles();

        return $this->mail->send();
    }

    public function setContent($subject, $body)
    {
        $this->mail->Body = $body;
        $this->mail->Subject = $subject;
    }
}

?>