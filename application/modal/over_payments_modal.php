<?php
$tid = $_SESSION['tid'];
$account=$_SESSION['account'];
$payment_id=$_SESSION['payment_id'];
$select = mysqli_query($link, "SELECT * FROM payments where balance < 0 and status='C' and account='$account' order by pay_date desc limit 1") or die (mysqli_error($link));
while ($row = mysqli_fetch_array($select)) {
    $id = $row['id'];
    $gl_code = $row['gl_code'];
    $baccount = $row['account'];

    $getLoan = mysqli_fetch_assoc(mysqli_query($link, "select id, borrower from loan_info where baccount='$baccount'"));
    $loanId = $getLoan['id'];
    $customer= $getLoan['borrower'];

    //Get the disbursed details
    $getTransactionDetails = mysqli_fetch_assoc(mysqli_query($link, "select transaction, disbursement_method from loan_disbursements where loan='$loanId'"));
    $bankDetails = json_decode($getTransactionDetails['transaction'], true);
    $disburseMethod = $getTransactionDetails['disbursement_method'];
    $accounts = mysqli_query($link,"select * from bank_accounts where transactionType='$disburseMethod'");
    switch ($disburseMethod) {
        case "Online Transfer":
            $bankName = str_replace("_", " ", $bankDetails['bankName']);
            $accountName = $bankDetails['accountName'];
            $branchName = str_replace("_", " ", $bankDetails['branchName']);;
            $accountNumber = $bankDetails['accountNumber'];
            $branchCode = $bankDetails['branchCode'];
            $typeOfAccount = $bankDetails['accountType'];
            break;
        case "Mobile Money":
            $bankName = $bankDetails['bankName'];
            $accountNumber = $bankDetails['accountNumber'];
            break;
    }

    $getCodeName = mysqli_fetch_assoc(mysqli_query($link, "select * from bank_accounts where gl_code='$gl_code'"));
    $loan_disburse_method = $getCodeName['transactionType'];
    ?>

    <div class="modal fade" id="paymentId<?php echo $payment_id; ?>" role="dialog">
        <div class="modal-dialog" id="printableArea">
            <!-- Modal content-->
            <div class="modal-content" style="width: 750px">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <legend style="color: red; text-align: center">
                        <strong>Loan Overpaid - Transaction Details</strong>
                    </legend>
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
                        <h4 style="color: green" align="center"><b>The payment was made into : <br>
                                <?php echo $gl_code . "-" . $getCodeName['accountNumber'] . "-" . $getCodeName['bankName']; ?></b>
                        </h4><br>
                        <h4 style="color: red">The Overpaid amount
                            is: <?php echo $get_searched['currency'] . " " . number_format(-1 * $row['balance'], 2, '.', ',') ?></h4>
                        <?php
                        $loanAccount=$row['account'];
                        $transactionId=$row['tx_id'];
                        $paidAmount=$row['amount_to_pay'];
                        switch ($disburseMethod) {
                            case "Online Transfer":
                                ?>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="" class="col-sm-5 control-label">Account Name</label>
                                        <div class="col-sm-7"><input type="text" placeholder="Account Name"
                                                                     class="form-control"
                                                                     name="accountName" id="accountName"
                                                                     value="<?php echo $accountName; ?>" required></div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group"><label for="" class="col-sm-5 control-label">Account
                                            Type</label>
                                        <div class="col-sm-7"><input type="text" placeholder="Account Type"
                                                                     class="form-control"
                                                                     name="accountType" id="accountType"
                                                                     value=" <?php echo $typeOfAccount; ?>" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="" class="col-sm-5 control-label">Bank Name</label>
                                        <div class="col-sm-7"><select class="form-control" name="bankName"
                                                                      id="bankName" required>
                                                <option><?php echo $bankName; ?></option>
                                            </select></div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group"><label for="" class="col-sm-5 control-label">Branch
                                            Name</label>
                                        <div class="col-sm-7"><input type="text" placeholder="Branch Name"
                                                                     class="form-control"
                                                                     name="branchName" id="branchName"
                                                                     value="<?php echo $branchName; ?>" required></div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="" class="col-sm-5 control-label">Account Number</label>
                                        <div class="col-sm-7"><input type="number" placeholder="Account No." min="0"
                                                                     class="form-control"
                                                                     name="accountNumber"
                                                                     id="bankAccountNumber"
                                                                     value="<?php echo $accountNumber; ?>" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group"><label for="" class="col-sm-5 control-label">Branch
                                            Code</label>
                                        <div class="col-sm-7"><input type="number" placeholder="Branch Code" min="0"
                                                                     class="form-control" name="recipient[branchCode]"
                                                                     id="branchCode" value="<?php echo $branchCode; ?>"
                                                                     required></div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="" class="col-sm-5 control-label">Transfer Amount: </label>
                                        <div class="col-sm-7"><input type="number" placeholder="Transfer Amount"
                                                                     value="<?php echo -1 * $row['balance']; ?>"
                                                                     readonly min="0"
                                                                     class="form-control"
                                                                     name="disbursedAmount"
                                                                     id="disbursedAmount" required></div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="" class="col-sm-5 control-label">From Account: </label>
                                        <div class="col-sm-7"><select class="form-control" name="fromAccount"
                                                                      id="disburseAccount" required>
                                                <option value="">Select
                                                </option><?php while ($row = mysqli_fetch_assoc($accounts)) { ?>
                                                    <option
                                                    value="<?php echo $row['gl_code']; ?>-<?php echo $row['accountNumber']; ?>"><?php echo $row['gl_code']; ?>
                                                    -<?php echo $row['bankName'] . " - " . $row['accountNumber'] . " - Bal (" . $row['balance'] . ")"; ?></option><?php } ?>
                                            </select></div>
                                    </div>
                                </div>

                                <?php break;
                            case "Mobile Money":
                                ?>
                                <div class="form-group">
                                    <label for="" class="col-sm-4 control-label">Mobile Money Service</label>
                                    <div class="col-sm-7"><select name="recipient[serviceProvider]" class="form-control"
                                                                  required>
                                            <option><?php echo $bankName; ?></option>
                                        </select></div>
                                </div>
                                <div class="form-group">
                                    <label for="" class="col-sm-4 control-label">Mobile Money Number</label>
                                    <div class="col-sm-7"><input type="text" placeholder="Mobile Money Reference"
                                                                 class="form-control" name="accountNumber"
                                                                 id="reference" value="<?php echo $accountNumber; ?>"
                                                                 required></div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="" class="col-sm-5 control-label">Transfer Amount: </label>
                                        <div class="col-sm-7"><input type="number" placeholder="Transfer Amount"
                                                                     value="<?php echo -1 * $row['balance']; ?>" readonly
                                                                     min="0" class="form-control"
                                                                     name="recipient[disbursedAmount]"
                                                                     id="disbursedAmount" required></div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="" class="col-sm-5 control-label">From Account: </label>
                                        <div class="col-sm-7"><select class="form-control" name="fromAccount"
                                                                      id="disburseAccount" required>
                                                <option value="">Select
                                                </option><?php while ($row = mysqli_fetch_assoc($accounts)) { ?>
                                                    <option
                                                    value="<?php echo $row['gl_code']; ?>-<?php echo $row['accountNumber']; ?>"><?php echo $row['gl_code']; ?>
                                                    -<?php echo $row['bankName'] . " - " . $row['accountNumber'] . " - Bal (" . $row['balance'] . ")"; ?></option><?php } ?>
                                            </select></div>
                                    </div>
                                </div>
                                <?php
                                break;
                        }
                        ?>
                        <input type="hidden" name="loanAccount" value="<?php echo $loanAccount; ?>">
                        <input type="hidden" name="transactionId" value="<?php echo $transactionId; ?>">
                        <input type="hidden" name="paidAmount" value="<?php echo $paidAmount; ?>">
                        <div class="modal-footer" align="center">
                            <button type="submit" name="Confirm" class="btn btn-flat btn-success"><i
                                        class="icon-save"></i>&nbsp;Confirm
                            </button>
                            <button class="btn btn-flat btn-danger" data-dismiss="modal" aria-hidden="true"><i
                                        class="icon-remove icon-large"></i> Close
                            </button>
                        </div>
                    </form>



                </div>
                <?php
                if(isset($_POST['Confirm'])){
                    //Do the transaction//
                    $disburseAccount = $_POST['fromAccount'];
                    $disbursedAmount= $_POST['disbursedAmount'];
                    $receivingAccountNumber = $_POST['accountNumber'];
                    $loanAccount = $_POST['loanAccount'];
                    $paidAmount = $_POST['paidAmount'];
                    $tx_id = $_POST['transactionId'];

                    //Get the GL Code of the loan Account
                    $loanGL=mysqli_fetch_assoc(mysqli_query($link,"select gl_code from loan_info where baccount='$loanAccount'"));

                    $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    $txID = substr(str_shuffle($permitted_chars), 0, 10);

                    $gl_code = explode("-",$disburseAccount)[0];
                    $bankAccount = explode("-",$disburseAccount)[1];

                    $actualToHaveBeenPaid=$paidAmount-$disbursedAmount;

                    //Updated the payment to set the balance to 0 and amount Paid to be less that the reimbursement
                    ///
                    /// Statuses
                    /// R-Reversed
                    /// P-Paid Back Excess
                    /// C-Completed
                    mysqli_query($link,"update payments set status='P' where tx_id='$transactionId' and account='$loanAccount'");

                    $insert = mysqli_query($link, "INSERT INTO payments(id,tid, account,balance, customer, loan, pay_date, amount_to_pay, remarks, payment_method,reference,tx_id,gl_code, status) 
                                                VALUES(0,'$tid','$loanAccount','0','$customer','$loanId',NOW(),'-$disbursedAmount','Overpayment Disbursement','$disburseMethod','Overpay Reimbursement','$txID','$gl_code','P')");

                    //Add the system_transaction for the bank account
                    //Paying Account(Credit)
                    $balanceCheck_to = mysqli_fetch_assoc(mysqli_query($link,"select balance from bank_accounts where accountNumber='$bankAccount'"));
                    $from_balance=$balanceCheck_to['balance'];//Opening Balance
                    $finalFromBalance = $from_balance-$disbursedAmount;

                    $from_transaction = mysqli_query($link, "INSERT into system_transactions values (0,NOW(),'$bankAccount','Reimbursement-$receivingAccountNumber','$from_balance','0','$disbursedAmount','$finalFromBalance','$tid','','$txID')");
                    mysqli_query($link,"update bank_accounts set balance ='$finalFromBalance' where accountNumber='$bankAccount'");
                    mysqli_query($link,"update gl_codes set balance ='$finalFromBalance' where code='$gl_code'");
                    //Loan Account (Debit)
                    $to_transaction = mysqli_query($link, "INSERT into system_transactions values (0,NOW(),'$loanAccount','Reimbursement-$receivingAccountNumber','$disbursedAmount','$disbursedAmount','0','0.00','$tid','$loanId','$txID')");

                    //Loan Account (Debit) - Journal Entry
                    $loan_journal = mysqli_query($link, "INSERT into journal_transactions values (0,NOW(),'$loanAccount','Overpay Reimbursement-$receivingAccountNumber','$overPayBalance','$disbursedAmount','0','0.00','$tid','$txID','','')");

                    //Get the balance and GL Code of Over Payment
                    $gl_overpayments=mysqli_fetch_assoc(mysqli_query($link,"select * from gl_codes where name='Loan Overpayments'"));
                    $overPayGL=$gl_overpayments['code'];
                    $overPayBalance=$gl_overpayments['balance'];
                    $final_balance=$overPayBalance-$disbursedAmount;

                    mysqli_query($link,"update gl_codes set balance='$final_balance' where code='$overPayGL'");
                    $accountBalance=-1*$accountBalance;

                    //Journal Entry///Credited
                    $loan_journal = mysqli_query($link, "INSERT into journal_transactions values (0,NOW(),'$overPayGL','Overpay Reimbursement-$loanAccount','$overPayBalance','0','$disbursedAmount','$final_balance','$tid','$txID','','')");
                    echo "<script>window.location='listpayment.php?id=" . $_SESSION['tid'] . "&mid=" . base64_encode("408") . "&&act=overPayments'; </script>";
                }

                ?>
            </div>
        </div>
    </div>
<?php }

?>

