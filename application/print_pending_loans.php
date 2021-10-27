<?php include "../config/session.php"; ?>  
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <?php 
      $call = mysqli_query($link, "SELECT * FROM systemset");
      if(mysqli_num_rows($call) == 0)
      {
         echo "<script>alert('Data Not Found!'); </script>";
      }
      else
      {
         while($row = mysqli_fetch_assoc($call)){
   ?>

   <link href="../img/<?php echo $row['image']; ?>" rel="icon" type="dist/img">
   <?php }}?>
   <?php 
      $call = mysqli_query($link, "SELECT * FROM systemset");
      while($row = mysqli_fetch_assoc($call)){
      ?>
   <title><?php echo $row ['title']?></title>
   <?php }?>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
  <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
    <strong> <link rel="stylesheet" href="../plugins/datatables/dataTables.bootstrap.css"></strong>

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
    <body onLoad="window.print();">
    <?php
    $getCompanyInfo  = mysqli_query($link, "SELECT * FROM systemset") or die(mysqli_error($link));
    $companyInfo = mysqli_fetch_assoc($getCompanyInfo);
    ?>
    <div class="container-fluid">
        <div class="row">
            <?php include "printingHeader.php"; ?>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Type</th>
                            <th>Account</th>
                            <th>Principal Amount</th>
                            <th>Instalment</th>
                            <th>Agent</th>
                            <th>Release Date</th>
                            <th>Payment Date</th>
                            <th>Loan Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $select = mysqli_query($link, "SELECT * FROM loan_info where status = 'Pending'") or die (mysqli_error($link));
                        if (mysqli_num_rows($select) == 0) {
                            echo '<div class="alert alert-info">
                                <a href = "#" class = "close" data-dismiss= "alert"> &times;</a>
                            No data found yet!.....Check back later!!</div>';
                        } else {
                            while ($row = mysqli_fetch_array($select)) {
                                $id = $row['id'];
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
                                <?php
                                if ($upstatus == "Pending") {
                                    ?>
                                    <tr>
                                        <td><?php echo $name . "&nbsp;" . $lname; ?></td>
                                        <td><?php
                                            if($row['loan_product']){
                                                echo $row['loan_product'];
                                            }
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
                                        <td><?php echo $row['pay_date']; ?></td>
                                        <td>
                                            <!--                                                    <span class="label label--->
                                            <?php //if($status =='Active')echo 'success'; elseif($status =='Disapproved')echo 'danger'; else echo 'warning';
                                            ?><!--">--><?php //echo $status;
                                            ?><!--</span>-->

                                            <span class="label label-<?php if ($status == 'Active') echo 'success'; elseif ($status == 'Disapproved') echo 'danger'; ?>"><?php echo $status; ?></span>
                                            <?php if ($status == "Pending") { ?>
                                                <?php
                                                //Registration - Active
                                                $regStatus = mysqli_query($link, "select * from borrowers where id='$borrower' and status='Active'");
                                                //Loan Status - Open and Active
                                                $loanStatus = mysqli_query($link, "select * from loan_info where borrower='$borrower' and id='$id' and  status='Pending'");
                                                ?>
                                                <?php if (mysqli_num_rows($regStatus) == 0 && mysqli_num_rows($loanStatus) == 1) { ?>


                                                    <div class="box box-default collapsed-box">
                                                        <div class="box-header with-border">
                                                            Incomplete Requirement
                                                            <div class="box-tools pull-right">
                                                                <button type="button" class="btn btn-box-tool"
                                                                        data-widget="collapse"><i
                                                                            class="fa fa-plus"></i>
                                                                </button>
                                                            </div>
                                                            <!-- /.box-tools -->
                                                        </div>
                                                        <!-- /.box-header -->
                                                        <?php if (mysqli_num_rows($regStatus) == "0") { ?>
                                                            <div class="box-body">
                                                                <?php echo ($pupdate == '1') ? '<a href="updateborrowers.php?document=&id=' . $borrower . '&&mid=' . base64_encode("403") . '" >Complete Details</a>' : ''; ?>
                                                            </div>
                                                        <?php } ?>

                                                        <?php
                                                        if (mysqli_num_rows($loanStatus) == 1) {
                                                            $getCollateral = mysqli_query($link, "select * from loan_settings where collateral='chkYes'");
                                                            if (mysqli_num_rows($getCollateral) > 0) {
                                                                $search = mysqli_query($link, "SELECT * FROM collateral WHERE idm = '$borrower' and loan='$id'") or die (mysqli_error($link));

                                                                $collateralComplete = mysqli_num_rows($search);
                                                                if (mysqli_num_rows($search) == 0) {
                                                                    ?>
                                                                    <div class="box-body">
                                                                        <?php echo ($pupdate == '1') ? '<a href="viewborrowersloan.php?loanId=' . $id . '&&id=' . $borrower . '&&mid=' . base64_encode("403") . '" >Complete Collateral</a>' : ''; ?>
                                                                    </div>
                                                                <?php }
                                                            }
                                                        }
                                                        ?>
                                                    </div>
                                                    <!-- /.box -->


                                                    <?php ?>
                                                <?php } else {
                                                    echo "<p>Pending</p>";
                                                }
                                            }
                                            ?>
                                        </td>
                                        

                                    </tr>
                                    <?php
                                } else {
                                    ?>
                                    <tr>

                                        <td><?php echo $name . "&nbsp;" . $lname; ?></td>
                                        <td><?php
                                            echo $row['loan_product'];
                                            ?></td>
                                        <td><?php echo $row['baccount']; ?></td>
                                        <td align="right"><?php echo "<b>" . $_SESSION['currency'] . "&nbsp;" . number_format($row['amount'], 2, ".", ",") . "</b>"; ?></td>
                                        <td align="right"><?php echo "<b>" . $_SESSION['currency'] . "&nbsp;" . number_format($row['amount_topay'], 2, ".", ",") . "</b>"; ?></td>
                                        <td><?php echo $row['teller']; ?></td>
                                        <td><?php echo $row['date_release']; ?></td>
                                        <td><?php echo $row['pay_date']; ?></td>
                                        <td>
                                            <span class="label label-<?php if ($status == 'Active') echo 'success'; elseif ($status == 'Disapproved') echo 'danger'; ?>"><?php echo $status; ?></span>
                                            <?php if ($status == "Pending") { ?>
                                                <?php
                                                //Registration - Active
                                                $regStatus = mysqli_query($link, "select * from borrowers where id='$borrower' and status='Active'");
                                                //Loan Status - Open and Active
                                                $loanStatus = mysqli_query($link, "select * from loan_info where borrower='$borrower' and id='$id' and  status='Pending'");
                                                ?>
                                                <?php if (mysqli_num_rows($regStatus) == 0 && mysqli_num_rows($loanStatus) == 1) { ?>


                                                    <div class="box box-default collapsed-box">
                                                        <div class="box-header with-border">
                                                            Incomplete
                                                            <div class="box-tools pull-right">
                                                                <button type="button" class="btn btn-box-tool"
                                                                        data-widget="collapse"><i
                                                                            class="fa fa-plus"></i>
                                                                </button>
                                                            </div>
                                                            <!-- /.box-tools -->
                                                        </div>
                                                        <!-- /.box-header -->
                                                        <?php if (mysqli_num_rows($regStatus) == "0") { ?>
                                                            <div class="box-body">
                                                                <?php echo ($pupdate == '1') ? '<a href="updateborrowers.php?document=&id=' . $borrower . '&&mid=' . base64_encode("403") .'&product='.$loan_product.'" >Complete Details!</a>' : ''; ?>
                                                            </div>
                                                        <?php } ?>

                                                        <?php
                                                        if (mysqli_num_rows($loanStatus) == 1) {
                                                            $getCollateral = mysqli_query($link, "select * from loan_settings where collateral='chkYes'");
                                                            if (mysqli_num_rows($getCollateral) > 0) {
                                                                $search = mysqli_query($link, "SELECT * FROM collateral WHERE idm = '$borrower' and loan='$id'") or die (mysqli_error($link));

                                                                $collateralComplete = mysqli_num_rows($search);
                                                                if (mysqli_num_rows($search) == 0) {
                                                                    ?>
                                                                    <div class="box-body">
                                                                        <?php echo ($pupdate == '1') ? '<a href="viewborrowersloan.php?loanId=' . $id . '&&id=' . $borrower . '&&mid=' . base64_encode("403") . '" >Complete Collateral</a>' : ''; ?>
                                                                    </div>
                                                                <?php }
                                                            }
                                                        }
                                                        ?>
                                                    </div>
                                                    <!-- /.box -->


                                                    <?php ?>
                                                <?php } else {
                                                    echo "Pending";
                                                }
                                            }
                                            ?>
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
</body>
</html>