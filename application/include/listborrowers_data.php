<style>
    th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: #D1F9FF;
    }
</style>
<div class="row">

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.6/css/responsive.bootstrap4.min.css">

    <section class="content">
        <div class="box box-success">
            <div class="box-body">
                <div class="table-responsive table-bordered dt-responsive nowrap">
                    <div class="box-body">
                        <form method="post">
                            <a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>">
                                <button type="button" class="btn btn-flat btn-warning"><i
                                            class="fa fa-mail-reply-all"></i>&nbsp;Back
                                </button>
                            </a>
                            
                            <?php
                            $check = mysqli_query($link, "SELECT * FROM emp_permission WHERE tid = '" . $_SESSION['tid'] . "' AND module_name = 'Borrower Details'") or die ("Error" . mysqli_error($link));
                            while ($get_check = mysqli_fetch_array($check)) {
                                $pdelete = $get_check['pdelete'];
                                $pcreate = $get_check['pcreate'];
                                ?>
                                <?php echo ($pdelete == '1') ? '<button type="submit" class="btn btn-flat btn-danger" name="delete"><i class="fa fa-times"></i>&nbsp;Deactivate</button>' : ''; ?>
                                <?php echo ($pcreate == '1') ? '<a href="newborrowers.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("403") . '"><button type="button" class="btn btn-flat btn-success"><i class="fa fa-plus"></i>&nbsp;Add Borrower</button></a>' : ''; ?>
                            <?php } ?>

                            <?php
                            $check = mysqli_query($link, "SELECT * FROM emp_permission WHERE tid = '" . $_SESSION['tid'] . "' AND module_name = 'Internal Message'") or die ("Error" . mysqli_error($link));
                            while ($get_check = mysqli_fetch_array($check)) {
                                $pcreate = $get_check['pcreate'];
                                ?>
                                <?php echo ($pcreate == '1') ? '<a href="send_smsloan.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("406") . '"><button type="button" class="btn btn-flat btn-info"><i class="fa fa-envelope"></i>&nbsp;Send SMS</button></a>' : ''; ?>
                            <?php } ?>
                            <a href="printborrow.php" target="_blank" class="btn btn-primary btn-flat"><i
                                        class="fa fa-print"></i>&nbsp;Print</a>
                            <a href="borrowexcel.php" target="_blank" class="btn btn-success btn-flat"><i
                                        class="fa fa-send"></i>&nbsp;Export Excel</a>
                            <a href="pdfborrow.php" target="_blank" class="btn btn-info btn-flat"><i
                                        class="fa fa-file-pdf-o"></i>&nbsp;Export PDF</a>

                            <hr>
                            <?php
                            if (isset($_POST['delete'])) {
                                $idm = $_GET['id'];
                                $id = $_POST['selector'];
                                $N = count($id);
                                if ($id == '') {
                                    echo '<div class="alert alert-danger" >
                                         <a href = "#" class = "close" data-dismiss= "alert"> &times;</a>
                                          No rows Selected!!!&nbsp; &nbsp;&nbsp;
                                            </div>';
                                } else {
                                    for ($i = 0; $i < $N; $i++) {
                                        $result = mysqli_query($link, "update borrowers set status='Deactivated' WHERE id ='$id[$i]'");

                                    }
                                    echo '<div class="alert alert-success" >
                                        <a href = "#" class = "close" data-dismiss= "alert"> &times;</a>
                                         Borrowers Updated Successfully!!!&nbsp; &nbsp;&nbsp;
                                           </div>';
                                }
                            }
                            ?>
                            <h4 style="color: green;"><b>Borrowers</b></h4><hr>

                            <table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                                <thead>
                                <tr>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Email</th>
                                    <th>Mobile Number</th>
                                    <th>Telephone</th>
                                    <th align="center"><b>Reg. Status</b></th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if(!isset($_GET['act'])) {
                                    $select = mysqli_query($link, "SELECT * FROM borrowers  where status = 'Active'") or die (mysqli_error($link));
                                }else{
                                    $select = mysqli_query($link, "SELECT * FROM borrowers where status = 'Partial'") or die (mysqli_error($link));
                                }

                                if (mysqli_num_rows($select) == 0) {
                                    echo "<div class='alert alert-info'>No data found yet!.....Check back later!!</div>";
                                } else {
                                    while ($row = mysqli_fetch_array($select)) {
                                        $id = $row['id'];
                                        $lname = $row['lname'];
                                        $fname = $row['fname'];
                                        $email = $row['email'];
                                        $phone = $row['phone'];
                                        $status = $row['status'];
//$image = $row['image'];
                                        $check = mysqli_query($link, "SELECT * FROM emp_permission WHERE tid = '" . $_SESSION['tid'] . "' AND module_name = 'Borrower Details'") or die ("Error" . mysqli_error($link));
                                        $get_check = mysqli_fetch_array($check);
                                        $pupdate = $get_check['pupdate'];
                                        $pread = $get_check['pread'];
                                        ?>
                                        <tr>
                                            <td><?php echo $fname; ?></td>
                                            <td><?php echo $lname; ?></td>
                                            <td><?php echo $email; ?></td>
                                            <td align="right"><?php echo $phone; ?></td>
                                            <td align="right"><?php echo $row['telephone']; ?></td>
                                            <?php
                                            if ($status == "Pending" || $status == "Partial") {
                                                ?>
                                                <td align="left" >
                                                   <?php echo ($pupdate == '1') ? '<a href="updateborrowers.php?id=' . $id . '&&mid=' . base64_encode("403") . '&&document="><span class="label label-danger">Complete registration</span></a>' : ''; ?><br>


                                                    </td>
                                                    <?php
                                                }
                                            else if ($status == "Deactivated") {
                                                ?>
                                                <td align="left" >
                                                    <?php echo ($pupdate == '1') ? '<a href="#?id=' . $id . '&&mid=' . base64_encode("403") . '" ><span class="label label-danger">In-Active</span></a>' : ''; ?>
                                                </td>
                                                <?php
                                            }
                                            else {
                                                    ?>
                                                    <td align="left"><span
                                                                class="label label-success"><?php echo $status; ?></span>

                                                    </td>
                                            <?php } ?>
                                            <td align="center">


                                                <?php echo ($pupdate == '1') ? '<a href="updateborrowers.php?id=' . $id . '&&mid=' . base64_encode("403") . '&&document="><i class="fa fa-pencil"></i></a>' : ''; ?>&nbsp;
                                                <a href="viewborrowers.php?id=<?php echo $id . "&&mid=" . base64_encode("403");?>"><i class="fa fa-eye"></i></a>&nbsp;
                                                <?php
                                                $se = mysqli_query($link, "SELECT * FROM battachment WHERE get_id = '$id'") or die (mysqli_error($link));
                                                if(mysqli_num_rows($se)>0) {
                                                    ?>
                                                    <?php echo ($pread == '1') ? '<a href="updateborrowers.php?id=' . $id . '&&mid=' . base64_encode("403") . '&&document=download"><i class="fa fa-download"></i>&nbsp;</a>' : ''; ?>
                                                <?php } ?>

                                            </td>
                                        </tr>
                                    <?php }
                                } ?>
                                </tbody>
                            </table>


                            <script type="text/javascript" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
                            <script type="text/javascript" src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
                            <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.6/js/dataTables.responsive.min.js"></script>
                            <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.6/js/responsive.bootstrap4.min.js"></script>
                            <script>
                                $(document).ready(function() {
                                    var table = $('#example').DataTable( {
                                        responsive: true
                                    } );

                                    new $.fn.dataTable.FixedHeader( table );
                                } );
                            </script>
                        </form>


                    </div>


                </div>
            </div>
        </div>
</div>