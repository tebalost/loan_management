<div class="row">
    <section class="content">
        <div class="box box-success">
            <?php
            include("bureauRecords.php");
            ?>
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
                            ?>

                            <?php
                            $get = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
                            $date = date("d/m/Y", $get);

                            //Add A unique Record based on Bureau Submission Settings
                            $cycle = mysqli_fetch_assoc(mysqli_query($link, "select * from systemset"));
                            $submissionCycle = $cycle['submission_cycle'];
                            $submissionDay = $cycle['day_of_submission'];

                            if ($submissionCycle == "M") { //FIXME Cater for daily Later
                                //Increment Batch Number...
                                $batch = date('Ym');
                                $actionDate = date('Y-m') . "-$submissionDay";

                                //Check If Exists//
                                $get = mysqli_query($link, "select * from bureau_submissions where action_date='$actionDate'");
                                if (mysqli_num_rows($get) == 0) {
                                    mysqli_query($link, "insert into bureau_submissions values (0,'$batch','0','Scheduled','$actionDate','')");
                                    echo mysqli_error($link);
                                }
                            }


                            $select = mysqli_query($link, "SELECT * FROM bureau_submissions") or die (mysqli_error($link));
                            //                            $num = mysqli_num_rows($select);
                            ?>
                            <a href="printloan.php" target="_blank" class="btn btn-info btn-flat"><i
                                        class="fa fa-print"></i>&nbsp;Print</a>
                            <a href="exportloan.php" target="_blank" class="btn btn-success btn-flat"><i
                                        class="fa fa-send"></i>&nbsp;Export Excel</a>

                            <hr>

                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <td align="center"><b>Batch Number</b></td>
                                    <td align="center"><b>Count</b></td>
                                    <td align="center"><b>Status</b></td>
                                    <td align="center"><b>Action Date</b></td>
                                    <td align="center"><b>Action By</b></td>
                                    <td align="center"><b>Action</b></td>
                                </tr>
                                </thead>
                                <tbody>
                                <?php while($submission=mysqli_fetch_assoc($select)){
                                    $status = $submission['status'];
                                    ?>
                                <tr>
                                    <td align="center">
                                        <?php
                                        $batch=$submission['batch'];
                                        echo $submission['batch'];
                                    ?>
                                    </td>
                                    <td align="center">
                                        <?php
                                        echo $submission['loan_records']
                                        ?>
                                    </td>
                                   <?php if ($status == "Scheduled") {
                                    ?>
                                    <td align="center" >
                                        <?php echo ($pupdate == '1') ? '<a href="#" ><span
                                                             class="label label-warning">Scheduled</span></a>' : ''; ?>
                                    </td>
                                    <?php
                                    }
                                    else if ($status == "Submitted") {
                                        ?>
                                        <td align="center" >
                                            <?php echo ($pupdate == '1') ? '<a href="#?id=' . $id . '&&mid=' . base64_encode("403") . '" ><span
                                                             class="label label-success">Submitted</span></a>' : ''; ?>
                                        </td>
                                        <?php
                                    }
                                    else {
                                        ?>
                                        <td align="center"><span class="label label-success"><?php echo str_replace("_"," ","$status"); ?></span></td>                                       </td>
                                    <?php } ?>
                                    <td align="center"><?php echo $submission['action_date']; ?></td>
                                    <td align="center">
                                        <?php
                                        $user=$submission['action_by'];
                                        $getUser=mysqli_fetch_assoc(mysqli_query($link,"select * from user where id='$user'"));
                                        echo $getUser['username'];
                                        ?>
                                    </td>
                                    <td align="center">
                                       <a href="viewBureauSubmittedFiles.php?batch=<?php echo $batch . "&&mid=" . base64_encode("403");?>" title="View Contents"><i class="fa fa-eye"></i></a>&nbsp;
                                       <!-- <a href="bureauFile.php?action=Download"><i class="fa fa-download"></i></a>&nbsp;
                                        <a href="bureausubmissions.php?action=Email"><i class="fa fa-send"></i></a>-->
                                    </td>
                                </tr>
                                <?php } ?>

                                </tbody>
                            </table>

                        </form>

                    </div>


                </div>
            </div>
        </div>
</div>
