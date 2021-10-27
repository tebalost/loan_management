<?php
include_once "../../config/connect.php";

class BureauRecord
{
    private function getLoanDetails($mployeeCode){
        global $link;
        $result = mysqli_query($link , "SELECT amount, baccount, loan_info.balance,amount_topay,loan_num_of_repayments,email,phone FROM loan_info,borrowers WHERE loan_info.borrower=borrowers.id AND emp_code='$mployeeCode'");
        return mysqli_fetch_assoc($result);
    }

    // returns baking informamtion in for associative array
    private function getBorrowersBankingDetails($mployeeCode){
        global $link;
        $result = mysqli_query($link , "SELECT transaction FROM loan_disbursements,borrowers,loan_info WHERE emp_code='$mployeeCode' AND loan_info.borrower=borrowers.id AND loan_disbursements.loan=loan_info.id");
        $info = mysqli_fetch_assoc($result);
        return json_decode($info['transaction'], true);

    }

    // exposing the data to whosoever need it
    public function genereteData($mployeeCode){
        return array_merge($this->getLoanDetails($mployeeCode), $this->getBorrowersBankingDetails($mployeeCode));
    }
}

// for testing purpose
//$record = new BureauRecord();
//print_r($record->genereteData('0013598));
?>