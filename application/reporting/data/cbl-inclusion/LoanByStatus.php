<?php
require_once "../../../config/connect.php";

class LoanByStatus
{
    public static function getNewlyDisbursedLoans(){
        global $link;
        $result = mysqli_query($link , "SELECT COUNT(*) as number_of_loans, SUM(balance) total_loans 
                                                FROM loan_info, loan_statuses
                                                WHERE loan_info.`status`=''
                                                AND loan_statuses.loan=loan_info.id
                                                AND loan_statuses.`status` = ''
                                                AND DATEDIFF(NOW(), added_date) <= 91") or die($link);
        if(mysqli_num_rows($result))
            return mysqli_fetch_assoc($result);
        else
            return null;
    }


    public static function getSettledLoans(){
        global $link;
        $result = mysqli_query($link , "SELECT COUNT(*) as number_of_loans, SUM(balance) total_loans 
                                                FROM loan_info, loan_statuses
                                                WHERE loan_info.`status`='P'
                                                AND loan_statuses.loan=loan_info.id
                                                AND loan_statuses.`status` = ''
                                                AND DATEDIFF(NOW(), added_date) <= 91") or die($link);
        if(mysqli_num_rows($result))
            return mysqli_fetch_assoc($result);
        else
            return null;
    }

    public static function getDuebyThirtyDays(){
        global $link;
        $result = mysqli_query($link , "SELECT COUNT(*) as number_of_loans, SUM(loan_info.balance) total_loans 
                                                FROM loan_info, loan_statuses,pay_schedule
                                                WHERE loan_info.`status`=''
                                                AND loan_statuses.loan=loan_info.id
                                                AND loan_statuses.`status` = ''
                                                AND DATEDIFF(NOW(), pay_schedule.`schedule`) = 30
                                                AND pay_type='Maturity'") or die($link);
        if(mysqli_num_rows($result))
            return mysqli_fetch_assoc($result);
        else
            return null;
    }

    /** return the sum of the loans overdue by 30 days but less than 90  days */
    public static function getOverdueLoans(){
        global $link;
        $result = mysqli_query($link , "SELECT COUNT(*) as number_of_loans, SUM(loan_info.balance) total_loans 
                                                FROM loan_info, loan_statuses,pay_schedule
                                                WHERE loan_info.`status`=''
                                                AND loan_statuses.loan=loan_info.id
                                                AND loan_statuses.`status` = ''
                                                AND DATEDIFF(NOW(), pay_schedule.`schedule`) > 30         
                                                AND DATEDIFF(NOW(), pay_schedule.`schedule`) < 90
                                                AND pay_type='Maturity'") or die($link);
        if(mysqli_num_rows($result))
            return mysqli_fetch_assoc($result);
        else
            return null;
    }

    /** TODO implement these function to get non performing loans for more than 90 days */
    public static function getNonPerformingLoans(){}

    /** TODO return the loans written off */
    public  static function getLoanWrittenOff(){}
}