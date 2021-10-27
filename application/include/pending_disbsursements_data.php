<div class="row">
    <section class="content">
        <div class="box box-success">
            <div class="box-body">
                <div class="table-responsive">
                    <div class="box-body">
                        <form method="post">
                            <a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>">
                                <button type="button" class="btn btn-flat btn-warning">
                                    <i class="fa fa-mail-reply-all"></i>&nbsp;Back
                                </button>
                            </a>
                            <?php
                            $check = mysqli_query($link, "SELECT * FROM emp_permission WHERE tid = '" . $_SESSION['tid'] . "' AND module_name = 'Loan Details'") or die ("Error" . mysqli_error($link));
                            $get_check = mysqli_fetch_array($check);
                            $pdelete = $get_check['pdelete'];
                            $pcreate = $get_check['pcreate'];
                            $pupdate = $get_check['pupdate'];

                            $strJsonFileContents = file_get_contents('include/packages.json');
                            $arrayOfTypes = json_decode($strJsonFileContents, true);
                            ?>

                            <?php
                            $get = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
                            $date = date("d/m/Y", $get);
                            $select = mysqli_query($link, "SELECT * FROM loan_info WHERE pay_date >= '$date' AND pay_date < '$date'") or die (mysqli_error($link));
                            $num = mysqli_num_rows($select);
                            ?>

                            <a href="print_pending_disbursements.php"class="btn btn-info btn-flat"><i
                                    class="fa fa-print"></i>&nbsp;Print</a>
                            <a href="exportpending_disbursement.php" class="btn btn-success btn-flat"><i
                                    class="fa fa-send"></i>&nbsp;Export Excel</a>

                            <hr>
                            <h4 style="color: green;"><b>Loans Pending Disbursement</b></h4><hr>
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
                                    <th>Customer</th>
                                    <th>Type</th>
                                    <th>Loan Account</th>
                                    <th>Amount</th>
                                    <th>Instalment</th>
                                    <th>Agent</th>
                                    <th>Release Date</th>
                                    <th>Method</th>
                                    <th>Account</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $select = mysqli_query($link, "SELECT * FROM loan_info where status = 'Pending Disbursement'") or die (mysqli_error($link));
                                if (mysqli_num_rows($select) == 0) {
                                    echo '<div class="alert alert-info">
                                     <a href = "#" class = "close" data-dismiss= "alert"> &times;</a>
                                    No data found yet!.....Check back later!!</div>';
                                } else {
                                    while ($row = mysqli_fetch_array($select)) {
                                        $id = $row['id'];

                                        $bankingDetails = mysqli_fetch_array(mysqli_query($link, "SELECT transaction, disbursement_method FROM loan_disbursements WHERE loan='$id'"));
                                        $bankDetails = json_decode($bankingDetails['transaction'], true);
                                        $disburseMethod = $bankingDetails['disbursement_method'];

                                        $borrower = $row['borrower'];
                                        $status = $row['status'];
                                        $upstatus = $row['upstatus'];
                                        $selectin = mysqli_query($link, "SELECT fname, lname, status FROM borrowers WHERE id = '$borrower'") or die (mysqli_error($link));
                                        $geth = mysqli_fetch_array($selectin);
                                        $name = $geth['fname'];
                                        $lname = $geth['lname'];
                                        $userStatus = $geth['status'];

                                        $collateral = mysqli_query($link, "SELECT * FROM collateral WHERE idm = '$borrower' and loan='$id'") or die (mysqli_error($link));
                                        $getCollateral = mysqli_fetch_array($collateral);

                                        ///Add More Checks to Verify Completion of the Loan
                                        /// collateral
                                        ///attachement
                                        //fin_info
                                        ?>

                                            <tr>
                                                <td><?php echo $name . "&nbsp;" . $lname; ?></td>
                                                <td><?php
                                                    $loan_product = $row['loan_product'];
                                                    $productName=mysqli_fetch_assoc(mysqli_query($link,"select * from products where product_id='$loan_product'"));
                                                    $loan_product=$productName['product_name'];
                                                    echo $loan_product;
                                                    ?>
                                                </td>
                                                <td><?php echo $row['baccount']; ?></td>
                                                <td align="right">
                                                    <b><?php echo $_SESSION['currency'] . "&nbsp;" . number_format($row['amount'], 2, ".", ","); ?></b>
                                                </td>
                                                <td align="right">
                                                    <b><?php echo $_SESSION['currency'] . "&nbsp;" . number_format($row['amount_topay'], 2, ".", ","); ?></b>
                                                </td>
                                                <td><?php echo $row['teller']; ?></td>
                                                <td><?php echo $row['date_release']; ?></td>
                                                <td><?php echo $row['loan_disbursed_by_id'];?></td>
                                                <td><?php echo $bankDetails['accountNumber']." - ".str_replace("_"," ",$bankDetails['bankName']);?></td>
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

                                    }
                                } ?>
                                </tbody>
                            </table>

                        </form>

                    </div>


                </div>
            </div>
        </div>
</div>