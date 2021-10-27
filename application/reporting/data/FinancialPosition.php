<?php
require_once "cbl-financial-position/NonCurrentAssets.php";
require_once "cbl-financial-position/CurrentAssets.php";
require_once "cbl-financial-position/CurrentLiabilities.php";
require_once "cbl-financial-position/EquityAndLiabilities.php";

require_once "../../../config/connect.php";

class FinancialPosition
{
    static function getCurrentAssets()
    {
        global $link;
        $currentAssets = new CurrentAssets;

        // loading the deposits maturity
        $result = mysqli_query($link, "SELECT sum(debit) as bank_deposit FROM system_transactions, bank_accounts 
                                                WHERE system_transactions.account = bank_accounts.accountNumber
                                                AND transactionType LIKE('Online Transfer')
                                                AND system_transactions.transaction LIKE ('Deposit')
                                                AND datediff(NOW(), system_transactions.date) < 365") or die("Couldn't get bank deposit less than year");
        if ($result) {
            $totalBankDeposit = mysqli_fetch_assoc($result);
            $currentAssets->setBankDepositMaturity($totalBankDeposit['bank_deposit']);
        }


        // loading other deposites
        $result = mysqli_query($link, "SELECT sum(debit) as mobile_money_deposit FROM system_transactions, bank_accounts 
                                            WHERE system_transactions.account = bank_accounts.accountNumber
                                            AND transactionType LIKE('Mobile Money')
                                            AND system_transactions.transaction LIKE ('Deposit')
                                            AND DATEDIFF(NOW(), system_transactions.date) < 365");
        if (mysqli_num_rows($result) > 0) {
            $totalMobileMoneyDeposit = mysqli_fetch_assoc($result);
            $currentAssets->setOtherDepositMaturity($totalMobileMoneyDeposit['mobile_money_deposit']);
        }


        // unearned interest
        $result = mysqli_query($link, "SELECT balance from gl_codes where code = 12003");
        if (mysqli_num_rows($result) > 0) {
            $unearnedInterest = mysqli_fetch_assoc($result);
            $currentAssets->setUnearnedInterest($unearnedInterest['balance']);
        } else
            $currentAssets->setUnearnedInterest(0);


        // Accounts receivable  in the organization
        $result = mysqli_query($link, "SELECT SUM(balance) as totol_recievables from gl_codes where portfolio = 'RECEIVABLES'");
        if (mysqli_num_rows($result) > 0) {
            $totalAccountReceivables = mysqli_fetch_assoc($result);
            $currentAssets->setAccountReceivable($totalAccountReceivables['totol_recievables']);
        }


        // loans repayable within a yeas
        $result = mysqli_query($link, "SELECT SUM(balance) FROM system_transactions,loan_statuses
                                            WHERE system_transactions.loan=loan_statuses.loan
                                            AND loan_statuses.`status`=''
                                            AND DATEDIFF(NOW(), loan_statuses.added_date) < 365") or die("Could not get loans payable in year");
        if(mysqli_num_rows($result)) {
            $loanPayableLessThanYear = mysqli_fetch_assoc($result);
            $currentAssets->setLoanPayableLessThanAYear($loanPayableLessThanYear['SUM(balance)']);
        }

        //provision of doubtful debts
        $result =  mysqli_query($link, "SELECT balance FROM gl_codes WHERE name='Provisions for Bad Debts'") or die("Could not get bad debts");
        if (mysqli_num_rows($result) > 0) {
            $provisionForBadDebts = mysqli_fetch_assoc($result);
            $currentAssets->setProvisionOfDoubtful($provisionForBadDebts['balance']);
        }

        // setting  other current assets
        $result = mysqli_query($link, "SELECT SUM(balance) other_current_assets FROM gl_codes WHERE portfolio LIKE 'LOAN PORTFOLIO' OR portfolio LIKE 'LOAN PORTFOLIO'");
        if (mysqli_num_rows($result) > 0) {
            $otherCurrentAssets = mysqli_fetch_assoc($result);
            $currentAssets->setOtherCurrentAssets($otherCurrentAssets['other_current_assets']);
        }

        return $currentAssets;
    }

    static function getNonCurrentAssets()
    {
        $nonCurrentAssets = new NonCurrentAssets;
        global $link;

        // setting the investment maturing in year or more
        $result = mysqli_query($link, "SELECT SUM(balance) FROM gl_codes WHERE name LIKE ('PPE - Investments')") or die("Could not get long term investment");
        if (mysqli_num_rows($result) > 0) {
            $LongTermInvestments = mysqli_fetch_assoc($result);
            $nonCurrentAssets->setInvestments($LongTermInvestments['SUM(balance)']);
        }

        // setting log term loans maturing within a period more than a year
        $result = mysqli_query($link, "SELECT SUM(balance) FROM gl_codes WHERE name LIKE ('Long-Term Loans')") or die("Could not get long term loans");
        if (mysqli_num_rows($result) > 0) {
            $LongTermLoans = mysqli_fetch_assoc($result);
            $nonCurrentAssets->setLongTermLoans($LongTermLoans['SUM(balance)']);
        }

        // get office furniture and fittings
        $result = mysqli_query($link, "SELECT SUM(balance) FROM gl_codes WHERE code IN ('10003','10002')") or die("Could not get furniture and fitting amount");
        if (mysqli_num_rows($result) > 0) {
            $totalOfficeFurniture = mysqli_fetch_assoc($result);
            $nonCurrentAssets->setOfficeFurnitureAndFittings($totalOfficeFurniture['SUM(balance)']);
        }

        // Setting the building with the value of premises
        $result = mysqli_query($link, "SELECT SUM(balance) FROM gl_codes WHERE name IN ('PPE - Building','PPE - Land') AND type='NON-CURRENT ASSETS'") or die("Could not get building and premises amount");
        if (mysqli_num_rows($result) > 0) {
            $building = mysqli_fetch_assoc($result);
            $nonCurrentAssets->setPremises($building['SUM(balance)']);
        }
        // provision for doubtful debts
        $result = mysqli_query($link,"SELECT balance FROM gl_codes WHERE name = 'Provisions for Bad Debts'") or die("Could not get provision for bad debts");
        if (mysqli_num_rows($result) > 0) {
            $provisionForBadDebts = mysqli_fetch_assoc($result);
            $nonCurrentAssets->setProvisionForBadDebts($provisionForBadDebts['balance']);
        }

        // setting other non current assets
        $result = mysqli_query($link,"SELECT balance FROM gl_codes WHERE portfolio = 'NON-CURRENT ASSETS'") or die("Could not get the rest of non current assets");
        if (mysqli_num_rows($result) > 0) {
            $otherNonCurrentAssets = mysqli_fetch_assoc($result);
            $nonCurrentAssets->setOtherNonCurrentAssets($otherNonCurrentAssets['balance']);
        }
        return $nonCurrentAssets;
    }

    static function getCurrentLiabilities()
    {
        $currentLiabilities = new CurrentLiabilities;
        global $link;
        //amount payable to debtors due
        $result = mysqli_query($link, "SELECT balance  FROM  gl_codes WHERE name='Loan Overpayments'");
        if (mysqli_num_rows($result) > 0) {
            $amountPayableToCreditors = mysqli_fetch_assoc($result);
            $currentLiabilities->setAmountPayableToCreditors($amountPayableToCreditors['balance']);;
        }

        return $currentLiabilities;
    }

    static function getNonCurrentLiabilities()
    {
        $nonCurrentLiabilities = new NonCurrentAssets();
    }

    static function getEquity(){
        global $link;
        $equity = new EquityAndLiabilities();

        // fully paid up shares
        $result = mysqli_query($link, "SELECT balance FROM gl_codes WHERE code='50002'") or die("Could not get share capital");
        if(mysqli_num_rows($result)){
            $shareCapital = mysqli_fetch_assoc($result);
            $equity->setShareCapital($shareCapital['balance']);
        }

        // fund capital
        $result = mysqli_query($link, "SELECT balance FROM gl_codes WHERE code='50003'") or die("Could not get fund capital");
        if(mysqli_num_rows($result)){
            $fundCapital = mysqli_fetch_assoc($result);
            $equity->setFundCapital($fundCapital['balance']);
        }

        // retained earnings
        $result = mysqli_query($link, "SELECT balance FROM gl_codes WHERE code='50001'") or die("Could not get fund capital");
        if(mysqli_num_rows($result)){
            $surplus = mysqli_fetch_assoc($result);
            $equity->setRetainedEarnings($surplus['balance']);
        }

        return $equity;
    }
}