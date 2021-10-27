<?php

class MobileAPI
{

    public function __construct() {
        global $link;
        $credentials = null;
        $result =  mysqli_query($link, "SELECT * FROM sms_gateway") OR die(mysqli_error($link));
        if(mysqli_num_rows($result) > 0)
            $credentials = mysqli_fetch_assoc($result);

        $this->url = $credentials['api'];
        $this->username = $credentials['username']; //your login username
        $this->password = $credentials['password']; //your login password
        //$this->validityperiod = '24'; //optional- set desired validity (represents hours)
    }

    public function checkCredits() {
        $data = array(
            'Type' => 'credits',
            'Username' => $this->username,
            'Password' => $this->password
        );
        $response = $this->querySmsServer($data);
        // NULL response only if connection to sms server failed or timed out
        if ($response == NULL) {
            return '???';
        } elseif ($response->call_result->result) {
            echo '</br>Credits: ' .  $response->data->credits;
            return $response->data->credits;
        }
    }

    public function sendSms($mobile_number, $msg) {
        $data = array(
            'Type' => 'sendparam',
            'Username' => $this->username,
            'Password' => $this->password,
            'numto' => $mobile_number,          //phone numbers (can be comma seperated)
            'data1' => $msg                     //your sms message
        );
        $response = $this->querySmsServer($data);
        return $this->returnResult($response);
    }

    // query API server and return response in object format
    private function querySmsServer($data, $optional_headers = null) {

        $ch = curl_init($this->url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // prevent large delays in PHP execution by setting timeouts while connecting and querying the 3rd party server
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 2000); // response wait time
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 2000); // output response time
        $response = curl_exec($ch);
        if (!$response) return NULL;
        else return new SimpleXMLElement($response);
    }

    // handle sms server response
    private function returnResult($response) {
        $return = new StdClass();
        $return->pass = NULL;
        $return->msg = '';
        if ($response == NULL) {
            $return->pass = FALSE;
            $return->msg = 'SMS connection error.';
        } elseif ($response->call_result->result) {
            $return->pass = 'CallResult: '.TRUE . '</br>';
            $return->msg = 'EventId: '.$response->send_info->eventid .'</br>Error: '.$response->call_result->error;
        } else {
            $return->pass = 'CallResult: '.FALSE. '</br>';
            $return->msg = 'Error: '.$response->call_result->error;
        }
        echo $return->pass;
        echo $return->msg;
        return $return;
    }

}
