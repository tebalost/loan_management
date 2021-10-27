<?php

require_once "../../../config/connect.php";
/**
 *  Class DurationalLoans
 *  This class collect the loans based on the duration or payment period
 */

class DurationalLoans
{

    /**
     * @return mixed
     */
    public static function getFirstLevelLoans()
    {
        global $link;
        $result = mysqli_query($link, "SELECT COUNT(*) AS number_of_loans, SUM(balance) AS total_loans FROM loan_info WHERE loan_info.loan_duration BETWEEN 1 AND 4 AND loan_info.`status`=''") or die(mysqli_error($link));
        if(mysqli_num_rows($result)){
            return $totalLoans = mysqli_fetch_assoc($result);
        }
    }

    /**
     * @param mixed
     */
    public static function getSecondLevelLoans()
    {
        global $link;
        $result = mysqli_query($link, "SELECT COUNT(*) AS number_of_loans, SUM(balance) AS total_loans FROM loan_info WHERE loan_info.loan_duration BETWEEN 4 AND 7 AND loan_info.`status`=''") or die(mysqli_error($link));
        if(mysqli_num_rows($result)){
            return mysqli_fetch_assoc($result);
        }
    }

    /**
     * @return mixed
     */
    public static function getThirdLevelLoans()
    {
        global $link;
        $result = mysqli_query($link, "SELECT COUNT(*) AS number_of_loans, SUM(balance) AS total_loans FROM loan_info WHERE loan_info.loan_duration BETWEEN 7 AND 12 AND loan_info.`status`=''") or die(mysqli_error($link));
        if(mysqli_num_rows($result)){
            return mysqli_fetch_assoc($result);
        }
    }

}