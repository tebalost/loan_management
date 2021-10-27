<div class="row">
    <section class="content">
        <div class="box box-success">
            <?php
            include("bureauRecords.php");
            ?>
            <div class="box-body">
                <?php
                //error_reporting(0);
                if (isset($_GET['response'])) {

                }
                ?>


                <div class="table-responsive">
                    <div class="box-body">
                        <form method="post">
                            <a href="bureausubmissions.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>">
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
                            ?>


                            <a href="printloan.php" target="_blank" class="btn btn-info btn-flat"><i class="fa fa-print"></i>&nbsp;Print</a>
                            <a href="exportBureauData.php" class="btn btn-success btn-flat"><i class="fa fa-send"></i>&nbsp;Export Excel</a>
                            <!--<a href="bureauFile.php?action=Download" class="btn btn-primary btn-flat"><i class="fa fa-download"></i>&nbsp;Download Response File</a>-->
                            <a href="viewBureauSubmittedFiles.php?action=SFTP&batch=<?php echo $_GET['batch']; ?>" class="btn btn-primary btn-flat"><i class="fa fa-upload"></i>&nbsp;Upload File</a>

                            <hr>

                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>Names</th>
                                    <th>Surname</th>
                                    <th>Date Of Birth</th>
                                    <th>ID</th>
                                    <th>Passport</th>
                                    <th>Gender</th>
                                    <th>Employer</th>
                                    <th>Telephone</th>
                                    <th>Account</th>
                                    <th>Date Account Opened</th>
                                    <th>Branch</th>
                                    <th>Term (Months)</th>
                                    <th>Principal</th>
                                    <th>Current Balance</th>
                                    <th>Balance Type</th>
                                    <th>Instalment Amount</th>
                                    <th>Date Last Payment received</th>
                                    <th>Months in Arrears</th>
                                    <th>Amount Overdue</th>
                                    <th>Status Code</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $select = mysqli_query($link, "SELECT id_number, passport, date_of_birth, baccount, amount,
                                        lname, fname, gender, phone, employer, application_date,loan_info.balance, 
                                        amount_topay, loan_duration,loan_duration_period, loan_info.status, branch, date_of_birth 
                                        FROM loan_info, borrowers WHERE borrowers.id = loan_info.borrower 
                                        AND loan_info.status not in('DECLINED','Pending','Pending Disbursement')")
                                or die (mysqli_error($link));
                                if (mysqli_num_rows($select) == 0) {
                                    echo '<div class="alert alert-info">
                                     <a href = "#" class = "close" data-dismiss= "alert"> &times;</a>
                                    No data found yet!.....Check back later!!</div>';
                                } else {
                                    $lastDay = date('Y-m-d');
                                    $today = date('Y-m-d');
                                    function dateDifference($lastDay, $today, $differenceFormat = '%m Months')
                                    {
                                        $datetime1 = date_create($lastDay);
                                        $datetime2 = date_create($today);

                                        $interval = date_diff($datetime1, $datetime2);

                                        return $interval->format($differenceFormat);
                                        //echo $interval;

                                    }
                                    while ($row = mysqli_fetch_array($select)) {
                                        $name = $row['fname'];
                                        $lname = $row['lname'];
                                        $account = $row['baccount'];
                                        $dateOfBirth = $row['date_of_birth'];
                                        $newDateOfBirth = date("Ymd", strtotime($dateOfBirth));
                                        //Get Max Payment
                                        $maxDay=mysqli_fetch_assoc(mysqli_query($link,"select max(pay_date), sum(amount_to_pay) from payments where account='$account'"));
                                        $remainingBalance = $row['balance'] - $maxDay['sum(amount_to_pay)'];
                                        if($remainingBalance>0){
                                            $balanceType = "Debit";
                                        }else{
                                            $balanceType = "Credit";
                                        }

                                        //Get Months in Arrears//Number of months based on Last Payment Made --- Date Diff
                                        $lastDay = substr($maxDay['max(pay_date)'],0,10);
                                        if($lastDay==""){
                                            $lastDay = substr($row['application_date'],0,10);
                                        }
                                        $today = date('Y-m-d');

                                        ?>
                                        <tr>
                                            <td><?php echo $name; ?></td>
                                            <td><?php echo $lname; ?></td>
                                            <td><?php echo $newDateOfBirth; ?></td>
                                            <td><?php echo $row['id_number']; ?></td>
                                            <td><?php echo $row['passport']; ?></td>
                                            <td><?php echo $row['gender']; ?></td>
                                            <td><?php echo $row['employer']; ?></td>
                                            <td><?php echo $row['phone']; ?></td>
                                            <td><?php echo $row['baccount']; ?></td>
                                            <td><?php echo substr($row['application_date'],0,10); ?></td>
                                            <td>
                                                <?php
                                                $branchValue =  $row['branch'];
                                                $branch = mysqli_fetch_assoc(mysqli_query($link,"select * from branches where code='$branchValue'"));
                                                echo $branch['name'];
                                                ?>
                                            </td>
                                            <td align="right"><?php echo $row['loan_duration']; ?></td>
                                            <td align="right">
                                                <?php
                                                echo number_format($row['amount'],0,".",",");
                                                ?>
                                            </td>
                                            <td align="right"><?php echo number_format($remainingBalance,0,".",","); ?></td>
                                            <td align="left"><?php echo $balanceType; ?></td>
                                            <td align="right"><?php echo  number_format($row['amount_topay'],0,".",",");?></td><!--Installment Amount-->
                                            <td><?php echo substr($maxDay['max(pay_date)'],0,10); ?></td>
                                            <td align="right"><?php echo dateDifference($lastDay, $today, $differenceFormat = '%m');; ?></td>
                                            <td align="right">
                                                <?php
                                                    $amountOverdue = $row['amount_topay']*dateDifference($lastDay, $today, $differenceFormat = '%m');
                                                    echo  number_format($amountOverdue,0,".",","); //Also Include Balances if Installpents paid before were not full
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $strJsonFileContents = file_get_contents('include/packages.json');
                                                $arrayOfTypes = json_decode($strJsonFileContents, true);
                                                $loan_status = $row['status'];
                                                foreach ($arrayOfTypes['accountStatusCodes'] as $key => $value) {
                                                    if ($loan_status == $key) {
                                                        $loan_status = $value;
                                                    }
                                                }
                                                if($loan_status==""){
                                                    $loan_status = "Open and Active";
                                                }
                                                echo $loan_status;
                                                ?>
                                            </td>

                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                                </tbody>
                            </table>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>