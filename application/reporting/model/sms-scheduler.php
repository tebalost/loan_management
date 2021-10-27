<?php

require_once "../data/CustomerContacts.php";
require_once  "../../../config/connect.php";
require_once "../apis/MobileAPI.php";
require_once "../controller/controllerCommon.php";

class SmsUtiler{

    public function destributeSms($action)
    {
        global $link;
        $SmsSenderObj = new MobileAPI();
        $clientInfo = new CustomerContacts;
        $company = $this->companyTradingName();
        $response = "";

        switch ($action) {
            case "paymentDue-warning":
                $clients = $clientInfo->getAllLoanDueTomorrow();
                controllerCommon::notifyable($clients);             // check if there are users who need to be notified
                foreach ($clients as $clientInfo) {
                    $message = "Dear ".$clientInfo['title'].". ".$clientInfo['lname'].", Your loan instalment of M ".number_format($clientInfo['balance'], 2,'.',',')." is due tomorrow. Please pay using Reference: ".$clientInfo['baccount']." ".$company.".";
                    $smsLength = strlen($message);
                    $messages = ceil($smsLength / 160);
                    mysqli_query($link, "insert into sms_messages values(0,".$clientInfo['phone'].",'$message',NOW(),'','','".$clientInfo['id']."','$smsLength','$messages')");
                    $response = $SmsSenderObj->sendSms($clientInfo['phone'], $message);

                }
                break;
            case "paymentOverdue-warning":
                $clients = $clientInfo->getOverDueClientContacts();
                controllerCommon::notifyable($clients);             // check if there are users who need to be notified
                foreach ($clients as $clientInfo) {
                    $message = "Dear " . $clientInfo['title'] . ". " . $clientInfo['lname'] . ", Your loan instalment of M " . number_format($clientInfo['balance'], 2, '.', ',') . " is overdue by " . $clientInfo['number_of_days'] . " days. Please pay using Reference: " . $clientInfo['baccount'] . " to avoid penalty. " . $company . ".";
                    $smsLength = strlen($message);
                    $messages = ceil($smsLength / 160);
                    mysqli_query($link, "insert into sms_messages values(0," . $clientInfo['phone'] . ",'$message',NOW(),'','','".$clientInfo['id']."','$smsLength','$messages')");
                    $response = $SmsSenderObj->sendSms($clientInfo['phone'], $message);
                }
                break;
            default:
        }

        var_dump($response);
    }

    public function generateMaturityWarning(){

    }

    private function companyTradingName(){
        global $link;
        $result = mysqli_query($link, "SELECT name FROM  systemset") or die(mysqli_error());
        if(mysqli_num_rows($result)) {
            $company = mysqli_fetch_assoc($result);
            return $company['name'];
        }
        else
            return null;
    }
}