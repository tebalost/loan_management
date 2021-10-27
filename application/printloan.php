<?php include "../config/session.php"; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        table th, table td {
            padding: 6px; /* Apply cell padding */
        }
    </style>
    <style type="text/css" media="print">
        @page {
            size: landscape;
        }
    </style>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <style>
        #customers {
            font-family: "Tahoma", Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        body {
            font-family: "Tahoma", Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        #customers td, #customers th {
            border: 1px solid #000000;
            padding: 0px;
        }

        #customers tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        #customers tr:hover {
            background-color: #ddd;
        }

        #customers th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
        }
    </style>
    <?php
    $call = mysqli_query($link, "SELECT * FROM systemset");
    if (mysqli_num_rows($call) == 0) {
        echo "<script>alert('Data Not Found!'); </script>";
    } else {
        while ($row = mysqli_fetch_assoc($call)) {
            ?>

            <link href="../img/<?php echo $row['image']; ?>" rel="icon" type="dist/img">
        <?php }
    } ?>
    <?php
    $call = mysqli_query($link, "SELECT * FROM systemset");
    while ($row = mysqli_fetch_assoc($call)) {
        ?>
        <title><?php echo $row ['title'] ?></title>
    <?php } ?>


</head>
<body onLoad="window.print();">

<div class="wrapper container">
    <!-- Main content -->
    <br>
    <?php
    $result = mysqli_query($link, "select * from systemset") or die(mysqli_error($link));
    include "printingHeader.php";
    while ($row = mysqli_fetch_array($result)) {
        ?>

    <?php } ?>
    <section class="invoice">
        <!-- title row -->
        <div class="row">
            <div class="col-xs-12">
                <h2 class="page-header">
                    <?php
                    $date1 = explode(">",base64_decode($_GET['printReq']))[0];
                    $date2 = explode(">",base64_decode($_GET['printReq']))[1];
                    $sql = "SELECT * FROM systemset";
                    $result = mysqli_query($link, $sql);
                    while ($row = mysqli_fetch_array($result))
                    {
                    ?>
                    <!-- <div style="color:#000000">
                        <div style="font-size:20px">
                            <div align="center"></div>
                        </div>
                    </div> -->
                    <small class="pull-left">
                        <div style="color:#000000">Loans details (<?php echo "$date1 - $date2" ?>)</div>
                    </small>
                    <small class="pull-right">
                        <div style="color:#009900"><?php $today = date('y:m:d');
                            $new = date('l, F d, Y', strtotime($today));
                            echo $new; ?></div>
                    </small>
                </h2>
                <?php
                }
                ?>
            </div>
            <!-- /.col -->
        </div>
        <!-- info row -->


        <table class="table-resposive" id="customers" width="100%">

            <thead>
            <tr>
                <th></th>
                <th>Customer</th>
                <th>Account</th>
                <th>Principal</th>
                <th>Instalment</th>
                <th>Expected</th>
                <th>Paid</th>
                <th>Balance</th>
                <th>Last Payment Date</th>
                <th>Arrears Months</th>
                <th>Release Date</th>
                <th>First Instalment Date</th>
                <th>Loan Status</th>
            </tr>
            </thead>
            <tbody>
            <?php

            $arrears=$totalPayments=$principal=$expected=$totalRemaining=$arrearsAmount=$count=0;
            $select = mysqli_query($link, "SELECT * FROM loan_info where status in ('','P') and date_release between '$date1' and '$date2'") or die (mysqli_error($link));
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
                    $id = $row['id'];
                    $borrower = $row['borrower'];
                    $account = $row['baccount'];
                    $count+=1;
                    //Check if the current loan is overdue or missed payment
                    $selectOverDueLoans = mysqli_query($link, "select * from loan_info where id in (SELECT get_id FROM pay_schedule where schedule<'$today' and payment<>balance  and get_id='$id') and status=''") or die (mysqli_error($link));



                    //Get Max Payment
                    $maxDay = mysqli_fetch_assoc(mysqli_query($link, "select max(pay_date), sum(amount_to_pay) from payments where account='$account'"));
                    $remainingBalance = $row['balance'] - $maxDay['sum(amount_to_pay)'];
                    if ($remainingBalance > 0) {
                        $balanceType = "Debit";
                    } else {
                        $balanceType = "Credit";
                    }

                    //Get Months in Arrears//Number of months based on Last Payment Made --- Date Diff
                    $lastDay = substr($maxDay['max(pay_date)'], 0, 10);
                    if ($lastDay == "") {
                        $lastDay = substr($row['application_date'], 0, 10);
                    }
                    $today = date('Y-m-d');

                    $strJsonFileContents = file_get_contents('include/packages.json');
                    $arrayOfTypes = json_decode($strJsonFileContents, true);
                    $status = $row['status'];
                    foreach ($arrayOfTypes['accountStatusCodes'] as $key => $value) {
                        if ($status == $key) {
                            $status = $value;
                        }
                        else if($status=="" && mysqli_num_rows($selectOverDueLoans)>0){
                            $status = "Missed Payment";
                            //Get how many are overdue with
                            $numDays=mysqli_fetch_assoc(mysqli_query($link,"SELECT datediff(NOW(),schedule) FROM pay_schedule where schedule<'$today' and payment<>balance  and get_id='$id'"));
                            $overdueDays=$numDays['datediff(NOW(),schedule)'];
                        }
                        else if ($status == "") {
                            $status = "Active";
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

                    ///Add More Checks to Verify Completion of the Loan
                    /// collateral
                    ///attachement
                    //fin_info
                    ?>

                    <tr>
                        <td><?php echo $count; ?></td>
                        <td><?php echo $name . "&nbsp;" . $lname; ?></td>
                        <td><?php echo $row['baccount']; ?></td>
                        <td align="right">
                            <?php
                            echo number_format($row['amount'], 2, ".", ",");
                            $principal+=$row['amount'];
                            ?>
                        </td>
                        <td align="right">
                            <?php echo number_format($row['amount_topay'], 2, ".", ","); ?>
                        </td>
                        <td align="right">
                            <?php echo number_format($row['balance'], 2, ".", ",");
                            $expected+=$row['balance'];
                            ?>
                        </td>
                        <td align="right">
                            <?php
                            echo number_format($maxDay['sum(amount_to_pay)'], '2', '.', ',');
                            $totalPayments+=$maxDay['sum(amount_to_pay)'];
                            ?>
                        </td>
                        <td align="right">
                            <?php

                            echo number_format($remainingBalance, '2', '.', ',') . substr($balanceType, 0, 1);
                            $totalRemaining+=$remainingBalance;
                            ?>
                        </td>
                        <td>
                            <?php
                            $date = date_create($lastDay);
                            echo date_format($date, "d/m/Y");
                            ?>
                        </td>
                        <td style="text-align: center">
                            <?php
                            $arrearsMonths = dateDifference($lastDay, $today, $differenceFormat = '%m');
                            if($status=="Missed Payment"){
                                echo $overdueDays." days";
                                $arrears+=1;
                                $overdueAmount=mysqli_fetch_assoc(mysqli_query($link,"select sum(balance)-sum(payment) from pay_schedule where schedule<'$today' and get_id='$id'"));
                                $arrearsAmount=$overdueAmount['sum(balance)-sum(payment)'];
                            }else {
                                echo "Up to date";
                            }


                        ?>
                        </td>
                        <td align="center">
                            <?php
                            $date = date_create($row['date_release']);
                            echo date_format($date, "d/m/Y");
                            ?>
                        </td>
                        <td align="center">
                            <?php
                            $date = date_create($row['pay_date']);
                            echo date_format($date, "d/m/Y");
                            ?>
                        </td>
                        <td>
                            <?php echo $status; ?>
                        </td>

                    </tr>
                    <?php
                }
            } ?>
            </tbody>
        </table><br>
        <table width="30%">
            <tr><th>Loans in Arrears:</th> <td><?php echo $arrears; ?></td></tr>
            <tr><th>Amount in Arrears:</th> <td><?php echo number_format($arrearsAmount,'2','.',','); ?></td></tr>
            <tr><th>Total Principal Issued: </th> <td><?php echo number_format($principal,'2','.',','); ?></td></tr>
            <tr><th>Total Expected: </th> <td><?php echo number_format($expected,'2','.',','); ?></td></tr>
            <tr><th>All Payments Total: </th> <td><?php echo number_format($totalPayments,'2','.',','); ?></td></tr>
            <tr><th>Balance Remaining: </th> <td><?php echo number_format($totalRemaining,'2','.',','); ?></td></tr>
        </table>
</div>