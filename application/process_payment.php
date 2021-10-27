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
    <?php if (isset($_POST['save'])) {
        $tid = $_SESSION['tid'];
        $name = mysqli_real_escape_string($link, $_POST['teller']);
        $account = mysqli_real_escape_string($link, $_POST['account']);
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
        $totalPaid=$get['sum(amount_to_pay)'];

        //Get the Opening Balance of the loan account
        $maxdate=mysqli_fetch_assoc(mysqli_query($link,"select max(pay_date) from payments where account='$account'"));
        $max_date=$maxdate['max(pay_date)'];

        $accoutBal = mysqli_fetch_assoc(mysqli_query($link,"select balance from payments where account='$account' and pay_date='$max_date'"));
        if(isset($accoutBal['balance'])) {
            $from_balance = $accoutBal['balance'];
        }else{
            $from_balance = 0;
        }
        if($from_balance==""){
            $start_balance=mysqli_fetch_assoc(mysqli_query($link,"select balance from loan_info where baccount='$account'"));
            $from_balance=$start_balance['balance'];////Loan Amount
        }
        $loan_info=mysqli_fetch_assoc(mysqli_query($link,"select id, balance, gl_code from loan_info where baccount='$account'"));
        $expectedBalance = $loan_info['balance'];
        $loanId=$loan_info['id'];
        $loan_gl_code=$loan_info['gl_code'];
        $accountBalance = $expectedBalance - ($totalPaid+ $amount_paid_today);
        $accountBalanceOrigional = $expectedBalance - ($totalPaid+ $amount_paid_today);

        $insert = mysqli_query($link, "INSERT INTO payments(id,tid, account,balance, customer, loan, pay_date, amount_to_pay, remarks, payment_method,reference,tx_id,gl_code) 
                                                VALUES(0,'$tid','$account','$accountBalance','$customer','$loan','$pay_date','$amount_paid_today','$remarks','$paymentMethod','$reference','$txID','$gl_code')");

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
        $total_due=$get_outstanding_schedule['total_due'];

        $expectedInstalment = $get_outstanding_schedule['balance'];


        if($amount_paid_today == $expectedInstalment){
            //Update Schedule
            if($total_due==$expectedInstalment) {
                mysqli_query($link, "update pay_schedule set payment='$expectedInstalment', principal_payment='$principal_due', 
                        interest_payment='$interest_due', fees_payment='$fees_due', penalty_payment='$penalty_due', open_indicator='C', payment_tx_id='$txID', total_due='0' where id='$rowId_Current'");
            }
            else{
                //Meaning there was a prayment
                //Close the current Instalment then carry forward the balance to the next instalment
                //I can not close the current instalment
                //Get Payments already done
                $principal_paid=$get_outstanding_schedule['principal_payment'];
                $interest_paid=$get_outstanding_schedule['interest_payment'];
                $fees_paid=$get_outstanding_schedule['fees_payment'];
                $penalty_paid=$get_outstanding_schedule['penalty_payment'];


                if($penalty_paid==$penalty_due){
                    $penalty_due=0;
                }else if($penalty_paid<$penalty_due){
                    $penalty_due-=$penalty_paid;
                }
                if($fees_paid==$fees_due){
                    $fees_due=0;
                }
                else if($fees_paid<$fees_due){
                    $fees_due-=$fees_paid;
                }
                if($interest_paid==$interest_due){
                    $interest_due=0;
                }
                else if($interest_paid<$interest_due){
                    $interest_due-=$interest_paid;
                }
                if($principal_paid==$principal_due){
                    $principal_due=0;
                }
                else if($principal_paid<$principal_due){
                    $principal_due-=$principal_paid;
                }
                $totalToBePaid=$penalty_due+$fees_due+$interest_due+$principal_due;



                mysqli_query($link, "update pay_schedule set payment='$expectedInstalment', principal_payment='$principal_due', 
                        interest_payment='$interest_due', fees_payment='$fees_due', penalty_payment='$penalty_due', open_indicator='C', payment_tx_id='$txID', total_due='0' where id='$rowId_Current'");

                //Remaining to be distributed to the next installment
                $balanceRemaining=$amount_paid_today-$totalToBePaid;


                //Get the next Instalment
                $get_outstanding_schedule = mysqli_fetch_assoc(mysqli_query($link,"select * from pay_schedule where get_id='$loanId' and open_indicator='O' LIMIT 1"));
                $rowId = $get_outstanding_schedule['id'];
                $principal_due_new=$get_outstanding_schedule['principal_due'];
                $interest_due_new=$get_outstanding_schedule['interest'];
                $fees_due_new=$get_outstanding_schedule['fees'];
                $penalty_due_new=$get_outstanding_schedule['penalty'];

                //now distribute the remainder to the next month//
                if($balanceRemaining>=$penalty_due_new){
                    $penalty_due+=$penalty_due_new;
                    $new_penalty_due+=$penalty_due_new;
                    $balanceRemaining=$balanceRemaining-$penalty_due_new;
                }else if($balanceRemaining<$penalty_due_new){
                    $penalty_due+=$balanceRemaining;
                    $new_penalty_due+=$balanceRemaining;
                    $balanceRemaining=0;
                }

                if($balanceRemaining>=$fees_due_new){
                    $fees_due+=$fees_due_new;
                    $new_fees_due=$fees_due_new;
                    $balanceRemaining=$balanceRemaining-$fees_due_new;
                }else if($balanceRemaining<$fees_due_new){
                    $fees_due+=$balanceRemaining;
                    $new_fees_due+=$balanceRemaining;
                    $balanceRemaining=0;
                }

                if($balanceRemaining>=$interest_due_new){
                    $interest_due+=$interest_due_new;
                    $new_interest_due+=$interest_due_new;
                    $balanceRemaining=$balanceRemaining-$interest_due_new;
                }else if($balanceRemaining<$interest_due_new){
                    $interest_due+=$balanceRemaining;
                    $new_interest_due+=$balanceRemaining;
                    $balanceRemaining=0;
                }

                if($balanceRemaining>=$principal_due_new){
                    $principal_due+=$principal_due_new;
                    $new_principal_due+=$principal_due_new;
                    $balanceRemaining=$balanceRemaining-$principal_due_new;
                }else if($balanceRemaining<$principal_due_new){
                    $principal_due+=$balanceRemaining;
                    $new_principal_due+=$balanceRemaining;
                    $balanceRemaining=0;
                }

                $nextTotalPaid=$new_principal_due+$new_interest_due+$new_fees_due+$new_penalty_due;
                $total_due=$expectedInstalment-$total_due;


                mysqli_query($link, "update pay_schedule set payment='$expectedInstalment', principal_payment='$principal_due', 
                        interest_payment='$interest_due', fees_payment='$fees_due', penalty_payment='$penalty_due', open_indicator='C', payment_tx_id='$txID', total_due='0' where id='$rowId_Current'");


                //echo "$total_due and next to be paid: $nextTotalPaid";
                mysqli_query($link, "update pay_schedule set payment='$nextTotalPaid', principal_payment='$new_principal_due', 
                        interest_payment='$new_interest_due', fees_payment='$new_fees_due', penalty_payment='$new_penalty_due', open_indicator='O', payment_tx_id='$txID', total_due='$total_due' where id='$rowId'");

            }
            //Credit the LOAN GL, INTEREST GL, FEES GL
            //DEBIT THE RECEIVING ACCOUNT
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

        }

        if($amount_paid_today !== $expectedInstalment){
            $totalPaid=$amount_paid_today;
            if($total_due==$amount_paid_today){
                $principal_paid=$get_outstanding_schedule['principal_payment'];
                $interest_paid=$get_outstanding_schedule['interest_payment'];
                $fees_paid=$get_outstanding_schedule['fees_payment'];
                $penalty_paid=$get_outstanding_schedule['penalty_payment'];


                if($penalty_paid==$penalty_due){
                    $penalty_due=0;
                }else if($penalty_paid<$penalty_due){
                    $penalty_due-=$penalty_paid;
                }
                if($fees_paid==$fees_due){
                    $fees_due=0;
                }
                else if($fees_paid<$fees_due){
                    $fees_due-=$fees_paid;
                }
                if($interest_paid==$interest_due){
                    $interest_due=0;
                }
                else if($interest_paid<$interest_due){
                    $interest_due-=$interest_paid;
                }
                if($principal_paid==$principal_due){
                    $principal_due=0;
                }
                else if($principal_paid<$principal_due){
                    $principal_due-=$principal_paid;
                }
            }
            else {

                $totalPaid = $amount_paid_today;
                if ($totalPaid >= $penalty_due) {
                    $penalty_due = $penalty_due;
                    $totalPaid = $totalPaid - $penalty_due;
                } else if ($totalPaid < $penalty_due) {
                    $penalty_due += $totalPaid;
                    $totalPaid = 0;
                }
                if ($totalPaid >= $fees_due) {
                    $fees_due = $fees_due;
                    $totalPaid = $totalPaid - $fees_due;
                } else if ($totalPaid < $fees_due) {
                    $fees_due += $totalPaid;
                    $totalPaid = 0;
                }
                if ($totalPaid >= $interest_due) {
                    $interest_due = $interest_due;
                    $totalPaid = $totalPaid - $interest_due;
                } else if ($totalPaid < $interest_due) {
                    $interest_due += $totalPaid;
                    $totalPaid = 0;
                }
                if ($totalPaid >= $principal_due) {
                    $principal_due = $principal_due;
                    $totalPaid = $totalPaid - $principal_due;
                } else if ($totalPaid < $principal_due) {
                    $principal_due = $totalPaid;
                } else if ($totalPaid < $principal_due) {
                    $principal_due += $totalPaid;
                    $totalPaid = 0;
                }

                //If there was overpayment, distribute the fees///
                $consecutivePayment = $totalPaid;
                $total_due = $expectedInstalment - $consecutivePayment;

                if ($totalPaid >= 0) {
                    //Get the second instalment value

                    //Close the schedule and get values for the next schedule
                    $update = mysqli_query($link, "update pay_schedule set payment='$expectedInstalment', principal_payment='$principal_due', 
                         interest_payment='$interest_due', fees_payment='$fees_due', penalty_payment='$penalty_due', open_indicator='C', payment_tx_id='$txID', total_due='0' where id='$rowId_Current'");

                    //Get Next Schedule
                    $get_outstanding_schedule = mysqli_fetch_assoc(mysqli_query($link, "select * from pay_schedule where get_id='$loanId' and open_indicator='O' LIMIT 1"));
                    $rowId = $get_outstanding_schedule['id'];
                    $principal_due_new = $get_outstanding_schedule['principal_due'];
                    $interest_due_new = $get_outstanding_schedule['interest'];
                    $fees_due_new = $get_outstanding_schedule['fees'];
                    $penalty_due_new = $get_outstanding_schedule['penalty'];

                    $expectedInstalment = $get_outstanding_schedule['balance'];

                    ///FIXME Build a recursive function here
                    ///
                    //$totalPaid = $amount_paid_today;
                    if ($totalPaid >= $penalty_due_new) {
                        $penalty_due += $penalty_due_new;
                        $new_penalty_due += $penalty_due_new;
                        $totalPaid = $totalPaid - $penalty_due_new;
                    } else if ($totalPaid < $penalty_due_new) {
                        $penalty_due += $totalPaid;
                        $new_penalty_due += $totalPaid;
                        $totalPaid = 0;
                    }


                    if ($totalPaid >= $fees_due_new) {
                        $fees_due += $fees_due_new;
                        $new_fees_due = $fees_due_new;
                        $totalPaid = $totalPaid - $fees_due_new;
                    } else if ($totalPaid < $fees_due_new) {
                        $fees_due += $totalPaid;
                        $new_fees_due += $totalPaid;
                        $totalPaid = 0;
                    }

                    if ($totalPaid >= $interest_due_new) {
                        $interest_due += $interest_due_new;
                        $new_interest_due += $interest_due_new;
                        $totalPaid = $totalPaid - $interest_due_new;
                    } else if ($totalPaid < $interest_due_new) {
                        $interest_due += $totalPaid;
                        $new_interest_due += $totalPaid;
                        $totalPaid = 0;
                    }


                    if ($totalPaid >= $principal_due_new) {
                        $principal_due += $principal_due_new;
                        $new_principal_due += $principal_due_new;
                        $totalPaid = $totalPaid - $principal_due_new;
                    } else if ($totalPaid < $principal_due_new) {
                        $principal_due += $totalPaid;
                        $new_principal_due += $totalPaid;
                        $totalPaid = 0;
                    }

                }
            }

            if($totalPaid==$expectedInstalment) {
                $update = mysqli_query($link, "update pay_schedule set payment='$consecutivePayment', principal_payment='$principal_due', 
                            interest_payment='$interest_due', fees_payment='$fees_due', penalty_payment='$penalty_due', open_indicator='C', payment_tx_id='$txID', total_due='0' where id='$rowId'");//Update Payments for the schedule of the first open instalment
            }
            else if($totalPaid<$expectedInstalment){

                //Close the First Payment
                $update = mysqli_query($link, "update pay_schedule set payment='$expectedInstalment', principal_payment='$principal_due',
                            interest_payment='$interest_due', fees_payment='$fees_due', penalty_payment='$penalty_due', open_indicator='C', payment_tx_id='$txID', total_due='0' where id='$rowId_Current'");//Update Payments for the schedule of the first open instalment
                //Close the 2nd Payment
                $update = mysqli_query($link, "update pay_schedule set payment='$consecutivePayment', principal_payment='$new_principal_due', 
                            interest_payment='$new_interest_due', fees_payment='$new_fees_due', penalty_payment='$new_penalty_due', open_indicator='O', payment_tx_id='$txID', total_due='$total_due' where id='$rowId'");
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
/*        class MyMobileAPI
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

        //$image = "<thml><body><img src='$logo' style='max-width: 283px; max-height: 93px' alt='logo' class='img-responsive'/></body></thml>";

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

        $smsLength=strlen($content);
        $messages=ceil($smsLength/160);
        mysqli_query($link, "insert into sms_messages values(0,'$phone','$content',NOW(),'','','$UserID','$smsLength','$messages')");*/

        if ($accountBalance <= '0.15') {
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

                $menu=base64_encode('405');
                $URL = "listpayment.php?id=$borrower&&mid=$menu&&loanId=$loanId";
                echo "<script type='text/javascript'>document.location.href='{$URL}';</script>";
                echo '<META HTTP-EQUIV="refresh" content="5;URL=' . $URL . '">';



        }
    } ?>
</div>
</body>
</html>
