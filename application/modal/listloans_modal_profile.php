
<?php

$getCompanyInfo = mysqli_query($link, "SELECT * FROM systemset") or die(mysqli_error($link));
$companyInfo = mysqli_fetch_assoc($getCompanyInfo);

$logo = $companyInfo['image'];
$companyName = $companyInfo['name'];
$companyEmail = $companyInfo['email'];
$regNo = $companyInfo['registration'];
$mobile = $companyInfo['mobile'];

$id = $_GET['loanId'];
$select = mysqli_query($link, "SELECT * FROM loan_info where id='$id'") or die (mysqli_error($link));
while ($row = mysqli_fetch_array($select)) {
    $id = $row['id'];
    ?>

    <div class="modal fade" id="myModal<?php echo $id; ?>" role="dialog">
        <div class="modal-dialog" style="width: 700px;">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <legend style="text-align: center; color: green"><strong>Change Loan Status</strong></legend>
                </div>
                <div class="modal-body">

                    <?php
                    $search = mysqli_query($link, "SELECT * FROM systemset");
                    $get_searched = mysqli_fetch_array($search);
                    ?>
                    <div align="center">
                    <img src="<?php echo $get_searched['image']; ?>">
                    </div>

                    <form class="form-horizontal" method="post" enctype="multipart/form-data">

                        <input type="hidden" value="<?php echo $id; ?>" name="userid">
                        <?php
                        //Check status of the Loan,
                        $status = mysqli_fetch_assoc(mysqli_query($link, "select * from loan_info where id='$id'"));
                        $loanStatus = $status['status'];
                        $loan_disbursed_by = $status['loan_disbursed_by_id'];
                        $loanAmount = number_format($status['amount'], 2, ".", ",");
                        $disbursed_amount = $status['amount'];
                        $payDay = $status['pay_date'];
                        $instalment = number_format($status['amount_topay'], 2, ".", ",");
                        $borrower = $status['borrower'];
                        $loanAccount = $status['baccount'];

                        //Get the Fees for the Loan and it's GL Code
                        $currentLoanFees=mysqli_fetch_assoc(mysqli_query($link,"select gl_code, sum(fee_amount) from loan_fees where loan='$id' and fee_name!='Interest' group by gl_code"));
                        $fees_gl_code=$currentLoanFees['gl_code'];
                        $totalFees=$currentLoanFees['sum(fee_amount)'];

                        //Get the Interest for the Loan and its GL Code
                        $currentLoanInterest=mysqli_fetch_assoc(mysqli_query($link,"select gl_code, sum(fee_amount) from loan_fees where loan='$id' and fee_name='Interest' group by gl_code"));
                        $interest_gl_code=$currentLoanInterest['gl_code'];
                        $interest_value=$currentLoanInterest['sum(fee_amount)'];

                        $loan_gl_code = $status['gl_code'];

                        //Get the Opening Balances of the Loan, Fees and the Interest
                        $loanGLBalance = mysqli_fetch_assoc(mysqli_query($link,"select balance from gl_codes where code='$loan_gl_code'"));
                        $feesGLBalance = mysqli_fetch_assoc(mysqli_query($link,"select balance from gl_codes where code='$fees_gl_code'"));
                        $interestGLBalance = mysqli_fetch_assoc(mysqli_query($link,"select balance from gl_codes where code='$interest_gl_code'"));

                        $gl_loan_balance=$loanGLBalance['balance'];
                        $gl_fees_balance=$feesGLBalance['balance'];
                        $gl_interest_balance=$interestGLBalance['balance'];

                        //Add the double entry to the income GL Code FIXME, add the link for the Income GL Codes
                        //Accumulate for the financial year//
                        $getIncomeCodeInterest = mysqli_fetch_assoc(mysqli_query($link,"select * from gl_codes where name like 'Interest Income%'"));
                        $getIncomeCodeFees = mysqli_fetch_assoc(mysqli_query($link,"select * from gl_codes where name like 'Fee Income%'"));
                        $interest_income_gl = $getIncomeCodeInterest['code'];
                        $fees_income_gl = $getIncomeCodeFees['code'];


                        //Get the balances for the Income Codes
                        $interestIncomeBalance = mysqli_fetch_assoc(mysqli_query($link,"select balance from gl_codes where code='$interest_income_gl'"));
                        $feesIncomeBalance = mysqli_fetch_assoc(mysqli_query($link,"select balance from gl_codes where code='$fees_income_gl'"));
                        $gl_interest_income_balance=$interestIncomeBalance['balance'];
                        $gl_fees_income_balance=$feesIncomeBalance['balance'];

                        //Get banking Details
                        $bankingDetails = mysqli_fetch_array(mysqli_query($link, "SELECT transaction, disbursement_method FROM loan_disbursements WHERE loan='$id'"));
                        $bankDetails = json_decode($bankingDetails['transaction'], true);
                        $disburseMethod = $bankingDetails['disbursement_method'];

                        switch ($disburseMethod) {
                            case "Online Transfer":
                                $bankName = str_replace("_"," ", $bankDetails['bankName']);
                                $accountName = $bankDetails['accountName'];
                                $branchName = str_replace("_"," ", $bankDetails['branchName']);;
                                $accountNumber = $bankDetails['accountNumber'];
                                $branchCode = $bankDetails['branchCode'];
                                $typeOfAccount = $bankDetails['accountType'];
                                break;
                            case "Mobile Money":
                                $bankName = $bankDetails['bankName'];
                                $accountNumber = $bankDetails['accountNumber'];
                                break;
                        }

                        //Get names of borrower
                        $names = mysqli_fetch_assoc(mysqli_query($link, "select fname, lname, phone, email from borrowers where id='$borrower'"));
                        $fname = $names['fname'];
                        $lname = $names['lname'];
                        $phone = $names['phone'];
                        $email = $names['email'];
                        ?>
                        <div class="form-group" style="align-content: center">
                            <label for="" class="col-sm-4 control-label">Update Status</label>
                            <div class="col-sm-7">
                                <select name="Status"
                                        class="form-control"
                                        id="loanStatus"
                                        data-placeholder="Status"
                                        onchange="showfield(this.options[this.selectedIndex].value)"
                                        style="width: 100%;">
                                    <option></option>
                                    <?php if ($loanStatus == "Pending") { ?>
                                        <option value="Pending Disbursement">Approve</option>
                                        <option value="DECLINED">Decline</option>
                                    <?php } else if ($loanStatus == "DECLINED") { ?>
                                        <option value="Pending Disbursement">Approve</option>
                                    <?php } else if ($loanStatus == "Pending Disbursement") { ?>
                                        <option value="">Disburse</option>
                                        <option value="DECLINED">Decline</option>
                                    <?php } else { ?>
                                        <option value="C">Account Closed</option>
                                        <option value="D">Dispute</option>
                                        <option value="E">Terms Extended</option>
                                        <option value="L">Handed Over</option>
                                        <option value="T">Early Settlement</option>
                                        <option value="V">Cooling Off Settlement</option>
                                        <option value="W">Written Off</option>
                                        <option value="P">Paid Up</option>
                                        <option value="Z">Deceased</option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div id="disbursement" class="form-group"></div>

                        <div class="modal-footer" align="center" style="align-content: center">
                            <button type="submit" name="update_status" class="btn btn-flat btn-success"><i
                                        class="icon-save"></i>&nbsp;Update
                            </button>
                            <button class="btn btn-flat btn-danger" data-dismiss="modal" aria-hidden="true"><i
                                        class="icon-remove icon-large"></i> Close
                            </button>
                        </div>
                    </form>
                </div>

                <?php
                if (isset($_POST['update_status'])) {

                    $Status_save = $_POST['Status'];

                    $UserID = $_POST['userid'];
                    if (isset($_POST['reference'])) {
                        $reference = $_POST['reference'];
                    } else {
                        $reference = "";
                    }
                    if (isset($_POST['reason'])) {
                        $reason = $_POST['reason'];
                    } else {
                        $reason = "";
                    }

                    $loan_update = date('Y-m-d H:i:s');
                    $tid = $_SESSION['tid'];
                    // echo "xhr.open(\"GET\", 'https://api.smsportal.com/api5/http5.aspx?Type=sendparam&username=serumula&password=5erumul@2020&numto=266$phone&data1=$content', true);";

                    if($Status_save!==""){
                        mysqli_query($link, "insert into loan_statuses values (0,'$Status_save','$tid','$loan_update','$UserID','$reason')");
                        mysqli_query($link, "UPDATE loan_info SET status='$Status_save', modified_date = '$loan_update', payment_reference='$reference', status_reason='$reason', modified_by='$tid' WHERE id = '$UserID'") or die(mysqli_error());
                    }

                    //Get User of the ID//
                    $borrower = mysqli_fetch_assoc(mysqli_query($link, "select * from loan_info where id='$UserID'"));
                    $borrowerId = $borrower['borrower'];
                    $loanAmount = $borrower['amount'];
                    $applicationDate = $borrower['date_release'];
                    $instalmentAmount = $borrower['amount_topay'];
                    $loanDuration = $borrower['loan_duration']." ".$borrower['loan_duration_period'];

                    switch ($Status_save) {
                        case "":
                            $disbursementData = json_encode($_POST['recipient']);
                            $receiverBank = $_POST['recipient']['bankName'];
                            $account = $_POST['recipient']['accountNumber'];
                            $payingAccount = $_POST['recipient']['fromAccount'];
                            $payout = $_POST['recipient']['disbursedAmount'];

                            $gl_code=explode("-",$payingAccount)[0];
                            $payingAccount=explode("-",$payingAccount)[1];

                            $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                            $txID = substr(str_shuffle($permitted_chars), 0, 10);


                            //Get the current balance
                            $balance=mysqli_fetch_assoc(mysqli_query($link,"select balance from bank_accounts where accountNumber='$payingAccount'"));
                            $currentBalance=$balance['balance'];

                            if($currentBalance>=$payout) {
                                mysqli_query($link, "insert into loan_statuses values (0,'$Status_save','$tid','$loan_update','$UserID','$reason')");
                                mysqli_query($link, "UPDATE loan_info SET status='$Status_save', modified_date = '$loan_update', payment_reference='$reference', status_reason='$reason', modified_by='$tid' WHERE id = '$UserID'") or die(mysqli_error());
                                //Credit the Paying Account
                                $finalBalance=$currentBalance-$payout;
                                $transaction = mysqli_query($link, "INSERT into system_transactions values (0,NOW(),'$payingAccount','$account-Loan Payout','$currentBalance','0','$payout','$finalBalance','$tid','','$txID')");
                                $paying_transaction_journal = mysqli_query($link, "INSERT into journal_transactions values (0,NOW(),'$gl_code','$account-Loan Payout','$currentBalance','0','$payout','$finalBalance','$tid','$txID','','')");

                                mysqli_query($link, "update bank_accounts set balance='$finalBalance' where accountNumber='$payingAccount'");
                                mysqli_query($link, "update gl_codes set balance='$finalBalance' where code='$gl_code'");


                                //$bankingInfo = mysqli_query($link, "update gl_codes set balance='$finalBalance' where code='$gl_code'");

                                //debit the Loan Account
                                $transaction = mysqli_query($link, "INSERT into system_transactions values (0,NOW(),'$loanAccount','Loan Deposit to $accountNumber','0','$payout','','$payout','$tid','$UserID','$txID')");
                                $loan_transaction_journal = mysqli_query($link, "INSERT into journal_transactions values (0,NOW(),'$loan_gl_code','Loan Deposit to $accountNumber','0','$payout','','$payout','$tid','$txID','','')");

                                $principalFees = $totalFees + $payout;
                                $finalLoanBalance = $principalFees + $interest_value;

                                //Updated Journal Balances
                                $totalLoanFees=$totalFees+$gl_fees_balance;
                                $totalInterestFees=$interest_value+$gl_interest_balance;
                                $totalPrincipalLoan=$payout+$gl_loan_balance;

                                $totalInterestIncome=$gl_interest_income_balance+$interest_value;
                                $totalFeesIncome=$gl_fees_income_balance+$totalFees;

                                //Update the GL Account Balances
                                //Receivables
                                mysqli_query($link, "update gl_codes set balance='$totalLoanFees' where code='$fees_gl_code'");
                                mysqli_query($link, "update gl_codes set balance='$totalInterestFees' where code='$interest_gl_code'");
                                mysqli_query($link, "update gl_codes set balance='$totalPrincipalLoan' where code='$loan_gl_code'");
                                //Income
                                mysqli_query($link, "update gl_codes set balance='$totalInterestIncome' where code='$interest_income_gl'");
                                mysqli_query($link, "update gl_codes set balance='$totalFeesIncome' where code='$fees_income_gl'");



                                //Get the current balance so that it can be updated....


                                //$transaction_principal = mysqli_query($link, "INSERT into system_transactions values (0,NOW(),'$account','$account-Loan','0','0','$principalAmount','$principalAmount','$tid','$loan_id','$txID')");
                                $transaction_fees = mysqli_query($link, "INSERT into system_transactions values (0,NOW(),'$loanAccount','Loan Fees','$payout','$totalFees','0','$principalFees','$tid','$UserID','$txID')");
                                $transaction_interest = mysqli_query($link, "INSERT into system_transactions values (0,NOW(),'$loanAccount','Loan Interest','$principalFees','$interest_value','0','$finalLoanBalance','$tid','$UserID','$txID')");

                                //Journal Entries for Fees and Interest (Debit)
                                $fees_transaction_journal = mysqli_query($link, "INSERT into journal_transactions values (0,NOW(),'$fees_gl_code','Loan Fees $loanAccount','$payout','$totalFees','','$principalFees','$tid','$txID','','')");
                                $interest_transaction_journal = mysqli_query($link, "INSERT into journal_transactions values (0,NOW(),'$interest_gl_code','Loan Interest $loanAccount','$principalFees','$interest_value','','$finalLoanBalance','$tid','$txID','','')");

                                //Journal Entries for Fees and Interest (Credit)
                                $fees_income_journal = mysqli_query($link, "INSERT into journal_transactions values (0,NOW(),'$fees_income_gl','Loan Fees $loanAccount','$gl_fees_income_balance','','$totalFees','$totalFeesIncome','$tid','$txID','','')");
                                $interest_income_journal = mysqli_query($link, "INSERT into journal_transactions values (0,NOW(),'$interest_income_gl','Loan Interest $loanAccount','$gl_interest_income_balance','','$interest_value','$totalInterestIncome','$tid','$txID','','')");


                                mysqli_query($link, "INSERT INTO loan_disbursements values (0,'$UserID',NOW(),'$disbursementData','$loan_disbursed_by')");

                                $content = "PFS: Hi $fname $lname, Your loan for M$loanAmount from $companyName have been paid to $accountNumber, first instalment of M$instalment is payable per month from $payDay.";
                            }else{
                                echo '<div class="alert alert-danger" >
                                        <a href = "#" class = "close" data-dismiss= "alert"> &times;</a>
                                         Chosen disbursing account does not have enough balance to make a transfer, please choose a differnt account!&nbsp; &nbsp;&nbsp;
                                           </div>';
                            }
                            break;
                        case "Pending Disbursement":
                            $loanStatus = "approved";
                            $content = "PFS: Hi $fname $lname, your loan application has been $loanStatus. Thank you for choosing $companyName.";
                            $contentApproval = "Application Date: <b>$applicationDate</b><br> Loan Period: <b>$loanDuration</b><br> Instalment: <b>$instalment</b><br> First Due Date: <b>$payDay</b>";
                            break;
                        case "DECLINED":
                            $loanStatus = "declined";
                            $content = "PFS: Hi $fname $lname, your loan application has been $loanStatus. Thank you for choosing $companyName.";
                            break;
                        case "Z":
                        case "C":
                            $loanStatus = "closed";
                        $content = "PFS: Hi $fname $lname, your loan application has been $loanStatus. Thank you for choosing $companyName.";
                        break;
                        case "D":
                            $loanStatus = "disputed";
                            $content = "PFS: Hi $fname $lname, your loan application has been $loanStatus. Thank you for choosing $companyName.";
                            break;
                        case "E":
                            $loanStatus = "extended";
                            $content = "PFS: Hi $fname $lname, your loan application has been $loanStatus. Thank you for choosing $companyName.";
                            break;
                        case "L":
                            $loanStatus = "handed over";
                            $content = "PFS: Hi $fname $lname, your loan application has been $loanStatus. Thank you for choosing $companyName.";
                            break;
                        case "T":
                            $loanStatus = "settled";
                            $content = "PFS: Hi $fname $lname, your loan application has been $loanStatus. Thank you for choosing $companyName.";
                            break;
                        case "W":
                            $loanStatus = "written off";
                            $content = "PFS: Hi $fname $lname, your loan application has been $loanStatus. Thank you for choosing $companyName.";
                            break;
                        case "P":
                            $loanStatus = "paid up";
                            $content = "PFS: Hi $fname $lname, your loan application has been $loanStatus. Thank you for choosing $companyName.";
                            break;
                        default:
                            $loanStatus ="";
                    }

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
                            echo $return->pass;
                            echo $return->msg;
                            return $return;
                        }

                    }

                    $sendSMS = new MyMobileAPI();
                    $sendSMS->sendSms("$phone","$content");
                    $logo=str_replace("../","","$logo");
                    $location=$baseURL."".$logo;
                   $image = "<img src='$location' style='max-width: 283px; max-height: 93px' alt='logo' class='img-responsive'/></body></html>";

                    $to = "$email";
                    $FromEmail = "admin@sbs-eazy.loans"; //Should Company Email
                    $subject = "$companyName - Loan Status";
                    $body .= "<html><head></head><body>";
                    $body .= "<img src='$location' alt='' /></body></html>";
                    $body .= "Dear <b>$fname $lname</b>,<br><br>";
                    $body .= "\n\nThis message is to confirm the status of your loan application.<br><br>";
                    $body .= "\n";
                    $body .= "\n";
                    $body .= "\n<b>The details for the loan application are below: </b><br><br>";
                    $body .= "\n";
                    $body .= "\n";
                    if($loanStatus!=="approved") {
                        $body .= "\n<b>$content</b>";
                    }else{
                        $body .= "\n$contentApproval";
                    }
                    $body .= "\n";
                    $body .= "<br><br>Regards.";
                    $body .= "<br>$companyName - $mobile.";
                    $body .= "<br>A Registered Financial Services Provider $regNo.";
                    $body .= "<br>$companyEmail.";
                    $headers = "Content-type: text/html; charset=iso-8859-1\r\n";
                    $headers .= "From: ".$companyName." <".$FromEmail.">";

                    mail($to, $subject, $body, $headers);


                    $smsLength=strlen($content);
                    $messages=ceil($smsLength/160);
                    mysqli_query($link, "insert into sms_messages values(0,'$phone','$content',NOW(),'','','$UserID','$smsLength','$messages')");

                    echo "<script>window.location='viewborrowersloan.php?loanId=" . $UserID . "&id=" . $borrowerId . "'; </script>";


                }

                ?>
            </div>
        </div>
    </div>
<?php } ?>

<!--<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript">
    $(function () {
        $("#loanStatus").change(function () {
            if ($(this).val() !== "Approve") {
                $("#reason").show();
            }
            else if ($(this).val() === "Approve") {
                $("#reference").show();
            } else {
                $("#reference").hide();
            }
        });
    });
</script>-->


<script type="text/javascript">
    <?php
    //Get All Bank Accounts
            $accounts = mysqli_query($link,"select * from bank_accounts where transactionType='$loan_disbursed_by'");
    ?>
    function showfield(name) {
        if (name !== '')
            document.getElementById('disbursement').innerHTML = '<div class="form-group">\n' +
                '    <label for="" class="col-sm-3 control-label">Reason for status</label>\n' +
                '    <div class="col-sm-8"><input type="text" placeholder="Please Enter reason for change in status" class="form-control" name="reason" id="reason" required>\n' +
                '    </div>\n' +
                '</div>';

        else if (name === '')
        <?php if ($loan_disbursed_by == "Cheque"){ ?>
            document.getElementById('disbursement').innerHTML = '<div class="form-group">\n' +
                '                            <label for="" class="col-sm-6 control-label">Cheque Number</label>\n' +
                '                            <div class="col-sm-6"><input type="text" placeholder="Please Enter Cheque Number" class="form-control" name="reference" id="reference" required></div></div>';

        <?php } ?>
        <?php if ($loan_disbursed_by == "Online Transfer"){ ?>
        document.getElementById('disbursement').innerHTML = '<div class="col-sm-6"><div class="form-group">\n' +
            '                            <label for="" class="col-sm-5 control-label">Account Name</label>\n' +
            '                            <div class="col-sm-7"><input type="text" placeholder="Account Name" class="form-control" name="recipient[accountName]"  id="accountName" value="<?php echo $accountName; ?>" required></div></div></div>' +
            '                            <div class="col-sm-6"><div class="form-group"><label for="" class="col-sm-5 control-label">Account Type</label>\n' +
            '                            <div class="col-sm-7"><input type="text" placeholder="Account Type" class="form-control" name="recipient[accountType]" id="accountType"  value=" <?php echo $typeOfAccount; ?>" required></div></div></div>' +
            '<div class="col-sm-6"><div class="form-group">\n' +
            '                            <label for="" class="col-sm-5 control-label">Bank Name</label>\n' +
            '                            <div class="col-sm-7"><select class="form-control" name="recipient[bankName]" id="bankName" required><option><?php echo $bankName; ?></option></select></div></div></div>' +
            '                            <div class="col-sm-6"><div class="form-group"><label for="" class="col-sm-5 control-label">Branch Name</label>\n' +
            '                            <div class="col-sm-7"><input type="text" placeholder="Branch Name" class="form-control" name="recipient[branchName]" id="branchName"  value="<?php echo $branchName; ?>" required></div></div></div>' +
            '<div class="col-sm-6"><div class="form-group">\n' +
            '                            <label for="" class="col-sm-5 control-label">Account Number</label>\n' +
            '                            <div class="col-sm-7"><input type="number" placeholder="Account No." min="0" class="form-control" name="recipient[accountNumber]" id="bankAccountNumber"  value="<?php echo $accountNumber; ?>" required></div></div></div>' +
            '                            <div class="col-sm-6"><div class="form-group"><label for="" class="col-sm-5 control-label">Branch Code</label>\n' +
            '                            <div class="col-sm-7"><input type="number" placeholder="Branch Code" min="0" class="form-control" name="recipient[branchCode]" id="branchCode"  value="<?php echo $branchCode; ?>" required></div></div></div>' +
            '<div class="col-sm-6"><div class="form-group">\n' +
            '                            <label for="" class="col-sm-5 control-label">Transfer Amount: </label>\n' +
            '                            <div class="col-sm-7"><input type="number" placeholder="Transfer Amount" value="<?php echo $disbursed_amount; ?>" readonly min="0" class="form-control" name="recipient[disbursedAmount]" id="disbursedAmount" required></div></div></div>'+
            '<div class="col-sm-6"><div class="form-group">\n' +
            '                            <label for="" class="col-sm-5 control-label">From Account: </label>\n' +
            '                            <div class="col-sm-7"><select class="form-control" name="recipient[fromAccount]" id="disburseAccount" required><option value="">Select</option><?php while($row=mysqli_fetch_assoc($accounts)){ ?><option value="<?php echo $row['gl_code']; ?>-<?php echo $row['accountNumber']; ?>"><?php echo $row['gl_code']; ?>-<?php echo $row['bankName']." - ".$row['accountNumber']." - Bal (".$row['balance'].")"; ?></option><?php } ?></select></div></div></div>';



        <?php } ?>
        <?php if ($loan_disbursed_by == "Cash"){ ?>
        document.getElementById('disbursement').innerHTML = '<div class="form-group">\n' +
            '                            <label for="" class="col-sm-6 control-label">Receipt Number</label>\n' +
            '                            <div class="col-sm-6"><input type="text" placeholder="Receipt Number" class="form-control" name="reference" id="reference" required></div></div>';

        <?php } ?>
        <?php if ($loan_disbursed_by == "Mobile Money"){ ?>
        document.getElementById('disbursement').innerHTML = '<div class="form-group">\n' +
            '                            <label for="" class="col-sm-4 control-label">Mobile Money Service</label>\n' +
            '                            <div class="col-sm-7"><select name ="recipient[serviceProvider]"  class="form-control"  required><option><?php echo $bankName; ?></option></select></div></div>' +
            '<div class="form-group">\n' +
            '                            <label for="" class="col-sm-4 control-label">Mobile Money Number</label>\n' +
            '                            <div class="col-sm-7"><input type="text" placeholder="Mobile Money Reference" class="form-control" name="recipient[accountNumber]" id="reference" value="<?php echo $accountNumber; ?>" required></div></div>\n' +
            '<div class="col-sm-6"><div class="form-group">\n' +
            '                            <label for="" class="col-sm-5 control-label">Transfer Amount: </label>\n' +
            '                            <div class="col-sm-7"><input type="number" placeholder="Transfer Amount" value="<?php echo $disbursed_amount; ?>" readonly min="0" class="form-control" name="recipient[disbursedAmount]" id="disbursedAmount" required></div></div></div>' +
            '<div class="col-sm-6"><div class="form-group">\n' +
            '                            <label for="" class="col-sm-5 control-label">From Account: </label>\n' +
            '                            <div class="col-sm-7"><select class="form-control" name="recipient[fromAccount]" id="disburseAccount" required><option value="">Select</option><?php while($row=mysqli_fetch_assoc($accounts)){ ?><option value="<?php echo $row['gl_code']; ?>-<?php echo $row['accountNumber']; ?>"><?php echo $row['gl_code']; ?>-<?php echo $row['bankName']." - ".$row['accountNumber']." - Bal (".$row['balance'].")"; ?></option><?php } ?></select></div></div></div>';

        <?php } ?>
    }
</script>
