<div class="row">
    <?php
    if(!isset($_GET['borrower_id'])) {
        $id = $_GET['id'];
    }else{
        $id = $_GET['borrower_id'];
    }
    //Get Permissions
    $check = mysqli_query($link, "SELECT * FROM emp_permission WHERE tid = '" . $_SESSION['tid'] . "' AND module_name = 'Loan Details'") or die ("Error" . mysqli_error($link));
    $get_check = mysqli_fetch_array($check);
    $pdelete = $get_check['pdelete'];
    $pcreate = $get_check['pcreate'];
    $pupdate = $get_check['pupdate'];



    $select = mysqli_query($link, "SELECT * FROM borrowers WHERE id = '$id'") or die (mysqli_error($link));
    $info = mysqli_fetch_assoc($select);
    $title = $info['title'];
    $fname = $info['fname'];
    $lname = $info['lname'];
    $postal = $info['addrs1'];
    $physical = $info['addrs2'];
    $district = $info['district'];
    $image = $info['image'];
    $country = $info['country'];
    $date_time = $info['date_time'];
    $gender = $info['gender'];
    $dateOfBirth = $info['date_of_birth'];
    $employer = $info['employer'];
    $email = $info['email'];
    $phone = $info['phone'];
    $savingAccountBalance = $info['balance'];
    $savingsAccount = $info['account'];
    $created_by = $info['created_by'];
    $id_number=$info['id_number'];
    $passport=$info['passport'];

    //Get username//
    $get = mysqli_fetch_assoc(mysqli_query($link,"select * from user where id='$created_by'"));
    $created_by=$get['username'];

    if ($info['status'] == "Completed") {
        $accountStatus = "Active";
    } else {
        $accountStatus = "In-Active";
    }
    $loanId = $_GET['loanId']

    ?>
    <section class="content-header"><h1><?php echo $title . " " . $fname . " " . $lname; ?> </h1>
    </section>
    <section class="content">
        <div class="box box-widget">
            <div class="box-header with-border">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="user-block">
                            <?php if ($image != "") { ?>
                                <a href="#"><img class="img-circle" src="../<?php echo $image . " " ?>"></a>
                            <?php } ?>
                            <span class="description" style="font-size:13px; color:#000000">
                            <?php echo "<b>Date of Birth: </b>" . $dateOfBirth . ", ";
                            $today = date('Y-m-d');
                            function dateDifference($dateOfBirth, $today, $differenceFormat = '%y Years')
                            {
                                $datetime1 = date_create($dateOfBirth);
                                $datetime2 = date_create($today);

                                $interval = date_diff($datetime1, $datetime2);

                                return $interval->format($differenceFormat);
                                //echo $interval;

                            }

                            echo dateDifference($dateOfBirth, $today, $differenceFormat = '%y Years');
                            ?>
                            <br><b>ID: </b><?php echo $id_number . " " ?>
                            <br><b>Passport: </b><?php echo $passport . " " ?>
                            <br><b>Employer: </b><?php echo $employer . " " ?>
                            <br><b>Gender: </b><?php echo $gender . " " ?>
                            </span>
                        </div><!-- /.user-block -->
                    </div><!-- /.col -->
                    <div class="col-sm-4">
                        <ul class="list-unstyled">
                            <li><b>Date Joined: </b><?php echo substr($date_time, 0, 16); ?></li>
                            <li><b>Address:</b><?php echo $physical . " " ?></li>
                            <li><b>Postal:</b> <?php echo $postal . " " ?></li>
                            <li><b>District:</b> <?php echo $district . " " ?></li>
                            <li><b>Country:</b> <?php echo $country . " " ?></li>
                        </ul>
                    </div>
                    <div class="col-sm-4">
                        <ul class="list-unstyled">

                            <li><b>Email:</b> <a
                                        onclick="javascript:window.open('mailto:teb@live.com', 'mail');event.preventDefault()"
                                        href="mailto:teb@live.com"> <?php echo $email . " " ?></a>
                                <div class="btn-group-horizontal">
                                    <a type="button" class="btn-xs bg-red"
                                       href="">Send
                                        Email</a>
                                </div>
                            </li>
                            <li><b>Mobile:</b> <?php echo $phone . " " ?>
                                <div class="btn-group-horizontal">
                                    <a type="button" class="btn-xs bg-red"
                                       href="">Send
                                        SMS</a>
                                </div>
                            </li>

                        </ul>
                    </div>
                </div><!-- /.row -->
                <div class="row">
                    <div class="col-sm-8">
                        <div class="btn-group-horizontal">
                            <a href="listborrowers.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>">
                                <button type="button" class="btn bg-orange"><i
                                            class="fa fa-mail-reply-all"></i>&nbsp;Back
                                </button>
                            </a>
                            <?php
                             $selectLoan = mysqli_query($link, "SELECT * FROM loan_info WHERE borrower = '$id'") or die (mysqli_error($link));
                             $loan = mysqli_fetch_array($selectLoan);
                             $account = $loan['baccount']; ?>
                           <?php echo ($pupdate == '1') ? '<a type="button" class="btn bg-olive margin" href="newloans.php?newSearch=' . $id . '&&mid=' . base64_encode("405") . '&&loanId=' . $account . '">Add loan</a>' : ''; ?>
                            <a type="button" class="btn bg-navy margin" href="viewborrowers.php?borrower_id=<?php echo $id; ?>">View All Loans</a>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="pull-left">
                            <div class="input-group-btn">
                                <button type="button" class="btn btn-info dropdown-toggle margin"
                                        data-toggle="dropdown">Borrower Statements
                                    <span class="fa fa-caret-down"></span>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="#">Download Statement</a></li>
                                    <li><a href="#">Email Statement to Borrower</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box box-widget">
            <div class="box-header with-border">
                <div class="row">
                    <div class="col-xs-6">
                        <a href="#">Add/Edit Restriction on Borrower</a> <!--POP UP a Modal -->
                    </div>
                </div>
            </div>
        </div>
        <?php if(!isset($_GET['borrower_id'])){ ?>
        <div class="box box-info">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-money"></i>&nbsp;All Borrower Accounts</h3>
                </div>
                <div class="row" style="margin-right:0.2%;margin-left:0.2%;margin-top: 1%;">
                    <div class="col-sm-12 table-responsive">
                        <div id="view-loans-borrower-1194107_wrapper"
                             class="dataTables_wrapper form-inline dt-bootstrap">
                            <div class="pull-left"></div>
                            <div id="view-loans-borrower-1194107_processing"
                                 class="dataTables_processing panel panel-default" style="display: none;"><img src="#">
                                Processing..
                            </div>
                            <div class="pull-right"></div>

                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr role="row">
                                    <th>Account</th>
                                    <th>Account Type</th>
                                    <th>Balance Type</th>
                                    <th>Created On</th>
                                    <td><b>Created By</b></td>
                                    <td align="center"><b>Balance (<?php echo $_SESSION['currency'] ?>)</b></td>
                                    <th align="center">Status</th>
                                    <th align="center">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><?php echo $savingsAccount; ?></td>
                                    <td>Savings Account</td>
                                    <td class=" text-left">
                                        <?php
                                        if ($savingAccountBalance >= 0) {
                                            $balanceType = "Credit";
                                        } else {
                                            $balanceType = "Debit";
                                        }
                                        echo $balanceType;
                                        ?>
                                    </td>
                                    <td class=" text-left"><?php echo substr($date_time, 0, 16); ?></td>
                                    <td class=" text-left"><?php echo $created_by; ?></td>
                                    <td class=" text-right">
                                        <b><?php echo number_format($savingAccountBalance, 2, '.', ','); ?></b>
                                    </td>
                                    <td class=" text-center"><span
                                                class="label label-primary"><?php echo $accountStatus; ?></span></td>
                                    <td class=" text-right"></td>
                                </tr>
                                <?php
                                $selectLoan = mysqli_query($link, "SELECT * FROM loan_info WHERE borrower = '$id'") or die (mysqli_error($link));
                                $loanBalance = 0;
                                while ($loan = mysqli_fetch_array($selectLoan)) {
                                    $account = $loan['baccount'];
                                    $date_time = $loan['application_date'];
                                    $agent = $loan['agent'];
                                    $release_date = $loan['date_release'];

                                    $strJsonFileContents = file_get_contents('include/packages.json');
                                    $arrayOfTypes = json_decode($strJsonFileContents, true);
                                    $loan_product = $loan['loan_product'];
                                    foreach ($arrayOfTypes['accountType'] as $key => $value) {
                                        if ($loan_product == $key) {
                                            $loan_product = $value;
                                        }
                                    }
                                    $maturity = "";
                                    $loanId = $loan['id'];
                                    $principal = $loan['amount'];
                                    $intest_rate = $loan['loan_interest'];
                                    $loan_interest_period = $loan['loan_interest_period'];
                                    $interest_amount = "0";
                                    $fees = $loan['fees'];
                                    $loan_duration = $loan['loan_duration'];
                                    $loan_duration_period = $loan['loan_duration_period'];
                                    $loan_interest_method = $loan['loan_interest_method'];
                                    $loan_payment_scheme = $loan['loan_payment_scheme'];
                                    $loan_num_of_repayments = $loan['loan_num_of_repayments'];
                                    $loan_disbursed_by = $loan['loan_disbursed_by_id'];
                                    $loan_status = $loan['status'];
                                    $loan_desc = $loan['reason'];
                                    $amount_to_pay = $loan['amount_topay'];
                                    $penality = "";
                                    $due = "";
                                    $paid = "";
                                    $balance = $loan['balance'];
                                    $maturity = $loan['loan_maturity'];
                                    $edit_date = $loan['modified_date'];
                                    $edit_user = $loan['modified_by'];
                                    $loan_remarks = $loan['reason'];
                                    $paydate = $loan['pay_date'];

                                    //Get username//
                                    $get = mysqli_fetch_assoc(mysqli_query($link,"select * from user where id='$edit_user'"));
                                    $created_by=$get['username'];
                                    $loanBalance += $balance;

                                    ?>
                                    <tr role="row" class="odd">
                                        <td class="sorting_1"><?php echo $account; ?></td>
                                        <td><?php echo $loan_product; ?></td>
                                        <td class=" text-left">
                                            <?php
                                            if ($balance > 0) {
                                                $loanBalanceType = "Debit";
                                            } else {
                                                $loanBalanceType = "Credit";
                                            }
                                            echo $loanBalanceType;
                                            ?>
                                        </td>
                                        <td class=" text-left"><?php echo substr($date_time, 0, 16); ?></td>
                                        <td class=" text-left"><?php echo $created_by ?></td>
                                        <td class=" text-right">
                                            <b><?php echo number_format($balance, 2, '.', ','); ?></b>
                                        </td>
                                        <td class=" text-center"><span
                                                    class="label label-primary"><?php echo $loan_status; ?></span></td>
                                        <td>
                                                                                        &nbsp;
                                            <?php echo ($pupdate == '1') ? '<a href="viewborrowersloan.php?id=' . $id . '&&mid=' . base64_encode("405") . '&&loanId=' . $loanId . '"><i class="fa fa-eye"></i></a>' : ''; ?>
                                            <?php
                                            $se = mysqli_query($link, "SELECT * FROM attachment WHERE get_id = '$id'") or die (mysqli_error($link));
                                            while ($gete = mysqli_fetch_array($se)) {
                                                ?>
                                                <a href="<?php echo $gete['attached_file']; ?>">
                                                    <i class="fa fa-download"></i>
                                                </a>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                                </tbody>

                                <tfoot class="bg-gray">
                                <tr>
                                    <th style="text-align:right" rowspan="1" colspan="1"></th>
                                    <th style="text-align:right" rowspan="1" colspan="1"><b>Net Balance</b></th>
                                    <th style="text-align:right" rowspan="1" colspan="1"></th>
                                    <th style="text-align:right" rowspan="1" colspan="1"></th>
                                    <th style="text-align:right" rowspan="1" colspan="1"></th>
                                    <th style="text-align:right" class="text-right" rowspan="1" colspan="1">
                                        <?php
                                        $overallBalance = $savingAccountBalance - $loanBalance;
                                        if ($overallBalance < 0) {
                                            $overallBalance = -1 * $overallBalance;
                                            $balStatus = "&nbsp;(Debit)";
                                        } else if ($overallBalance == 0) {
                                            $overallBalance = -1 * $overallBalance;
                                            $balStatus = "-";
                                        } else {
                                            $balStatus = "&nbsp;(Credit)";
                                        }
                                        echo number_format($overallBalance, 2, '.', ',') . "$balStatus";
                                        ?>
                                    </th>
                                    <th style="text-align:right" rowspan="1" colspan="1"></th>
                                    <th style="text-align:right" rowspan="1" colspan="1"></th>
                                </tr>
                                </tfoot>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php } else { ?>
            <div class="box box-info">
                <div class="panel panel-success">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-money"></i>&nbsp;All Loans</h3>
                    </div>
                    <div class="row" style="margin-right:0.2%;margin-left:0.2%;margin-top: 1%;">
                        <div class="col-sm-12 table-responsive">
                            <div id="view-loans-borrower-1194107_wrapper"
                                 class="dataTables_wrapper form-inline dt-bootstrap">
                                <div class="pull-left"></div>
                                <div id="view-loans-borrower-1194107_processing"
                                     class="dataTables_processing panel panel-default" style="display: none;"><img src="#">
                                    Processing..
                                </div>
                                <div class="pull-right"></div>

                                <table id="example1" class="table table-bordered table-striped">

                                    <thead>
                                    <?php
                                    if (isset($_POST['delete'])) {
                                        $idm = $_GET['id'];
                                        $id = $_POST['selector'];
                                        $N = count($id);
                                        if ($id == '') {
                                            echo '<div class="alert alert-danger" >
                                                                     <a href = "#" class = "close" data-dismiss= "alert"> &times;</a>
                                                                       Please Select rows to Delete Employee!!!&nbsp; &nbsp;&nbsp;
                                                                       </div>';
                                        } else {
                                            for ($i = 0; $i < $N; $i++) {
                                                $result = mysqli_query($link, "DELETE FROM loan_info WHERE id ='$id[$i]'");
                                                echo '<div class="alert alert-success" >
                                                                           <a href = "#" class = "close" data-dismiss= "alert"> &times;</a>
                                                                           Load was Successfully Deleted!!!&nbsp; &nbsp;&nbsp;
                                                                           </div>';
                                            }
                                        }
                                    }
                                    ?>

                                    <tr>
                                        <th>Type</th>
                                        <th>Release Date</th>
                                        <th>Payment Date</th>
                                        <th>Account</th>
                                        <th>Principal</th>
                                        <th>Instalment</th>
                                        <th>Interest</th>
                                        <th>Fees</th>
                                        <th>Penalty</th>
                                        <th>Paid</th>
                                        <th>Balance</th>
                                        <th>Agent</th>
                                        <th>Loan Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $select = mysqli_query($link, "SELECT * FROM loan_info where borrower='$id'") or die (mysqli_error($link));
                                    if (mysqli_num_rows($select) == 0) {
                                        echo '<div class="alert alert-info">
                                     <a href = "#" class = "close" data-dismiss= "alert"> &times;</a>
                                    No data found yet!.....Check back later!!</div>';
                                    } else {
                                        echo '                                    <div class="input-group">
                                        <button type="button" class="btn btn-default pull-right" id="daterange-btn">
                                    <span>
                                      <i class="fa fa-calendar"></i>&nbsp;Customize View of Loans:
                                    </span>
                                            <i class="fa fa-caret-right"></i>
                                        </button>
                                    </div><br>';
                                        while ($row = mysqli_fetch_array($select)) {
                                            $id = $row['id'];
                                            $borrower = $row['borrower'];


                                            $strJsonFileContents = file_get_contents('include/packages.json');
                                            $arrayOfTypes = json_decode($strJsonFileContents, true);
                                            $status = $row['status'];
                                            foreach ($arrayOfTypes['accountStatusCodes'] as $key => $value) {
                                                if ($status == $key) {
                                                    $status = $value;
                                                }
                                                else if($status==""){
                                                    $status = "Open and Active";
                                                }
                                            }

                                            $upstatus = $row['upstatus'];
                                            $selectin = mysqli_query($link, "SELECT fname, lname, status FROM borrowers WHERE id = '$borrower'") or die (mysqli_error($link));
                                            $geth = mysqli_fetch_array($selectin);
                                            $name = $geth['fname'];
                                            $lname = $geth['lname'];
                                            $userStatus = $geth['status'];

                                            $collateral = mysqli_query($link, "SELECT * FROM collateral WHERE idm = '$borrower' and loan='$id'") or die (mysqli_error($link));
                                            $getCollateral = mysqli_fetch_array($collateral);

                                            $interest = mysqli_fetch_assoc(mysqli_query($link,"select sum(interest) from pay_schedule where get_id='$id'"));

                                            ///Add More Checks to Verify Completion of the Loan
                                            /// collateral
                                            ///attachement
                                            //fin_info
                                            ?>
                                            <?php
                                            if ($upstatus == "Pending") {
                                                ?>
                                                <tr>
                                                    <td>
                                                        <?php
                                                        $loan_product = $row['loan_product'];
                                                        $totalLoanAmount = $row['balance'];
                                                        $account=$row['baccount'];
                                                        //Total Loan payment///
                                                        $getPayments=mysqli_fetch_assoc(mysqli_query($link,"select sum(amount_to_pay) from payments where account='$account'"));

                                                        $payments = $getPayments['sum(amount_to_pay)'];
                                                        $balance = $totalLoanAmount-$payments;

                                                        foreach ($arrayOfTypes['accountType'] as $key => $value) {
                                                            if ($loan_product == $key) {
                                                                $loan_product = $value;
                                                            }
                                                        }
                                                        echo $loan_product;
                                                        ?>
                                                    </td>
                                                    <td><?php echo $row['date_release']; ?></td>
                                                    <td><?php echo $row['pay_date']; ?></td>
                                                    <td><?php echo $account; ?></td>
                                                    <td align="right"><?php echo number_format($row['amount'], 2, ".", ","); ?></td>
                                                    <td align="right"><?php echo number_format($row['amount_topay'], 2, ".", ","); ?></td>
                                                    <td align="right"><?php echo number_format($interest['sum(interest)'], 2, ".", ","); ?></td>
                                                    <td align="right"><?php echo number_format($row['fees'], 2, ".", ","); ?></td>
                                                    <td align="right">0</td>
                                                    <td align="right"><?php echo number_format($payments, 2, ".", ","); ?></td>
                                                    <td align="right"><?php echo number_format($balance, 2, ".", ","); ?></td>
                                                    <td><?php echo $row['teller']; ?></td>
                                                    <td>
                                                        <span class="label label-<?php if ($status == 'Open and Active' || $status == 'Paid Up') echo 'success'; elseif ($status == 'Disapproved') echo 'danger'; else echo 'warning'; ?>"><?php echo $status; ?></span>
                                                    </td>
                                                    <td>
                                                        <?php //echo ($pupdate == '1' && $userStatus == "Active" && mysqli_num_rows($collateral) > 0) ? '<a href="#myModal ' . $id . '"> <i data-target="#myModal' . $id . '" data-toggle="modal" class="fa fa-pencil"></i></a>' : ''; ?>
                                                        &nbsp;
                                                        <?php echo ($pupdate == '1') ? '<a href="viewborrowersloan.php?id=' . $borrower . '&&mid=' . base64_encode("405") . '&&loanId=' . $id . '"><i class="fa fa-eye"></i></a>' : ''; ?>
                                                        <?php
                                                        $se = mysqli_query($link, "SELECT * FROM attachment WHERE get_id = '$borrower'") or die (mysqli_error($link));
                                                        while ($gete = mysqli_fetch_array($se)) {
                                                            ?>
                                                            <a href="<?php echo $gete['attached_file']; ?>"><i
                                                                        class="fa fa-download"></i></a>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                                <?php
                                            } else {
                                                ?>
                                                <tr>
                                                    <td>
                                                        <?php
                                                        $loan_product = $row['loan_product'];
                                                        $totalLoanAmount = $row['balance'];
                                                        $account=$row['baccount'];
                                                        //Total Loan payment///
                                                        $getPayments=mysqli_fetch_assoc(mysqli_query($link,"select sum(amount_to_pay) from payments where account='$account'"));

                                                        $payments = $getPayments['sum(amount_to_pay)'];
                                                        $balance = $totalLoanAmount-$payments;

                                                        foreach ($arrayOfTypes['accountType'] as $key => $value) {
                                                            if ($loan_product == $key) {
                                                                $loan_product = $value;
                                                            }
                                                        }
                                                        echo $loan_product;
                                                        ?>
                                                    </td>
                                                    <td><?php echo $row['date_release']; ?></td>
                                                    <td><?php echo $row['pay_date']; ?></td>
                                                    <td><?php echo $account; ?></td>
                                                    <td align="right"><?php echo number_format($row['amount'], 2, ".", ","); ?></td>
                                                    <td align="right"><?php echo number_format($row['amount_topay'], 2, ".", ","); ?></td>
                                                    <td align="right"><?php echo number_format($interest['sum(interest)'], 2, ".", ","); ?></td>
                                                    <td align="right"><?php echo number_format($row['fees'], 2, ".", ","); ?></td>
                                                    <td align="right">0</td>
                                                    <td align="right"><?php echo number_format($payments, 2, ".", ","); ?></td>
                                                    <td align="right"><?php echo number_format($balance, 2, ".", ","); ?></td>
                                                    <td><?php echo $row['teller']; ?></td>
                                                    <td>
                                                        <span class="label label-<?php if ($status == 'Open and Active' || $status == 'Paid Up') echo 'success'; elseif ($status == 'Disapproved') echo 'danger'; else echo 'warning'; ?>"><?php echo $status; ?></span>
                                                    </td>
                                                    <td>
                                                        <?php //echo ($pupdate == '1' && $userStatus == "Active" && mysqli_num_rows($collateral) > 0) ? '<a href="#myModal ' . $id . '"> <i data-target="#myModal' . $id . '" data-toggle="modal" class="fa fa-pencil"></i></a>' : ''; ?>
                                                        &nbsp;
                                                        <?php echo ($pupdate == '1') ? '<a href="viewborrowersloan.php?id=' . $borrower . '&&mid=' . base64_encode("405") . '&&loanId=' . $id . '"><i class="fa fa-eye"></i></a>' : ''; ?>
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
                                    } ?>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </section>
</div>
