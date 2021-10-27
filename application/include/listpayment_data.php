<div class="row">

    <section class="content">
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

                            <a href="newpayments.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("408"); ?>">
                                <button type="button" class="btn btn-flat btn-info"><i class="fa fa-dollar"></i>&nbsp;New
                                    Payment
                                </button>
                            </a>

                            <a href="printpayment.php" target="_blank" class="btn btn-primary btn-flat"><i
                                        class="fa fa-print"></i>&nbsp;Print Payments</a>
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
                            <?php
                            $tid = $_SESSION['tid'];
                            $check = mysqli_query($link, "SELECT * FROM emp_permission WHERE tid = '" . $_SESSION['tid'] . "' AND module_name = 'Loan Details'") or die ("Error" . mysqli_error($link));
                            $get_check = mysqli_fetch_array($check);
                            $pdelete = $get_check['pdelete'];
                            $pcreate = $get_check['pcreate'];
                            $pupdate = $get_check['pupdate'];
                            if (!isset($_GET['act'])){ ?>
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th><input type="checkbox" id="select_all"/></th>
                                        <th>Customer</th>
                                        <th>Instalment</th>
                                        <th>Amount Paid</th>
                                        <th>Balance</th>
                                        <th>Date</th>
                                        <th>Teller</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php

                                    $select = mysqli_query($link, "SELECT * FROM payments") or die (mysqli_error($link));
                                    if (mysqli_num_rows($select) == 0) {
                                        echo "<div class='alert alert-info'>No data found yet!.....Check back later!!</div>";
                                    } else {
                                        while ($row = mysqli_fetch_array($select)) {
                                            $id = $row['id'];
                                            $customer = $row['customer'];
                                            $getin = mysqli_query($link, "SELECT fname, lname, account FROM borrowers WHERE id = '$customer'") or die (mysqli_error($link));
                                            $have = mysqli_fetch_array($getin);
                                            $nameit = $have['fname'] . '&nbsp;' . $have['lname'];
                                            $loanAccount = $row['account'];
//$accte = $have['account'];
                                            $loan = $row['loan'];
                                            $amount_to_pay = $row['amount_to_pay'];
                                            $pay_date = $row['pay_date'];
                                            $balance = $row['balance'];
                                            $select1 = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
                                            while ($row1 = mysqli_fetch_array($select1)) {
                                                $currency = $row1['currency'];
                                                ?>
                                                <tr>
                                                    <td><input id="optionsCheckbox" class="checkbox" name="selector[]"
                                                               type="checkbox" value="<?php echo $id; ?>"></td>
                                                    <td><?php echo $nameit; ?></td>
                                                    <td><?php echo "$loanAccount - " . number_format($loan, 2, ".", ","); ?></td>
                                                    <td align="right"><?php echo number_format($amount_to_pay, 2, ".", ","); ?></td>
                                                    <td align="right"><?php echo number_format($balance, 2, ".", ","); ?></td>
                                                    <td><?php echo $pay_date; ?></td>
                                                    <td><?php echo $name; ?></td>
                                                    <td>
                                                        <a href="#myModal <?php echo $id; ?>"> <i class="fa fa-print"
                                                                                                  data-target="#myModal<?php echo $id; ?>"
                                                                                                  data-toggle="modal"></i>
                                                    </td>
                                                </tr>
                                            <?php }
                                        }
                                    } ?>
                                    </tbody>
                                </table>

                            <?php } else{ ?>

                            <?php
                            $tid = $_SESSION['tid'];
                            $today = date('Y-m-d');
                            if ($_GET['act'] == "overPayments"){
                            ?>
                            <h4 style="color: green;"><b>Loans that are Overpaid</b></h4>
                            <hr>
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th><input type="checkbox" id="select_all"/></th>
                                    <th>Customer</th>
                                    <th>Instalment</th>
                                    <th>Amount Paid</th>
                                    <th>Overpaid Amount</th>
                                    <th>Date</th>
                                    <th>Teller</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $selectPaidUp = mysqli_query($link, "SELECT * FROM loan_info where status='P'") or die (mysqli_error($link));
                                while ($rowLoan = mysqli_fetch_array($selectPaidUp)) {
                                    $balance = $rowLoan['balance'];
                                    $baccount = $rowLoan['baccount'];
                                    //Get the totalPaid
                                    $paid = mysqli_fetch_assoc(mysqli_query($link, "select sum(amount_to_pay) from payments where account='$baccount'"));

                                    $totalPaid = $paid['sum(amount_to_pay)'];
                                    $difference=$balance-$totalPaid;
                                    if ($totalPaid > $balance) {
                                        $countOverPayments += 1;
                                        $overpayments += $difference;

                                        $selectOverpaid = mysqli_query($link, "SELECT * FROM payments where account='$baccount' and balance<0") or die (mysqli_error($link));
                                        while ($row = mysqli_fetch_array($selectOverpaid)) {
                                        $id = $row['id'];
                                        $customer = $row['customer'];
                                        $getin = mysqli_query($link, "SELECT fname, lname, account FROM borrowers WHERE id = '$customer'") or die (mysqli_error($link));
                                        $have = mysqli_fetch_array($getin);
                                        $nameit = $have['fname'] . '&nbsp;' . $have['lname'];
                                        $loanAccount = $row['account'];
                                        $borrower = $row['customer'];
                                        $loan = $row['loan'];
                                        $amount_to_pay = $row['amount_to_pay'];
                                        $pay_date = $row['pay_date'];
                                        $balance = $row['balance'];

                                        //Get the Loan ID
                                            $loanNum=mysqli_fetch_assoc(mysqli_query($link,"select * from loan_info where baccount='$baccount'"));
                                            $loanId=$loanNum['id'];

                                            ?>
                                            <tr>
                                                <td><input id="optionsCheckbox" class="checkbox" name="selector[]"
                                                           type="checkbox" value="<?php echo $id; ?>"></td>
                                                <td><?php echo $nameit; ?></td>
                                                <td><?php echo "$loanAccount - " . number_format($loan, 2, ".", ","); ?></td>
                                                <td align="right"><?php echo number_format($amount_to_pay, 2, ".", ","); ?></td>
                                                <td align="right"><b><?php echo number_format((-1)*$balance, 2, ".", ","); ?></b></td>
                                                <td><?php echo $pay_date; ?></td>
                                                <td><?php echo $name; ?></td>
                                                <td>
                                                    <a href="viewborrowersloan.php?id=<?php echo $borrower; ?>&mid=<?php echo base64_encode("405");?>&loanId=<?php echo $loanId; ?>&loanAccount=<?php echo $baccount; ?>"><i class="fa fa-eye text-green"></i></a>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                    }
                                }
                                }
                                if ($_GET['act'] == "reversedPayments"){
                                ?>
                                <h4 style="color: green;"><b>Payments Pending Reversal</b></h4>
                                <hr>
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th><input type="checkbox" id="select_all"/></th>
                                        <th>Customer</th>
                                        <th>Instalment</th>
                                        <th>Amount Paid</th>
                                        <th>Balance</th>
                                        <th>Date</th>
                                        <th>Teller</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $selectReversed = mysqli_query($link, "SELECT * FROM payments where status='R'") or die (mysqli_error($link));
                                    while ($row = mysqli_fetch_array($selectReversed)) {
                                        $id = $row['id'];
                                        $customer = $row['customer'];
                                        $getin = mysqli_query($link, "SELECT fname, lname, account FROM borrowers WHERE id = '$customer'") or die (mysqli_error($link));
                                        $have = mysqli_fetch_array($getin);
                                        $nameit = $have['fname'] . '&nbsp;' . $have['lname'];
                                        $loanAccount = $row['account'];
//$accte = $have['account'];
                                        $loan = $row['loan'];
                                        $amount_to_pay = $row['amount_to_pay'];
                                        $pay_date = $row['pay_date'];
                                        $balance = $row['balance'];
                                        $select1 = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
                                        while ($row1 = mysqli_fetch_array($select1)) {
                                            $currency = $row1['currency'];
                                            ?>
                                            <tr>
                                                <td><input id="optionsCheckbox" class="checkbox" name="selector[]"
                                                           type="checkbox" value="<?php echo $id; ?>"></td>
                                                <td><?php echo $nameit; ?></td>
                                                <td><?php echo "$loanAccount - " . number_format($loan, 2, ".", ","); ?></td>
                                                <td align="right"><?php echo number_format($amount_to_pay, 2, ".", ","); ?></td>
                                                <td align="right"><?php echo number_format($balance, 2, ".", ","); ?></td>
                                                <td><?php echo $pay_date; ?></td>
                                                <td><?php echo $name; ?></td>
                                                <td>
                                                    <a href="#myModal <?php echo $id; ?>" title="Approve"> <i
                                                                class="fa fa-check-square text-green"
                                                                data-target="#myModal<?php echo $id; ?>"
                                                                data-toggle="modal"></i></a>
                                                    &nbsp;
                                                    <a href="#d<?php echo $id; ?>" title="Decline"><i
                                                                data-target="#d<?php echo $id; ?>"
                                                                data-toggle="modal"><i class="fa fa-times text-red"></i></i></a>

                                                </td>
                                            </tr>
                                        <?php }
                                    }
                                    }
                                    if ($_GET['act'] == "duePayments"){
                                    ?>
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th><input type="checkbox" id="select_all"/></th>
                                            <th>Customer</th>
                                            <th>Principal Due</th>
                                            <th>Interest</th>
                                            <th>Fees</th>
                                            <th>Instalment</th>
                                            <th>Actions</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $selectDuePayments = mysqli_query($link, "SELECT * FROM pay_schedule where schedule='$today'") or die (mysqli_error($link));

                                        while ($row = mysqli_fetch_array($selectDuePayments)) {
                                            $id = $row['id'];
                                            $loanId = $row['get_id'];
                                            $getin = mysqli_query($link, "SELECT * FROM loan_info WHERE id = '$loanId'") or die (mysqli_error($link));
                                            $have = mysqli_fetch_array($getin);
                                            $nameit = $have['fname'] . '&nbsp;' . $have['lname'];
                                            $loanAccount = $row['account'];
//$accte = $have['account'];
                                            $loan = $row['loan'];
                                            $amount_to_pay = $row['amount_to_pay'];
                                            $pay_date = $row['pay_date'];
                                            $balance = $row['balance'];
                                            $select1 = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
                                            while ($row1 = mysqli_fetch_array($select1)) {
                                                $currency = $row1['currency'];
                                                //$loan=$row['get_id'];
                                                $getBorrower=mysqli_fetch_assoc(mysqli_query($link,"select * from borrowers where id in(select borrower from loan_info where id='$loanId')"));
                                                $fname=$getBorrower['fname'];
                                                $lname=$getBorrower['lname'];
                                                $borrower=$getBorrower['id'];
                                                ?>
                                                <tr>
                                                    <td><input id="optionsCheckbox" class="checkbox" name="selector[]"
                                                               type="checkbox" value="<?php echo $id; ?>"></td>
                                                    <td><?php echo $fname." ".$lname; ?></td>
                                                    <td align="right"><?php echo number_format($row['principal_due'], 2, ".", ","); ?></td>
                                                    <td align="right"><?php echo number_format($row['interest'], 2, ".", ","); ?></td>
                                                    <td align="right"><?php echo number_format($row['fees'], 2, ".", ","); ?></td>
                                                    <td style="text-align: right"><?php echo number_format($row['balance'], 2, ".", ",");; ?></td>
                                                    <td>
                                                        <?php //echo ($pupdate == '1' && $userStatus == "Active" && mysqli_num_rows($collateral) > 0) ? '<a href="#myModal ' . $id . '"> <i data-target="#myModal' . $id . '" data-toggle="modal" class="fa fa-pencil"></i></a>' : ''; ?>
                                                        &nbsp;
                                                        <?php echo ($pupdate == '1') ? '<a href="viewborrowersloan.php?id=' . $borrower . '&&mid=' . base64_encode("405") . '&&loanId=' . $loanId . '&&contract=' . $contract . '"><i class="fa fa-eye"></i></a>' : ''; ?>
                                                        <?php
                                                        $se = mysqli_query($link, "SELECT * FROM attachment WHERE get_id = '$borrower'") or die (mysqli_error($link));
                                                        while ($gete = mysqli_fetch_array($se)) {
                                                            ?>
                                                            <a href="<?php echo $gete['attached_file']; ?>"><i
                                                                        class="fa fa-download"></i></a>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                            <?php }
                                        }
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                    <?php } ?>
                                    <?php
                                    if (isset($_POST['delete'])) {
                                        $idm = $_GET['id'];
                                        $id = $_POST['selector'];
                                        $N = count($id);
                                        if ($id == '') {
                                            echo "<script>alert('Row Not Selected!!!'); </script>";
                                            echo "<script>window.location='listpayment.php?id=" . $_SESSION['tid'] . "&&mid=" . base64_encode("408") . "'; </script>";
                                        } else {
                                            for ($i = 0; $i < $N; $i++) {
                                                $result = mysqli_query($link, "DELETE FROM payments WHERE id ='$id[$i]'");
                                                echo "<script>alert('Row Delete Successfully!!!'); </script>";
                                                echo "<script>window.location='listpayment.php?id=" . $_SESSION['tid'] . "&&mid=" . base64_encode("408") . "'; </script>";
                                            }
                                        }
                                    }
                                    ?>

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

                <div id="chartdiv1"></div>
            </div>
        </div>

</div>