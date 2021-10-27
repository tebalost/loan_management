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
  <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="../plugins/datatables/dataTables.bootstrap.css">


  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- font awesone cdn -->
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="custom/style.css">
  <style type="text/css" media="print">
      @media print{@page {size: landscape}}
  </style>
  <![endif]-->
</head>

    <?php
    $getCompanyInfo  = mysqli_query($link, "SELECT * FROM systemset") or die(mysqli_error($link));
    $companyInfo = mysqli_fetch_assoc($getCompanyInfo);
    ?>
    <div class="container-fluid">
        <?php include "printingHeader.php"; ?>
        <div class="row">

            <div class="col-xs-12">
                <div class="text-right">
                    <div class="noprint">
                        <a href="" class="btn btn-warning" id="back"><i class="fa fa-mail-reply-all">&nbsp;&nbsp;</i>Back</a>
                        <button class="btn btn-success print" id="print"><i class="fa fa-print">&nbsp;&nbsp;</i>Print</button>
                    </div>
                </div>
                <h4 align="center"><strong>Pre-Disbursement Report <?php echo date('d/m/Y'); ?></strong></h4><hr>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Loan Account</th>
                            <th>Amount</th>
                            <th>Instalment</th>
                            <th>Agent</th>
                            <th>Release Date</th>
                            <th>Method</th>
                            <th>Account</th>
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
                            $total=0;
                            while ($row = mysqli_fetch_array($select)) {
                                $id = $row['id'];
                                $date=date_create($row['date_release']);
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

                                    <td><?php echo $row['baccount']; ?></td>
                                    <td align="right">
                                        <b><?php echo $_SESSION['currency'] . "&nbsp;" . number_format($row['amount'], 2, ".", ","); ?></b>
                                    </td>
                                    <td align="right">
                                        <b><?php echo $_SESSION['currency'] . "&nbsp;" . number_format($row['amount_topay'], 2, ".", ","); ?></b>
                                    </td>
                                    <td><?php echo $row['teller']; ?></td>
                                    <td><?php echo date_format($date,"d/m/Y"); ?></td>
                                    <td><?php echo $row['loan_disbursed_by_id'];?></td>
                                    <td><?php echo $bankDetails['accountNumber']." - ".str_replace("_"," ",$bankDetails['bankName']);?></td>

                                </tr>
                                <?php
                            $total+=$row['amount'];
                            }
                            }
                        ?>
                    </tbody>
                </table>
                <?php
                $selectMethods = mysqli_query($link, "SELECT loan_disbursed_by_id, sum(amount) FROM loan_info where status = 'Pending Disbursement' group by loan_disbursed_by_id") or die (mysqli_error($link));
                while($row = mysqli_fetch_assoc($selectMethods)){
                    echo"<h4><div class=\"col-xs-3\">".$row['loan_disbursed_by_id']."</div><div class=\"col-xs-3\" align='right'>M ".number_format($row['sum(amount)'], 2, ".", ",")."</div></h4><br>";
                }
                ?>
                <br>
                <h4 align="left"><div class="col-xs-3"><strong>Total to be Disbursed: </strong></div><div class="col-xs-3" align='right'><strong>M <?php echo number_format($total, 2, ".", ","); ?></strong></div></h4>
                <div class="col-xs-12"><hr></div>
            </div>
        </div>
        </div>
</body>
<script type="text/javascript">
    //back
    $("#back").on("click", function(e){
        e.preventDefault();
        window.history.back();
    });

    // printing
    $(document).ready(()=>{
        $('.print').click(()=>{
            window.print();
        });
        console.log('the button clicked');
    });
</script>
</html>