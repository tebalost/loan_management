<?php


require_once "../../include/phpmailer/PHPMailer.php";
require_once "../../include/phpmailer/Exception.php";
require_once "../../include/phpmailer/SMTP.php";
require_once "../../../config/connect.php";
include_once "../model/response-status.php";
include_once "../data/IndividualStatementData.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


class MailUtil{
    private $mail;
    function __construct($sender){
        global $link;
        $this->mail = new PHPMailer(true);
        // reading the authentication details
        $credetailsInfo = null;
        if($sender !== null){
            $result = mysqli_query($link, "SELECT email_credentials FROM systemset");
            $credInfo = mysqli_fetch_assoc($result);
            $credetailsInfo = json_decode($credInfo['email_credentials'], true);
        }
        else{
            $credetailsInfo = json_decode(file_get_contents("../resources/system_credentials.json", FILE_USE_INCLUDE_PATH), true);
        }

        //setting the authentication details
        $this->mail->Host       = $credetailsInfo['host'];
        $this->mail->Port       = $credetailsInfo["port"];
        $this->mail->Username   = $credetailsInfo['email'];
        $this->mail->Password   = $credetailsInfo["password"];


        $this->mail->SMTPDebug = SMTP::DEBUG_SERVER;                                // enabling the verbose ouput to the server
        $this->mail->isSMTP();
        $this->mail->SMTPAuth   = true;
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;                 // ebaling the tls ecryption over the email send
        $this->mail->SMTPDebug = 0;
        $this->mail->isHTML(true);                                          // enabling html formatting the email send
        $this->mail->setFrom($credetailsInfo['email'], $credetailsInfo['name']);  //adding sender settings

    }


    public function distributeEmailWithAttachment($toEmailList, $bccEmailsRecipients = [], $invoiceAttachment = null, $ccEmailRecipients=[], $message, $fileFormat){

        if(!empty($toEmailList)){
            foreach($toEmailList as $value){
                $this->mail->addAddress($value);
            }
        }


        // handling the email bcc list of recipients

        if(!empty($bccEmailsRecipients)){
            foreach($bccEmailsRecipients as $value){
                $this->mail->addCC($value);
            }
        }



        if(!empty($ccEmailRecipients)){
            if(empty($ccEmailRecipients)){
                foreach($ccEmailRecipients as $key=>$value)
                    $this->mail->addCC($value, $key);
            }
        }


        // setting the email contents
        $this->SetEmailMessageContents($message);


        //geting the attachment
        $this->AttachFile($invoiceAttachment, $fileFormat);

        $this->sendMails();
    }


    public function distrubuteEmails($bccList, $message){
        if(empty($bccList))
            echo  "no recipients";

        foreach($bccList as $key=>$value){
            $this->mail->addBcc($value, $key);
        }

        // setting the email bosy and subject
        $this->SetEmailMessageContents($message);
        $this->sendMails();
    }

    private function SendMails(){

        try {
            if($this->mail->send()){
                HttpStatusCode::setHttpHeaders(200);
                $response = array("response"=>"Email Success send");
                echo $this->encodeResponseToJson($response, JSON_PRETTY_PRINT);
            }

        }
        catch (Exception $e) {
            HttpStatusCode::setHttpHeaders(500);
            echo json_encode(array("response"=>$this->mail->ErrorInfo));
        }
    }

    private function AttachFile($attachment, $fileFormat){
        $name = "";

        if(strtolower($fileFormat) == 'pdf') {
            $dateTimeObject = new DateTime();
            $dueDate = $dateTimeObject->modify('+6 days')->format('Y-m-d');
            $name = "Invoice_" . date('Y_m_d') . "_" . $dueDate . ".pdf";

            if($attachment !== null)
                $this->mail->addStringAttachment($attachment, $name);         // Add attachments

        }
        else if(strtolower($fileFormat) == 'xlsx'){
            global $link;
            $path =  "../files-to-insurance/".$attachment;
            $result = mysqli_fetch_assoc(mysqli_query($link, "SELECT name FROM systemset"));
            $compayName = $result['name'];
            $name = $compayName . "_Credit life cover.".$fileFormat;
            $this->mail->addAttachment($path,$name);
        }

    }

    private function SetEmailMessageContents($message){
        $this->mail->Subject = $message['subject'];
        $this->mail->Body    = $message['body'];
    }

    // function that incode the response to send to the file;
    private function encodeResponseToJson($responseData) {
        $jsonResponse = json_encode($responseData, JSON_PRETTY_PRINT);
        return $jsonResponse;
    }

    // private attach local files
    public function sendLocalMail($emailList, $subject, $content){
        foreach ($emailList as $key=>$value)
            $this->mail->addBCC($value, $key);

        for ($i = 0; $i < count($_FILES['userfiles']); $i++){
            $this->mail->addStringAttachment($_FILES['userfiles']['tmp_name'], $_FILES['userfiles']['name'] );
        }

        $this->mail->Subject = $subject;
        $this->mail->Body = $content;
        try{
            return $this->mail->send();
        }
        catch (Exception $e){
            echo "Somthing went wring ";
        }

    }


}