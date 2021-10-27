<?php
require_once('system-charge.php');
include_once "../../../config/connect.php";                        // just for testing
/**
this class get the System charge Object data depending on the how the consumer wants to get it
 **/
define('DEFINE','0.50');


class SystemChargeData{

    /**
     * Gets back the System Charge given the specific date
     **/
    private static function getCompanyInfo(){
        global $link;
        $companyInfo =  mysqli_query($link, "SELECT name,address FROM systemset") or die("could not find the company info");
        if(mysqli_num_rows($companyInfo) > 0 )
            return mysqli_fetch_assoc($companyInfo);
        else
            return null;
    }

    //SELECT SUM(fee_amount) as totalCharge, COUNT(fee_name) AS number_of_loans FROM loan_fees,loan_statuses WHERE MONTH(added_date)='11' AND loan_fees.loan=loan_statuses.loan AND loan_statuses.status='' AND fee_name='System Charge on loan' GROUP BY fee_name

    public static function getSystemChargeByDate(){
        global $link;
        $systemCharge = new SystemCharge;
        $date = date("Y-m");
        $result = mysqli_query($link, "SELECT SUM(fee_amount) as totalCharge, COUNT(fee_name) AS number_of_loans 
                                            FROM loan_fees,loan_statuses 
                                            WHERE `fee_name` LIKE 'System Charge on loan' 
                                            AND loan_statuses.loan=loan_fees.loan 
                                            AND loan_statuses.status='' 
                                            AND DATE_FORMAT(date_added, '%Y-%m') = date_format(now(), '%Y-%m')") or die(mysqli_error($link));

        if($result){
            $loanChargeinfo = mysqli_fetch_assoc($result);
            $systemCharge->setLoanTransactionsCharge($loanChargeinfo['totalCharge']);
            $systemCharge->setNumberOfLoanCharged($loanChargeinfo['number_of_loans']);
        }else
            $systemCharge->setLoanTransactionsCharge(0);



        // sms charge needed further refinement
        $smsquery = mysqli_query($link, "SELECT SUM(messages) AS number_of_messages FROM sms_messages WHERE DATE_FORMAT(DATETIME,'%Y-%m') = '$date'") or die(mysqli_error($link));
        if(mysqli_num_rows($smsquery) > 0){
            $SmsInfo = mysqli_fetch_assoc($smsquery);
            $smscharge = $SmsInfo['number_of_messages'] * DEFINE;
            $systemCharge->setNumberOfSMSs($SmsInfo['number_of_messages']);
        }
        else
            $smscharge = 0;
        $systemCharge->setSmsCharge($smscharge);			// to get the value from db



        // save the billing info as json
        $fixedCharges = file_get_contents('../resources/static_charges.json', FILE_USE_INCLUDE_PATH);
        $fee = json_decode($fixedCharges, true);
        $systemCharge->setHostingCharge($fee['hosting-fee']);


        //setting the client info to be displayed in the info
        $companyInfo =  SystemChargeData::getCompanyInfo($link);
        $systemCharge->setclientBillingDetails($companyInfo);

        return $systemCharge;
    }
}

?>