<?php
require_once "../../../config/connect.php";

class Indicators
{
    public static function getMaxLoan(){
        global $link;
        $result = mysqli_query($link, "SELECT MAX(balance) FROM loan_info") or die(mysqli_error($link));
        if(mysqli_num_rows($result)){
            $maxLoan = mysqli_fetch_assoc($result);
            return $maxLoan['MAX(balance)'];
        }
        else
            return 0;
    }

    public static function getMinLoan(){
        global $link;
        $result = mysqli_query($link, "SELECT MIN(balance) FROM loan_info") or die(mysqli_error($link));
        if(mysqli_num_rows($result)){
            $maxLoan = mysqli_fetch_assoc($result);
            return $maxLoan['MIN(balance)'];
        }
        else
            return 0;
    }

    public static function getInterestRate(){
        global $link;
        $result = mysqli_query($link, "SELECT MIN(balance) FROM loan_info") or die(mysqli_error($link));
    }
}