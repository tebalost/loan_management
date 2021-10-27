<div class="row">

    <section class="content">
        <div class="box box-success">
            <?php
            if (isset($_POST['save'])) {
//$id = $_POST['selector'];
                $tide = mysqli_real_escape_string($link, $_POST['tide']);

                $verify = mysqli_query($link, "SELECT * FROM emp_permission WHERE tid = '$tide'") or die ("Error: " . mysqli_error($link));
                $get_verify = mysqli_num_rows($verify);
                if ($get_verify == 11) {
                    echo '<div class="alert alert-danger" >
                                                <a href = "#" class = "close" data-dismiss= "alert"> &times;</a>
                                                Error: Permission Already granted. Please visit permission list to see!!&nbsp; &nbsp;&nbsp;
                                                </div>';
                } else {
//Starting of first module
                    $module1 = mysqli_real_escape_string($link, $_POST['email_t']);
                    $pcreate1 = (isset($_POST['email_create'])) ? 1 : 0;
                    $pread1 = (isset($_POST['email_read'])) ? 1 : 0;
                    $pupdate1 = (isset($_POST['email_update'])) ? 1 : 0;
                    $pdelete1 = (isset($_POST['email_delete'])) ? 1 : 0;

                    $insert1 = mysqli_query($link, "INSERT INTO emp_permission VALUES('0','$tide','$module1','$pcreate1','$pread1','$pupdate1','$pdelete1')") or die ("Error: " . mysqli_error($link));
//End of first module

//Starting of second module
                    $module2 = mysqli_real_escape_string($link, $_POST['borrow_d']);
                    $pcreate2 = (isset($_POST['borrow_create'])) ? 1 : 0;
                    $pread2 = (isset($_POST['borrow_read'])) ? 1 : 0;
                    $pupdate2 = (isset($_POST['borrow_update'])) ? 1 : 0;
                    $pdelete2 = (isset($_POST['borrow_delete'])) ? 1 : 0;

                    $insert1 = mysqli_query($link, "INSERT INTO emp_permission VALUES('0','$tide','$module2','$pcreate2','$pread2','$pupdate2','$pdelete2')") or die ("Error: " . mysqli_error($link));
//End of second module

//Starting of third module
                    $module3 = mysqli_real_escape_string($link, $_POST['disbursement_t']);
                    $pcreate3 = (isset($_POST['disbursement_create'])) ? 1 : 0;
                    $pread3 = (isset($_POST['wallet_read'])) ? 1 : 0;
                    $pupdate3 = (isset($_POST['wallet_update'])) ? 1 : 0;
                    $pdelete3 = (isset($_POST['wallet_delete'])) ? 1 : 0;

                    $insert1 = mysqli_query($link, "INSERT INTO emp_permission VALUES('0','$tide','$module3','$pcreate3','0','0','0')") or die ("Error: " . mysqli_error($link));
//End of third module

//Starting of fourth module
                    $module4 = mysqli_real_escape_string($link, $_POST['loan_d']);
                    $pcreate4 = (isset($_POST['loan_create'])) ? 1 : 0;
                    $pread4 = (isset($_POST['loan_read'])) ? 1 : 0;
                    $pupdate4 = (isset($_POST['loan_update'])) ? 1 : 0;
                    $pdelete4 = (isset($_POST['loan_delete'])) ? 1 : 0;

                    $insert1 = mysqli_query($link, "INSERT INTO emp_permission VALUES('0','$tide','$module4','$pcreate4','$pread4','$pupdate4','$pdelete4')") or die ("Error: " . mysqli_error($link));
//End of fourth module

//Starting of fifth module
                    $module5 = mysqli_real_escape_string($link, $_POST['msg']);
                    $pcreate5 = (isset($_POST['msg_create'])) ? 1 : 0;
                    $pread5 = (isset($_POST['msg_read'])) ? 1 : 0;
                    $pupdate5 = (isset($_POST['msg_update'])) ? 1 : 0;
                    $pdelete5 = (isset($_POST['msg_delete'])) ? 1 : 0;

                    $insert1 = mysqli_query($link, "INSERT INTO emp_permission VALUES('0','$tide','$module5','$pcreate5','$pread5','$pupdate5','$pdelete5')") or die ("Error: " . mysqli_error($link));
//End of fifth module

//Starting of sixth module
                    $module6 = mysqli_real_escape_string($link, $_POST['mpay']);
                    $pcreate6 = (isset($_POST['mpay_create'])) ? 1 : 0;
                    $pread6 = (isset($_POST['mpay_read'])) ? 1 : 0;
                    $pupdate6 = (isset($_POST['mpay_update'])) ? 1 : 0;
                    $pdelete6 = (isset($_POST['mpay_delete'])) ? 1 : 0;

                    $insert1 = mysqli_query($link, "INSERT INTO emp_permission VALUES('0','$tide','$module6','$pcreate6','$pread6','$pupdate6','$pdelete6')") or die ("Error: " . mysqli_error($link));
//End of sixth module

//Starting of seventh module
                    $module7 = mysqli_real_escape_string($link, $_POST['payment']);
                    $pcreate7 = (isset($_POST['payment_create'])) ? 1 : 0;
                    $pread7 = (isset($_POST['payment_read'])) ? 1 : 0;
                    $pupdate7 = (isset($_POST['payment_update'])) ? 1 : 0;
                    $pdelete7 = (isset($_POST['payment_delete'])) ? 1 : 0;

                    $insert1 = mysqli_query($link, "INSERT INTO emp_permission VALUES('0','$tide','$module7','$pcreate7','$pread7','$pupdate7','$pdelete7')") or die ("Error: " . mysqli_error($link));
//End of seventh module

//Starting of eight module
                    $module8 = mysqli_real_escape_string($link, $_POST['emp_d']);
                    $pcreate8 = (isset($_POST['emp_create'])) ? 1 : 0;
                    $pread8 = (isset($_POST['emp_read'])) ? 1 : 0;
                    $pupdate8 = (isset($_POST['emp_update'])) ? 1 : 0;
                    $pdelete8 = (isset($_POST['emp_delete'])) ? 1 : 0;

                    $insert1 = mysqli_query($link, "INSERT INTO emp_permission VALUES('0','$tide','$module8','$pcreate8','$pread8','$pupdate8','$pdelete8')") or die ("Error: " . mysqli_error($link));
//End of eight module

//Starting of ninth module
                    $module9 = mysqli_real_escape_string($link, $_POST['permission_m']);
                    $pcreate9 = (isset($_POST['permission_create'])) ? 1 : 0;
                    $pread9 = (isset($_POST['permission_read'])) ? 1 : 0;
                    $pupdate9 = (isset($_POST['permission_update'])) ? 1 : 0;
                    $pdelete9 = (isset($_POST['permission_delete'])) ? 1 : 0;

                    $insert1 = mysqli_query($link, "INSERT INTO emp_permission VALUES('0','$tide','$module9','$pcreate9','$pread9','$pupdate9','$pdelete9')") or die ("Error: " . mysqli_error($link));
//End of ninth module

//Starting of tenth module
                    $module10 = mysqli_real_escape_string($link, $_POST['savings_t']);
                    $pcreate10 = (isset($_POST['savings_create'])) ? 1 : 0;
                    $pread10 = (isset($_POST['savings_read'])) ? 1 : 0;
                    $pupdate10 = (isset($_POST['savings_update'])) ? 1 : 0;
                    $pdelete10 = (isset($_POST['savings_delete'])) ? 1 : 0;

                    $insert1 = mysqli_query($link, "INSERT INTO emp_permission VALUES('0','$tide','$module10','$pcreate10','$pread10','$pupdate10','$pdelete10')") or die ("Error: " . mysqli_error($link));
//End of tenth module

//Starting of eleventh module
                    $module11 = mysqli_real_escape_string($link, $_POST['general_t']);
                    $pcreate11 = (isset($_POST['general_create'])) ? 1 : 0;
                    $pread11 = (isset($_POST['general_read'])) ? 1 : 0;
                    $pupdate11 = (isset($_POST['general_update'])) ? 1 : 0;
                    $pdelete11 = (isset($_POST['general_delete'])) ? 1 : 0;

                    $insert1 = mysqli_query($link, "INSERT INTO emp_permission VALUES('0','$tide','$module11','$pcreate11','$pread11','$pupdate11','$pdelete11')") or die ("Error: " . mysqli_error($link));
//End of eleventh module


                    //Starting of eleventh module
                    $module12 = mysqli_real_escape_string($link, $_POST['approver_t']);
                    $pcreate12 = (isset($_POST['approver_create'])) ? 1 : 0;
                    $pread12 = (isset($_POST['approver_read'])) ? 1 : 0;
                    $pupdate12 = (isset($_POST['approver_update'])) ? 1 : 0;
                    $pudelete12 = (isset($_POST['approver_delete'])) ? 1 : 0;

                    $insert1 = mysqli_query($link, "INSERT INTO emp_permission VALUES('0','$tide','$module12','$pcreate12','0','0','0')") or die ("Error: " . mysqli_error($link));
//End of eleventh module

                    //Starting of Thirteenth module
                    $module13 = mysqli_real_escape_string($link, $_POST['Reports_t']);
                    $pcreate13 = (isset($_POST['Reports_create'])) ? 1 : 0;
                    $pread13 = (isset($_POST['Reports_read'])) ? 1 : 0;
                    $pupdate13 = (isset($_POST['Reports_update'])) ? 1 : 0;
                    $pdelete13 = (isset($_POST['Reports_delete'])) ? 1 : 0;

                    $insert1 = mysqli_query($link, "INSERT INTO emp_permission VALUES('0','$tide','$module13','$pcreate13','$pread13','$pupdate13','$pdelete13')") or die ("Error: " . mysqli_error($link));

                    //Starting of eleventh module
                    $module14 = mysqli_real_escape_string($link, $_POST['Accounting_t']);
                    $pcreate14 = (isset($_POST['Accounting_create'])) ? 1 : 0;
                    $pread14 = (isset($_POST['Accounting_read'])) ? 1 : 0;
                    $pupdate14 = (isset($_POST['Accounting_update'])) ? 1 : 0;
                    $pdelete14 = (isset($_POST['Accounting_delete'])) ? 1 : 0;

                    $insert1 = mysqli_query($link, "INSERT INTO emp_permission VALUES('0','$tide','$module14','$pcreate14','$pread14','$pupdate14','$pdelete14')") or die ("Error: " . mysqli_error($link));

                    //Starting of eleventh module
                    $module11 = mysqli_real_escape_string($link, $_POST['general_t']);
                    $pcreate11 = (isset($_POST['general_create'])) ? 1 : 0;
                    $pread11 = (isset($_POST['general_read'])) ? 1 : 0;
                    $pupdate11 = (isset($_POST['general_update'])) ? 1 : 0;
                    $pdelete11 = (isset($_POST['general_delete'])) ? 1 : 0;

                    $insert1 = mysqli_query($link, "INSERT INTO emp_permission VALUES('0','$tide','$module11','$pcreate11','$pread11','$pupdate11','$pdelete11')") or die ("Error: " . mysqli_error($link));

                    //Starting of Fifteenth module
                    $module15 = mysqli_real_escape_string($link, $_POST['Scheduler_t']);
                    $pcreate15 = (isset($_POST['Scheduler_create'])) ? 1 : 0;
                    $pread15 = (isset($_POST['Scheduler_read'])) ? 1 : 0;
                    $pupdate15 = (isset($_POST['Scheduler_update'])) ? 1 : 0;
                    $pdelete15 = (isset($_POST['Scheduler_delete'])) ? 1 : 0;

                    $insert1 = mysqli_query($link, "INSERT INTO emp_permission VALUES('0','$tide','$module15','$pcreate15','$pread15','$pupdate15','$pdelete15')") or die ("Error: " . mysqli_error($link));


                    if (!$insert1) {

                        echo '<div class="alert alert-success" >
                                                <a href = "#" class = "close" data-dismiss= "alert"> &times;</a>
                                                Record not inserted.....Please try again later&nbsp; &nbsp;&nbsp;
                                                </div>';
                    } else {
                        echo '<div class="alert alert-success" >
                                                <a href = "#" class = "close" data-dismiss= "alert"> &times;</a>
                                                Permissions Added Successfully!!&nbsp; &nbsp;&nbsp;
                                                </div>';
                    }
                }
            }
            ?>
            <div class="box-body">
                <div class="table-responsive">
                    <div class="box-body">
                        <form method="post">
                            <a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>">
                                <button type="button" class="btn btn-flat btn-warning"><i
                                            class="fa fa-mail-reply-all"></i>&nbsp;Back
                                </button>
                            </a>
                            <hr>

                            <form class="form-horizontal" method="post" enctype="multipart/form-data">

                                <div class="box-body">

                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label">User Name:</label>
                                        <div class="col-sm-10">
                                            <select name="tide" class="form-control select2" required>
                                                <option value="">-Select user-</option>
                                                <?php
                                                $search_user = mysqli_query($link, "SELECT * FROM user WHERE id != '" . $_SESSION['tid'] . "'") or die ("Error: " . mysqli_error($link));
                                                while ($get_users = mysqli_fetch_array($search_user)) {
                                                    ?>
                                                    <option value="<?php echo $get_users['id']; ?>"><?php echo $get_users['name']; ?></option>
                                                <?php } ?>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <hr>
                                        <hr>
                                        <div>

                                            <table class="table" border="0.95">
                                                <thead>
                                                <tr>
                                                    <th>S/No.</th>
                                                    <th>Module Name</th>
                                                    <th>
                                                        <div align="center">Create</div>
                                                    </th>
                                                    <th>
                                                        <div align="center">Read</div>
                                                    </th>
                                                    <th>
                                                        <div align="center">Update</div>
                                                    </th>
                                                    <th>
                                                        <div align="center">Delete</div>
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td width="30">1</td>
                                                    <td width="450">Email Panel <input type="hidden" name="email_t"
                                                                                       value="Email Panel"/></td>
                                                    <td>
                                                        <div align="center"><input name="email_create" type="checkbox"
                                                                                   value="On"></div>
                                                    </td>
                                                    <td>
                                                        <div align="center"><input name="email_read" type="checkbox"
                                                                                   value="On"></div>
                                                    </td>
                                                    <td>
                                                        <div align="center"><input name="email_update" type="checkbox"
                                                                                   value="On"></div>
                                                    </td>
                                                    <td>
                                                        <div align="center"><input name="email_delete" type="checkbox"
                                                                                   value="On"></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="30">2</td>
                                                    <td width="450">Borrower Details <input type="hidden"
                                                                                            name="borrow_d"
                                                                                            value="Borrower Details"/>
                                                    </td>
                                                    <td>
                                                        <div align="center"><input name="borrow_create" type="checkbox"
                                                                                   value="On"></div>
                                                    </td>
                                                    <td>
                                                        <div align="center"><input name="borrow_read" type="checkbox"
                                                                                   value="On"></div>
                                                    </td>
                                                    <td>
                                                        <div align="center"><input name="borrow_update" type="checkbox"
                                                                                   value="On"></div>
                                                    </td>
                                                    <td>
                                                        <div align="center"><input name="borrow_delete" type="checkbox"
                                                                                   value="On"></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="30">3</td>
                                                    <td width="450">Loans Disbursement<input type="hidden"
                                                                                             name="disbursement_t"
                                                                                             value="Loans Disbursement"/>
                                                    </td>
                                                    <td>
                                                        <div align="center"><input name="disbursement_create"
                                                                                   type="checkbox"
                                                                                   value="On"></div>
                                                    </td>
                                                    <td>

                                                    </td>
                                                    <td>

                                                    </td>
                                                    <td>

                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="30">4</td>
                                                    <td width="450">Loan Details <input type="hidden" name="loan_d"
                                                                                        value="Loan Details"/></td>
                                                    <td>
                                                        <div align="center"><input name="loan_create" type="checkbox"
                                                                                   value="On"></div>
                                                    </td>
                                                    <td>
                                                        <div align="center"><input name="loan_read" type="checkbox"
                                                                                   value="On"></div>
                                                    </td>
                                                    <td>
                                                        <div align="center"><input name="loan_update" type="checkbox"
                                                                                   value="On"></div>
                                                    </td>
                                                    <td>
                                                        <div align="center"><input name="loan_delete" type="checkbox"
                                                                                   value="On"></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="30">5</td>
                                                    <td width="450">Internal Message <input type="hidden" name="msg"
                                                                                            value="Internal Message"/>
                                                    </td>
                                                    <td>
                                                        <div align="center"><input name="msg_create" type="checkbox"
                                                                                   value="On"></div>
                                                    </td>
                                                    <td>
                                                        <div align="center"><input name="msg_read" type="checkbox"
                                                                                   value="On"></div>
                                                    </td>
                                                    <td>
                                                        <div align="center"><input name="msg_update" type="checkbox"
                                                                                   value="On"></div>
                                                    </td>
                                                    <td>
                                                        <div align="center"><input name="msg_delete" type="checkbox"
                                                                                   value="On"></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="30">6</td>
                                                    <td width="450">Missed Payment <input type="hidden" name="mpay"
                                                                                          value="Missed Payment"/></td>
                                                    <td>
                                                        <div align="center"><input name="mpay_create" type="checkbox"
                                                                                   value="On"></div>
                                                    </td>
                                                    <td>
                                                        <div align="center"><input name="mpay_read" type="checkbox"
                                                                                   value="On"></div>
                                                    </td>
                                                    <td>
                                                        <div align="center"><input name="mpay_update" type="checkbox"
                                                                                   value="On"></div>
                                                    </td>
                                                    <td>
                                                        <div align="center"><input name="mpay_delete" type="checkbox"
                                                                                   value="On"></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="30">7</td>
                                                    <td width="450">Payment <input type="hidden" name="payment"
                                                                                   value="Payment"/></td>
                                                    <td>
                                                        <div align="center"><input name="payment_create" type="checkbox"
                                                                                   value="On"></div>
                                                    </td>
                                                    <td>
                                                        <div align="center"><input name="payment_read" type="checkbox"
                                                                                   value="On"></div>
                                                    </td>
                                                    <td>
                                                        <div align="center"><input name="payment_update" type="checkbox"
                                                                                   value="On"></div>
                                                    </td>
                                                    <td>
                                                        <div align="center"><input name="payment_delete" type="checkbox"
                                                                                   value="On"></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="30">8</td>
                                                    <td width="450">Employee Details <input type="hidden" name="emp_d"
                                                                                            value="Employee Details"/>
                                                    </td>
                                                    <td>
                                                        <div align="center"><input name="emp_create" type="checkbox"
                                                                                   value="On"></div>
                                                    </td>
                                                    <td>
                                                        <div align="center"><input name="emp_read" type="checkbox"
                                                                                   value="On"></div>
                                                    </td>
                                                    <td>
                                                        <div align="center"><input name="emp_update" type="checkbox"
                                                                                   value="On"></div>
                                                    </td>
                                                    <td>
                                                        <div align="center"><input name="emp_delete" type="checkbox"
                                                                                   value="On"></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="30">9</td>
                                                    <td width="450">Module Permission <input type="hidden"
                                                                                             name="permission_m"
                                                                                             value="Module Permission"/>
                                                    </td>
                                                    <td>
                                                        <div align="center"><input name="permission_create"
                                                                                   type="checkbox" value="On"></div>
                                                    </td>
                                                    <td>
                                                        <div align="center"><input name="permission_read"
                                                                                   type="checkbox" value="On"></div>
                                                    </td>
                                                    <td>
                                                        <div align="center"><input name="permission_update"
                                                                                   type="checkbox" value="On"></div>
                                                    </td>
                                                    <td>
                                                        <div align="center"><input name="permission_delete"
                                                                                   type="checkbox" value="On"></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="30">10</td>
                                                    <td width="450">Savings Account <input type="hidden"
                                                                                           name="savings_t"
                                                                                           value="Savings Account"/>
                                                    </td>
                                                    <td>
                                                        <div align="center"><input name="savings_create" type="checkbox"
                                                                                   value="On"></div>
                                                    </td>
                                                    <td>
                                                        <div align="center"><input name="savings_read" type="checkbox"
                                                                                   value="On"></div>
                                                    </td>
                                                    <td>
                                                        <div align="center"><input name="savings_update" type="checkbox"
                                                                                   value="On"></div>
                                                    </td>
                                                    <td>
                                                        <div align="center"><input name="savings_delete" type="checkbox"
                                                                                   value="On"></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="30">11</td>
                                                    <td width="450">General Settings <input type="hidden"
                                                                                            name="general_t"
                                                                                            value="General Settings"/>
                                                    </td>
                                                    <td>
                                                        <div align="center"><input name="general_create" type="checkbox"
                                                                                   value="On"></div>
                                                    </td>
                                                    <td>
                                                        <div align="center"><input name="general_read" type="checkbox"
                                                                                   value="On"></div>
                                                    </td>
                                                    <td>
                                                        <div align="center"><input name="general_update" type="checkbox"
                                                                                   value="On"></div>
                                                    </td>
                                                    <td>
                                                        <div align="center"><input name="general_delete" type="checkbox"
                                                                                   value="On"></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="30">12</td>
                                                    <td width="450">Loans Approval <input type="hidden"
                                                                                          name="approver_t"
                                                                                          value="Loans Approval"/>
                                                    </td>
                                                    <td>
                                                        <div align="center"><input name="approver_create"
                                                                                   type="checkbox"
                                                                                   value="On"></div>
                                                    </td>
                                                    <td>

                                                    </td>
                                                    <td>

                                                    </td>
                                                    <td>

                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="30">13</td>
                                                    <td width="450">Reports <input type="hidden"
                                                                                   name="Reports_t"
                                                                                   value="Reports"/>
                                                    </td>
                                                    <td>
                                                        <div align="center"><input name="Reports_create" type="checkbox"
                                                                                   value="On"></div>
                                                    </td>
                                                    <td>
                                                        <div align="center"><input name="Reports_read" type="checkbox"
                                                                                   value="On"></div>
                                                    </td>
                                                    <td>
                                                        <div align="center"><input name="Reports_update" type="checkbox"
                                                                                   value="On"></div>
                                                    </td>
                                                    <td>
                                                        <div align="center"><input name="Reports_delete" type="checkbox"
                                                                                   value="On"></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="30">14</td>
                                                    <td width="450">Accounting <input type="hidden"
                                                                                      name="Accounting_t"
                                                                                      value="Accounting"/>
                                                    </td>
                                                    <td>
                                                        <div align="center"><input name="Accounting_create"
                                                                                   type="checkbox"
                                                                                   value="On"></div>
                                                    </td>
                                                    <td>
                                                        <div align="center"><input name="Accounting_read"
                                                                                   type="checkbox"
                                                                                   value="On"></div>
                                                    </td>
                                                    <td>
                                                        <div align="center"><input name="Accounting_update"
                                                                                   type="checkbox"
                                                                                   value="On"></div>
                                                    </td>
                                                    <td>
                                                        <div align="center"><input name="Accounting_delete"
                                                                                   type="checkbox"
                                                                                   value="On"></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="30">15</td>
                                                    <td width="450">Scheduler <input type="hidden"
                                                                                     name="Scheduler_t"
                                                                                     value="Scheduler"/>
                                                    </td>
                                                    <td>
                                                        <div align="center"><input name="Scheduler_create"
                                                                                   type="checkbox"
                                                                                   value="On"></div>
                                                    </td>
                                                    <td>
                                                        <div align="center"><input name="Scheduler_read" type="checkbox"
                                                                                   value="On"></div>
                                                    </td>
                                                    <td>
                                                        <div align="center"><input name="Scheduler_update"
                                                                                   type="checkbox"
                                                                                   value="On"></div>
                                                    </td>
                                                    <td>
                                                        <div align="center"><input name="Scheduler_delete"
                                                                                   type="checkbox"
                                                                                   value="On"></div>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>


                                            <div align="center">
                                                <div class="box-footer">
                                                    <button type="submit" class="btn btn-info btn-flat" name="save"><i
                                                                class="fa fa-save">&nbsp;Save Module</i></button>
                                                </div>
                                            </div>

                                        </div>
                            </form>
                    </div>

                </div>
            </div>

        </div>


</div>