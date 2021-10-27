<?php

require_once "cbl_financial-performance/Expenses.php";
require_once "cbl_financial-performance/Incomes.php";
require_once "../../../config/connect.php";

class FinancialPerformance
{
    static function getIncomeData(){
        global $link;
        $income = new Incomes();

        //interest from the loan
        $result = mysqli_query($link, "SELECT balance FROM gl_codes WHERE code='30001'");
        if(mysqli_num_rows($result) > 0){
           $loanInterest = mysqli_fetch_assoc($result);
           $income->setLoanInterest($loanInterest['balance']);
        }


        // fees income
        $result = mysqli_query($link, "SELECT balance FROM gl_codes WHERE code='30003'");
        if(mysqli_num_rows($result) > 0){
            $feeIncome = mysqli_fetch_assoc($result);
            $income->setFeeIncome($feeIncome['balance']);
        }

        // fees other incomes
        $result = mysqli_query($link, "SELECT SUM(balance) AS other_incomes FROM gl_codes WHERE code IN ('30005','30004', '30005')");
        if(mysqli_num_rows($result) > 0){
            $otherIncome = mysqli_fetch_assoc($result);
            $income->setAnyOtherIncome($otherIncome['other_incomes']);
        }

        return $income;
    }

    static function getExpenses(){
        global $link;
        $expenses = new Expenses();

        // accondation and rent
        $result =  mysqli_query($link, "SELECT balance FROM gl_codes WHERE code ='40004'") OR die("Could not get rental cost");
        if(mysqli_num_rows($result)){
            $accommodationAndRest = mysqli_fetch_assoc($result);
            $expenses->setAccommodation($accommodationAndRest['balance']);
        }


        // computer charges
        $result =  mysqli_query($link, "SELECT SUM(balance) AS digital_expenses FROM gl_codes WHERE code IN ('40003','40005', '40010')") OR die("Could not get computer and communication charge");
        if(mysqli_num_rows($result) > 0){
            $computerScience = mysqli_fetch_assoc($result);
            $expenses->setComputerCharges($computerScience['digital_expenses']);
        }

        // salaries and pay as you earn return
        $result = mysqli_query($link, "SELECT SUM(balance) as wages_and_salaries FROM gl_codes WHERE code IN ('41001','41000','41002')") OR die("Could not get wages and salaries with pay as you ean");
        if(mysqli_num_rows($result)){
            $salariesAndWages = mysqli_fetch_assoc($result);
            $expenses->setSalariesAndPayAsYouEarn($salariesAndWages['wages_and_salaries']);
        }

        // other expenses
        $result = mysqli_query($link, "SELECT SUM(balance) as other_expenses FROM gl_codes WHERE portfolio = 'OTHER OPERATING EXPENSES' OR code IN (40001, 40002, 40004, 40006, 40007, 40008, 40009, 40011)")OR die("Could not get other  expenses");
        if(mysqli_num_rows($result)){
            $otherExpenses = mysqli_fetch_assoc($result);
            $expenses->setOtherExpenses($otherExpenses['other_expenses']);
        }

        // bad debts
        $result = mysqli_query($link, "SELECT SUM(balance) as bad_debts FROM gl_codes WHERE code IN('43001','43002')");
        if(mysqli_num_rows($result)){
            $badDebts = mysqli_fetch_assoc($result);
            $expenses->setBadDebts($badDebts['bad_debts']);
        }

        return $expenses;
    }
}

//$expenses =  FinancialPerformance::getExpenses();
//echo $expenses->getTotalExpenses();