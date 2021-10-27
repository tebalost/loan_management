<?php include "../config/session.php"; ?>

<!DOCTYPE html>
<html>
<head>

    <style>
        .loader {
            border: 16px solid #f3f3f3;
            border-radius: 50%;
            border-top: 16px solid orange;
            border-right: 16px solid green;
            border-bottom: 16px solid orange;
            border-left: 16px solid green;
            width: 120px;
            height: 120px;
            -webkit-animation: spin 2s linear infinite;
            animation: spin 2s linear infinite;
            margin: auto;

        }

        @-webkit-keyframes spin {
            0% {
                -webkit-transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>
<body>
<br><br><br><br><br><br><br><br><br>
<div style="width:100%;text-align:center;vertical-align:bottom">
    <div class="loader"></div>
    <?php
    $tid = $_SESSION['tid'];
    //Guarantor Information
    $gname = mysqli_real_escape_string($link, $_POST['g_name']);
    $gphone = mysqli_real_escape_string($link, $_POST['g_phone']);
    $g_rela = mysqli_real_escape_string($link, $_POST['grela']);
    $g_address = mysqli_real_escape_string($link, $_POST['gaddress']);
    //$guaratorStatus = mysqli_real_escape_string($link, $_POST['guaratorStatus']);
    //$guarantorRemarks = mysqli_real_escape_string($link, $_POST['guarantorRemarks']);

    //Repayment Information
    //FIXME Add Open Indicatots (O(Open), C(Closed)) on Loan Schedule.
    $pay_date = mysqli_real_escape_string($link, $_POST['pay_date']);
    $amount_topay = mysqli_real_escape_string($link, $_POST['amount_topay']);
    $loanStatus = mysqli_real_escape_string($link, $_POST['loanStatus']);
    $teller = mysqli_real_escape_string($link, $_POST['teller']);
    $repayment_remark = mysqli_real_escape_string($link, $_POST['repayment_remark']);
    $currentBalance = mysqli_real_escape_string($link, $_POST['currentBalance']);

    $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $txID = substr(str_shuffle($permitted_chars), 0, 10);

    //Loan Terms
    $loan_num_of_repayments = mysqli_real_escape_string($link, $_POST['loan_num_of_repayments']);
    $loan_payment_scheme = mysqli_real_escape_string($link, $_POST['loan_payment_scheme']);
    $loan_duration_period = mysqli_real_escape_string($link, $_POST['loan_duration_period']);
    $loan_duration = mysqli_real_escape_string($link, $_POST['loan_duration']);
    $loan_interest_period = mysqli_real_escape_string($link, $_POST['loan_interest_period']);
    $loan_interest = mysqli_real_escape_string($link, $_POST['loan_interest']);
    $loan_interest_type = mysqli_real_escape_string($link, $_POST['loan_interest_type']);
    $loan_interest_method = mysqli_real_escape_string($link, $_POST['loan_interest_method']);
    $loan_released_date = mysqli_real_escape_string($link, $_POST['loan_released_date']);
    $loan_disbursed_by_id = mysqli_real_escape_string($link, $_POST['loan_disbursed_by_id']);
    $principalAmount = mysqli_real_escape_string($link, $_POST['principalAmount']);
    $ownershipType = mysqli_real_escape_string($link, $_POST['ownershipType']);

    if ($_FILES["image"]["name"] != "") {
        $target_dir = "../img/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
        $check = getimagesize($_FILES["image"]["tmp_name"]);

        $id = "Loan" . "=" . rand(10000000, 340000000);

        $sourcepath = $_FILES["image"]["tmp_name"];
        $targetpath = "../img/" . $_FILES["image"]["name"];
        move_uploaded_file($sourcepath, $targetpath);

        $location = "img/" . $_FILES['image']['name'];
    } else {
        $location = "";
    }
    if ($_POST['loanId']!=="") {
        $loan_id = $_POST['loanId'];
        $borrower = $_POST['id'];

        $strJsonFileContents = file_get_contents('include/packages.json');
        $arrayOfTypes = json_decode($strJsonFileContents, true);

        $loanReason = mysqli_real_escape_string($link, $_POST['loanReasonUpdate']);

        foreach ($arrayOfTypes['loanReasonCode'] as $key => $value) {

            if ($_POST['loanReason'] == $value) {
                $loanReason = $key;
            }
        }

        $date_application = mysqli_real_escape_string($link, $_POST['date_application']);
        $agent = mysqli_real_escape_string($link, $_POST['agent']);
        $branch = mysqli_real_escape_string($link, $_POST['branch']);
        $loan_repayment_method = mysqli_real_escape_string($link, $_POST['loan_repayment_method']);


        $loan_create = date('Y-m-d H:i:s');

        $loan_maturity_period = $loan_duration . " " . $loan_duration_period;
        $interest_amount = round($_POST['loanFees']['Interest'], 2);

        $principal_due = round($principalAmount / $loan_duration, 2);
        $fees = round($principalAmount / $loan_duration, 2);

        //Get New Values After Calculations, Possibility of Decimal Places
        $totalDuePrincipal = $principal_due * $loan_duration;
        $totalInterest = $interest_amount * $loan_duration;
        $dueTotal = $amount_topay * $loan_duration;

        //Get the Differences
        $totalDuePrincipalDiff = $principalAmount - $totalDuePrincipal;
        $totalInterestDiff = $interest_amount - $totalInterest;
        $toPayDiff = $currentBalance - $dueTotal;


        $date = strtotime("$loan_released_date");
        $loanMaturity = date("Y-m-d", strtotime("+$loan_maturity_period", $date));

        //Loan Terms
        $loan_num_of_repayments = mysqli_real_escape_string($link, $_POST['loan_num_of_repayments']);
        $loan_payment_scheme = mysqli_real_escape_string($link, $_POST['loan_payment_scheme']);
        $loan_duration_period = mysqli_real_escape_string($link, $_POST['loan_duration_period']);
        $loan_duration = mysqli_real_escape_string($link, $_POST['loan_duration']);
        $loan_interest_period = mysqli_real_escape_string($link, $_POST['loan_interest_period']);
        $loan_interest = mysqli_real_escape_string($link, $_POST['loan_interest']);
        $loan_interest_type = mysqli_real_escape_string($link, $_POST['loan_interest_type']);
        $loan_interest_method = mysqli_real_escape_string($link, $_POST['loan_interest_method']);
        $loan_released_date = mysqli_real_escape_string($link, $_POST['loan_released_date']);
        $loan_disbursed_by_id = mysqli_real_escape_string($link, $_POST['loan_disbursed_by_id']);
        $principalAmount = mysqli_real_escape_string($link, $_POST['principalAmount']);
        $ownershipType = mysqli_real_escape_string($link, $_POST['ownershipType']);

        $loanProductCode = mysqli_real_escape_string($link, $_POST['productCode']);
        $compuscanAccountType = mysqli_real_escape_string($link, $_POST['compuscanAccountType']);


        $loanProduct = mysqli_real_escape_string($link, $_POST['loanProductUpdate']);
        $loanGL = explode("-", $loanProduct)[0];
        $account = mysqli_real_escape_string($link, $_POST['account']);
        //Get Product Id
        $loanProduct = explode("-", "$loanProduct")[1];

        $product = mysqli_fetch_assoc(mysqli_query($link, "select product_id from products where product_name='$loanProduct'")) or die(mysqli_error($link));
        $loanProduct = $product['product_id'];

        $loanFees = $_POST['loanFees'];
        $loanFeesGL = $_POST['loanFeesGL'];

        $totalFees = 0;

        //Remove all Fees
        $removeFees = mysqli_query($link, "delete from loan_fees where loan='$loan_id'");

        //Remove the Schedule
        $removeSchedule = mysqli_query($link, "delete from pay_schedule where get_id='$loan_id'");

        //Remove the Guarantor
        $removeGuarantor = mysqli_query($link, "delete from loan_guarantors where loan_id='$loan_id'");

        //Get the loan disbursements then remove them//
        $loan_disbursements = mysqli_fetch_assoc(mysqli_query($link, "select * from loan_disbursements where loan='$loan_id'"));
        $transaction = $loan_disbursements['transaction'];
        $disburseMethod = $loan_disbursements['disbursement_method'];

        //Remove transaction
        $removeLoanInfo = mysqli_query($link, "delete from loan_disbursements where loan='$loan_id'");


        //Remove the Loan Info and add the new one
        $removeLoanInfo = mysqli_query($link, "delete from loan_info where id='$loan_id'");

        //Update the loan Information
        $contract = $borrower . rand(1000, 9999);
        $insert_loan = mysqli_query($link, "INSERT INTO loan_info VALUES($loan_id,'$borrower','$account','$loanReason','$principalAmount', '$loan_create', '$agent', 
                    '$loanProduct', '$repayment_remark', '$amount_topay', '$pay_date','$currentBalance','$teller','$loanStatus', 
                    '$loan_num_of_repayments','$loan_payment_scheme','$loan_duration_period','$loan_duration','$loan_interest_period',
                    '$loan_interest','$loan_interest_type','$loan_interest_method','$loan_released_date','$loan_disbursed_by_id','Pending','$loanMaturity','$loan_create','$tid','0','$branch','$loan_repayment_method','$ownershipType','','','0','$interest_amount','$compuscanAccountType','$loanGL','$contract')")
        or die (mysqli_error($link));

        $disburse = mysqli_query($link, "insert into loan_disbursements values (0,'$loan_id',NOW(),'$transaction','$disburseMethod')");

        $insert_guarantor = mysqli_query($link, "INSERT INTO loan_guarantors VALUES(0,'$borrower','$loan_id','$gname','$g_rela','$gphone', '', '', '$location','$g_address')")
        or die (mysqli_error($link));

        foreach ($loanFees as $keyFee => $values) {
            foreach ($loanFeesGL as $keyGL => $valuesGL) {
                if ($keyFee == "Interest") {
                    $gl_code = '12003';//Hard Coded For now
                }
                if ($keyFee == $keyGL) {
                    $gl_code = $valuesGL;
                }
            }
            $insert_loan_fees = mysqli_query($link, "INSERT INTO loan_fees VALUES(0,'$keyFee','$values','$loan_id','$loan_create','$tid','$gl_code')") or die (mysqli_error($link));
            if ($keyFee !== "Interest") {
                $totalFees += $values;
            }
        }


        $updateLoanFees = mysqli_query($link, "update loan_info set fees='$totalFees' where id='$loan_id' ");
        $fees = round($totalFees / $loan_duration, 2);
        //Add Schedule Here....///
        $id = $loan_id;//Loan ID
        //Logged in user
        //Count Number of Repayments
        $total_interest = 0;
        $principal_owing = 0;
        $balance = 0;

        function PMT($rate = 0, $nper = 0, $pv = 0, $fv = 0, $type = 0)
        {
            if ($rate > 0) {
                return (-$fv - $pv * pow(1 + $rate, $nper)) / (1 + $rate * $type) / ((pow(1 + $rate, $nper) - 1) / $rate);
            } else {
                return (-$pv - $fv) / $nper;
            }
        }

        $rate = $loan_interest / 100; // rate = 10%
        $nper = $loan_duration; // months
        $pv = $principalAmount; //Principal Amount
        $fv = 0; //Expected Balance at the end
        $payment = round(PMT($rate, $nper, -$pv, $fv), 2);//EMI
        if ($loan_payment_scheme == "Monthly") {
            //Add Months
            for ($i = 0; $i < $loan_num_of_repayments; $i++) {
                $dateAdd = "$i Month";
                $date = strtotime("$pay_date");
                $repayment = date("Y-m-d", strtotime("+$dateAdd", $date));

                //Check if there is a balance in the Total Principal after calculating monthly instalments
                $totalDuePrincipalDiff = $principalAmount - $totalDuePrincipal;
                $toPayDiff = $currentBalance - $dueTotal;
                $totalFeesDiff =
                $last = $i + 1;

                if ($i == 0) {
                    $interest = $pv * $rate;
                    $principal = ($payment - $interest);
                } else {
                    //Get the New Principal
                    $pv = $pv - $principal;
                    $interest = $pv * $rate;
                    $principal = $payment - $interest;

                }

                if ($last == $loan_num_of_repayments) {
                    $payType = "Maturity";
                    $amount_topay += $toPayDiff;
                    $principal_due += $totalDuePrincipalDiff;
                    $total_due = $currentBalance - $balance;
                    $balance += $amount_topay;
                    $principal -= $totalDuePrincipalDiff;
                } else {
                    $total_due = $currentBalance - $balance;
                    $balance += $amount_topay;
                    $payType = "Repayment";
                }

                $insert = mysqli_query($link, "INSERT INTO pay_schedule(id,get_id,tid,schedule,balance,interest,payment,principal_due, pay_type,fees, total_due, open_indicator) VALUES(0,'$loan_id','$tid','$repayment','$amount_topay','$interest','0','$principal','$payType','$fees','$balance','O')") or die (mysqli_error($link));
            }
        }
        if ($loan_payment_scheme == "Weekly") {
            //Add Months
            for ($i = 0; $i < $loan_num_of_repayments; $i++) {
                $dateAdd = "$i Week";
                $date = strtotime("$pay_date");
                $repayment = date("Y-m-d", strtotime("+$dateAdd", $date));

                //Check if there is a balance in the Total Principal after calculating monthly instalments
                $totalDuePrincipalDiff = $principalAmount - $totalDuePrincipal;
                $toPayDiff = $currentBalance - $dueTotal;
                $last = $i + 1;
                if ($last == $loan_num_of_repayments) {
                    $payType = "Maturity";
                    $amount_topay += $toPayDiff;
                    $principal_due += $totalDuePrincipalDiff;
                } else {
                    $payType = "Repayment";
                }

                $insert = mysqli_query($link, "INSERT INTO pay_schedule(id,get_id,tid,schedule,balance,interest,payment,principal_due, pay_type,fees,open_indicator) VALUES(0,'$loan_id','$tid','$repayment','$amount_topay','$interest_amount','0','$principal_due','$payType','$fees','O')") or die (mysqli_error($link));
            }
        }
        if (!$insert && !$insert_loan_fees && !$insert_loan && !$disburse) {
            echo '<meta http-equiv="refresh" content="2;url=newloans.php?tid=' . $_SESSION['tid'] . '">';
            echo '<br>';
            echo '<span class="itext" style="color: #FF0000">Unable to Update Loan Information.....Please try again later!</span>';

        } else {
            unset($_SESSION['affordability']);
            unset($_SESSION['affordabilityCheck']);
            unset($_SESSION['instalment']);
            echo '<meta http-equiv="refresh" content="2;url=viewborrowersloan.php?id=' . $borrower . '&&document=&&loanId=' . $loan_id . '">';
            echo '<br>';
            echo '<span class="itext" style="color: #FF0000">Updating Loan Information.....4 more steps to complete the request.</span>';
        }
    } else {

        //Applicant Information
        $borrower = mysqli_real_escape_string($link, $_SESSION['id']);


        if (isset($_SESSION['affordabilityCheck'])) {
            $basicPay = $_SESSION['affordabilityCheck']['basicPay'];
            $additionalFixed = $_SESSION['affordabilityCheck']['additionalFixed'];
            $grossPay = $_SESSION['affordabilityCheck']['grossPay'];
            $statutory = $_SESSION['affordabilityCheck']['statutory'];
            $loanInstalments = $_SESSION['affordabilityCheck']['loanInstalments'];
            $netPay = $_SESSION['affordabilityCheck']['netPay'];
            $otherBankLoans = $_SESSION['affordabilityCheck']['otherBankLoans'];
            $monthly_living_expenses = $_SESSION['affordabilityCheck']['monthly_living_expenses'];
            $max_available = $_SESSION['affordabilityCheck']['max_available'];
            $compuscan = $_SESSION['affordabilityCheck']['compuscan'];
            $cdas = $_SESSION['affordabilityCheck']['cdas'];
        }

        //New Applicant Information
        if ($borrower == "") {
            $firstName = $_POST['newName'];
            $lastName = $_POST['newSurname'];
            $disposableIncome = $_POST['disposableIncome'];
            $employeeCode = $_POST['newCode'];
            $account = '508' . rand(1000000, 10000000);
            $employer = mysqli_real_escape_string($link, $_POST['employer']);
            $date = $_SESSION['dateofbirth'];
            $salary = $_POST['salary'];
            $membership = $_POST['membership'];

            //Check if employee was saved already
            $get = mysqli_query($link, "select * from borrowers where id='$borrower'");
            if (mysqli_num_rows($get) == 0) {
                $insert = mysqli_query($link, "insert into borrowers (id, fname, lname, employer, salary, disposable_income,created_by, emp_code, status, occupation, account, image, date_of_birth,member) 
                    values (0,'$firstName','$lastName',' $employer','$salary','$disposableIncome','$tid','$employeeCode','Partial','','$account','','$date','$membership')")
                or die (mysqli_error($link));
            }

            $get = mysqli_query($link, "SELECT * FROM borrowers where account='$account'") or die (mysqli_error($link));
            $row = mysqli_fetch_assoc($get);
            $borrower = $row['id'];

            //Get the borrower ID of the borrower just created
        }

        $strJsonFileContents = file_get_contents('include/packages.json');
        $arrayOfTypes = json_decode($strJsonFileContents, true);
        $loanReason = mysqli_real_escape_string($link, $_POST['loanReason']);

        foreach ($arrayOfTypes['loanReasonCode'] as $key => $value) {

            if ($_POST['loanReason'] == $value) {
                $loanReason = $key;
            }
        }

        $date_application = mysqli_real_escape_string($link, $_POST['date_application']);
        $agent = mysqli_real_escape_string($link, $_POST['agent']);
        $branch = mysqli_real_escape_string($link, $_POST['branch']);
        $loan_repayment_method = mysqli_real_escape_string($link, $_POST['loan_repayment_method']);
        if (isset($_POST['recipient'])) {
            $disbursementData = json_encode($_POST['recipient']);
        }


        $loanProductCode = mysqli_real_escape_string($link, $_POST['productCode']);
        $compuscanAccountType = mysqli_real_escape_string($link, $_POST['compuscanAccountType']);

        $loanFees = $_POST['loanFees'];
        $loanFeesGL = $_POST['loanFeesGL'];


        $loan_create = date('Y-m-d H:i:s');

        $loan_maturity_period = $loan_duration . " " . $loan_duration_period;
        $interest_amount = round($_POST['loanFees']['Interest'], 2);

        $principal_due = round($principalAmount / $loan_duration, 2);
        $fees = round($principalAmount / $loan_duration, 2);

        //Get New Values After Calculations, Possibility of Decimal Places
        $totalDuePrincipal = $principal_due * $loan_duration;
        $totalInterest = $interest_amount * $loan_duration;
        $dueTotal = $amount_topay * $loan_duration;

        //Get the Differences
        $totalDuePrincipalDiff = $principalAmount - $totalDuePrincipal;
        $totalInterestDiff = $interest_amount - $totalInterest;
        $toPayDiff = $currentBalance - $dueTotal;


        $date = strtotime("$loan_released_date");
        $loanMaturity = date("Y-m-d", strtotime("+$loan_maturity_period", $date));
        if (isset($_POST['loanProductUpdate'])) {
            $loanProduct = mysqli_real_escape_string($link, $_POST['loanProductUpdate']);
        } else {
            $loanProduct = mysqli_real_escape_string($link, $_POST['loanProduct']);
        }
        $loanGL = explode("-", $loanProduct)[0];
        $account = mysqli_real_escape_string($link, $_POST['account']);
        //Get Product Id
        $loanProduct = explode("-", "$loanProduct")[1];

        $product = mysqli_fetch_assoc(mysqli_query($link, "select product_id from products where product_name='$loanProduct'")) or die(mysqli_error($link));
        $loanProduct = $product['product_id'];


        $accountNumber = 0;
        $getMaxAccount = mysqli_query($link, "select * from loan_info where loan_product='$loanProduct'") or die(mysqli_error($link));
        $count = mysqli_fetch_assoc($getMaxAccount);
        $records = mysqli_num_rows($getMaxAccount) + 1;
        if (mysqli_num_rows($getMaxAccount) >= 0 && mysqli_num_rows($getMaxAccount) < 9) {
            $accountNumber = "0000" . $records;
        } else if (mysqli_num_rows($getMaxAccount) >= 9 && mysqli_num_rows($getMaxAccount) < 100) {
            $accountNumber = "000" . $records;
        } else if (mysqli_num_rows($getMaxAccount) >= 99 && mysqli_num_rows($getMaxAccount) < 1000) {
            $accountNumber = "00" . $records;
        } else if (mysqli_num_rows($getMaxAccount) >= 999 && mysqli_num_rows($getMaxAccount) < 10000) {
            $accountNumber = "0" . $records;
        } else {
            $accountNumber = "" . $records;
        }

        $account = date('Ym') . $loanProductCode . "$accountNumber";


        //Check If Collateral is needed before loan approval
        //Get all Applicable Fees then calculate charges//
        //echo "select product_id from products where product_id='$loanProduct'";

        $contract = $borrower . rand(1000, 9999);
        $insert_loan = mysqli_query($link, "INSERT INTO loan_info VALUES(0,'$borrower','$account','$loanReason','$principalAmount', '$loan_create', '$agent', 
                    '$loanProduct', '$repayment_remark', '$amount_topay', '$pay_date','$currentBalance','$teller','$loanStatus', 
                    '$loan_num_of_repayments','$loan_payment_scheme','$loan_duration_period','$loan_duration','$loan_interest_period',
                    '$loan_interest','$loan_interest_type','$loan_interest_method','$loan_released_date','$loan_disbursed_by_id','Pending','$loanMaturity','$loan_create','$tid','0','$branch','$loan_repayment_method','$ownershipType','','','0','$interest_amount','$compuscanAccountType','$loanGL','$contract')")
        or die (mysqli_error($link));

        $salary = mysqli_query($link, "SELECT * FROM borrowers_salaries WHERE borrower='$borrower'") or die(mysqli_error($link));
        $salaryInfo = mysqli_fetch_assoc($salary);

        if (mysqli_num_rows($salary) === 0) {
            mysqli_query($link, "INSERT INTO borrowers_salaries(id, borrower, basic_pay, additional_fixed_allowance, gross_pay, statutory_deductions, loan_instalments, net_pay, other_bank_loans, monthly_living_expenses, max_available, compuscan, cdas) 
                VALUES 
                (0,'$borrower','$basicPay','$additionalFixed','$grossPay','$statutory','$loanInstalments','$netPay','$otherBankLoans','$monthly_living_expenses','$max_available','$compuscan','$cdas')") or die(mysqli_error($link));
        } else {
            mysqli_query($link, "UPDATE borrowers_salaries SET  basic_pay='$basicPay', additional_fixed_allowance='$additionalFixed', 
gross_pay='$grossPay',statutory_deductions='$statutory',loan_instalments='$loanInstalments',net_pay='$netPay', 
other_bank_loans='$otherBankLoans',monthly_living_expenses='$monthly_living_expenses',max_available='$max_available', compuscan='$compuscan', cdas='$cdas' WHERE borrower='$borrower'");
        }

        $employer = $_POST['employer'];
        $dateOfBirth = $_SESSION['dateofbirth'];
        $salary = $_POST['salary'];
        $incomeFrequency = $_POST['incomeFrequency'];
        $membership = $_POST['membership'];

        $fin = mysqli_query($link, "select * from fin_info where get_id='$borrower'");
        if (mysqli_num_rows($fin) > 0) {
            $insert = mysqli_query($link, "UPDATE fin_info SET mincome = '$salary', frequency='$incomeFrequency' WHERE id = '$borrower'") or die (mysqli_error($link));
        } else {
            $insert = mysqli_query($link, "insert into fin_info values(0, '$borrower', '$tid', '$employer', '$salary', '$incomeFrequency')");
        }
        mysqli_query($link, "UPDATE borrowers SET modified_by='$tid', member='$membership', date_of_birth='$dateOfBirth', modified_on='$date' WHERE id = '$borrower'") or die (mysqli_error($link));

        $employer = mysqli_query($link, "SELECT * FROM employer_details WHERE id='$borrower'") or die(mysqli_error($link));

        $employerName = $_POST['employer'];

        if (mysqli_num_rows($employer) === 0) {
            mysqli_query($link, "INSERT INTO employer_details (id, employer_name) VALUES('$borrower','$employerName')") or die(mysqli_error($link));
        } else {
            mysqli_query($link, "UPDATE employer_details SET employer_name='$employerName' WHERE id='$borrower'");


        }

        $getLoan = mysqli_fetch_assoc(mysqli_query($link, "select max(id) from loan_info where borrower='$borrower'"));
        $loan_id = $getLoan['max(id)'];

        mysqli_query($link, "INSERT INTO loan_disbursements values (0,'$loan_id',NOW(),'$disbursementData','$loan_disbursed_by_id')");

        $getCollateral = mysqli_query($link, "select * from loan_settings where collateral='chkYes'");
        if (mysqli_num_rows($getCollateral) == 0) {
            //Dont Require Collateral for loan application
            mysqli_query($link, "update loan_info set upstatus='Completed' where id='$loan_id' and borrower='$borrower'");
        }

        $insert_guarantor = mysqli_query($link, "INSERT INTO loan_guarantors VALUES(0,'$borrower','$loan_id','$gname','$g_rela','$gphone', '', '', '$location','$g_address')")
        or die (mysqli_error($link));

        $totalFees = 0;

        foreach ($loanFees as $keyFee => $values) {
            foreach ($loanFeesGL as $keyGL => $valuesGL) {
                if ($keyFee == "Interest") {
                    $gl_code = '12003';//Hard Coded For now
                }
                if ($keyFee == $keyGL) {
                    $gl_code = $valuesGL;
                }
            }
            $insert_loan_fees = mysqli_query($link, "INSERT INTO loan_fees VALUES(0,'$keyFee','$values','$loan_id','$loan_create','$tid','$gl_code')") or die (mysqli_error($link));
            if ($keyFee !== "Interest") {
                $totalFees += $values;
            }
        }


        $updateLoanFees = mysqli_query($link, "update loan_info set fees='$totalFees' where id='$loan_id' ");
        $fees = round($totalFees / $loan_duration, 2);
        //Add Schedule Here....///
        $id = $loan_id;//Loan ID
        //Logged in user
        //Count Number of Repayments
        $total_interest = 0;
        $principal_owing = 0;
        $balance = 0;

        function PMT($rate = 0, $nper = 0, $pv = 0, $fv = 0, $type = 0)
        {
            if ($rate > 0) {
                return (-$fv - $pv * pow(1 + $rate, $nper)) / (1 + $rate * $type) / ((pow(1 + $rate, $nper) - 1) / $rate);
            } else {
                return (-$pv - $fv) / $nper;
            }
        }

        $rate = $loan_interest / 100; // rate = 10%
        $nper = $loan_duration; // months
        $pv = $principalAmount; //Principal Amount
        $fv = 0; //Expected Balance at the end
        $payment = round(PMT($rate, $nper, -$pv, $fv), 2);//EMI
        if ($loan_payment_scheme == "Monthly") {
            //Add Months
            for ($i = 0; $i < $loan_num_of_repayments; $i++) {
                $dateAdd = "$i Month";
                $date = strtotime("$pay_date");
                $repayment = date("Y-m-d", strtotime("+$dateAdd", $date));

                //Check if there is a balance in the Total Principal after calculating monthly instalments
                $totalDuePrincipalDiff = $principalAmount - $totalDuePrincipal;
                $toPayDiff = $currentBalance - $dueTotal;
                $totalFeesDiff =
                $last = $i + 1;

                if ($i == 0) {
                    $interest = $pv * $rate;
                    $principal = ($payment - $interest);
                } else {
                    //Get the New Principal
                    $pv = $pv - $principal;
                    $interest = $pv * $rate;
                    $principal = $payment - $interest;

                }

                if ($last == $loan_num_of_repayments) {
                    $payType = "Maturity";
                    $amount_topay += $toPayDiff;
                    $principal_due += $totalDuePrincipalDiff;
                    $total_due = $currentBalance - $balance;
                    $balance += $amount_topay;
                    $principal -= $totalDuePrincipalDiff;
                } else {
                    $total_due = $currentBalance - $balance;
                    $balance += $amount_topay;
                    $payType = "Repayment";
                }

                $insert = mysqli_query($link, "INSERT INTO pay_schedule(id,get_id,tid,schedule,balance,interest,payment,principal_due, pay_type,fees, total_due, open_indicator) VALUES(0,'$id','$tid','$repayment','$amount_topay','$interest','0','$principal','$payType','$fees','$balance','O')") or die (mysqli_error($link));
            }
        }
        if ($loan_payment_scheme == "Weekly") {
            //Add Months
            for ($i = 0; $i < $loan_num_of_repayments; $i++) {
                $dateAdd = "$i Week";
                $date = strtotime("$pay_date");
                $repayment = date("Y-m-d", strtotime("+$dateAdd", $date));

                //Check if there is a balance in the Total Principal after calculating monthly instalments
                $totalDuePrincipalDiff = $principalAmount - $totalDuePrincipal;
                $toPayDiff = $currentBalance - $dueTotal;
                $last = $i + 1;
                if ($last == $loan_num_of_repayments) {
                    $payType = "Maturity";
                    $amount_topay += $toPayDiff;
                    $principal_due += $totalDuePrincipalDiff;
                } else {
                    $payType = "Repayment";
                }

                $insert = mysqli_query($link, "INSERT INTO pay_schedule(id,get_id,tid,schedule,balance,interest,payment,principal_due, pay_type,fees,open_indicator) VALUES(0,'$id','$tid','$repayment','$amount_topay','$interest_amount','0','$principal_due','$payType','$fees','O')") or die (mysqli_error($link));
            }
        }
        if (!$insert_loan && !$insert_guarantor) {
            echo '<meta http-equiv="refresh" content="2;url=newloans.php?tid=' . $_SESSION['tid'] . '">';
            echo '<br>';
            echo '<span class="itext" style="color: #FF0000">Unable to Save Loan Information.....Please try again later!</span>';

        } else {
            unset($_SESSION['affordability']);
            unset($_SESSION['affordabilityCheck']);
            unset($_SESSION['instalment']);
            echo '<meta http-equiv="refresh" content="2;url=updateborrowers.php?id=' . $borrower . '&&document=&&product=' . $loanProduct . '">';
            echo '<br>';
            echo '<span class="itext" style="color: #FF0000">Saving Loan Information.....4 more steps to complete the request.</span>';
        }
    }


    ?>
</div>
</body>
</html>
