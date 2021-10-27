<?php
require_once "../../../config/connect.php";
require_once "cbl_liquidity-return/BankDeposit.php";
require_once "cbl_liquidity-return/MobileMoney.php";


class LiquidityReturn
{
    static function getBankingDeposit(){
        global $link;
        $bankDeposit = new BankDeposit();


        // fnb deposit no older than 90 days
        $result = mysqli_query($link,"SELECT debit FROM system_transactions,bank_accounts 
                                            WHERE transaction like 'Deposit' 
                                            AND datediff(NOW(), system_transactions.date) < 90
                                            AND bank_accounts.accountNumber=system_transactions.account
                                            AND bankName='First National Bank'") OR die("could not get fnb deposit");
        if(mysqli_num_rows($result)){
            $deposit = mysqli_fetch_assoc($result);
            $bankDeposit->setFnbDeposit($deposit['debit']);
        }

        // Postbank deposit no older than 90 days
        $result = mysqli_query($link,"SELECT debit FROM system_transactions,bank_accounts 
                                            WHERE transaction like 'Deposit' 
                                            AND datediff(NOW(), system_transactions.date) < 90
                                            AND bank_accounts.accountNumber=system_transactions.account
                                            AND bankName='Postbank'") OR die("could not get Postbank deposit");
        if(mysqli_num_rows($result)){
            $deposit = mysqli_fetch_assoc($result);
            $bankDeposit->setPostBankDespoit($deposit['debit']);
        }

        // NetBank deposit no older than 90 days
        $result = mysqli_query($link,"SELECT debit FROM system_transactions,bank_accounts 
                                            WHERE transaction like 'Deposit' 
                                            AND datediff(NOW(), system_transactions.date) < 90
                                            AND bank_accounts.accountNumber=system_transactions.account
                                            AND bankName='NetBank'") OR die("could not get NetBank deposit");
        if(mysqli_num_rows($result)){
            $deposit = mysqli_fetch_assoc($result);
            $bankDeposit->setPostBankDespoit($deposit['debit']);
        }

        // Standard Lesotho Bank deposit no older than 90 days
        $result = mysqli_query($link,"SELECT debit FROM system_transactions,bank_accounts 
                                            WHERE transaction like 'Deposit' 
                                            AND datediff(NOW(), system_transactions.date) < 90
                                            AND bank_accounts.accountNumber=system_transactions.account
                                            AND bankName='Standard Lesotho Bank'") OR die("could not get Standard Lesotho Bank deposit");
        if(mysqli_num_rows($result)){
            $deposit = mysqli_fetch_assoc($result);
            $bankDeposit->setStandardBank($deposit['debit']);
        }

        return $bankDeposit;
    }


    static function getMobileMoneyDeposit(){
        global $link;
        $mobileMoney = new MobileMoney();


        // mpesa deposit not older 90 day
        $result = mysqli_query($link,"SELECT debit FROM system_transactions,bank_accounts 
                                            WHERE transaction like 'Deposit' 
                                            AND datediff(NOW(), system_transactions.date) < 90
                                            AND bank_accounts.accountNumber=system_transactions.account
                                            AND bankName='Vodacom M-pesa'") OR die("could not get M-pesa deposit");
        if(mysqli_num_rows($result)){
            $deposit = mysqli_fetch_assoc($result);
            $mobileMoney->setMpesa($deposit['debit']);
        }


        // ecocash deposit not older 90 day
        $result = mysqli_query($link,"SELECT debit FROM system_transactions,bank_accounts 
                                            WHERE transaction like 'Deposit' 
                                            AND datediff(NOW(), system_transactions.date) < 90
                                            AND bank_accounts.accountNumber=system_transactions.account
                                            AND bankName='Econet Ecocash'") OR die("could not get echo-cash deposit");
        if(mysqli_num_rows($result)){
            $deposit = mysqli_fetch_assoc($result);
            $mobileMoney->setMpesa($deposit['debit']);
        }

        return $mobileMoney;
    }

}
