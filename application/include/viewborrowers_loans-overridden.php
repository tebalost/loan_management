<div class="row">
    <?php
    $getCompanyInfo = mysqli_query($link, "SELECT * FROM systemset") or die(mysqli_error($link));
    $companyInfo = mysqli_fetch_assoc($getCompanyInfo);

    $logo = $companyInfo['image'];
    $companyName = $companyInfo['name'];

    $id = $_GET['id'];
    $postal = $info['addrs2'];
    $physical = $info['addrs1'];
    $district = $info['district'];
    $borrower = $info['id'];
    $image = $info['image'];
    $country = $info['country'];
    $gender = $info['gender'];
    $employer = $info['employer'];
    $email = $info['email'];
    $phone = $info['phone'];
    $loanId = $_GET['loanId'];
    $userStatus = $info['status'];
    $id_number = $info['id_number'];
    $passport = $info['passport'];
    $selectLoan = mysqli_query($link, "SELECT * FROM loan_info WHERE id = '$loanId'") or die (mysqli_error($link));
    while ($loan = mysqli_fetch_array($selectLoan)) {
        $account = $loan['baccount'];
        $date_time = $loan['application_date'];
        $agent = $loan['agent'];
        $release_date = substr($loan['application_date'],0,10);
        $loan_product = $loan['loan_product'];
        $loan_gl_code = $loan['gl_code'];

        $getProductName = mysqli_fetch_assoc(mysqli_query($link,"select * from products where product_id='$loan_product'"));
        $loan_product=$getProductName['product_name'];

        $maturity = "";
        $principal = $loan['amount'];
        $intest_rate = $loan['loan_interest'];
        $loan_interest_period = $loan['loan_interest_period'];
        $interest_amount = "0";
        $fees = $loan['fees'];
        $loan_duration = $loan['loan_duration'];
        $loan_duration_period = $loan['loan_duration_period'];
        $loan_interest_method = $loan['loan_interest_method'];
        if($loan_interest_method=="REDUCING_RATE_EQUAL_INSTALLMENTS"){
            $loan_interest_method="Reducing Balance - Equal Installments";
        }
        $loan_payment_scheme = $loan['loan_payment_scheme'];
        $loan_num_of_repayments = $loan['loan_num_of_repayments'];
        $loan_disbursed_by = $loan['loan_disbursed_by_id'];
        $payment_reference = $loan['payment_reference'];
        $interest_amount = $loan['interest_value'];

        $strJsonFileContents = file_get_contents('include/packages.json');
        $arrayOfTypes = json_decode($strJsonFileContents, true);
        $loan_status = $loan['status'];
        foreach ($arrayOfTypes['accountStatusCodes'] as $key => $value) {
            if ($loan_status == $key) {
                $loan_status = $value;
            }
        }

        ////Add Collateral on Customer Update Page for News Borrowers.... and Only Show is there is a pending loan
        /// requiring collated ...???? Add a button?? Already get the Loan that is pending.... After all done,
        /// Send internal message to Approver.

        $loan_desc = $loan['reason'];
        $instalment = $loan['amount_topay'];
        $penality = "";
        $due = "";
        $paid = "";
        $expectedBalance = $loan['balance'];
        $maturity = $loan['loan_maturity'];
        $edit_date = $loan['modified_date'];
        $edit_user = $loan['modified_by'];
        $branch = $loan['branch'];

        $getBranch = mysqli_fetch_assoc(mysqli_query($link, "select * from branches where code='$branch'"));
        $branchName = $getBranch['name'];
        $branchDistrict = $getBranch['location'];

        $strJsonFileContents = file_get_contents('include/packages.json');
        $arrayOfTypes = json_decode($strJsonFileContents, true);
        $loan_remarks = $loan['reason'];
        foreach ($arrayOfTypes['loanReasonCode'] as $key => $value) {
            if ($loan_remarks == $key) {
                $loan_remarks = $value;
            }
        }

        $paydate = $loan['pay_date'];

        $interest_due = $loan['interest_value'];
        $totalLoanBalance = round(($principal + $fees + $interest_due), 2);
    }

    $check = mysqli_query($link, "SELECT * FROM emp_permission WHERE tid = '" . $_SESSION['tid'] . "' AND module_name = 'Borrower Details'") or die ("Error" . mysqli_error($link));
    $get_check = mysqli_fetch_array($check);
    $pupdate = $get_check['pupdate'];
    $pread = $get_check['pread'];

    $check = mysqli_query($link, "SELECT * FROM emp_permission WHERE tid = '" . $_SESSION['tid'] . "' AND module_name = 'Loans Approval'") or die ("Error" . mysqli_error($link));
    $get_check = mysqli_fetch_array($check);
    if(mysqli_num_rows($check)>0) {
        $loanApproval = $get_check['pcreate'];
    }else{
        $loanApproval = 0;
    }
    ?>
    <section class="content-header"><h1><?php echo $title . " " . $fname . " " . $lname; ?> </h1>
        <div class="box box-widget">
            <div class="box-header with-border">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="user-block">
                            <?php if ($image) { ?>
                                <a href="#"><img class="img-circle"
                                                 src="../<?php echo $image . " " ?>"></a>
                            <?php } ?>
                            <span class="description" style="font-size:13px; color:#000000">
                                <b>ID:   </b><?php echo $id_number . " " ?>
                                 <br><b>Passport:   </b><?php echo $passport . " " ?>
                                <br> <b>Employee:   </b><?php echo $employer . " " ?>
                                <br><b>Gender:   </b><?php echo $gender . " " ?>
                                <br><b>Loan Agent: </b><?php echo $agent . " " ?>
                            </span>
                        </div><!-- /.user-block -->
                    </div><!-- /.col -->
                    <div class="col-sm-4">
                        <ul class="list-unstyled">
                            <li><b>Create Date: </b><?php echo substr($date_time, 0, 16); ?></li>
                            <li><b>Address:</b> <?php echo $physical . " " ?></li>
                            <li><b>Postal:</b> <?php echo $postal . " " ?></li>
                            <li><b>District:</b> <?php echo $district . " " ?></li>
                            <li><b>Country:</b> <?php echo $country . " " ?></li>
                        </ul>
                    </div>
                    <div class="col-sm-4">
                        <ul class="list-unstyled">
                            <?php if(isset($email)) {  ?>
                                <form method="post" action="">
                                    <li><b>Email:</b> <?php echo $email . " " ?>
                                        <div class="btn-group-horizontal">
                                            <input type="submit" name="sendStatement" value="Email Statement" class="btn-xs bg-red">
                                        </div>
                                    </li>
                                </form>

                                <?php
                                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                    // Something posted
                                    if (isset($_POST['sendStatement'])) {
                                        include "reporting/mail.php";
                                    } else {
                                        // Assume btnSubmit
                                    }
                                }
                                ?>
                            <?php } ?>

                            <li><b>Mobile:</b> <?php echo $phone . " " ?>
                                <div class="btn-group-horizontal">
                                    <a type="button" class="btn-xs bg-red"
                                       href="#">Send
                                        SMS</a>
                                </div>
                            </li>

                        </ul>
                    </div>
                </div><!-- /.row -->
                <div class="row">
                    <div class="col-sm-8">
                        <div class="btn-group-horizontal">
                            <a type="button" class="btn bg-orange"
                               href="listloans.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>">
                                <i class="fa fa-mail-reply-all"></i>&nbsp;Back
                            </a>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="pull-left">
                            <div class="input-group-btn">
                                <button type="button" class="btn btn-info dropdown-toggle margin"
                                        data-toggle="dropdown">Current Loan Documents
                                    <span class="fa fa-caret-down"></span>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="loanApplication.php?id=<?php echo $_GET['id'];?>&&loanId=<?php echo $_GET['loanId'] ?>">Loan Application</a></li>
                                    <li><a href="loanAgreement.php?id=<?php echo $_GET['id'];?>&&loanId=<?php echo $_GET['loanId'] ?>">Loan Agreement Contract</a></li>
                                    <li><a href="authorizationDeduction.php?id=<?php echo $_GET['id'];?>&&loanId=<?php echo $_GET['loanId'] ?>">Authorization Deduction Form</a></li>
                                    <li><a href="disbursementReport.php?id=<?php echo $_GET['id'];?>&&loanId=<?php echo $_GET['loanId'] ?>">Repayment Report</a></li>
                                    <li><a href="loanStatement.php?id=<?php echo $_GET['id'];?>&&loanId=<?php echo $_GET['loanId'] ?>">Loan Statement</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-success">
            <div class="panel-heading">

                <h3 class="panel-title">
                    <i class="fa fa-money"></i>&nbsp; <b>
                        <?php echo $loan_product . " for " . $loan_num_of_repayments . " " . $loan_duration_period; ?></b>
                </h3>

            </div>
            <?php if (isset($_POST['savePayment'])) {
                $tid = $_SESSION['tid'];
                $name = mysqli_real_escape_string($link, $_POST['teller']);
                $account = mysqli_real_escape_string($link, $_POST['account']);
                $balance = mysqli_real_escape_string($link, $_POST['account']);
                $customer = mysqli_real_escape_string($link, $_POST['customer']);
                $loan = mysqli_real_escape_string($link, $_POST['loan']);
                $pay_date = mysqli_real_escape_string($link, $_POST['pay_date']) . date(' H:i:s');
                $amount_paid_today = mysqli_real_escape_string($link, $_POST['paid_amount']);
                $remarks = mysqli_real_escape_string($link, $_POST['remarks']);
                $paymentMethod = mysqli_real_escape_string($link, $_POST['paymentMethod']);
                $reference = mysqli_real_escape_string($link, $_POST['reference']);
                $toAccount = mysqli_real_escape_string($link, $_POST['toAccount']);

                $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $txID = substr(str_shuffle($permitted_chars), 0, 10);

                //Get names of borrower
                $names = mysqli_fetch_assoc(mysqli_query($link, "select fname, lname, phone, email from borrowers where id='$customer'"));
                $fname = $names['fname'];
                $lname = $names['lname'];
                $phone = $names['phone'];
                $email = $names['email'];

                $gl_code = explode("-",$toAccount)[0];
                $bankAccount = explode("-",$toAccount)[1];

                //Get All payment//
                $get = mysqli_fetch_assoc(mysqli_query($link, "select sum(amount_to_pay) from payments where account=$account and customer='$customer'"));

                //Get the Opening Balance of the loan account
                $maxdate=mysqli_fetch_assoc(mysqli_query($link,"select max(pay_date) from payments where account='$account'"));
                $max_date=$maxdate['max(pay_date)'];

                $accoutBal = mysqli_fetch_assoc(mysqli_query($link,"select balance from payments where account='$account' and pay_date='$max_date'"));
                $from_balance=$accoutBal['balance'];
                if($from_balance==""){
                    $start_balance=mysqli_fetch_assoc(mysqli_query($link,"select balance from loan_info where baccount='$account'"));
                    $from_balance=$start_balance['balance'];////Loan Amount
                }

                $accountBalance = $expectedBalance - ($get['sum(amount_to_pay)'] + $amount_paid_today);

                $insert = mysqli_query($link, "INSERT INTO payments(id,tid, account,balance, customer, loan, pay_date, amount_to_pay, remarks, payment_method,reference,tx_id,gl_code) 
                                                VALUES(0,'$tid','$account','$accountBalance','$customer','$loan','$pay_date','$amount_paid_today','$remarks','$paymentMethod','$reference','$txID','$gl_code')")
                or die (mysqli_error($link));

                $balanceCheck_to = mysqli_fetch_assoc(mysqli_query($link,"select balance from bank_accounts where accountNumber='$bankAccount'"));
                $to_balance=$balanceCheck_to['balance'];//Opening Balance
                $toBalance = $amount_paid_today+$to_balance;

                //Receiving Account(Debit)
                $to_transaction = mysqli_query($link, "INSERT into system_transactions values (0,NOW(),'$bankAccount','Loan Repayment-$account','$to_balance','$amount_paid_today','0','$toBalance','$tid','','$txID')");
                mysqli_query($link,"update bank_accounts set balance ='$toBalance' where accountNumber='$bankAccount'");
                mysqli_query($link,"update gl_codes set balance ='$toBalance' where code='$gl_code'");
                //Debit the journal entry with payment
                $receiving_transaction_journal = mysqli_query($link, "INSERT into journal_transactions values (0,NOW(),'$gl_code','Loan Repayment $account','$to_balance','$amount_paid_today','0','$toBalance','$tid','$txID','','')");

                //Loan Account (Credit)
                $from_transaction = mysqli_query($link, "INSERT into system_transactions values (0,NOW(),'$account','Loan Repayment-$account','$from_balance','0','$amount_paid_today','$accountBalance','$tid','$loanId','$txID')");

                //If payment made is equivalent to the instalment, split the payments accordingly and allocate payments to respective GL Account in the Journal
                //Update the schedule
                $get_outstanding_schedule = mysqli_fetch_assoc(mysqli_query($link,"select * from pay_schedule where get_id='$loanId' and open_indicator='O' LIMIT 1"));
                $rowId_Current = $get_outstanding_schedule['id'];
                $principal_due=$get_outstanding_schedule['principal_due'];
                $interest_due=$get_outstanding_schedule['interest'];
                $fees_due=$get_outstanding_schedule['fees'];
                $penalty_due=$get_outstanding_schedule['penalty'];

                $expectedInstalment = $get_outstanding_schedule['balance'];

                if($amount_paid_today == $expectedInstalment){

                    //Get the Fees for the Loan and it's GL Code
                    $currentLoanFees=mysqli_fetch_assoc(mysqli_query($link,"select gl_code from loan_fees where loan='$loanId' and fee_name!='Interest' group by gl_code"));
                    $fees_gl_code=$currentLoanFees['gl_code'];
                    $totalFees=$fees_due;

                    //Get the Interest for the Loan and its GL Code
                    $currentLoanInterest=mysqli_fetch_assoc(mysqli_query($link,"select gl_code from loan_fees where loan='$loanId' and fee_name='Interest' group by gl_code"));
                    $interest_gl_code=$currentLoanInterest['gl_code'];
                    $interest_value=$interest_due;

                    //Get the Opening Balances of the Loan, Fees and the Interest
                    $loanGLBalance = mysqli_fetch_assoc(mysqli_query($link,"select balance from gl_codes where code='$loan_gl_code'"));
                    $feesGLBalance = mysqli_fetch_assoc(mysqli_query($link,"select balance from gl_codes where code='$fees_gl_code'"));
                    $interestGLBalance = mysqli_fetch_assoc(mysqli_query($link,"select balance from gl_codes where code='$interest_gl_code'"));

                    $gl_loan_balance=$loanGLBalance['balance'];
                    $gl_fees_balance=$feesGLBalance['balance'];
                    $gl_interest_balance=$interestGLBalance['balance'];

                    $newLoanBalance=$gl_loan_balance-$principal_due;
                    $newFeesBalance=$gl_fees_balance-$fees_due;
                    $newInterestBalance=$gl_interest_balance-$interest_due;

                    mysqli_query($link, "update gl_codes set balance='$newFeesBalance' where code='$fees_gl_code'");
                    mysqli_query($link, "update gl_codes set balance='$newInterestBalance' where code='$interest_gl_code'");
                    mysqli_query($link, "update gl_codes set balance='$newLoanBalance' where code='$loan_gl_code'");

                    //Credit the Loan GL, Fees and Interest
                    $fees_journal = mysqli_query($link, "INSERT into journal_transactions values (0,NOW(),'$fees_gl_code','Fees Repayment $account','$gl_fees_balance','','$fees_due','$newFeesBalance','$tid','$txID','','')");
                    $interest_journal = mysqli_query($link, "INSERT into journal_transactions values (0,NOW(),'$interest_gl_code','Interest Repayment $account','$gl_interest_balance','','$interest_due','$newInterestBalance','$tid','$txID','','')");
                    $loan_journal = mysqli_query($link, "INSERT into journal_transactions values (0,NOW(),'$loan_gl_code','Loan Repayment $account','$gl_loan_balance','','$principal_due','$newLoanBalance','$tid','$txID','','')");

                    //Update Schedule
                    mysqli_query($link,"update pay_schedule set payment='$amount_paid_today', principal_payment='$principal_due', 
                    interest_payment='$interest_due', fees_payment='$fees_due', penalty_payment='$penalty_due', open_indicator='C', payment_tx_id='$txID', total_due='0' where id='$rowId_Current'");

                    //Credit the LOAN GL, INTEREST GL, FEES GL
                    //DEBIT THE RECEIVING ACCOUNT
                }

                if($amount_paid_today !== $expectedInstalment){
                    /*                    $principal_due=$_POST['principal_repayment_amount_i'];
                    $interest_due=$_POST['interest_repayment_amount_i'];
                    $fees_due=$_POST['fees_repayment_amount_i'];
                    $penalty_due=$_POST['penalty_repayment_amount_i'];*/

                    ///FIXME How do we handle the repayment that was outstanding --- Specify that it's a repayment of outstanding due when making payment//

                    $totalPaid = $amount_paid_today;
                    if($totalPaid>=$penalty_due){
                        $penalty_due=$penalty_due;
                        $totalPaid=$totalPaid-$penalty_due;
                    }
                    else if($totalPaid<$penalty_due){
                        $penalty_due+=$totalPaid;
                        $totalPaid=0;
                    }
                    if($totalPaid>=$fees_due){
                        $fees_due=$fees_due;
                        $totalPaid=$totalPaid-$fees_due;
                    }else if($totalPaid<$fees_due){
                        $fees_due+=$totalPaid;
                        $totalPaid=0;
                    }
                    if($totalPaid>=$interest_due){
                        $interest_due=$interest_due;
                        $totalPaid=$totalPaid-$interest_due;
                    }else if($totalPaid<$interest_due){
                        $interest_due+=$totalPaid;
                        $totalPaid=0;
                    }
                    if($totalPaid>=$principal_due){
                        $principal_due=$principal_due;
                        $totalPaid=$totalPaid-$principal_due;
                    }else if($totalPaid<$principal_due) {
                        $principal_due=$totalPaid;
                    }else if($totalPaid<$principal_due){
                        $principal_due+=$totalPaid;
                        $totalPaid=0;
                    }


                    //If there was overpayment, distribute the fees///
                    $consecutivePayment = $totalPaid;
                    if($totalPaid>=0){
                        //Get the second instalment value
                        //Close the schedule and get values for the next schedule
                       $update=mysqli_query($link,"update pay_schedule set payment='$expectedInstalment', principal_payment='$principal_due', 
                         interest_payment='$interest_due', fees_payment='$fees_due', penalty_payment='$penalty_due', open_indicator='C', payment_tx_id='$txID', total_due='0' where id='$rowId_Current'");

                       //Get Next Schedule
                        $get_outstanding_schedule = mysqli_fetch_assoc(mysqli_query($link,"select * from pay_schedule where get_id='$loanId' and open_indicator='O' LIMIT 1"));
                        $rowId = $get_outstanding_schedule['id'];
                        $principal_due_new=$get_outstanding_schedule['principal_due'];
                        $interest_due_new=$get_outstanding_schedule['interest'];
                        $fees_due_new=$get_outstanding_schedule['fees'];
                        $penalty_due_new=$get_outstanding_schedule['penalty'];

                        $expectedInstalment = $get_outstanding_schedule['balance'];

                        ///FIXME Build a recursive function here
                        ///
                        //$totalPaid = $amount_paid_today;
                        if($totalPaid>=$penalty_due_new){
                            $penalty_due+=$penalty_due_new;
                            $new_penalty_due+=$penalty_due_new;
                            $totalPaid=$totalPaid-$penalty_due_new;
                        }else if($totalPaid<$penalty_due_new){
                            $penalty_due+=$totalPaid;
                            $new_penalty_due+=$totalPaid;
                            $totalPaid=0;
                        }


                        if($totalPaid>=$fees_due_new){
                            $fees_due+=$fees_due_new;
                            $new_fees_due=$fees_due_new;
                            $totalPaid=$totalPaid-$fees_due_new;
                        }else if($totalPaid<$fees_due_new){
                            $fees_due+=$totalPaid;
                            $new_fees_due+=$totalPaid;
                            $totalPaid=0;
                        }

                        if($totalPaid>=$interest_due_new){
                            $interest_due+=$interest_due_new;
                            $new_interest_due+=$interest_due_new;
                            $totalPaid=$totalPaid-$interest_due_new;
                        }else if($totalPaid<$interest_due_new){
                            $interest_due+=$totalPaid;
                            $new_interest_due+=$totalPaid;
                            $totalPaid=0;
                        }


                        if($totalPaid>=$principal_due_new){
                            $principal_due+=$principal_due_new;
                            $new_principal_due+=$principal_due_new;
                            $totalPaid=$totalPaid-$principal_due_new;
                        }else if($totalPaid<$principal_due_new){
                            $principal_due+=$totalPaid;
                            $new_principal_due+=$totalPaid;
                            $totalPaid=0;
                        }

                    }
                        if($totalPaid==$expectedInstalment) {
                            $update = mysqli_query($link, "update pay_schedule set payment='$consecutivePayment', principal_payment='$principal_due', 
                            interest_payment='$interest_due', fees_payment='$fees_due', penalty_payment='$penalty_due', open_indicator='C', payment_tx_id='$txID', total_due='C' where id='$rowId'");//Update Payments for the schedule of the first open instalment
                        }
                        else if($totalPaid<$expectedInstalment){
                            //Close the First Payment
                           /* $update = mysqli_query($link, "update pay_schedule set payment='$expectedInstalment', principal_payment='$principal_due',
                            interest_payment='$interest_due', fees_payment='$fees_due', penalty_payment='$penalty_due', open_indicator='C', payment_tx_id='$txID', total_due='C' where id='$rowId_Current'");//Update Payments for the schedule of the first open instalment
                           */ //Close the 2nd Payment
                            $update = mysqli_query($link, "update pay_schedule set payment='$consecutivePayment', principal_payment='$new_principal_due', 
                            interest_payment='$new_interest_due', fees_payment='$new_fees_due', penalty_payment='$new_penalty_due', open_indicator='C', payment_tx_id='$txID', total_due='O' where id='$rowId'");
                        }

                    //Get the Fees for the Loan and it's GL Code
                    $currentLoanFees=mysqli_fetch_assoc(mysqli_query($link,"select gl_code from loan_fees where loan='$loanId' and fee_name!='Interest' group by gl_code"));
                    $fees_gl_code=$currentLoanFees['gl_code'];
                    $totalFees=$fees_due;

                    //Get the Interest for the Loan and its GL Code
                    $currentLoanInterest=mysqli_fetch_assoc(mysqli_query($link,"select gl_code from loan_fees where loan='$loanId' and fee_name='Interest' group by gl_code"));
                    $interest_gl_code=$currentLoanInterest['gl_code'];
                    $interest_value=$interest_due;

                    //Get the Opening Balances of the Loan, Fees and the Interest
                    $loanGLBalance = mysqli_fetch_assoc(mysqli_query($link,"select balance from gl_codes where code='$loan_gl_code'"));
                    $feesGLBalance = mysqli_fetch_assoc(mysqli_query($link,"select balance from gl_codes where code='$fees_gl_code'"));
                    $interestGLBalance = mysqli_fetch_assoc(mysqli_query($link,"select balance from gl_codes where code='$interest_gl_code'"));

                    $gl_loan_balance=$loanGLBalance['balance'];
                    $gl_fees_balance=$feesGLBalance['balance'];
                    $gl_interest_balance=$interestGLBalance['balance'];

                    $newLoanBalance=$gl_loan_balance-$principal_due;
                    $newFeesBalance=$gl_fees_balance-$fees_due;
                    $newInterestBalance=$gl_interest_balance-$interest_due;

                    mysqli_query($link, "update gl_codes set balance='$newFeesBalance' where code='$fees_gl_code'");
                    mysqli_query($link, "update gl_codes set balance='$newInterestBalance' where code='$interest_gl_code'");
                    mysqli_query($link, "update gl_codes set balance='$newLoanBalance' where code='$loan_gl_code'");

                    //Credit the Loan GL, Fees and Interest
                    $fees_journal = mysqli_query($link, "INSERT into journal_transactions values (0,NOW(),'$fees_gl_code','Fees Repayment $account','$gl_fees_balance','','$fees_due','$newFeesBalance','$tid','$txID','','')");
                    $interest_journal = mysqli_query($link, "INSERT into journal_transactions values (0,NOW(),'$interest_gl_code','Interest Repayment $account','$gl_interest_balance','','$interest_due','$newInterestBalance','$tid','$txID','','')");
                    $loan_journal = mysqli_query($link, "INSERT into journal_transactions values (0,NOW(),'$loan_gl_code','Loan Repayment $account','$gl_loan_balance','','$principal_due','$newLoanBalance','$tid','$txID','','')");

                    //Update Schedule
                    //If the paid amount is less that exptecte...Leave the account Open
                    //Credit the LOAN GL, INTEREST GL, FEES GL
                    //DEBIT THE RECEIVING ACCOUNT
                }
                //Send an SMS for the payment received.
                class MyMobileAPI
                {

                    public function __construct() {
                        $this->url = 'http://api.smsportal.com/api5/http5.aspx';
                        $this->username = 'serumula'; //your login username
                        $this->password = '5erumul@2020'; //your login password
                        //$this->validityperiod = '24'; //optional- set desired validity (represents hours)
                    }

                    public function checkCredits() {
                        $data = array(
                            'Type' => 'credits',
                            'Username' => $this->username,
                            'Password' => $this->password
                        );
                        $response = $this->querySmsServer($data);
                        // NULL response only if connection to sms server failed or timed out
                        if ($response == NULL) {
                            return '???';
                        } elseif ($response->call_result->result) {
                            echo '</br>Credits: ' .  $response->data->credits;
                            return $response->data->credits;
                        }
                    }

                    public function sendSms($mobile_number, $msg) {
                        $data = array(
                            'Type' => 'sendparam',
                            'Username' => $this->username,
                            'Password' => $this->password,
                            'numto' => $mobile_number, //phone numbers (can be comma seperated)
                            //'validityperiod' => $this->validityperiod, //the duration of validity
                            'data1' => $msg //your sms message

                        );
                        $response = $this->querySmsServer($data);
                        return $this->returnResult($response);
                    }

                    // query API server and return response in object format
                    private function querySmsServer($data, $optional_headers = null) {

                        $ch = curl_init($this->url);
                        curl_setopt($ch, CURLOPT_POST, true);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        // prevent large delays in PHP execution by setting timeouts while connecting and querying the 3rd party server
                        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 2000); // response wait time
                        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 2000); // output response time
                        $response = curl_exec($ch);
                        if (!$response) return NULL;
                        else return new SimpleXMLElement($response);
                    }

                    // handle sms server response
                    private function returnResult($response) {
                        $return = new StdClass();
                        $return->pass = NULL;
                        $return->msg = '';
                        if ($response == NULL) {
                            $return->pass = FALSE;
                            $return->msg = 'SMS connection error.';
                        } elseif ($response->call_result->result) {
                            $return->pass = 'CallResult: '.TRUE . '</br>';
                            $return->msg = 'EventId: '.$response->send_info->eventid .'</br>Error: '.$response->call_result->error;
                        } else {
                            $return->pass = 'CallResult: '.FALSE. '</br>';
                            $return->msg = 'Error: '.$response->call_result->error;
                        }
                        //echo $return->pass;
                        //echo $return->msg;
                        return $return;
                    }

                }
                $paymentDate=date('d/m/Y');
                $content= "PFS: Thank you for the payment of M$amount_paid_today paid towards your loan. REF: $reference $paymentDate";
                $sendSMS = new MyMobileAPI();
                $sendSMS->sendSms("$phone","$content");

                $image = "<thml><body><img src='$logo' style='max-width: 283px; max-height: 93px' alt='logo' class='img-responsive'/></body></thml>";

                $to = "$email";
                $subject = "$companyName - Loan Status";
                $body = "Hi $fname $lname,";
                $body .= "\nHere is the status of your loan application";
                $body .= "\n";
                $body .= "\n";
                $body .= "\n$content";
                $body .= "\n";
                $body .= "\nRegards.";
                $body .= "\n$image.";
                $additionalheaders = "From: pulamaliboho@sbs-eazy.loans";
                mail($to, $subject, $body, $additionalheaders);


                mysqli_query($link, "insert into sms_messages values(0,'$phone','$content',NOW(),'','','$loanId')");


                if ($accountBalance <= 0) {
                    $loan_update = date('Y-m-d H:i:s');
                    //P – Paid Up
                    mysqli_query($link, "update loan_info set status='P' where borrower='$borrower' and id='$loanId'");
                    mysqli_query($link, "insert into loan_statuses values (0,'P','$tid','$loan_update','$loanId','Loan Fully Paid')");

                    if($accountBalance<0){
                        //Add a Journal Entry for Over-repayment
                        //Debit the journal entry with payment
                        //Get the balance and GL Code of Over Payment
                        $gl_overpayments=mysqli_fetch_assoc(mysqli_query($link,"select * from gl_codes where name='Loan Overpayments'"));
                        $overPayGL=$gl_overpayments['code'];
                        $overPayBalance=$gl_overpayments['balance'];
                        $final_balance=$overPayBalance-$accountBalance;
                        mysqli_query($link,"update gl_codes set balance='$final_balance' where code='$overPayGL'");
                        $accountBalance=-1*$accountBalance;

                        //Journal Entry///
                        $loan_journal = mysqli_query($link, "INSERT into journal_transactions values (0,NOW(),'$overPayGL','Loan Overpayment $account','$overPayBalance','$accountBalance','','$final_balance','$tid','$txID','','')");
                        }
                }

                if (!$insert) {
                    echo '<div class="alert alert-success" >
                                        <a href = "#" class = "close" data-dismiss= "alert"> &times;</a>
                                         Unable to make payment.....Please try again later!&nbsp; &nbsp;&nbsp;
                                           </div>';
                } else {
                    echo '<div class="alert alert-success" >
                                        <a href = "#" class = "close" data-dismiss= "alert"> &times;</a>
                                         Payment Successfully Saved!&nbsp; &nbsp;&nbsp;
                                           </div>';
                }
            } ?>
            <?php
            if (isset($_POST['submit'])) {
                $id = $_GET['id'];
                $tid = $_SESSION['tid'];
                $name = mysqli_real_escape_string($link, $_POST['name']);
                $type_of_collateral = mysqli_real_escape_string($link, $_POST['type_of_collateral']);
                $model = mysqli_real_escape_string($link, $_POST['model']);
                $make = mysqli_real_escape_string($link, $_POST['make']);
                $serial_number = mysqli_real_escape_string($link, $_POST['serial_number']);
                $estimated_price = mysqli_real_escape_string($link, $_POST['estimated_price']);

                $image = addslashes(file_get_contents($_FILES['image']['tmp_name']));
                $image_name = addslashes($_FILES['image']['name']);
                $image_size = getimagesize($_FILES['image']['tmp_name']);

                move_uploaded_file($_FILES["image"]["tmp_name"], "../cimage/" . $_FILES["image"]["name"]);

                $cimage = "cimage/" . $_FILES['image']['name'];

                $observation = mysqli_real_escape_string($link, $_POST['observation']);

//upload random name/number
                $rd2 = mt_rand(1000, 9999) . "_File";

                //Check that we have a file
                if ((!empty($_FILES["uploaded_file"])) && ($_FILES['uploaded_file']['error'] == 0)) {
                    //Check if the file is JPEG image and it's size is less than 350Kb
                    $filename = basename($_FILES['uploaded_file']['name']);

                    $ext = substr($filename, strrpos($filename, '.') + 1);

                    if (($ext != "exe") && ($_FILES["uploaded_file"]["type"] != "application/x-msdownload")) {
                        //Determine the path to which we want to save this file
                        //$newname = dirname(__FILE__).'/upload/'.$filename;
                        $newname = "document/" . $rd2 . "_" . $filename;
                        //Check if the file with the same name is already exists on the server

                        //Attempt to move the uploaded file to it's new place
                        if ((move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $newname))) {
                            //successful upload
                            // echo "It's done! The file has been saved as: ".$newname;

                            //Check if All Needed Requirements were met to enable Loan Approval

                            $insert = mysqli_query($link, "INSERT INTO collateral VALUES(0,'$id','$tid','$name','$type_of_collateral','$model','$make','$serial_number','$estimated_price','$filename','$cimage','$observation','$loanId')") or die (mysqli_error($link));
                            if (!$insert) {
                                echo '<div class="alert alert-warning" >
                                        <a href = "#" class = "close" data-dismiss= "alert"> &times;</a>
                                         Collateral Failed to Save!&nbsp; &nbsp;&nbsp;
                                           </div>';
                            } else {
                                echo '<div class="alert alert-success" >
                                        <a href = "#" class = "close" data-dismiss= "alert"> &times;</a>
                                         Collateral Successfully Saved!&nbsp; &nbsp;&nbsp;
                                           </div>';
                                mysqli_query($link, "update loan_info set upstatus='Completed' where id='$loanId' and borrower='$borrower'");
                            }
                        }
                    }
                }
            }
            ?>
            <?php if (isset($_POST['saveComments'])) {
                $tid = $_SESSION['tid'];
                $customer = mysqli_real_escape_string($link, $_POST['customer']);
                $account = mysqli_real_escape_string($link, $_POST['account']);
                $comments = mysqli_real_escape_string($link, $_POST['comment']);


                $insert = mysqli_query($link, "INSERT INTO comments
                                                            VALUES(0,'$tid','$comments', NOW(),'$account','$customer')")
                or die (mysqli_error($link));
                if (!$insert) {
                    echo '<div class="alert alert-success" >
                                                    <a href = "#" class = "close" data-dismiss= "alert"> &times;</a>
                                                     Unable to Save Comments.....Please try again later!&nbsp; &nbsp;&nbsp;
                                                       </div>';
                } else {
                    echo '<div class="alert alert-success" >
                                                    <a href = "#" class = "close" data-dismiss= "alert"> &times;</a>
                                                     Comments saved Successfully Saved!&nbsp; &nbsp;&nbsp;
                                                       </div>';
                }
            } ?>
            <div class="col-md-14">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <?php //Check if Collateral is Accepted
                        $getCollateral = mysqli_query($link, "select * from loan_settings where collateral='chkYes'");
                        $id = $_GET['id'];
                        $search = mysqli_query($link, "SELECT * FROM collateral WHERE idm = '$id' and loan='$loanId'") or die (mysqli_error($link));

                        ?>
                        <?php if (($loan_status !== 'Pending' || (mysqli_num_rows($getCollateral) > 0 && mysqli_num_rows($search) > 0))) { ?>
                            <li class="active"><a href="#tab_terms" data-toggle="tab">Terms</a></li>
                        <?php } ?>
                        <?php if (($loan_status == 'Pending' && mysqli_num_rows($getCollateral) == 0)) { ?>
                            <li class="active"><a href="#tab_terms" data-toggle="tab">Terms</a></li>
                        <?php } ?>
                        <?php if (($loan_status == 'Pending' && mysqli_num_rows($getCollateral) > 0)) { ?>
                            <li class=""><a href="#tab_terms" data-toggle="tab">Terms</a></li>
                        <?php } ?>
                        <li style=""><a href="#tab_schedule" data-toggle="tab">Schedule</a></li>
                        <li style=""><a href="#tab_repayments" data-toggle="tab">Repayments</a></li>
                        <li style=""><a href="#tab_statement" data-toggle="tab">Statement</a></li>
                        <li style=""><a href="#tab_loan_fees" data-toggle="tab">Loan Fees</a></li>
                        <?php
                        //Check if There are Penalities allowed on Loans
                        $getPenalty = mysqli_query($link, "select * from loan_settings where penalty_fees='1'");
                        if (mysqli_num_rows($getPenalty)) {
                            ?>
                            <li style=""><a href="#tab_penalty_settings" data-toggle="tab">Penalty Settings</a></li>
                        <?php } ?>
                        <?php

                        if (mysqli_num_rows($getCollateral)) {
                            $id = $_GET['id'];
                            $search = mysqli_query($link, "SELECT * FROM collateral WHERE idm = '$id' and loan='$loanId'") or die (mysqli_error($link));
                            ?>
                            <li class="<?php if ($loan_status == 'Pending' && mysqli_num_rows($search) == 0) {
                                echo "active";
                            } ?>"><a href="#tab_collateral" data-toggle="tab">Collateral</a></li>
                        <?php } else {
                            //Dont Require Collateral
                            mysqli_query($link, "update loan_info set upstatus='Completed' where id='$loanId' and borrower='$borrower'");
                        } ?>
                        <li style=""><a href="#tab_guarantor" data-toggle="tab">Guarantor</a></li>
                        <?php if ($loan_status === "Handed Over") { ?>
                            <li style=""><a href="#tab_collections" data-toggle="tab">Collection Fees</a></li>
                        <?php } ?>
                        <li style=""><a href="#tab_other_income" data-toggle="tab">Income</a></li>
                        <li style=""><a href="#tab_documents" data-toggle="tab">Documents</a></li>
                        <li style=""><a href="#tab_comments" data-toggle="tab">Comments</a></li>
                        <li style=""><a href="#tab_timeline" data-toggle="tab">Timeline</a></li>
                    </ul>
                    <style>
                        #makePayment {
                            display: none;
                        }

                        #makeComment {
                            display: none;
                        }
                    </style>
                    <div class="tab-content">
                        <?php
                        $id = $_GET['id'];
                        $search = mysqli_query($link, "SELECT * FROM collateral WHERE idm = '$id' and loan='$loanId'") or die (mysqli_error($link));
                        ?>
                        <?php if (($loan_status !== 'Pending' || (mysqli_num_rows($getCollateral) > 0 && mysqli_num_rows($search) > 0))) { ?>
                        <div class="tab-pane active" id="tab_terms">
                            <?php } ?>
                            <?php if (($loan_status == 'Pending' && mysqli_num_rows($getCollateral) == 0)) { ?>
                            <div class="tab-pane active" id="tab_terms">
                                <?php } ?>
                                <?php if (($loan_status == 'Pending' && mysqli_num_rows($getCollateral) > 0)) { ?>
                                <div class="tab-pane" id="tab_terms">
                                    <?php } ?>
                                    <div class="box-body no-padding">
                                        <table class="table table-condensed">
                                            <tbody>
                                            <tr>
                                                <td><b>Account Number</b></td>
                                                <td><b><?php echo $account; ?></b></td>
                                            </tr>
                                            <tr>
                                                <td><b>Branch Account Openend</b></td>
                                                <td><b><?php echo $branchName; ?></b></td>
                                            </tr>
                                            <?php
                                            ///Get the date the status changed to Open and Active ""
                                            $maxDateActive = mysqli_query($link, "select max(added_date) from loan_statuses where loan='$loanId'");

                                            $max_date = mysqli_fetch_assoc($maxDateActive);
                                            $release_date = substr($max_date['max(added_date)'],0,10);
                                            $actionDate = $max_date['max(added_date)'];
                                            //Get the status of the maximum date

                                            if(mysqli_num_rows($maxDateActive)>0 && $actionDate!="") {
                                                $loanStatus = mysqli_fetch_assoc(mysqli_query($link, "select status from loan_statuses where loan='$loanId' and added_date='$actionDate'"));
                                                $status = $loanStatus['status'];
                                            }
                                            else{
                                                $status = $loan_status;
                                            }

                                            if(mysqli_num_rows($maxDateActive)>0 && $status==""){

                                                ?>
                                                <tr>
                                                    <td><b>Loan Age</b></td>
                                                    <td><b>
                                                            <?php
                                                            $today = date('Y-m-d');
                                                            function dateDifference($release_date, $today, $differenceFormat = '%d Days')
                                                            {
                                                                $datetime1 = date_create($release_date);
                                                                $datetime2 = date_create($today);

                                                                $interval = date_diff($datetime1, $datetime2);

                                                                return $interval->format($differenceFormat);
                                                                //echo $interval;

                                                            }

                                                            echo dateDifference($release_date, $today, $differenceFormat = '%d Days');
                                                            ?>
                                                        </b>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            <tr>
                                                <td><b>Loan Status</b></td>
                                                <td>
                                                    <?php if ($loan_status=="DECLINED"){ ?>
                                                        <span class="label label-danger "><?php echo $loan_status; ?></span>
                                                    <?php } else if ($loan_status==""){  ?>
                                                        <span class="label label-success"><?php echo "Open and Active"; ?></span>
                                                    <?php } else{ ?>
                                                        <span class="label label-warning"><?php echo $loan_status; ?></span>
                                                    <?php } ?>
                                                    <?php
                                                    if ($loan_status !== "Paid Up" && $loan_status !== "Account Closed" && $loan_status !== "Early Settlement") {
                                                        if (mysqli_num_rows($getCollateral) > 0) {
                                                            echo ($pupdate == '1' && $loanApproval=='1' && $userStatus == "Active" && mysqli_num_rows($search) > 0) ? '<a href="#myModal ' . $borrower . '"> <i data-target="#myModal' . $loanId . '" data-toggle="modal"><span class="label label-warning ">Change Status</span></a>' : '';
                                                        } else {
                                                            echo ($pupdate == '1' && $loanApproval=='1' && $userStatus == "Active") ? '<a href="#myModal ' . $borrower . '"> <i data-target="#myModal' . $loanId . '" data-toggle="modal"><span class="label label-warning ">Change Status</span></a>' : '';
                                                        }
                                                    }
                                                    ?>

                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Approval Reference</b></td>
                                                <td><?php echo $payment_reference; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Reminders</b></td>
                                                <td>None <a href="#" target="_blank">Set loan reminders</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" class="bg-navy disabled color-palette">
                                                    Terms
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Disbursed By</b></td>
                                                <td><?php echo $loan_disbursed_by; ?></td>
                                            </tr>
                                            <tr>

                                                <td><b>Principal Amount</b></td>
                                                <td><?php echo "<b>" . $_SESSION['currency'] . "&nbsp;" . number_format($principal, 2, ".", ",") . "</b>"; ?></td>

                                            </tr>
                                            <!--Get Fees Required Before disbursement -->
                                            <?php
                                            $get = mysqli_query($link, "SELECT * FROM loan_fees_settings WHERE $principal BETWEEN min_loan AND max_loan and deductible='1'");
                                            if (mysqli_num_rows($get) > 0) {
                                                $totalDeductible = 0;
                                                while ($charges = mysqli_fetch_assoc($get)) {
                                                    $totalDeductible += $charges['fee_amount'];
                                                    ?>
                                                    <tr>
                                                        <td><b><?php echo $charges['fee_name']; ?></b></td>
                                                        <td><?php echo "<b>" . $_SESSION['currency'] . "&nbsp;" . number_format($charges['fee_amount'], 2, ".", ",") . "</b>"; ?></td>
                                                    </tr>
                                                <?php }
                                                $disbursedAmount = $principal - $totalDeductible;
                                                mysqli_query($link, "update loan_info set disbursed_amount = '$disbursedAmount' where id='$loanId'");
                                                ?>
                                                <tr>
                                                    <td><b>Disbursed Amount</b></td>
                                                    <td><?php echo "<b>" . $_SESSION['currency'] . "&nbsp;" . number_format($disbursedAmount, 2, ".", ",") . "</b>"; ?></td>
                                                </tr>
                                            <?php } ?>
                                            <tr>
                                                <td><b>Interest</b></td>
                                                <td><?php echo $intest_rate; ?>%/<?php echo $loan_interest_period; ?>
                                                    <?php
                                                    //$interest_due = $loan['interest_value'];
                                                    echo "<b>(" . $_SESSION['currency'] . "&nbsp;" . number_format($interest_amount, 2, ".", ",") . ")</b>";
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Fees</b></td>
                                                <td>
                                                    <?php
                                                    echo "<b>" . $_SESSION['currency'] . "&nbsp;" . number_format($fees, 2, ".", ",") . "</b>";
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>

                                                <td><b>Expected Repayment</b></td>
                                                <td><?php
                                                    $expected_payment = $principal + $interest_due + $fees;
                                                    echo "<b>" . $_SESSION['currency'] . "&nbsp;" . number_format($expectedBalance, 2, ".", ",") . "</b>"; ?>
                                                </td>

                                            </tr>
                                            <tr>
                                                <td><b>Instalment</b></td>
                                                <td><?php echo "<b>" . $_SESSION['currency'] . "&nbsp;" . number_format($instalment, 2, ".", ",") . "</b>"; ?></td>
                                            </tr>
                                            <tr>

                                                <td><b>Release Date</b></td>
                                                <td><?php echo $release_date; ?></td>

                                            </tr>
                                            <tr>
                                                <td><b>Interest Method</b></td>
                                                <td> <?php echo $loan_interest_method; ?>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td><b>Loan Duration</b></td>
                                                <td><?php echo $loan_duration . " " . $loan_duration_period; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Repayment Cycle</b></td>
                                                <td><?php echo $loan_payment_scheme; ?>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td><b>Number of Repayments</b></td>
                                                <td><?php echo $loan_num_of_repayments; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Interest Start Date</b></td>
                                                <td><?php echo $paydate; ?></td>
                                            </tr>
                                            <tr>
                                                <td><b>Maturity Date</b></td>
                                                <td><b><?php echo $maturity; ?></b></td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" class="bg-navy disabled color-palette">
                                                    Description
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <b><?php echo $loan_remarks; ?></b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <i>Loan added on <?php echo $date_time; ?></i>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <i>Loan last edited on <?php echo $edit_date; ?> by <?php
                                                        //Get User Names from user
                                                        $get_user = mysqli_fetch_assoc(mysqli_query($link, "select * from user where id='$edit_user'"));
                                                        $edit_user = $get_user['name'];
                                                        echo $edit_user;
                                                        ?></i>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <script>
                                        $(document).ready(function () {
                                            initialise_confirm_action();
                                        });
                                    </script>
                                    <!-- Modal -->
                                    <div class="modal fade" id="loanApplicationModal" role="dialog">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal">×</button>
                                                    <h4 class="modal-title">Loan Applications/Agreements</h4>
                                                </div>
                                                <div class="modal-body"><p>You have not uploaded any loan
                                                        applications/agreements</p>
                                                    <hr>
                                                    <h4><b><a href="#" target="_blank">Upload Loan Templates</a></b>
                                                    </h4>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default pull-left"
                                                            data-dismiss="modal">
                                                        Close
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab_schedule">
                                    <div class="tab_content">
                                        <div class="row">
                                            <div class="col-sm-3">

                                                <div class="input-group-btn">
                                                    <button type="button" class="btn btn-info dropdown-toggle margin"
                                                            data-toggle="dropdown" aria-expanded="false">Original Loan
                                                        Schedule
                                                        <span class="fa fa-caret-down"></span>
                                                    </button>
                                                    <ul class="dropdown-menu" role="menu">
                                                        <li><a href="#">Print Schedule</a></li>
                                                        <li><a href="#">Download in PDF</a></li>
                                                        <li><a href="#">Download in Excel</a></li>
                                                        <li><a href="#">Download in CSV</a></li>
                                                        <li><a href="#">Email Schedule to Borrower</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="btn-group-horizontal">
                                                    <a type="button" class="btn bg-gray margin" href="#">Edit
                                                        Schedule</a>
                                                    <a type="button" class="btn bg-gray margin" href="#">Early
                                                        Settlement</a>
                                                    <a type="button" class="btn bg-gray margin" href="#">Add
                                                        Disbursement</a>
                                                </div>
                                            </div>
                                            <div class="col-xs-3">
                                                <div class="btn-group">

                                                    <div class="input-group-btn">
                                                        <button type="button"
                                                                class="btn btn-info dropdown-toggle margin"
                                                                data-toggle="dropdown" aria-expanded="false">Adjusted
                                                            Loan Schedule<span class="fa fa-caret-down"></span>
                                                        </button>
                                                        <ul class="dropdown-menu" role="menu">


                                                            <li><a href="#">Print Schedule</a></li>

                                                            <li><a href="#">Download in PDF</a></li>

                                                            <li><a href="#">Download in Excel</a></li>

                                                            <li><a href="#">Download in CSV</a></li>

                                                            <li><a href="#">Email Schedule to Borrower</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="box box-info">
                                            <div class="box-body table-responsive no-padding">
                                                <table id="daily_collections"
                                                       class="table table-bordered table-condensed table-hover">
                                                    <thead>
                                                    <tr style="background-color: #F2F8FF">
                                                        <th style="width: 10px">
                                                            <b>#</b>
                                                        </th>
                                                        <th class=""><b>Date</b></th>
                                                        <th class=""><b>Description</b></th>
                                                        <th class="text-right"><b>Principal Oustanding</b></th>
                                                        <th class="text-right"><b>Interest</b></th>
                                                        <th class="text-right"><b>Fees</b></th>
                                                        <th class="text-right"><b>Instalment</b></th>
                                                        <th class="text-right"><b>Paid</b></th>
                                                        <th class="text-right"><b>Pending Due</b></th>
                                                        <th class="text-right"><b>Total Due</b></th>
                                                        <th class="text-right"><b>Principal Balance</b></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td></td>
                                                        <td class="text-right">
                                                        </td>
                                                        <td class="text-right">
                                                        </td>
                                                        <td class="text-right">
                                                        </td>
                                                        <td class="text-right">
                                                        </td>
                                                        <td class="text-right">
                                                        </td>
                                                        <td class="text-right">
                                                        </td>
                                                        <td class="text-right">
                                                        </td>
                                                        <td class="text-right">
                                                        </td>
                                                        <td class="text-right">
                                                        </td>
                                                        <td class="text-right"><?php echo number_format($principal, 2, ".", ","); ?>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                    $totalPaid = 0;
                                                    $selectPayments = mysqli_query($link, "SELECT * FROM payments WHERE customer = '$borrower' and account='$account'") or die (mysqli_error($link));
                                                    while ($payment = mysqli_fetch_assoc($selectPayments)) {
                                                        $totalPaid += $payment['amount_to_pay'];
                                                        $transactionID=$payment['tx_id'];
                                                        //Get payments for the schedules
                                                        $schedulePayment = mysqli_fetch_assoc(mysqli_query($link,"select sum(principal_payment), sum(interest_payment), sum(fees_payment) from pay_schedule where payment_tx_id='$transactionID' group by payment_tx_id"));
                                                        ?>
                                                        <tr>
                                                            <td class="bg-gray"></td>
                                                            <th width="10%"
                                                                class="bg-gray"><?php echo substr($payment['pay_date'], 0, 10); ?></th>
                                                            <th width="15%" class="bg-gray"><?php echo $transactionID; ?> Payment</th>
                                                            <td class="bg-gray text-right"><strong><?php echo number_format($schedulePayment['sum(principal_payment)'], 2, ".", ","); ?></strong></td>
                                                            <td class="bg-gray text-right"><strong><?php echo number_format($schedulePayment['sum(interest_payment)'], 2, ".", ","); ?></td>
                                                            <td class="bg-gray text-right"><strong><?php echo number_format($schedulePayment['sum(fees_payment)'], 2, ".", ","); ?></td>
                                                            <td class="bg-gray"></td>
                                                            <th class="bg-gray text-right"><?php echo number_format($payment['amount_to_pay'], 2, ".", ","); ?></th>
                                                            <td class="bg-gray"></td>
                                                            <td class="bg-gray"></td>
                                                            <td class="bg-gray"></td>
                                                        </tr>
                                                    <?php } ?>

                                                    <?php
                                                    //Get All Schedules of the loan
                                                    $count = 1;
                                                    $principal_total = 0;
                                                    $interest_total = 0;
                                                    $due_total = $principalTotalOwing = 0;
                                                    $fees_total = $pending_due_total = 0;
                                                    $monthToDateDueTotal=0;
                                                    $getSchedule = mysqli_query($link, "SELECT * FROM pay_schedule where get_id='$loanId'");
                                                    ?>
                                                    <?php while ($schedule = mysqli_fetch_assoc($getSchedule)) { ?>

                                                        <?php

                                                        if ($count < $loan_duration && $due_total <= $totalPaid) {
                                                            echo "<tr class=\"success\">";//class=success for all repayments, get interest from the paid amount
                                                        } else if ($count < $loan_duration && $due_total > $totalPaid) {
                                                            echo "<tr class=\"\">";//class=success for all repayments, get interest from the paid amount
                                                        } else {
                                                            echo "<tr class=\"danger\">";
                                                        }
                                                        ?>
                                                        <td><?php echo $count; ?></td>
                                                        <td><?php echo $schedule['schedule']; ?></td>
                                                        <td class="">
                                                            <?php
                                                            if ($count < $loan_duration) {
                                                                echo $schedule['pay_type'];
                                                            } else {
                                                                echo $schedule['pay_type'];
                                                            }
                                                            ?>
                                                        </td>
                                                        <td class="text-right">
                                                            <?php
                                                            $principal_due = round($schedule['principal_due'], 2);
                                                            echo number_format($principal_due, 2, ".", ",");
                                                            $principal_total += $principal_due;
                                                            ?>
                                                        </td>
                                                        <td class="text-right">
                                                            <?php
                                                            $interest_due = round($schedule['interest'], 2);
                                                            echo number_format($interest_due, 2, ".", ",");
                                                            $interest_total += $interest_due;
                                                            ?>
                                                        </td>
                                                        <td class="text-right">
                                                            <?php
                                                            $fees_due = round($schedule['fees'], 2);
                                                            echo number_format($fees_due, 2, ".", ",");
                                                            $fees_total += $fees_due;
                                                            ?>
                                                        </td>
                                                        <td class="text-right">
                                                            <?php
                                                            $totalDue = number_format($schedule['balance'], 2, ".", ",");
                                                            echo $totalDue;
                                                            $due_total += $schedule['balance'];
                                                            ?>
                                                        </td>
                                                        <td class="text-right">
                                                            <?php
                                                            $paymentReceived = number_format($schedule['payment'], 2, ".", ",");
                                                            echo $paymentReceived;
                                                            ?>
                                                        </td>
                                                        <td class="text-right">
                                                            <?php
                                                            $balance = $schedule['balance']-$schedule['payment'];
                                                            $pending_due_total+=$balance;
                                                            $monthToDateDue = number_format($pending_due_total, 2, ".", ",");
                                                            if($pending_due_total>=0) {
                                                                echo $monthToDateDue;
                                                            }
                                                            $monthToDateDueTotal += $schedule['payment'];
                                                            ?>
                                                        </td>
                                                        <td class="text-bold text-right"></td>
                                                        <td class="text-right">   <?php
                                                            $principal=$principal-$schedule['principal_due'];
                                                            echo number_format($principal, 2, ".", ",");
                                                            ?></td>
                                                        </tr>
                                                        <?php
                                                        $count++;
                                                    }
                                                    ?>

                                                    <tr>
                                                        <td></td>
                                                        <td class="">
                                                        </td>
                                                        <td class=""><b>Total Due</b>
                                                        </td>
                                                        <td class="text-right">
                                                            <b><?php echo number_format($principal_total, 2, ".", ","); ?></b>
                                                        </td>

                                                        <td class="text-right">
                                                            <b><?php echo number_format($interest_total, 2, ".", ","); ?></b>
                                                        </td>
                                                        <td class="text-right">
                                                            <b><?php echo number_format($fees_total, 2, ".", ","); ?></b>

                                                        <td class="text-right">
                                                            <b><?php echo number_format($due_total, 2, ".", ","); ?></b>
                                                        </td>

                                                        <td class="text-right"><b>-</b></td>
                                                        <td class="text-right"></td>
                                                        <td class="text-right"><b>-</b></td>

                                                    </tr>

                                                    <tr>
                                                        <td></td>
                                                        <td class="">
                                                        </td>
                                                        <td class=""><b>Total Paid</b>
                                                        </td>
                                                        <td class="text-right">
                                                        </td>
                                                        <td class="text-right">
                                                        </td>
                                                        <td class="text-right">
                                                        </td>
                                                        <td class="text-right">
                                                        </td>

                                                        <td class="text-right">
                                                            <b><?php echo number_format($totalPaid, 2, ".", ","); ?></b>
                                                        </td>
                                                        <td class="text-right text-bold"><b></b>
                                                        </td>
                                                        <td class="text-right">
                                                        </td>
                                                        <td class="text-right"><b></b>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td></td>
                                                        <td class="">
                                                        </td>
                                                        <td class=""><b>Total Pending Due</b>
                                                        </td>
                                                        <td class="text-right">
                                                        </td>
                                                        <td class="text-right">
                                                        </td>
                                                        <td class="text-right">
                                                        </td>
                                                        <td class="text-right">
                                                        </td>

                                                        <td class="text-right text-bold"><b><?php echo number_format($expectedBalance - $totalPaid, 2, ".", ","); ?></b>
                                                        </td>
                                                        <td class="text-right"><b></b>
                                                        </td>
                                                        <td class="text-right">
                                                        </td>
                                                        <td class="text-right"><b></b>
                                                        </td>
                                                    </tr>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <small><p><b>Principal Balance</b>: The above Principal Balance column is
                                                calculated
                                                as
                                                follows: Any collection date that is less than or equal to today's date,
                                                total
                                                principal is reduced by the principal payments only. Any collection date
                                                after
                                                today's date, the total principal is reduced by the total principal due
                                                until
                                                the
                                                collection date.</p>
                                            <p><b>Branch Holidays</b>: If you don't want schedule to be generated on
                                                holidays or
                                                Fridays/Saturdays/Sundays, visit <b><a href="#">Branch Holidays</a></b>.
                                            </p>
                                        </small>
                                        <script>
                                            $(document).ready(function () {
                                                initialise_plus_minus();
                                            });
                                        </script>

                                        <script>
                                            $(document).ready(function () {
                                                initialise_confirm_action();
                                            });
                                        </script>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab_repayments">
                                    <?php
                                    //Get Current Balance and set it a maximum to pay
                                    $maxdate = mysqli_fetch_assoc(mysqli_query($link, "SELECT max(pay_date) FROM payments WHERE customer = '$id' and account='$account'"));
                                    $lastPaid = $maxdate['max(pay_date)'];
                                    $checkPayment = mysqli_query($link, "SELECT balance FROM payments WHERE customer = '$id' and account='$account' and pay_date='$lastPaid'");
                                    if (mysqli_num_rows($checkPayment) > 0) {
                                        $maxBalance = mysqli_fetch_assoc($checkPayment);
                                        $currentBalance = $maxBalance['balance'];
                                        $allowDigits = strlen($currentBalance);
                                        if ($currentBalance == "") {
                                            $currentBalance = $expectedBalance;
                                            $allowDigits = strlen($expectedBalance);
                                        }
                                    } else {
                                        $currentBalance = $expectedBalance;
                                        $allowDigits = strlen($currentBalance);
                                    }
                                    $lastPaid = substr($maxdate['max(pay_date)'],0,10);
                                    if($lastPaid==""){
                                        $lastPaid = $release_date;
                                    }
                                    ?>
                                    <div class="btn-group-horizontal">
                                        <?php if ($loan_status == "") { ?>
                                            <button class="btn bg-gray margin" type="button" id="addPayment">Add
                                                Repayment
                                            </button>

                                            <a type="button" class="btn bg-gray margin" href="printpayment.php"
                                               target="_blank"
                                               class="btn btn-primary btn-flat"><i class="fa fa-print"></i>&nbsp;Print
                                                Payments</a>
                                            <a type="button" class="btn bg-gray margin" href="excelpayment.php"
                                               target="_blank"
                                               class="btn btn-success btn-flat"><i class="fa fa-send"></i>&nbsp;Export
                                                Excel</a>
                                            <h4>Total Repayment:
                                            <b><?php echo number_format($expectedBalance, 2, ".", ","); ?></b> |
                                            Instalment:
                                            <b><?php echo number_format($instalment, 2, ".", ","); ?></b> |
                                            Current Balance:
                                            <b><?php echo number_format($currentBalance, 2, ".", ","); ?></b> |
                                            Overdue:
                                            <?php $ovedueDays = dateDifference($lastPaid, $today, $differenceFormat = '%m');

                                            if($ovedueDays==1){
                                                ?>
                                                <b style="color: red"><?php echo dateDifference($lastPaid, $today, $differenceFormat = '%m Month %d days'); ?></b></h4>

                                            <?php } else if($ovedueDays>1){?>
                                                <b style="color: red"><?php echo dateDifference($lastPaid, $today, $differenceFormat = '%m Months %d days'); ?></b></h4>
                                                ?>
                                            <?php }

                                            else{?>
                                                <b style="color: green"><?php echo dateDifference($lastPaid, $today, $differenceFormat = '%d days'); ?></b></h4>
                                            <?php }
                                        }?>
                                        <form class="form-horizontal" id="makePayment" method="post"
                                              enctype="multipart/form-data"
                                              action="#">

                                            <div class="box-body">
                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">Customer</label>
                                                    <div class="col-sm-6">
                                                        <select class="customer select2" name="customer"
                                                                style="width: 100%;"
                                                                readonly="">
                                                            <?php
                                                            echo '<option value="' . $borrower . '">' . $fname . '&nbsp;' . $lname . '</option>';
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">Customer Account#</label>
                                                    <div class="col-sm-6">
                                                        <select class="account select2" name="account" style="width: 100%;">
                                                            <?php
                                                            echo '<option value="' . $account . '">' . $account . '</option>';
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">Loan</label>
                                                    <div class="col-sm-6">
                                                        <select class="loan select2" name="loan" style="width: 100%;">
                                                            <?php
                                                            echo '<option value="' . $instalment . '">' . $loan_product . "(" . $_SESSION['currency'] . "&nbsp;" . $expectedBalance . "-" . "&nbsp;" . "Instalment:" . $instalment . ")" . '</option>';
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">Payment Date</label>
                                                    <div class="col-sm-6">
                                                        <input type="text" class="form-control"
                                                               value="<?php echo date('Y-m-d'); ?>" name="pay_date"
                                                               readonly="readonly">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="paymentMethod" class="col-sm-3 control-label">Payment
                                                        Method
                                                        *</label>
                                                    <div class="col-sm-6">
                                                        <select class="form-control" name="paymentMethod"
                                                                id="paymentMethod"
                                                                required>
                                                            <option value="">Select</option>
                                                            <option value="01">Payroll Deduction</option>
                                                            <option value="02">Differed Payment</option>
                                                            <option value="03">Staff Account</option>
                                                            <option value="04">Under Administration</option>
                                                            <option value="05">Judgement Granted</option>
                                                            <option value="06">Debt Restructured</option>
                                                            <option value="07">Voluntary Debt Consolidation</option>
                                                            <option value="08">Debt Rescheduled</option>
                                                            <option value="09">Forced Reduction of Overdraft</option>
                                                            <option value="10">In-Excess</option>
                                                            <option value="11">Pending Registration</option>
                                                            <option value="00">Other</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <?php $getAccounts = mysqli_query($link,"select * from bank_accounts"); ?>
                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">Receiving Account *</label>
                                                    <div class="col-sm-6">
                                                        <select name="toAccount" class="form-control" required>
                                                            <option value="" selected disabled>Select receiving account</option>
                                                            <?php while($row=mysqli_fetch_assoc($getAccounts)){ ?>
                                                                <option value="<?php echo $row['gl_code']."-".$row['accountNumber']; ?>"><?php echo $row['gl_code']."-".$row['accountNumber']." - ".$row['bankName']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">Payment Amount</label>
                                                    <div class="col-sm-6">
                                                        <input name="paid_amount"
                                                               type="number"
                                                               step="0.01"
                                                               class="form-control"
                                                               min="0"
                                                               placeholder="Amount to Pay" required>
                                                    </div>
                                                    <script>
                                                        // This is an old version, for a more recent version look at
                                                        // https://jsfiddle.net/DRSDavidSoft/zb4ft1qq/2/
                                                        function maxLengthCheck(object) {
                                                            if (object.value.length > object.maxLength)
                                                                object.value = object.value.slice(0, object.maxLength)
                                                        }
                                                    </script>
                                                </div>

                                                <div class="panel panel-default">
                                                    <div class="panel-body bg-gray text-bold">Advance Settings (Split Payment): <a href="#" class="show_hide_advance_settings" style="display: inline;">Show</a>
                                                    </div>
                                                </div>


                                                <div class="slidingDivAdvanceSettings" style="display: none;">
                                                    <div class="form-group">
                                                        <div class="checkbox col-sm-offset-3 col-sm-9">
                                                            <label>
                                                                <input type="checkbox" name="repayment_manual_composition_i" id="inputManualCompositionCheck" value="1" onchange="checkManualComposition();">
                                                                <b>Allocate repayment amount manually based on the below values:</b>
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="inputRepaymentAmount" class="col-sm-3 control-label">Principal Amount</label>
                                                        <div class="col-sm-6">
                                                            <input type="text" name="principal_repayment_amount_i" class="form-control decimal-2-places" id="inputPrincipalRepaymentAmount" placeholder="Number or decimal only" value="" disabled="" onkeyup="updatesum()">
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="inputRepaymentAmount" class="col-sm-3 control-label">Interest Amount</label>
                                                        <div class="col-sm-6">
                                                            <input type="text" name="interest_repayment_amount_i" class="form-control decimal-2-places" id="inputInterestRepaymentAmount" placeholder="Number or decimal only" value="" disabled="" onkeyup="updatesum()">
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="inputRepaymentAmount" class="col-sm-3 control-label">Fees Amount</label>
                                                        <div class="col-sm-6">
                                                            <input type="text" name="fees_repayment_amount_i" class="form-control decimal-2-places" id="inputFeesRepaymentAmount" placeholder="Number or decimal only" value="" disabled="" onkeyup="updatesum()">
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="inputRepaymentAmount" class="col-sm-3 control-label">Penalty Amount</label>
                                                        <div class="col-sm-6">
                                                            <input type="text" name="penalty_repayment_amount_i" class="form-control decimal-2-places" id="inputPenaltyRepaymentAmount" placeholder="Number or decimal only" value="" disabled="" onkeyup="updatesum()">
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="inputRepaymentAmount" class="col-sm-3 control-label">Total Amount</label>
                                                        <div class="col-sm-6">
                                                            <strong><div id="RepaymentAmountTotal" style="margin-top:7px">0.00</div></strong>
                                                            <b>Total Amount</b> must equal <b>Repayment Amount</b> field above
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">Payment
                                                        Reference</label>
                                                    <div class="col-sm-6">
                                                        <input name="reference" type="text"
                                                               class="form-control"
                                                               placeholder="Payment Reference" required>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">Teller By</label>
                                                    <div class="col-sm-6">
                                                        <?php
                                                        $tid = $_SESSION['tid'];
                                                        $sele = mysqli_query($link, "SELECT * from user WHERE id = '$tid'") or die (mysqli_error($link));
                                                        while ($row = mysqli_fetch_array($sele)) {
                                                            ?>
                                                            <input name="teller" type="text" class="form-control"
                                                                   value="<?php echo $row['name']; ?>" readonly>
                                                        <?php } ?>
                                                    </div>
                                                </div>


                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">Description / Comments
                                                        (optional)</label>
                                                    <div class="col-sm-6">
                                                <textarea name="remarks" class="form-control" rows="2"
                                                          cols="80"></textarea>
                                                    </div>
                                                </div>

                                            </div>

                                            <div align="center">
                                                <div class="box-footer">
                                                    <button type="reset" class="btn btn-danger submit-button"><i class="fa fa-times">&nbsp;Reset</i></button>
                                                    <button name="savePayment" type="submit" class="btn btn-info submit-button">Make Payment</button>

                                                </div>
                                            </div>
                                        </form>


                                    </div>
                                    <div class="box box-info">
                                        <div class="row" style="margin-right:0.2%;margin-left:0.2%;margin-top: 1%;">
                                            <div class="col-sm-12 table-responsive">
                                                <style>
                                                    th {
                                                        padding-top: 12px;
                                                        padding-bottom: 12px;
                                                        text-align: left;
                                                        background-color: #D1F9FF;
                                                    }
                                                </style>
                                                <table id="example1" class="table table-bordered table-striped">
                                                    <thead>
                                                    <tr>
                                                        <th><input type="checkbox" id="select_all"/></th>
                                                        <th>Collection Date</th>
                                                        <th>Payment Method</th>
                                                        <th>Amount Paid</th>
                                                        <th>Balance</th>
                                                        <th>Reference</th>
                                                        <th>Teller</th>
                                                        <th>Receipt</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                    $tid = $_SESSION['tid'];
                                                    $select = mysqli_query($link, "SELECT * FROM payments WHERE customer = '$id' and account='$account'") or die (mysqli_error($link));
                                                    if (mysqli_num_rows($select) == 0) {
                                                        echo "<div class='alert alert-info'>No data found yet!.....Check back later!!</div>";
                                                    } else {
                                                        while ($row = mysqli_fetch_array($select)) {
                                                            $id = $row['id'];
                                                            $user = $row['tid'];
                                                            $getin = mysqli_query($link, "SELECT username FROM user WHERE id = '$user'") or die (mysqli_error($link));
                                                            $have = mysqli_fetch_array($getin);
                                                            $agent = $have['username'];
//$accte = $have['account'];
                                                            $instalment = $row['loan'];
                                                            $amount_paid = $row['amount_to_pay'];
                                                            $pay_date = $row['pay_date'];
                                                            $balance = $row['balance'];


                                                            $strJsonFileContents = file_get_contents('include/packages.json');
                                                            $arrayOfTypes = json_decode($strJsonFileContents, true);
                                                            $payment_method = $row['payment_method'];
                                                            foreach ($arrayOfTypes['paymentType'] as $key => $value) {
                                                                if ($payment_method == $key) {
                                                                    $payment_method = $value;
                                                                }
                                                            }

                                                            $reference = $row['reference'];
                                                            $select1 = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));

                                                            ?>
                                                            <tr>
                                                                <td><input id="optionsCheckbox" class="checkbox"
                                                                           name="selector[]"
                                                                           type="checkbox" value="<?php echo $id; ?>">
                                                                </td>
                                                                <td><?php echo substr($pay_date, 0, 10); ?></td>
                                                                <td><?php echo $payment_method; ?></td>
                                                                <td align="right"><?php echo number_format($amount_paid, 2, ".", ","); ?></td>
                                                                <td align="right"><?php echo number_format($balance, 2, ".", ","); ?></td>
                                                                <td><?php echo $row['reference']; ?></td>
                                                                <td><?php echo $agent; ?></td>
                                                                <td><a href="view_pmt.php?id=<?php echo $id; ?>">
                                                                        <i class="fa fa-eye"></i>
                                                                    </a>&nbsp;
                                                                    <a href="#myModal <?php echo $id; ?>"> <i
                                                                                class="fa fa-print"
                                                                                data-target="#myModal<?php echo $id; ?>"
                                                                                data-toggle="modal"></i></a>
                                                                </td>
                                                            </tr>
                                                            <?php
                                                        }
                                                    } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <script type="text/javascript" language="javascript">

                                        $(document).ready(function () {

                                            var dataTable = $('#view-repayments-loan-details-1348140').DataTable({
                                                "dom": '<"pull-left"f>r<"pull-right"l>tip', "order": [0, 'desc'],
                                                "autoWidth": true,
                                                "lengthMenu": [[20, 50, 100, 250, 500, 2500], [20, 50, 100, 250, 500, "All (Slow)"]],
                                                "iDisplayLength": 20,
                                                "processing": true,
                                                "serverSide": true,
                                                "language": {
                                                    "processing": "<img src='#'> Processing..",
                                                    "searchPlaceholder": "Search repayments",
                                                    "emptyTable": "No repayments found",
                                                    search: ""
                                                },
                                                "columnDefs": [
                                                    {
                                                        "targets": [4, 5], // column or columns numbers
                                                        "orderable": false
                                                    },

                                                    {
                                                        className: "text-right",
                                                        "targets": [3, 5]
                                                    }
                                                ],
                                                "ajax": {
                                                    url: "#", // json datasource
                                                    type: "post",  // method  , by default get
                                                    error: function () {  // error handling
                                                        $(".view-repayments-loan-details-1348140-error").html("");
                                                        $("#view-repayments-loan-details-1348140").append('<tbody class="borrowers-list-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                                                        $("#view-repayments-loan-details-1348140-processing").css("display", "none");

                                                    }
                                                },
                                                stateSave: true,
                                                "footerCallback": function (row, data, start, end, display) {
                                                    var api = this.api(), data;

                                                    // Remove the formatting to get integer data for summation
                                                    var intVal = function (i) {
                                                        return typeof i === 'string' ?
                                                            i.replace(/[\$,]|<(\w+)\b[^<>]*>[\s\S]*?<\/\1>|<br\s*[\/]?>/g, '') * 1 :
                                                            typeof i === 'number' ?
                                                                i : 0;
                                                    };
                                                    // Total over this page
                                                    pageTotal3 = api
                                                        .column(3, {page: 'current'})
                                                        .data()
                                                        .reduce(function (a, b) {
                                                            return intVal(a) + intVal(b);
                                                        }, 0);

                                                    // Update footer
                                                    $(api.column(3).footer()).html(
                                                        '' + pageTotal3.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ''
                                                    );
                                                }
                                            });

                                            var buttons = new $.fn.dataTable.Buttons(dataTable, {
                                                buttons:
                                                    [
                                                        {
                                                            extend: 'collection',
                                                            text: 'Export Data',
                                                            buttons: [
                                                                {
                                                                    text: 'Print',
                                                                    extend: 'print',
                                                                    exportOptions: {
                                                                        columns: ':visible:not(.not-export-col)'
                                                                    },
                                                                    footer: true
                                                                },
                                                                {
                                                                    text: 'Copy',
                                                                    extend: 'copyHtml5',
                                                                    exportOptions: {
                                                                        columns: ':visible:not(.not-export-col)'
                                                                    },
                                                                    footer: true
                                                                },
                                                                {
                                                                    text: 'Excel',
                                                                    extend: 'excelHtml5',
                                                                    exportOptions: {
                                                                        columns: ':visible:not(.not-export-col)'
                                                                    },
                                                                    footer: true
                                                                },
                                                                {
                                                                    text: 'CSV',
                                                                    extend: 'csvHtml5',
                                                                    exportOptions: {
                                                                        columns: ':visible:not(.not-export-col)'
                                                                    },
                                                                    footer: true
                                                                }
                                                            ]
                                                        }
                                                    ]
                                            }).container().appendTo($('#export_button'));


                                            $("#view-repayments-loan-details-1348140").unbind().on('click', '.confirm_action', function (e) {
                                                e.preventDefault();
                                                var href_value = $(this).attr('href');
                                                var confirm_text = $(this).attr('actionconfirm');
                                                $.confirm({
                                                    title: 'Please Confirm',
                                                    content: 'Are you sure you want to ' + confirm_text + '?',
                                                    type: 'green',
                                                    buttons: {
                                                        confirm: function () {
                                                            window.location = href_value;
                                                            return true;
                                                        },
                                                        cancel: function () {
                                                            return true;
                                                        }
                                                    }
                                                });
                                            });
                                        });
                                    </script>
                                </div>
                                <div class="tab-pane" id="tab_statement">

                                    <div class="btn-group-horizontal">

                                            <a type="button" class="btn bg-gray margin" href="loanStatement.php?id=<?php echo $_GET['id'];?>&&loanId=<?php echo $_GET['loanId'] ?>"
                                               class="btn btn-primary btn-flat"><i class="fa fa-print"></i>&nbsp;Print
                                                Statement</a>
                                            <a type="button" class="btn bg-gray margin" href="excelpayment.php"
                                               class="btn btn-success btn-flat"><i class="fa fa-send"></i>&nbsp;Export
                                                Excel</a>


                                        <form class="form-horizontal" id="makePayment" method="post"
                                              enctype="multipart/form-data"
                                              action="#">

                                            <div class="box-body">
                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">Customer</label>
                                                    <div class="col-sm-6">
                                                        <select class="customer select2" name="customer"
                                                                style="width: 100%;"
                                                                readonly="">
                                                            <?php
                                                            echo '<option value="' . $borrower . '">' . $fname . '&nbsp;' . $lname . '</option>';
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">Customer Account#</label>
                                                    <div class="col-sm-6">
                                                        <select class="account select2" name="account" style="width: 100%;">
                                                            <?php
                                                            echo '<option value="' . $account . '">' . $account . '</option>';
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">Loan</label>
                                                    <div class="col-sm-6">
                                                        <select class="loan select2" name="loan" style="width: 100%;">
                                                            <?php
                                                            echo '<option value="' . $instalment . '">' . $loan_product . "(" . $_SESSION['currency'] . "&nbsp;" . $expectedBalance . "-" . "&nbsp;" . "Instalment:" . $instalment . ")" . '</option>';
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">Payment Date</label>
                                                    <div class="col-sm-6">
                                                        <input type="text" class="form-control"
                                                               value="<?php echo date('Y-m-d'); ?>" name="pay_date"
                                                               readonly="readonly">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="paymentMethod" class="col-sm-3 control-label">Payment
                                                        Method
                                                        *</label>
                                                    <div class="col-sm-6">
                                                        <select class="form-control" name="paymentMethod"
                                                                id="paymentMethod"
                                                                required>
                                                            <option value="">Select</option>
                                                            <option value="01">Payroll Deduction</option>
                                                            <option value="02">Differed Payment</option>
                                                            <option value="03">Staff Account</option>
                                                            <option value="04">Under Administration</option>
                                                            <option value="05">Judgement Granted</option>
                                                            <option value="06">Debt Restructured</option>
                                                            <option value="07">Voluntary Debt Consolidation</option>
                                                            <option value="08">Debt Rescheduled</option>
                                                            <option value="09">Forced Reduction of Overdraft</option>
                                                            <option value="10">In-Excess</option>
                                                            <option value="11">Pending Registration</option>
                                                            <option value="00">Other</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <?php $getAccounts = mysqli_query($link,"select * from bank_accounts"); ?>
                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">Receiving Account*</label>
                                                    <div class="col-sm-6">
                                                        <select name="toAccount" class="form-control">
                                                            <option value="" selected disabled>Select receiving account</option>
                                                            <?php while($row=mysqli_fetch_assoc($getAccounts)){ ?>
                                                                <option value="<?php echo $row['gl_code']."-".$row['accountNumber']; ?>"><?php echo $row['gl_code']."-".$row['accountNumber']." - ".$row['bankName']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">Amount to Pay</label>
                                                    <div class="col-sm-6">
                                                        <input name="paid_amount"
                                                               type="number"
                                                               step="0.01"
                                                               class="form-control"
                                                               oninput="maxLengthCheck(this)"
                                                               min="0"
                                                               max="<?php echo $currentBalance; ?>"
                                                               maxlength="<?php echo $allowDigits; ?>"
                                                               placeholder="Amount to Pay" required>
                                                    </div>
                                                    <script>
                                                        // This is an old version, for a more recent version look at
                                                        // https://jsfiddle.net/DRSDavidSoft/zb4ft1qq/2/
                                                        function maxLengthCheck(object) {
                                                            if (object.value.length > object.maxLength)
                                                                object.value = object.value.slice(0, object.maxLength)
                                                        }
                                                    </script>
                                                </div>
                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">Payment
                                                        Reference</label>
                                                    <div class="col-sm-6">
                                                        <input name="reference" type="text"
                                                               class="form-control"
                                                               placeholder="Payment Reference" required>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">Teller By</label>
                                                    <div class="col-sm-6">
                                                        <?php
                                                        $tid = $_SESSION['tid'];
                                                        $sele = mysqli_query($link, "SELECT * from user WHERE id = '$tid'") or die (mysqli_error($link));
                                                        while ($row = mysqli_fetch_array($sele)) {
                                                            ?>
                                                            <input name="teller" type="text" class="form-control"
                                                                   value="<?php echo $row['name']; ?>" readonly>
                                                        <?php } ?>
                                                    </div>
                                                </div>


                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">Remarks</label>
                                                    <div class="col-sm-6">
                                                <textarea name="remarks" class="form-control" rows="2"
                                                          cols="80"></textarea>
                                                    </div>
                                                </div>

                                            </div>

                                            <div align="center">
                                                <div class="box-footer">
                                                    <button type="reset" class="btn btn-primary btn-flat"><i
                                                                class="fa fa-times">&nbsp;Reset</i>
                                                    </button>
                                                    <button name="savePayment" type="submit"
                                                            class="btn btn-success btn-flat"><i
                                                                class="fa fa-save">&nbsp;Make
                                                            Payment</i></button>

                                                </div>
                                            </div>
                                        </form>


                                    </div>
                                    <div class="box box-info">
                                        <div class="row" style="margin-right:0.2%;margin-left:0.2%;margin-top: 1%;">
                                            <div class="wrapper">
                                                <section class="invoice">
                                                    <div class="row">
                                                        <h3>Statement as at <?php echo date('d/m/Y'); ?></h3>
                                                        <div class="col-xs-12 table-responsive">
                                                            <table id="others"
                                                                   class="table table-striped table-condensed">
                                                                <thead>
                                                                <tr style="background-color: #F2F8FF">
                                                                    <th class=""><b>Date</b></th>
                                                                    <th class=""><b>Transaction</b></th>
                                                                    <th class=""><b>Description</b></th>
                                                                    <th class="text-right"><b>Debit</b></th>
                                                                    <th class="text-right"><b>Credit</b></th>
                                                                    <th class="text-right"><b>Balance</b></th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>



                                                                <?php
                                                                //Get All Schedules of the loan
                                                                $count = 1;
                                                                $principal_total = 0;
                                                                $interest_total = 0;
                                                                $due_total = $principalTotalOwing = 0;
                                                                $fees_total = 0;
                                                                $monthToDateDueTotal = 0;
                                                                $getSchedule = mysqli_query($link, "SELECT * FROM system_transactions where account='$account'");
                                                                ?>
                                                                <?php while ($schedule = mysqli_fetch_assoc($getSchedule)) {
                                                                    if($schedule['debit']=="0.00"){
                                                                        $debit="";
                                                                    }
                                                                    else{
                                                                        $debit=number_format($schedule['debit'], 2, ".", ",");
                                                                    }
                                                                    if($schedule['credit']=="0.00"){
                                                                        $credit="";
                                                                    }
                                                                    else{
                                                                        $credit=number_format($schedule['credit'], 2, ".", ",");;
                                                                    }
                                                                    ?>

                                                                    <td><?php echo date("d/m/Y", strtotime($schedule['date'])); ?></td>
                                                                    <td><?php echo $schedule['tx_id']; ?></td>
                                                                    <td><?php echo $schedule['transaction']; ?></td>
                                                                    <td class="text-right"><?php echo $debit; ?></td><!-- Now Debit-->
                                                                    <td class="text-right"><?php echo $credit; ?></td>
                                                                    <td class="text-right"><?php echo number_format($schedule['balance'], 2, ".", ","); ?></td>

                                                                    </tr>
                                                                    <?php
                                                                    $count++;
                                                                }
                                                                ?>




                                                                </tbody>
                                                            </table>
                                                            <br><br>
                                                        </div>
                                                    </div>
                                                </section>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="tab-pane" id="tab_loan_fees">
                                    <div class="tab_content">
                                        <p>Here you can see all fees related to the current loan</p>
                                        <div class="box box-info">
                                            <div class="box-body">
                                                <div class="col-xs-12">
                                                    <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <div class="table-responsive">
                                                                    <table id="example2"
                                                                           class="table table-bordered table-condensed table-hover dataTable no-footer"
                                                                           role="grid">
                                                                        <thead>
                                                                        <tr style="background-color: #F2F8FF" role="row">
                                                                            <th rowspan="1" colspan="1">Fees</th>
                                                                            <th class="text-right" rowspan="1" colspan="1">All Released Loans</th>
                                                                        </tr>
                                                                        </thead>
                                                                        <tbody>

                                                                        <?php
                                                                        if(isset($_POST['search'])){
                                                                            $date1=date("Y-m-d", strtotime(explode(" - ", $_POST['date'])[0]));
                                                                            $date2=date("Y-m-d", strtotime(explode(" - ", $_POST['date'])[1]));
                                                                        }else{
                                                                            $date1 = date('Y-m-01');
                                                                            $date2 = date('Y-m-t');
                                                                        }
                                                                        $allFees = mysqli_query($link, "SELECT fee_name, sum(fee_amount) FROM loan_fees where loan in 
(SELECT id FROM loan_info where status not in ('DECLINED','Pending','Pending Disbursement')  and 
application_date between '$date1' and '$date2' and id='$loanId') and fee_name !='Interest' group by fee_name") or die (mysqli_error($link));

                                                                        $fees_total = 0;
                                                                        while ($fees = mysqli_fetch_assoc($allFees)) { ?>
                                                                            <tr role="row" class="even">
                                                                                <td class="text-bold"><?php echo $fees['fee_name']; ?></td>
                                                                                <td class="text-right"><?php echo $fees['sum(fee_amount)']; ?></td>
                                                                            </tr>
                                                                            <?php $fees_total += $fees['sum(fee_amount)'];
                                                                        } ?>
                                                                        <tr class="danger odd" role="row">
                                                                            <td><b>Total Fees (All Loans)</b></td>
                                                                            <td style="text-align:right">
                                                                                <b><?php echo number_format($fees_total, 2, ".", ","); ?></b></td>
                                                                        </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-5"></div>
                                                            <div class="col-sm-7"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                if (mysqli_num_rows($getPenalty)) {
                                    ?>
                                    <div class="tab-pane" id="tab_penalty_settings">
                                        <div class="tab_content">
                                            <div class="well">
                                                <p>This loan is currently using the <b>Business Loan</b> penalty
                                                    settings.
                                                    To
                                                    add/edit
                                                    penalty settings, you can do one of the following:
                                                </p>
                                                <b>1. Business Loan Penalty:</b><br>
                                                <a href="#">Click here</a> if you would like to <b>edit the Business
                                                    Loan
                                                    penalty
                                                    settings</b>. In this case <u>all loans in this loan product</u>
                                                will be
                                                affected.<br><br>
                                                <i>OR</i><br><br>
                                                <b>2. Individual Penalty:</b><br>
                                                <a href="#">Click here</a> if you would like to add penalty settings <b>just
                                                    for
                                                    this
                                                    loan</b>. <u>Only this loan</u> will be affected.
                                            </div>

                                            <div class="box-body no-padding">
                                                <table class="table table-bordered table-hover">

                                                    <tbody>
                                                    <tr>
                                                        <td colspan="2"
                                                            class="bg-light-blue  color-palette text-center text-bold">
                                                            Business Loan Penalty - System Generated
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" class="bg-gray text-bold">Late Repayment
                                                            Penalty:
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-red" colspan="2"><b>Late Repayment Penalty is
                                                                Disabled</b>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                <br><br>
                                                <table class="table table-bordered table-hover">
                                                    <tbody>
                                                    <tr>
                                                        <td colspan="2" class="bg-gray text-bold">After Maturity Date
                                                            Penalty:
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-red" colspan="2"><b>After Maturity Date Penalty
                                                                is
                                                                Disabled</b>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php
                                if (mysqli_num_rows($getCollateral)) {
                                    ?>
                                    <div class="tab-pane  <?php if ($loan_status == 'Pending' && mysqli_num_rows($search) == 0) {
                                        echo "active";
                                    } ?>" id="tab_collateral">

                                        <?php

                                        if (mysqli_num_rows($search) == 1) {
                                            $row = mysqli_fetch_array($search);
                                            ?>
                                            <form class="form-horizontal" method="post" enctype="multipart/form-data">

                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">Name:</label>
                                                    <div class="col-sm-6">
                                                        <input name="name" type="text" class="form-control"
                                                               value="<?php echo $row['name']; ?>">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">Type
                                                        of Collateral:</label>
                                                    <div class="col-sm-6">
                                                        <input name="type_of_collateral" type="text"
                                                               class="form-control"
                                                               value="<?php echo $row['type_of_collateral']; ?>">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">Model:</label>
                                                    <div class="col-sm-6">
                                                        <input name="model" type="text" class="form-control"
                                                               value="<?php echo $row['model']; ?>">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">Make:</label>
                                                    <div class="col-sm-6">
                                                        <input name="make" type="text" class="form-control"
                                                               value="<?php echo $row['make']; ?>">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">Serial
                                                        Number:</label>
                                                    <div class="col-sm-6">
                                                        <input name="serial_number" type="text" class="form-control"
                                                               value="<?php echo $row['serial_number']; ?>">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">Estimated
                                                        Price:</label>
                                                    <div class="col-sm-6">
                                                        <input name="estimated_price" type="text" class="form-control"
                                                               value="<?php echo $row['estimated_price']; ?>">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">Proof of
                                                        Ownership:</label>
                                                    <div class="col-sm-6">
                                                        Accepted file types <span style="color:#FF0000">jpg, gif, png, xls, xlsx, csv, doc, docx, pdf</span>
                                                        <input name="uploaded_file" type="file" class="btn btn-info"
                                                               required>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">Image:</label>
                                                    <div class="col-sm-6">
                                                        Accepted file types <span style="color:#FF0000">jpg, png </span>
                                                        <input name="image" type="file" class="btn btn-info" required>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">Observations:</label>
                                                    <div class="col-sm-6">
                                                        <textarea name="observation" class="form-control" rows="4"
                                                                  cols="80"><?php echo $row['observation']; ?></textarea>
                                                    </div>
                                                </div>
                                                <div align="center">
                                                    <div class="box-footer">
                                                        <button type="submit" class="btn btn-success btn-flat"
                                                                name="submit"><i class="fa fa-save">&nbsp;Save</i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>

                                            <?php
                                        } else {
                                            ?>
                                            <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">Name:</label>
                                                    <div class="col-sm-6">
                                                        <input name="name" type="text" class="form-control">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">Type
                                                        of Collateral:</label>
                                                    <div class="col-sm-6">
                                                        <input name="type_of_collateral" type="text"
                                                               class="form-control">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">Model:</label>
                                                    <div class="col-sm-6">
                                                        <input name="model" type="text" class="form-control">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">Make:</label>
                                                    <div class="col-sm-6">
                                                        <input name="make" type="text" class="form-control">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">Serial
                                                        Number:</label>
                                                    <div class="col-sm-6">
                                                        <input name="serial_number" type="text" class="form-control">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">Estimated
                                                        Price:</label>
                                                    <div class="col-sm-6">
                                                        <input name="estimated_price" type="text" class="form-control">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">Proof
                                                        of Ownership:</label>
                                                    <div class="col-sm-6">
                                                        Accepted file types <span style="color:#FF0000">jpg, gif, png, xls, xlsx, csv, doc, docx, pdf</span>
                                                        <input name="uploaded_file" type="file" class="btn btn-info"
                                                               required>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">Image:</label>
                                                    <div class="col-sm-6">
                                                        Accepted file types <span style="color:#FF0000">jpg, png </span>
                                                        <input name="image" type="file" class="btn btn-info" required>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">Observations:</label>
                                                    <div class="col-sm-6">
                                                        <textarea name="observation" class="form-control" rows="4"
                                                                  cols="80"></textarea>
                                                    </div>
                                                </div>

                                                <div align="center">
                                                    <div class="box-footer">
                                                        <button type="submit" class="btn btn-success btn-flat"
                                                                name="submit"><i class="fa fa-save">&nbsp;Save</i>
                                                        </button>
                                                    </div>
                                                </div>

                                            </form>
                                        <?php } ?>


                                    </div>

                                <?php } ?>
                                <div class="tab-pane" id="tab_guarantor">
                                    <!--FIXME Use the Terms Style Here Done @Hlaka-->
                                    <?php
                                    $id = $_GET['id'];
                                    $search = mysqli_query($link, "SELECT * FROM loan_guarantors WHERE borrower = '$id' and loan_id='$loanId'") or die (mysqli_error($link));
                                    if (mysqli_num_rows($search) == 1) {
                                        $row = mysqli_fetch_array($search);
                                        $name = $row['name'];
                                        $relationship = $row['relationship'];
                                        $phone = $row['phone'];

                                        ?>
                                        <div class="box-body no-padding">
                                            <table class="table table-condensed">
                                                <tbody>
                                                <tr>
                                                    <td colspan="2" class="bg-navy disabled color-palette">
                                                        Guarantor
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><b>Loan Agent</b></td>
                                                    <td><?php echo $name; ?></td>
                                                </tr>
                                                <tr>
                                                    <td><b>Relationship</b></td>
                                                    <td><?php echo $relationship; ?></td>
                                                </tr>
                                                <tr>
                                                    <td><b>Phone Numbers</b></td>
                                                    <td><?php echo $phone; ?></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <?php
                                    } else {
                                        ?>
                                        <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                            <div class="form-group">
                                                <label for="" class="col-sm-3 control-label">Name:</label>
                                                <div class="col-sm-6">
                                                    <input name="name" type="text" class="form-control">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="" class="col-sm-3 control-label">Type
                                                    of Collateral:</label>
                                                <div class="col-sm-6">
                                                    <input name="type_of_collateral" type="text"
                                                           class="form-control">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="" class="col-sm-3 control-label">Model:</label>
                                                <div class="col-sm-6">
                                                    <input name="model" type="text" class="form-control">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="" class="col-sm-3 control-label">Make:</label>
                                                <div class="col-sm-6">
                                                    <input name="make" type="text" class="form-control">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="" class="col-sm-3 control-label">Serial
                                                    Number:</label>
                                                <div class="col-sm-6">
                                                    <input name="serial_number" type="text" class="form-control">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="" class="col-sm-3 control-label">Estimated
                                                    Price:</label>
                                                <div class="col-sm-6">
                                                    <input name="estimated_price" type="text" class="form-control">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="" class="col-sm-3 control-label">Proof
                                                    of Ownership:</label>
                                                <div class="col-sm-6">
                                                    Accepted file types <span style="color:#FF0000">jpg, gif, png, xls, xlsx, csv, doc, docx, pdf</span>
                                                    <input name="uploaded_file" type="file" class="btn btn-info">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="" class="col-sm-3 control-label">Image:</label>
                                                <div class="col-sm-6">
                                                    Accepted file types <span style="color:#FF0000">jpg, png </span>
                                                    <input name="image" type="file" class="btn btn-info">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="" class="col-sm-3 control-label">Observations:</label>
                                                <div class="col-sm-6">
                                                        <textarea name="observation" class="form-control" rows="4"
                                                                  cols="80"></textarea>
                                                </div>
                                            </div>

                                            <div align="center">
                                                <div class="box-footer">
                                                    <button type="submit" class="btn btn-success btn-flat"
                                                            name="submit"><i class="fa fa-save">&nbsp;Save</i>
                                                    </button>
                                                </div>
                                            </div>

                                        </form>
                                    <?php } ?>
                                </div>
                                <?php if ($loan_status === "L") { ?>
                                    <div class="tab-pane" id="tab_collections">
                                        <p>Collection Fees Will be Here.</p>
                                    </div>
                                <?php } ?>
                                <div class="tab-pane" id="tab_other_income">
                                    <div class="btn-group-horizontal">
                                        <?php echo ($pupdate == '1') ? '<a type="button" class="btn bg-gray margin" href="updateborrowers.php?id=' . $id . '&&mid=' . base64_encode("403") . '&&document=">Add Other Income</a>' : ''; ?>
                                        &nbsp;
                                    </div>

                                    <table cellspacing="0" id="loan-fees"
                                           class="table table-small-font table-bordered table-striped">

                                        <thead>
                                        <tr>
                                            <th width="2%"><input id="checkAll_occupation"
                                                                  class="formcontrol" type="checkbox">
                                            </th>
                                            <th width="20%">Occupation</th>
                                            <th width="20%">Monthly Income</th>
                                            <th width="20%">Income Frequency</th>
                                        </tr>
                                        </thead>

                                        <tbody>

                                        <?php
                                        //Get all Settings
                                        $count = 0;
                                        $fin_info = mysqli_query($link, "SELECT * FROM fin_info WHERE get_id = '$id'");

                                        // Get the contents of the JSON file
                                        $strJsonFileContents = file_get_contents('include/packages.json');
                                        $arrayOfTypes = json_decode($strJsonFileContents, true);
                                        //echo $arrayOfTypes;
                                        $income = 0;
                                        while ($finInfo = mysqli_fetch_assoc($fin_info)) {
                                            $id = $finInfo['id'];
                                            $idm = $_GET['id'];
                                            ?>
                                            <input type="hidden"
                                                   name="occupation[<?php echo $count; ?> ][id]"
                                                   value="<?php echo $id; ?>">
                                            <tr>
                                                <td width="30"><input id="optionsCheckbox"
                                                                      class="uniform_on"
                                                                      name="selector[]" type="checkbox"
                                                                      value="<?php echo $id; ?>"
                                                    >
                                                </td>
                                                <td width="800"><?php echo $finInfo['occupation']; ?>
                                                </td>
                                                <td align="right">
                                                    <?php
                                                    $income += $finInfo['mincome'];
                                                    echo number_format($finInfo['mincome'], 2, ".", ",");

                                                    ?>
                                                </td>
                                                <td width="300">
                                                    <?php
                                                    foreach ($arrayOfTypes['incomeFrequencyCode'] as $key => $value) {
                                                        if ($finInfo['frequency'] == $key) {
                                                            echo "$value";
                                                        }

                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php } ?>

                                        <tbody>

                                        <tfoot class="bg-gray" style="">
                                        <tr>
                                            <th style="text-align:right" rowspan="1" colspan="1"></th>
                                            <th style="text-align:right" rowspan="1" colspan="1"></th>
                                            <th style="text-align:right" class="text-right" rowspan="1"
                                                colspan="1">
                                                <?php echo number_format($income, 2, ".", ","); ?>
                                            </th>
                                            <th style="text-align:right" rowspan="1" colspan="1"></th>
                                        </tr>
                                        </tfoot>


                                    </table>

                                </div>
                                <div class="tab-pane" id="tab_documents">
                                    <?php $se = mysqli_query($link, "SELECT * FROM battachment WHERE get_id = '$borrower'") or die (mysqli_error($link));
                                    if (mysqli_num_rows($se) > 0) {
                                        ?>
                                        <table id="example1" class="table table-bordered table-striped">
                                            <thead>
                                            <tr>
                                                <th>
                                                    Date Uploaded
                                                </th>
                                                <th>
                                                    Document Type
                                                </th>
                                                <th>
                                                    Uploaded By
                                                </th>
                                                <th>
                                                    Type
                                                </th>
                                                <th>
                                                    Action
                                                </th>
                                            </thead>
                                            </tr>
                                            <?php
                                            while ($gete = mysqli_fetch_array($se)) {
                                                ?>
                                                <tr>
                                                    <td><?php echo $gete['date_time']; ?></td>
                                                    <td><?php
                                                        //Get Document Type description
                                                        echo $gete['document_type'];
                                                        ?>
                                                    </td>
                                                    <td><?php
                                                        //Get User
                                                        $user = $gete['tid'];
                                                        $username = mysqli_fetch_assoc(mysqli_query($link, "select * from user where id='$user'"));
                                                        echo $username['name'];

                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $bytes = $gete['file_size'];
                                                        //Show Size of file
                                                        if ($bytes >= 1073741824) {
                                                            $bytes = number_format($bytes / 1073741824, 1) . ' GB';
                                                        } elseif ($bytes >= 1048576) {
                                                            $bytes = number_format($bytes / 1048576, 1) . ' MB';
                                                        } elseif ($bytes >= 1024) {
                                                            $bytes = number_format($bytes / 1024, 1) . ' KB';
                                                        } elseif ($bytes > 1) {
                                                            $bytes = $bytes . ' bytes';
                                                        }

                                                        $type = $gete['file_ext'];
                                                        $attachment = $gete['attached_file'];
                                                        //Show Size of file
                                                        if ($type == "jpg" || $type == "png" || $type == "jpeg") {
                                                            $type = "<a href='" . $attachment . "'><i class='fa fa-file-image-o'></i></a>";
                                                        } elseif ($type == "xls" || $type == "xlsx") {
                                                            $type = "<a href='" . $attachment . "'><i class='fa fa-file-excel-o'></i></a>";
                                                        } elseif ($type == "doc" || $type == "docx") {
                                                            $type = "<a href='" . $attachment . "'><i class='fa fa-file-word-o'></i></a>";
                                                        } elseif ($type == "pdf") {
                                                            $type = "<a href='" . $attachment . "'><i class='fa fa-file-pdf-o'></i></a>";
                                                        }

                                                        echo $type . "&nbsp;" . $bytes . ""; ?>
                                                    </td>
                                                    <td>
                                                        <a href="<?php echo $gete['attached_file']; ?>">
                                                            <i class="fa fa-download"></i>
                                                        </a>&nbsp;
                                                        <a href="<?php echo $gete['attached_file']; ?>">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>

                                            <?php } ?>
                                        </table>
                                    <?php } else { ?>
                                        <?php echo ($pread == '1') ? '<a href="updateborrowers.php?id=' . $id . '&&mid=' . base64_encode("403") . '&&document=download"><i class="fa fa-upload"></i>&nbsp;</a>' : ''; ?>
                                    <?php } ?>
                                </div>
                                <div class="tab-pane" id="tab_comments">
                                    <div class="btn-group-horizontal">
                                        <button class="btn bg-gray margin" type="button" id="addComment">Add Comments
                                        </button>
                                        <form class="form-horizontal" id="makeComment" method="post"
                                              enctype="multipart/form-data"
                                              action="#">

                                            <div class="box-body">
                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">Customer Names</label>
                                                    <div class="col-sm-6">
                                                        <select class="customer select2" name="customer"
                                                                style="width: 100%;"
                                                                readonly="">
                                                            <?php
                                                            echo '<option value="' . $borrower . '">' . $fname . '&nbsp;' . $lname . '</option>';
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">Customer
                                                        Account</label>
                                                    <div class="col-sm-6">
                                                        <select class="account select2" name="account"
                                                                style="width: 100%;">
                                                            <?php
                                                            echo '<option value="' . $account . '">' . $account . '</option>';
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">Teller By</label>
                                                    <div class="col-sm-6">
                                                        <?php
                                                        $tid = $_SESSION['tid'];
                                                        $sele = mysqli_query($link, "SELECT * from user WHERE id = '$tid'") or die (mysqli_error($link));
                                                        while ($row = mysqli_fetch_array($sele)) {
                                                            ?>
                                                            <input name="tid" type="text" class="form-control"
                                                                   value="<?php echo $row['name']; ?>" readonly>
                                                        <?php } ?>
                                                    </div>
                                                </div>


                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">Comments</label>
                                                    <div class="col-sm-6">
                                                                            <textarea name="comment"
                                                                                      class="form-control" rows="2"
                                                                                      cols="80"></textarea>
                                                    </div>
                                                </div>

                                            </div>

                                            <div align="center">
                                                <div class="box-footer">
                                                    <button type="reset" class="btn btn-primary btn-flat"><i
                                                                class="fa fa-times">&nbsp;Reset</i>
                                                    </button>
                                                    <button name="saveComments" type="submit"
                                                            class="btn btn-success btn-flat"><i
                                                                class="fa fa-save">&nbsp;Make
                                                            Comments</i></button>

                                                </div>
                                            </div>
                                        </form>


                                        <div class="box box-info">
                                            <div class="row" style="margin-right:0.2%;margin-left:0.2%;margin-top: 1%;">
                                                <div class="col-sm-12 table-responsive">
                                                    <table id="example1" class="table table-bordered table-striped">
                                                        <thead>
                                                        <tr>
                                                            <th><input type="checkbox" id="select_all"/></th>
                                                            <th>Loan Agent</th>
                                                            <th>Comments</th>
                                                            <th>Date</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php
                                                        $id = $_GET['id'];
                                                        $select = mysqli_query($link, "SELECT * FROM comments WHERE customer='$borrower'and account='$account'") or die (mysqli_error($link));
                                                        if (mysqli_num_rows($select) == 0) {
                                                            echo "<div class='alert alert-info'>No data found yet!.....Check back later!!</div>";
                                                        } else {
                                                            while ($row = mysqli_fetch_array($select)) {
                                                                $tid = $row['tid'];
                                                                $account = $row['account'];
                                                                $comments = $row['comment'];
                                                                $date = $row['date'];
                                                                $created = mysqli_fetch_array(mysqli_query($link, "SELECT * from user where id='$tid'"));
                                                                $username = $created['name'];
//
                                                                ?>
                                                                <tr>
                                                                    <td><input id="optionsCheckbox" class="checkbox"
                                                                               name="selector[]"
                                                                               type="checkbox"
                                                                               value="<?php echo $id; ?>">
                                                                    </td>
                                                                    <td><?php echo $username; ?></td>
                                                                    <td><?php echo $comments; ?></td>
                                                                    <td><?php echo $date; ?></td>
                                                                </tr>
                                                            <?php }

                                                        } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab_timeline">
                                            <div class="box-body">

                                                <!-- Main content -->
                                                <section class="content">

                                                    <!-- row -->
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <!-- The time line -->
                                                            <ul class="timeline">
                                                                <!-- timeline time label -->
                                                                <li class="time-label">
                  <span class="bg-red">
                    10 Feb. 2014
                  </span>
                                                                </li>
                                                                <!-- /.timeline-label -->
                                                                <!-- timeline item -->
                                                                <li>
                                                                    <i class="fa fa-envelope bg-blue"></i>

                                                                    <div class="timeline-item">
                                                                        <span class="time"><i class="fa fa-clock-o"></i> 12:05</span>

                                                                        <h3 class="timeline-header"><a href="#">Support Team</a> sent you an email</h3>

                                                                        <div class="timeline-body">
                                                                            Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles,
                                                                            weebly ning heekya handango imeem plugg dopplr jibjab, movity
                                                                            jajah plickers sifteo edmodo ifttt zimbra. Babblely odeo kaboodle
                                                                            quora plaxo ideeli hulu weebly balihoo...
                                                                        </div>
                                                                        <div class="timeline-footer">
                                                                            <a class="btn btn-primary btn-xs">Read more</a>
                                                                            <a class="btn btn-danger btn-xs">Delete</a>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <!-- END timeline item -->
                                                                <!-- timeline item -->
                                                                <li>
                                                                    <i class="fa fa-user bg-aqua"></i>

                                                                    <div class="timeline-item">
                                                                        <span class="time"><i class="fa fa-clock-o"></i> 5 mins ago</span>

                                                                        <h3 class="timeline-header no-border"><a href="#">Sarah Young</a> accepted your friend request</h3>
                                                                    </div>
                                                                </li>
                                                                <!-- END timeline item -->
                                                                <!-- timeline item -->
                                                                <li>
                                                                    <i class="fa fa-comments bg-yellow"></i>

                                                                    <div class="timeline-item">
                                                                        <span class="time"><i class="fa fa-clock-o"></i> 27 mins ago</span>

                                                                        <h3 class="timeline-header"><a href="#">Jay White</a> commented on your post</h3>

                                                                        <div class="timeline-body">
                                                                            Take me to your leader!
                                                                            Switzerland is small and neutral!
                                                                            We are more like Germany, ambitious and misunderstood!
                                                                        </div>
                                                                        <div class="timeline-footer">
                                                                            <a class="btn btn-warning btn-flat btn-xs">View comment</a>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <!-- END timeline item -->
                                                                <!-- timeline time label -->
                                                                <li class="time-label">
                  <span class="bg-green">
                    3 Jan. 2014
                  </span>
                                                                </li>
                                                                <!-- /.timeline-label -->
                                                                <!-- timeline item -->
                                                                <li>
                                                                    <i class="fa fa-camera bg-purple"></i>

                                                                    <div class="timeline-item">
                                                                        <span class="time"><i class="fa fa-clock-o"></i> 2 days ago</span>

                                                                        <h3 class="timeline-header"><a href="#">Mina Lee</a> uploaded new photos</h3>

                                                                        <div class="timeline-body">
                                                                            <img src="http://placehold.it/150x100" alt="..." class="margin">
                                                                            <img src="http://placehold.it/150x100" alt="..." class="margin">
                                                                            <img src="http://placehold.it/150x100" alt="..." class="margin">
                                                                            <img src="http://placehold.it/150x100" alt="..." class="margin">
                                                                        </div>
                                                                    </div>
                                                                </li>

                                                                <li>
                                                                    <i class="fa fa-clock-o bg-gray"></i>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <!-- /.col -->
                                                    </div>
                                                    <!-- /.row -->

                                                    <!-- /.row -->

                                                </section>
                                                <!-- /.content -->

                                            </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <script
                    src="https://code.jquery.com/jquery-3.3.1.js"
                    integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
                    crossorigin="anonymous"></script>
            <script>
                $(document).ready(function () {
                    $("#addPayment").click(function () {
                        $("#makePayment").toggle();
                    });
                });
                $(document).ready(function () {
                    $("#addCollateral").click(function () {
                        $("#collateral").toggle();
                    });
                });

            </script>

            <script>
                $(function () {
                    $("#allFees").DataTable();
                    $("#customerFees").DataTable();
                    $("#others").DataTable();
                    $('#pending').DataTable({
                        "paging": true,
                        "lengthChange": true,
                        "searching": true,
                        "ordering": true,
                        "info": true,
                        "autoWidth": true
                    });
                });
            </script>

            <script>
                $(document).ready(function () {
                    $("#addComment").click(function () {
                        $("#makeComment").toggle();
                    });
                });
            </script>
            <script src="https://x.loandisk.com/include/js/confirm_dialog1.js"></script>
            <script src="https://x.loandisk.com/s3_uploader/s3_live_new_new.js"></script>
            <script src="https://x.loandisk.com/include/js/analytics_live_new_2.js"></script>
            <!-- REQUIRED JS SCRIPTS -->
            <script type="text/javascript">
                $(".numeric").numeric();
                $(".positive").numeric({negative: false});
                $(".positive-integer").numeric({decimal: false, negative: false});
                $(".negative-integer").numeric({decimal: false, negative: true});
                $(".decimal-2-places").numeric({decimalPlaces: 2});
                $(".decimal-4-places").numeric({decimalPlaces: 4});
                $("#remove").click(
                    function (e) {
                        e.preventDefault();
                        $(".numeric,.positive,.positive-integer,.decimal-2-places,.decimal-4-places").removeNumeric();
                    }
                );
            </script>
            <script>

                $(document).ready(function () {
                    var spinner = '<i class="fa fa-spinner fa-spin" aria-hidden="true"></i> <b>Loading. Please wait...</b>';
                    var menuClass = '.loan_tabs';

                    var loadTab = function (tabName) {

                        if (tabName) {
                            var d = document.querySelector('div[data-pws-tab="' + tabName + '"]');
                            if (d) {
                                if (d.innerHTML === "") {
                                    d.innerHTML = spinner;
                                }
                                if (d.innerHTML === spinner) {
                                    // here are remote page request
                                    var url = "tabs/" + tabName + ".php?loan_id=1348140";
                                    $('div[data-pws-tab="' + tabName + '"]').load(url);
                                }
                            }
                        }
                    };

                    $(menuClass)
                        .pwstabs({
                            effect: 'none',
                            responsive: true,
                            mobile_text: 'click here',
                            theme: 'pws_theme_grey',
                            defaultTab: 1,

                            onBeforeChange: function (tabName) {
                                loadTab(tabName);
                            },

                            onAfterInit: function () {
                                loadTab($(this.defaultTab).attr('data-pws-tab'));
                            }
                        });
                });
            </script>


            <script src="https://x.loandisk.com/include/js/ajax_func.js" type="text/javascript"></script>
            <script src="https://x.loandisk.com/include/js/ajax_job1.js" type="text/javascript"></script>
            <script src="https://x.loandisk.com/include/js/jquery.stickytableheaders.min.js"></script>
            <script>$("#daily_collections").stickyTableHeaders();</script>
            <div style="display:none">view_loan_details</div>



            <script type="text/javascript">
                $(".numeric").numeric();
                $(".positive").numeric({ negative: false });
                $(".positive-integer").numeric({ decimal: false, negative: false });
                $(".negative-integer").numeric({ decimal: false, negative: true });
                $(".decimal-2-places").numeric({ decimalPlaces: 2 });
                $(".decimal-4-places").numeric({ decimalPlaces: 4 });
                $("#remove").click(
                    function(e)
                    {
                        e.preventDefault();
                        $(".numeric,.positive,.positive-integer,.decimal-2-places,.decimal-4-places").removeNumeric();
                    }
                );
            </script>
            <script>
                $(document).ready(function () {
                    $(".slidingDivAdvanceSettings").hide();
                    $('.show_hide_advance_settings').click(function (e) {
                        $(".slidingDivAdvanceSettings").slideToggle("fast");
                        var val = $(this).text() == "Hide" ? "Show" : "Hide";
                        $(this).hide().text(val).fadeIn("fast");
                        e.preventDefault();
                    });
                });
            </script>
            <script type="text/javascript">
                $('#form').on('submit', function(e) {

                    $('.submit-button').prop('disabled', true);
                    $('.submit-button').html('<i class="fa fa-spinner fa-spin"></i> Please wait..');
                    return true;
                });
            </script>
            <script>
                $(function() {
                    $('.date_select').datepick({

                        defaultDate: '10/10/2020', showTrigger: '#calImg',
                        yearRange: 'c-20:c+20', showTrigger: '#calImg',

                        dateFormat: 'dd/mm/yyyy',
                        minDate: '01/01/1980'
                    });
                });

                function checkManualComposition()
                {
                    var inputManualCompositionCheck = document.getElementById("inputManualCompositionCheck");
                    var inputPrincipalRepaymentAmount = document.getElementById("inputPrincipalRepaymentAmount");
                    var inputInterestRepaymentAmount = document.getElementById("inputInterestRepaymentAmount");
                    var inputFeesRepaymentAmount = document.getElementById("inputFeesRepaymentAmount");
                    var inputPenaltyRepaymentAmount = document.getElementById("inputPenaltyRepaymentAmount");
                    if(inputManualCompositionCheck.checked)
                    {
                        inputPrincipalRepaymentAmount.disabled = false;
                        inputInterestRepaymentAmount.disabled = false;
                        inputFeesRepaymentAmount.disabled = false;
                        inputPenaltyRepaymentAmount.disabled = false;
                    }
                    else{
                        inputPrincipalRepaymentAmount.disabled = true;
                        inputInterestRepaymentAmount.disabled = true;
                        inputFeesRepaymentAmount.disabled = true;
                        inputPenaltyRepaymentAmount.disabled = true;
                        updatesum();
                    }
                }

                function updatesum()
                {
                    var inputRepaymentAmountTotal = 0;
                    var inputPrincipalRepaymentAmount = document.getElementById("inputPrincipalRepaymentAmount").value;
                    if (inputPrincipalRepaymentAmount == "")
                        inputPrincipalRepaymentAmount = 0;

                    var inputInterestRepaymentAmount = document.getElementById("inputInterestRepaymentAmount").value;
                    if (inputInterestRepaymentAmount == "")
                        inputInterestRepaymentAmount = 0;

                    var inputFeesRepaymentAmount = document.getElementById("inputFeesRepaymentAmount").value;
                    if (inputFeesRepaymentAmount == "")
                        inputFeesRepaymentAmount = 0;

                    var inputPenaltyRepaymentAmount = document.getElementById("inputPenaltyRepaymentAmount").value;
                    if (inputPenaltyRepaymentAmount == "")
                        inputPenaltyRepaymentAmount = 0;

                    inputRepaymentAmountTotal = parseFloat(inputPrincipalRepaymentAmount)*100  + parseFloat(inputInterestRepaymentAmount)*100 + parseFloat(inputFeesRepaymentAmount)*100  + parseFloat(inputPenaltyRepaymentAmount)*100;

                    document.getElementById("RepaymentAmountTotal").innerHTML = numberWithCommas((inputRepaymentAmountTotal / 100).toFixed(2));
                }
                function numberWithCommas(x) {
                    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                }
                function enableDisableAdjustManual() {
                    if ($('#inputAdjustRemainingSchedule').is(":checked"))
                    {
                        $('#inputAdjustRemainingScheduleProRata').prop('disabled', false);
                        $('#inputManualCompositionCheck').prop('disabled', true);
                    }
                    else if ($('#inputManualCompositionCheck').is(":checked"))
                    {
                        $('#inputAdjustRemainingScheduleProRata').prop('disabled', true);
                        $('#inputAdjustRemainingScheduleProRata').prop('checked', false);
                        $('#inputAdjustRemainingSchedule').prop('disabled', true);
                    }
                    else
                    {
                        $('#inputManualCompositionCheck').prop('disabled', false);
                        $('#inputAdjustRemainingSchedule').prop('disabled', false);
                        $('#inputAdjustRemainingScheduleProRata').prop('disabled', true);
                        $('#inputAdjustRemainingScheduleProRata').prop('checked', false);
                    }
                }
                $('#inputAdjustRemainingSchedule, #inputManualCompositionCheck').change(function() {
                    enableDisableAdjustManual();
                });

                $(function ()
                {
                    updatesum();
                    enableDisableAdjustManual();
                });


            </script>

    </section>
</div>
