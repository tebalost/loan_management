<?php

include_once "../model/email-util.php";
include_once "../model/response-status.php";
include_once "controllerCommon.php";
include_once "../data/IndividualStatementData.php";

// the url has to be beautified
$systemInvoiceEmail = "INVOICE";
$distributeEmails = "EMAILS";
$systemInsuranceFile = "INSURANCE-REPORT";
$customerSatement =  "CLIENT_LOAN_STATEMENT";

//disabling warning
error_reporting(E_ALL & ~E_NOTICE);

  class EmailRestController{

    private function generateInvoiceByDate(){
        include_once "../view/system-invoice.php";
        return $base64pdf;
    }
    // methods that distribute invoice to company clients
    private function sendEmails($emailList, $bccEmailList, $attachment, $message, $sender, $fileFormat){
        $mailer = new MailUtil($sender);
        $mailer->distributeEmailWithAttachment($emailList, $bccEmailList, $attachment,null, $message,$fileFormat);
    }
    public function sendDateRangedInvoiceToEmails($dataParams){
        $emailList = $dataParams['emailList'];
        $bccEmailList = $dataParams['bcclist'];
        $fileFormat = $dataParams['fileFormat'];
        $message = array("subject"=>$dataParams['emailSubject'], "body"=>$dataParams['emailBody']);
        if(isset($dataParams['sender']))
            $sender = $dataParams['sender'];
        else
            $sender = null;
        $invoiceAttachment = $this->generateInvoiceByDate();
        $this->sendEmails($emailList, $bccEmailList, $invoiceAttachment, $message, $sender, $fileFormat);
        
    }

    public function sendMonthlyInsuranceFile($dataParams){
        $emailList = $dataParams['emailList'];
        $bccEmailList = $dataParams['bcclist'];
        $message = array("subject"=>$dataParams['emailSubject'], "body"=>$dataParams['emailBody']);
        $fileFormat = $dataParams['fileFormat'];
        if(isset($dataParams['sender']))
            $sender = $dataParams['sender'];
        else
            $sender = null;
        $invoiceAttachment = $this->generateInsuranceFile();
        $this->sendEmails($emailList, $bccEmailList, $invoiceAttachment, $message, $sender, $fileFormat);
    }

    private function generateInsuranceFile(){
        $filename = "";
        include_once "../view/insurance-report.php";
        return $filename;
    }
	  
	// methods that distrubute emails to clients
	public function DistrubuteEmailsToMultipleRecipients($dataParams){
		//validate the inputs
        $sender = null;
		$bccList = $dataParams['bcclist'];
		$message = $dataParams['message'];
		if(isset($dataParams['sender']))
		    $sender = $dataParams['sender'];
		$mailer = new MailUtil($sender);
		$mailer->distrubuteEmails($bccList, $message);

	}

	public function SendStatement($dataParams){
        $message = $dataParams['emailBody'];
        $fileFormat = $dataParams['fileFormat'];
        $sender = $dataParams['sender'];
        $clientInformation = IndividualStatementData::getClientInformation();
        while($row = mysqli_fetch_assoc($clientInformation)){
            $id = $row['id'];
            $loanId = $row['loanId'];
            include "../view/statement.php";
        }
        HttpStatusCode::setHttpHeaders(200);
        echo json_encode(array("response"=>"success"));
        exit(1);


    }
}


DEFINE('MESSAGE_TYPE', 'EMAIL');
$dataFromRequest = json_decode(file_get_contents('php://input'), true);
//controllerCommon::validateInput($dataFromRequest['messageType'], MESSAGE_TYPE);
//getting the request type
$requestContentType = $_SERVER['CONTENT_TYPE'];
if($requestContentType !== 'application/json'){
	HttpStatusCode::setHttpHeaders(500);
	echo json_encode(array("response"=>"Unsupported content Type expecting json object"));
	exit(1);
}





switch($dataFromRequest['scheduleType']){
    case $systemInvoiceEmail:
        $controller = new EmailRestController;
        $controller->sendDateRangedInvoiceToEmails($dataFromRequest);
        break;
    case $systemInsuranceFile:
        $controller = new EmailRestController;
        $controller->sendMonthlyInsuranceFile($dataFromRequest);
        break;
        
	case $distributeEmails:
        $controller = new EmailRestController;
        $controller->DistrubuteEmailsToMultipleRecipients($dataFromRequest);
        break;
    case $customerSatement:
        $controller = new EmailRestController;
        $controller->SendStatement($dataFromRequest);
        break;

    default:
}

?>