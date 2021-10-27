<?php
$tid=$_SESSION['tid'];
$select = mysqli_query($link, "SELECT * FROM payments") or die (mysqli_error($link));
while ($row = mysqli_fetch_array($select)) {
    $id = $row['id'];
    $gl_code = $row['gl_code'];

    $getCodeName = mysqli_fetch_assoc(mysqli_query($link,"select * from bank_accounts where gl_code='$gl_code'"))
    ?>

    <div class="modal fade" id="myModal<?php echo $id; ?>" role="dialog">
        <div class="modal-dialog" id="printableArea">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <legend style="color: red; text-align: center">
                        <strong>Payment Reversal Confirmation</strong>
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


                    <table id="example1" class="table table-bordered table-striped">
                        <tr>
                            <td width="130">Transaction Date:</td>
                            <th style="color: black;"><?php echo $row['pay_date']; ?></th>
                        </tr>
                        <tr>
                            <td width="130">Transaction ID:</td>
                            <th style="color: black;"><?php echo $row['tx_id']; ?></th>
                        </tr>
                        <?php
                        $borrower = $row['customer'];
                        $get = mysqli_fetch_assoc(mysqli_query($link,"select * from borrowers where id='$borrower'"));
                        ?>
                        <tr>
                            <td width="130">Account Owner:</td>
                            <th style="color: black;"><?php echo strtoupper($get['fname'])."&nbsp;".strtoupper($get['lname']); ?><br>
                                <?php echo strtoupper($get['addrs2']) ?><br><?php echo strtoupper($get['addrs1']) ?>
                                &nbsp; </th>
                        </tr>
                        <tr>
                            <td width="130">Account Type:</td>
                            <th style="color: black;"><?php echo strtoupper($row['account']); ?>
                                &nbsp; <?php
                                //Get Loan Info//
                                $account=$row['account'];
                                $loan=mysqli_fetch_assoc(mysqli_query($link,"select * from loan_info where baccount='$account'"));

                                $strJsonFileContents = file_get_contents('include/packages.json');
                                $arrayOfTypes = json_decode($strJsonFileContents, true);
                                $loan_product = $loan['loan_product'];
                                $productName=mysqli_fetch_assoc(mysqli_query($link,"select * from products where product_id='$loan_product'"));
                                $loan_product=$productName['product_name'];
                                echo "- $loan_product";
                                 ?>
                            </th>
                        </tr>
                        <tr>
                            <td width="150">Purpose</td>
                            <th style="color: black;">Repayment</th>
                        </tr>
                        <tr>
                            <td width="150">Details</td>
                            <th style="color: black;">
                                Paid: - <?php echo $get_searched['currency'] . number_format($row['amount_to_pay'], 2, '.', ',')?><br>
                                Balance: <?php echo $get_searched['currency'] . number_format($row['balance'], 2, '.', ',')?><br>
                                Paid By:
                                <?php
                                //$arrayOfTypes = json_decode($strJsonFileContents, true);
                                $payment_method = $row['payment_method'];
                                foreach ($arrayOfTypes['paymentType'] as $key => $value) {
                                if ($payment_method == $key) {
                                $payment_method = $value;
                                }
                                } echo $payment_method;
                                ?>
                            </th>
                        </tr>

                       <tr>
                    </table>
                    <form class="form-horizontal" method="post" enctype="multipart/form-data">
                        <h4 style="color: green"><b>The payment will be reversed for: <?php echo $gl_code."-".$getCodeName['accountNumber']."-".$getCodeName['bankName']; ?> for the amount of M <?php echo number_format($row['amount_to_pay'], 2, '.', ',') ?></b></h4>

                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <div class="modal-footer" align="center">
                            <button type="submit" name="Confirm" class="btn btn-flat btn-success"><i
                                        class="icon-save"></i>&nbsp;Confirm
                            </button>
                            <button class="btn btn-flat btn-danger" data-dismiss="modal" aria-hidden="true"><i
                                        class="icon-remove icon-large"></i> Close
                            </button>
                        </div>
                    </form>
                    <?php
                    if (isset($_POST['Confirm'])) {
                        $id=$_POST['id'];

                        //Get Payment Details
                        $payment=mysqli_fetch_assoc(mysqli_query($link,"select * from payments where id='$id'"));
                        $transactionId=$payment['tx_id'];
                        $account = $payment['account'];
                        $amount_paid_today = $payment['amount_to_pay'];
                        $toAccount = $payment['gl_code'];
                        $customer = $payment['customer'];

                        $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                        $txID = substr(str_shuffle($permitted_chars), 0, 10);

                        $insert = mysqli_query($link, "INSERT INTO reversed_payments(id,tid, account,balance, customer, loan, pay_date, amount_to_pay, remarks, payment_method,reference,tx_id,gl_code) 
                                                select id,tid, account,balance, customer, loan, pay_date, amount_to_pay, remarks, payment_method,reference,tx_id,gl_code from payments where id='$id'");

                        $remove = mysqli_query($link,"delete from payments where id='$id'");

                        //Update Schedule///
                        mysqli_query($link, "update pay_schedule set payment='0', principal_payment='0', 
                        interest_payment='0', fees_payment='0', penalty_payment='0', open_indicator='O', payment_tx_id='' where payment_tx_id='$transactionId'");

                        //Set the new Balance
                        //Get the account Number that the payment was made to
                        $account=mysqli_fetch_assoc(mysqli_query($link,"select * from bank_accounts where gl_code='$gl_code'"));
                        $bankAccount=$account['accountNumber'];

                        $balanceCheck_to = mysqli_fetch_assoc(mysqli_query($link,"select balance from bank_accounts where gl_code='$gl_code'"));
                        $to_balance=$balanceCheck_to['balance'];//Opening Balance
                        $toBalance = $to_balance-$amount_paid_today;//Final Balance

                        $get = mysqli_fetch_assoc(mysqli_query($link, "select sum(amount_to_pay) from payments where account=$account and customer='$customer'"));

                        //Get the Opening Balance of the loan account
                        $maxdate=mysqli_fetch_assoc(mysqli_query($link,"select max(pay_date) from payments where account='$account'"));
                        $max_date=$maxdate['max(pay_date)'];

                        $selectLoan = mysqli_query($link, "SELECT * FROM loan_info WHERE baccout = '$account'");
                        $expectedBalance=$selectLoan['balance'];
                        $loanId=$selectLoan['id'];
                        $status=$selectLoan['status'];
                        $accountBalance = $expectedBalance - ($get['sum(amount_to_pay)']);

                        $accountBalUpdateAfterDelete = mysqli_query($link,"update payments set balance='$accountBalance' where account='$account' and pay_date='$max_date'");

                        if($status=="P"){
                            mysqli_query($link,"update loan_info set status='' where id='$loanId'");
                            mysqli_query($link,"delete from loan_statuses set status='P' where loan='$loanId'");
                        }
                        //Get System Transactions of this payment
                        $system_tx=mysqli_query($link,"select * from system_transactions where tx_id='$transactionId'");
                        while($row=mysqli_fetch_assoc($system_tx)){

                            $account=$row['account'];
                            $debit=$row['debit'];
                            $credit=$row['credit'];

                            //Get the bank balance
                            $getBalance = mysqli_fetch_assoc(mysqli_query($link,"select balance from bank_accounts where accountNumber='$account'"));
                            $balanceCheck=mysqli_query($link,"select balance from bank_accounts where accountNumber='$account'");
                            if(mysqli_num_rows($balanceCheck)>0) {
                                $opening_balance = $getBalance['balance'];
                                $balance = $getBalance['balance'] - $debit;
                            }else{
                                $opening_balance = $row['balance'];
                                $balance = $row['opening_balance'];
                            }

                            mysqli_query($link,"insert into system_transactions values (0,NOW(),'$account','Payment Reversal','$opening_balance','$credit','$debit','$balance','$tid','$loanId','$txID')");
                            //Swap the transactions
                        }
                        //Get Journal Transactions of this payment
                        $journal_tx=mysqli_query($link,"select * from journal_transactions where tx_id='$transactionId'");

                        while($row=mysqli_fetch_assoc($journal_tx)){
                            $account=$row['account'];
                            $debit=$row['debit'];
                            $credit=$row['credit'];

                            //Get the Bank balance
                            $getBalance = mysqli_fetch_assoc(mysqli_query($link,"select balance from bank_accounts where gl_code='$account'"));
                            $balanceCheck=mysqli_query($link,"select balance from bank_accounts where gl_code='$account'");
                            if(mysqli_num_rows($balanceCheck)>0) {
                                $opening_balance = $getBalance['balance'];
                                $balance = $getBalance['balance'] - $debit;
                            }else{
                                $opening_balance = $row['balance'];
                                $balance = $row['opening_balance'];
                            }

                            mysqli_query($link,"insert into journal_transactions values (0,NOW(),'$account','Payment Reversal','$opening_balance','$credit','$debit','$balance','$tid','$txID','','')");
                            //Swap the transactions
                            //update the balances of the GL Accounts
                            mysqli_query($link, "update gl_codes set balance='$balance' where code='$account'");
                            mysqli_query($link, "update bank_accounts set balance='$balance' where gl_code='$account'");

                        }

                       //mysqli_query($link, "insert into sms_messages values(0,'$phone','$content',NOW(),'','','$loanId')");

                        echo "<script>window.location='listpayment.php?tid=" . $_SESSION['tid'] . "&act=reversedPayments'; </script>";


                    }

                    ?>

                </div>
            </div>
        </div>
    </div>
<?php } ?>