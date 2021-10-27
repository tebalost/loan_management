<?php

class InsuranceData
{
    static function getInsuranceRecord(){
        global $link;
        $result = mysqli_query($link, "SELECT lname, fname, date_of_birth,  baccount, date_release, loan_duration,
                                            loan_info.amount - SUM(pay_schedule.principal_payment) AS sum_insured
                                            FROM loan_info, borrowers, pay_schedule 
                                            WHERE loan_info.borrower=borrowers.id
                                            AND loan_info.`status` =''
                                            AND loan_info.id=pay_schedule.get_id
                                            GROUP BY pay_schedule.get_id");
        if($result)
            return $result;
        else
            return null;
    }
}