<?php
require_once "../model/sms-scheduler.php";
include_once "../model/response-status.php";
class SmsController
{
    public function distributeSms($scheduleType){
        $smsSender = new SmsUtiler();
        $smsSender->destributeSms($scheduleType);
    }
}

DEFINE('MESSAGE_TYPE', 'SMS');
$dataParam = json_decode(file_get_contents('php://input'), true);
controllerCommon::validateInput($dataParam['messageType'], MESSAGE_TYPE);

// TODO continue with business logic
$scheduleType = $dataParam['scheduleType'];

switch($scheduleType){
    case "paymentOverdue-warning":
    case "paymentDue-warning":
    $smsController = new SmsController;
    $smsController->distributeSms($scheduleType);
    break;

    default:
        controllerCommon::returnDefaultResponse();
}



//





