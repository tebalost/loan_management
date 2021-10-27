<?php
require_once "../../../config/connect.php";

class GenderBasedLoans
{
    // count of loans belonging to male
    static function getMalesLoans(){
        global $link;
            $result = mysqli_query($link, "SELECT COUNT(*) AS number_of_loans, SUM(loan_info.balance) AS total_loans 
                                                    FROM loan_info, borrowers
                                                    WHERE loan_info.borrower=borrowers.id
                                                    AND borrowers.gender='Male'
                                                    AND loan_info.`status` = ''") or die(mysqli_error($link));

            if(mysqli_num_rows($result))
                return mysqli_fetch_assoc($result);
            else
                return null;
    }

    // count of email belongs to females
    static function getFemale(){
        global $link;
        $result = mysqli_query($link, "SELECT COUNT(*) AS number_of_loans, SUM(loan_info.balance) AS total_loans
                                                FROM loan_info, borrowers
                                                WHERE loan_info.borrower=borrowers.id
                                                AND borrowers.gender='Female'
                                                AND loan_info.`status` = ''") or die(mysqli_error($link));
        if(mysqli_num_rows($result))
            return mysqli_fetch_assoc($result);
        else
            return null;
    }

    //


}