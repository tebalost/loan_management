<div class="row">

    <section class="content">



        <?php
        if(isset($_POST['transfer'])){
            $amount=$_POST['amount'];
            $sourceAccount=$_POST['fromAccount'];
            $destinationAccount=$_POST['toAccount'];

            $fromAccount=explode("-",$sourceAccount)[1];
            $toAccount=explode("-",$destinationAccount)[1];

            $fromGL=explode("-",$sourceAccount)[0];
            $toGL=explode("-",$destinationAccount)[0];
            //Get GL Codes//

            $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $txID = substr(str_shuffle($permitted_chars), 0, 10);

            //Get Balance of each account
            $balanceCheck_from = mysqli_fetch_assoc(mysqli_query($link,"select balance from bank_accounts where accountNumber='$fromAccount'"));
            $from_balance=$balanceCheck_from['balance'];
            if($from_balance>=$amount){
                $balanceCheck_to = mysqli_fetch_assoc(mysqli_query($link,"select balance from bank_accounts where accountNumber='$toAccount'"));
                $to_balance=$balanceCheck_to['balance'];
                $toBalance = $amount+$to_balance;
                $fromBalance = $from_balance-$amount;
                //Update Balances and save the transactions with debit and credit
                mysqli_query($link,"update bank_accounts set balance = '$toBalance' where accountNumber='$toAccount'");
                mysqli_query($link,"update bank_accounts set balance = '$fromBalance' where accountNumber='$fromAccount'");
                //double entry

                mysqli_query($link,"update gl_codes set balance = '$toBalance' where code='$toGL'");
                mysqli_query($link,"update gl_codes set balance = '$fromBalance' where code='$fromGL'");

                //Update Journal Accounts//
                //mysqli_query($link,"update gl_codes set balance = '$toBalance' where code='$toAccountGL'");
                //mysqli_query($link,"update gl_codes set balance = '$fromBalance' where code='$fromAccountGL'");
                //double entry

                $to_transaction = mysqli_query($link, "INSERT into system_transactions values (0,NOW(),'$toAccount','Internal Transfer from $fromAccount','$to_balance','$amount','','$toBalance','$tid','','$txID')");
                $from_transaction = mysqli_query($link, "INSERT into system_transactions values (0,NOW(),'$fromAccount','Internal Transfer to $toAccount','$from_balance','','$amount','$fromBalance','$tid','','$txID')");

                $to_transaction_journal = mysqli_query($link, "INSERT into journal_transactions values (0,NOW(),'$toGL','Internal Transfer from $fromAccount','$to_balance','$amount','','$toBalance','$tid','$txID','','')");
                $from_transaction_journal = mysqli_query($link, "INSERT into journal_transactions values (0,NOW(),'$fromGL','Internal Transfer to $toAccount','$from_balance','','$amount','$fromBalance','$tid','$txID','','')");

                echo "<div class=\"alert alert-success\" ><a href = \"#\" class = \"close\" data-dismiss= \"alert\"> &times;</a>
                                                    Transfer successfully completed.!&nbsp; &nbsp;&nbsp;
                                              </div>";
            }else{
                echo "<div class=\"alert alert-danger\" ><a href = \"#\" class = \"close\" data-dismiss= \"alert\"> &times;</a>
                                                    Unable to transfer, insufficient funds from the transferring account!&nbsp; &nbsp;&nbsp;
                                              </div>";
            }
        }

        $getAccounts = mysqli_query($link,"select * from bank_accounts");
        $getAccounts1 = mysqli_query($link,"select * from bank_accounts");
        ?>

        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Internal Accounts Transfer</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <form action="" method="post">
                        <div class="col-xs-2">
                            <input type="number" class="form-control" name="amount" placeholder="Transfer Amount">
                        </div>
                    <div class="col-xs-4">
                        <select name="fromAccount" class="form-control">
                            <option value="" selected disabled>Select from account</option>
                            <?php while($row=mysqli_fetch_assoc($getAccounts)){ ?>
                            <option value="<?php echo $row['gl_code']; ?>-<?php echo $row['accountNumber']; ?>"><?php echo $row['gl_code']; ?>-<?php echo $row['accountNumber']." - ".$row['bankName']." - Bal. (".$row['balance'].")"; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-xs-1  text-center" style="padding-top: 5px;">
                        to
                    </div>
                    <div class="col-xs-3">
                        <select name="toAccount" class="form-control">
                            <option value="" selected disabled>Select to account</option>
                            <?php while($row=mysqli_fetch_assoc($getAccounts1)){ ?>
                                <option value="<?php echo $row['gl_code']; ?>-<?php echo $row['accountNumber']; ?>"><?php echo $row['gl_code']; ?>-<?php echo $row['accountNumber']." - ".$row['bankName']." - Bal. (".$row['balance'].")"; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-xs-1">
                                <span class="input-group-btn">
                                  <button type="submit" name="transfer" class="btn bg-olive btn-flat">Transfer!</button>
                                </span>
                    </div>
                </form>
                </div>

                <div class="row search_str" style="display: none;">
                    <br>
                    <div class="col-xs-11">
                        <input type="text" name="search_str" class="form-control" id="inputSearchStr" placeholder="Borrower Name or Business Name or Group Name" value="">
                    </div>
                </div>
            </div>
        <!-- /.box-body -->
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
                            <?php if(!isset($_GET['act'])){ ?>
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th><input type="checkbox" id="select_all"/></th>
                                    <th>Bank</th>
                                    <th>Account Number</th>
                                    <th>Used For</th>
                                    <th align="right">Balance</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $tid = $_SESSION['tid'];
                                $select = mysqli_query($link, "SELECT * FROM bank_accounts") or die (mysqli_error($link));
                                if (mysqli_num_rows($select) == 0) {
                                    echo "<div class='alert alert-info'>No data found yet!.....Check back later!!</div>";
                                } else {
                                    while ($row = mysqli_fetch_array($select)) {
                                            $id=$row['id'];
                                            ?>
                                            <tr>
                                                <td><input id="optionsCheckbox" class="checkbox" name="selector[]"
                                                           type="checkbox" value="<?php echo $id; ?>"></td>
                                                <td><?php echo $row['bankName']; ?></td>
                                                <td><?php echo $row['accountNumber']; ?></td>
                                                <td><?php echo $row['transactionType']; ?></td>
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
                            <?php }  ?>


                        </form>
                    </div>

                </div>
            </div>

        </div>

        <div class="box box-info">
            <div class="box-body">
                <div class="alert alert-info" align="center" class="style2" style="color: #FFFFFF">NUMBER OF LOAN
                    APPLICANTS:&nbsp;
                    <?php
                    $call3 = mysqli_query($link, "SELECT * FROM payments ");
                    $num3 = mysqli_num_rows($call3);
                    ?>
                    <?php echo $num3; ?>

                </div>
                <!--FIXME, add the accounts to the chart---Work on the JSON to loop through the accounts -->
                <div id="accounts"></div>
            </div>
        </div>

</div>