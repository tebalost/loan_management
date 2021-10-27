<?php
require_once "../model/HttpStatusCode.php";

class controllerCommon
{
    static function validateInput($requestMessageType, $declaredMessageType){
        if($requestMessageType !== $declaredMessageType){
            HttpStatusCode::setHttpHeaders(515);
            echo json_encode(array("response"=>"unknown operation please check the documentation"));
        }
    }

    static function returnDefaultResponse(){
        HttpStatusCode::setHttpHeaders(404);
        echo json_encode(array("response"=>"unsupprted operation please check documentation"));
    }

    // check if there are users to be send the notification sms
    static function notifyable($customer){
        if($customer == null) {
            HttpStatusCode::setHttpHeaders(201);
            echo json_encode(array("response"=>"no loan due soon"));
        }
    }

}




// TODO check if the message goes to indivual or group