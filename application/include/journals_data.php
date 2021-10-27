<div class="row">

    <section class="content">


        <?php
        if (isset($_POST['save'])) {
            $debit = $_POST['debit'];//Receiving Account
            $credit = $_POST['credit'];//Paying Account
            $notes = $_POST['notes'];
            $entryDate = $_POST['entryDate'];
            $debitAccount = $_POST['debitAccount'];//Debit this account and increase Balance
            $creditAccount = $_POST['creditAccount'];//Credit this account and decrease balance
            $file = $_POST['file'];

            $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $txID = substr(str_shuffle($permitted_chars), 0, 10);

            //Get Balance of the paying account(Credited Account)
            $balanceCheck_from = mysqli_fetch_assoc(mysqli_query($link, "select balance from gl_codes where code='$creditAccount'"));//Paying Account Balance
            $from_balance = $balanceCheck_from['balance'];

            if($debit !== $credit){
                echo "<div class=\"alert alert-danger\" ><a href = \"#\" class = \"close\" data-dismiss= \"alert\"> &times;</a>
                                                    Debit and credit accounts cannot be the same!&nbsp; &nbsp;&nbsp;
                                              </div>";
            }
            else if($debitAccount == $creditAccount){
                echo "<div class=\"alert alert-danger\" ><a href = \"#\" class = \"close\" data-dismiss= \"alert\"> &times;</a>
                                                    Please Make Sure Debit amount is equal to Credit amount!&nbsp; &nbsp;&nbsp;
                                              </div>";
            }
            else if ($from_balance >= $debit) {
                $balanceCheck_to = mysqli_fetch_assoc(mysqli_query($link, "select balance from gl_codes where code='$debitAccount'"));
                $to_balance = $balanceCheck_to['balance']; /////Increase Balance of Debit and Decrease balance of credit
                $toBalance = $debit + $to_balance;
                $fromBalance = $from_balance - $debit;

                //Update Balances and save the transactions with debit and credit
                mysqli_query($link, "update gl_codes set balance = '$toBalance' where code='$debitAccount'");
                mysqli_query($link, "update gl_codes set balance = '$fromBalance' where code='$creditAccount'");

                //If the Paying account is the bank account
                if($creditAccount>=13001 && $creditAccount<=13020){
                    mysqli_query($link, "update bank_accounts set balance = '$fromBalance' where gl_code='$creditAccount'");
                    //Paying Account....Credit
                    //Get the bank account of this code//
                    $bank = mysqli_fetch_assoc(mysqli_query($link,"select * from bank_accounts where gl_code='$creditAccount'"));
                    $bankAccount = $bank['accountNumber'];

                    //Get the name of the debit account//Receiver
                    $receiver=mysqli_fetch_assoc(mysqli_query($link,"select * from gl_codes where code='$debitAccount'"));
                    $debitName=$receiver['name']; ///This can come from the form and be exploded

                    $payer=mysqli_fetch_assoc(mysqli_query($link,"select * from gl_codes where code='$creditAccount'"));
                    $creditName=$payer['name'];

                  /*Credit*/ $from_transaction = mysqli_query($link, "INSERT into system_transactions values (0,'$entryDate','$bankAccount','Transfer to $debitAccount - $debitName','$from_balance','0','$credit','$fromBalance','$tid','','$txID')");
                 /*Debit*/   $to_transaction = mysqli_query($link, "INSERT into system_transactions values (0,'$entryDate','$debitAccount','Transfer from $creditAccount - $creditName','$to_balance','$debit','0','$toBalance','$tid','','$txID')");
                }
                if($debitAccount>=13001 && $debitAccount<=13020){
                    //$balanceCheck_to = mysqli_fetch_assoc(mysqli_query($link, "select balance from gl_codes where code='$debitAccount'"));
                    //$to_balance = $balanceCheck_to['balance']; /////Increase Balance of Debit and Decrease balance of credit
                    $toBalance = $debit + $to_balance;
                    mysqli_query($link, "update bank_accounts set balance = '$toBalance' where gl_code='$debitAccount'");
                    //Paying Account....Credit
                    //Get the bank account of this code//
                    $bank = mysqli_fetch_assoc(mysqli_query($link,"select * from bank_accounts where gl_code='$debitAccount'"));
                    $bankAccount = $bank['accountNumber'];

                    //Get the name of the debit account//Receiver
                    //$receiver=mysqli_fetch_assoc(mysqli_query($link,"select * from gl_codes where code='$debitAccount'"));
                    //$debitName=$receiver['name']; ///This can come from the form and be exploded

                    $payer=mysqli_fetch_assoc(mysqli_query($link,"select * from gl_codes where code='$creditAccount'"));
                    $creditName=$payer['name'];

                    /*Debit*/   $to_transaction = mysqli_query($link, "INSERT into system_transactions values (0,'$entryDate','$bankAccount','Transfer from $creditAccount - $creditName','$to_balance','$debit','0','$toBalance','$tid','','$txID')");
                }

                //double entry

                //Receiving Account....Debit
                $to_transaction = mysqli_query($link, "INSERT into journal_transactions values (0,'$entryDate','$debitAccount','Transfer from $creditAccount - $creditName','$to_balance','$debit','0','$toBalance','$tid','$txID','$notes','$file')");

                //Paying Account....Credit
                $from_transaction = mysqli_query($link, "INSERT into journal_transactions values (0,'$entryDate','$creditAccount','Transfer to $debitAccount - $debitName','$from_balance','0','$credit','$fromBalance','$tid','$txID','$notes','$file')");

                echo "<div class=\"alert alert-success\" ><a href = \"#\" class = \"close\" data-dismiss= \"alert\"> &times;</a>
                                                    Transfer successfully completed.!&nbsp; &nbsp;&nbsp;
                                              </div>";
            } else {
                echo "<div class=\"alert alert-danger\" ><a href = \"#\" class = \"close\" data-dismiss= \"alert\"> &times;</a>
                                                    Unable to transfer, insufficient funds from the transferring account!&nbsp; &nbsp;&nbsp;
                                              </div>";
            }
        }

        $getAccounts = mysqli_query($link, "select * from gl_codes");
        $getAccounts1 = mysqli_query($link, "select * from gl_codes");
        ?>

        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Journal Entries</h3>
            </div>
            <div class="box-body">
                <form action="" method="post">
                    <div class="row">

                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label"><h4>Debit</h4></label>
                            <div class="col-xs-2">
                                <input type="number" step="0.01" class="form-control" value="<?php echo $debit; ?>" name="debit" placeholder="Debit" required>
                            </div>

                            <div class="col-xs-4">
                                <select name="debitAccount" class="form-control select2" required><!-- Receiving Account-->
                                    <option value="" selected disabled><h4>Select account</h4></option>
                                    <?php while ($row = mysqli_fetch_assoc($getAccounts)) { ?>
                                        <option value="<?php echo $row['code']; ?>"  <?php if($row['code']==$debitAccount){echo "selected";} ?>><?php echo $row['code'] . " - " . $row['name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                    </div>
                    <br>
                    <div class="row">
                        <div class="form-group">
                            <label for="credit" class="col-sm-3 control-label"><h4>Credit</h4></label>
                            <div class="col-sm-2">
                                <input type="number" step="0.01" required class="form-control" id="credit"  value="<?php echo $credit; ?>" name="credit" placeholder="Credit">
                            </div>

                            <div class="col-sm-4">
                                <select name="creditAccount" required class="form-control select2"><!-- Paying Account-->
                                    <option value="" selected disabled><h4>Select account</h4></option>
                                    <?php while ($row = mysqli_fetch_assoc($getAccounts1)) { ?>
                                        <option value="<?php echo $row['code']; ?>" <?php if($row['code']==$creditAccount){echo "selected";} ?>><?php echo $row['code'] . " - " . $row['name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                    </div>
                    <br>
                    <div class="row">
                        <div class="form-group">
                            <label for="entryDate" class="col-sm-3 control-label"><h4>Entry Date</h4></label>
                            <div class="col-sm-6">
                                <input type="datetime-local" required id="entryDate" value="<?php echo $entryDate; ?>" class="form-control" name="entryDate"
                                       placeholder="Entry Date">
                            </div>

                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="form-group">
                            <label for="notes" class="col-sm-3 control-label"><h4>Notes</h4></label>
                            <div class="col-xs-6">
                                <textarea class="form-control" id="notes" name="notes"
                                          placeholder="Notes"><?php echo $notes; ?></textarea>
                            </div>


                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="form-group">
                            <label for="notes" class="col-sm-3 control-label"><h4>Upload a File</h4></label>
                            <div class="col-xs-6">
                                <input type="file" name="file">
                            </div>


                        </div>
                    </div>
                    <div align="center">
                        <div class="box-footer">

                            <button name="save" type="submit"
                                    class="btn btn-success "><i
                                        class="fa fa-save">&nbsp;Log Journal Entry</i>
                            </button>

                        </div>
                    </div>
                </form>
                <div class="row search_str" style="display: none;">
                    <br>
                    <div class="col-xs-11">
                        <input type="text" name="search_str" class="form-control" id="inputSearchStr"
                               placeholder="Borrower Name or Business Name or Group Name" value="">
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
        </div>

        <div class="box box-info">
            <div class="box-body">
                <div class="table-responsive">
                    <div class="box-body">
                            <?php if(!isset($_GET['act'])){ ?>
                                <table id="allTransactions" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th><input type="checkbox" id="select_all"/></th>

                                        <th>Bank</th>
                                        <th>Account Number</th>
                                        <th>Type</th>
                                        <th>Today Opening Balance</th>
                                        <th><strong>Balance</strong></th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $tid = $_SESSION['tid'];
                                    $select = mysqli_query($link, "SELECT * FROM gl_codes where code in (select account from journal_transactions)") or die (mysqli_error($link));

                                    if (mysqli_num_rows($select) == 0) {
                                        echo "<div class='alert alert-info'>No data found yet!.....Check back later!!</div>";
                                    } else {
                                        while ($row = mysqli_fetch_array($select)) {
                                            $id=$row['id'];
                                            $account=$row['code'];
                                            $today=date('Y-m-d');

                                            $first_transation = mysqli_fetch_assoc(mysqli_query($link, "SELECT min(date) FROM system_transactions where account='$account' and date>'$today'"));
                                            $time=$first_transation['min(date)'];
                                            $today_opening_balance = mysqli_fetch_assoc(mysqli_query($link, "SELECT opening_balance FROM system_transactions where date='$time' and account='$account'"));
                                            if($time==""){
                                                $opening_balance = $row['balance'];
                                            }else{
                                                $opening_balance=$today_opening_balance['opening_balance'];
                                            }
                                            ?>
                                            <tr>
                                                <td><input id="optionsCheckbox" class="checkbox" name="selector[]"
                                                           type="checkbox" value="<?php echo $id; ?>"></td>
                                                <td><?php echo $row['code']; ?></td>
                                                <td><?php echo $row['name']; ?></td>
                                                <td><?php echo $row['type']; ?></td>
                                                <td align="right"><strong><?php echo number_format($opening_balance, 2, ".", ","); ?></strong></td>
                                                <td align="right"><strong><?php echo number_format($row['balance'], 2, ".", ","); ?></strong></td>
                                                <td>
                                                    <a href="#myModal <?php echo $id; ?>"> <i class="fa fa-eye" data-target="#myModal<?php echo $id; ?>" data-toggle="modal"></i>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    } ?>
                                    </tbody>
                                </table>
                            <?php } ?>


                    </div>

                </div>
            </div>

        </div>

        <div class="box box-info">
            <div class="box-body">
                <div class="table-responsive">
                    <div class="box-body">
                        <form method="post">
                            <a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>">
                                <button type="button" class="btn btn-flat btn-warning"><i
                                            class="fa fa-mail-reply-all"></i>&nbsp;Back
                                </button>
                            </a>


                            <a href="printpayment.php" target="_blank" class="btn btn-primary btn-flat"><i
                                        class="fa fa-print"></i>&nbsp;Print Summary</a>
                            <a href="excelpayment.php" target="_blank" class="btn btn-success btn-flat"><i
                                        class="fa fa-send"></i>&nbsp;Export Excel</a>

                            <hr>
                            <style>
                                th {
                                    padding-top: 12px;
                                    padding-bottom: 12px;
                                    text-align: left;
                                    background-color: #D1F9FF;
                                }
                            </style>
                            <?php if (!isset($_GET['act'])) { ?>
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th><input type="checkbox" id="select_all"/></th>
                                        <th>Date</th>
                                        <th>GL Code</th>
                                        <th>Name</th>
                                        <th>Transaction</th>
                                        <th align="right">Debit</th>
                                        <th align="center">Credit</th>
                                        <th align="center">Balance</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $tid = $_SESSION['tid'];
                                    $select = mysqli_query($link, "SELECT * FROM journal_transactions") or die (mysqli_error($link));
                                    if (mysqli_num_rows($select) == 0) {
                                        echo "<div class='alert alert-info'>No data found yet!.....Check back later!!</div>";
                                    } else {
                                        while ($row = mysqli_fetch_array($select)) {
                                            $account = $row['account'];
                                            $getCode = mysqli_fetch_assoc(mysqli_query($link,"select * from gl_codes where code = '$account'"));
                                            if($row['debit']=="0.00"){
                                                $debit="";
                                            }else{
                                                $debit=number_format($row['debit'], 2, ".", ",");
                                            }
                                            if($row['credit']=="0.00"){
                                                $credit="";
                                            }else{
                                                $credit=number_format($row['credit'], 2, ".", ",");
                                            }
                                            ?>
                                            <tr>
                                                <td><input id="optionsCheckbox" class="checkbox" name="selector[]"
                                                           type="checkbox" value="<?php echo $id; ?>"></td>
                                                <td><?php echo $row['date']; ?></td>
                                                <td><?php echo $row['account']; ?></td>
                                                <td><?php echo $getCode['name']; ?></td>
                                                <td><?php echo $row['transaction']; ?></td>
                                                <td align="right">
                                                    <strong><?php echo $debit; ?></strong>
                                                </td>
                                                <td align="right">
                                                    <strong><?php echo $credit; ?></strong>
                                                </td>
                                                <td align="right">
                                                    <strong><?php echo number_format($row['balance'], 2, ".", ","); ?></strong>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    } ?>
                                    </tbody>
                                </table>
                            <?php } ?>


                        </form>
                    </div>

                </div>
            </div>

        </div>

</div>
<script src="../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../plugins/datatables/dataTables.bootstrap.min.js"></script>
<!-- page script --><script>
    $(function () {

        $('#example1').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": true
        });
    });

    $(function () {

        $('#allTransactions').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": true
        });
    });
</script>