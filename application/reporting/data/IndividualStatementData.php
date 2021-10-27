<?php
require_once "../../../config/connect.php";


class IndividualStatementData
{

    static function getClientInformation(){
        global $link;
        $result = mysqli_query($link, "SELECT loan AS loanId, borrowers.id, added_date AS borrowerId 
                                            FROM loan_statuses,loan_info,borrowers
                                            WHERE loan_statuses.loan=loan_info.id
                                            AND loan_info.borrower = borrowers.id
                                            AND loan_statuses.`status` =''
                                            AND DATEDIFF(CURDATE(), loan_statuses.added_date) >= 30");
        if($result)
            return $result;
        else
            return null;
    }
}