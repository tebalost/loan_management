<?php
include_once "../../../config/connect.php";

class Debtors
{
    static function getTopDebtors(){
        global $link;
        $result  = mysqli_query($link, "SELECT fname,lname , loan_info.balance AS loan_amount, amount, interest_value, amount_topay, loan_info.balance - SUM(payments.amount_to_pay) AS balance FROM loan_info, borrowers,payments WHERE loan_info.borrower=borrowers.id AND loan_info.baccount = payments.account GROUP BY fname,lname ,loan_amount, amount, interest_value, amount_topay ORDER BY balance DESC LIMIT 10");
        if($result)
            return $result;
        else
            return null;
    }
}