<?php
require('../config/connect.php');
$getCompanyInfo = mysqli_query($link, "SELECT * FROM systemset") or die(mysqli_error($link));
$companyInfo = mysqli_fetch_assoc($getCompanyInfo);

$id = $_GET['id'];
$loanId = $_GET['loanId'];
$client = mysqli_query($link, "SELECT * FROM borrowers WHERE id='$id'") or die (mysqli_error($link));
$clientinfo = mysqli_fetch_assoc($client);

$loan = mysqli_query($link, "SELECT * FROM loan_info WHERE borrower='$id' and id='$loanId'");
$loaninfo = mysqli_fetch_assoc($loan);
$account = $loaninfo['baccount'];
$loan_duration = $loaninfo['loan_duration'];
$expectedBalance = $loaninfo['balance'];
$borrower = $clientinfo['id'];
$principal = $loaninfo['amount'];

$totalPayments = mysqli_fetch_assoc(mysqli_query($link, "SELECT sum(amount_to_pay) FROM payments WHERE customer = '$borrower' and account='$account'"));
$totalPaid = $totalPayments['sum(amount_to_pay)'];
$outstanding = $expectedBalance - $totalPaid;
?>


<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="https://x.loandisk.com/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="https://x.loandisk.com/dist/css/skins/skin-blue.min.css">
    <link rel="stylesheet" href="https://x.loandisk.com/plugins/timepicker/bootstrap-timepicker.min.css">
    <link rel="stylesheet" href="https://x.loandisk.com/plugins/select2/select2_new.min.css">
    <link rel="stylesheet" href="https://x.loandisk.com/dist/css/AdminLTE.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<!-- custom style for printing -->
    <link rel="stylesheet" href="custom/style.css">
    <![endif]-->
    <link rel="stylesheet" href="https://x.loandisk.com/css/style_new.css">
    <link rel="stylesheet" href="https://x.loandisk.com/css/billing_plans.css">
    <script src="https://x.loandisk.com/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <script src="https://x.loandisk.com/dist/js/jquery-confirm.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://x.loandisk.com/dist/css/jquery-confirm.min.css">
    <link rel="stylesheet" type="text/css" href="https://x.loandisk.com/css/jquery.datepick.css">
    <script type="text/javascript" src="https://x.loandisk.com/include/js/jquery.plugin.js"></script>
    <script type="text/javascript" src="https://x.loandisk.com/include/js/jquery.datepick.min.js"></script>
    <script type="text/javascript" src="https://x.loandisk.com/include/js/jquery.numeric.js"></script>
    <style type="text/css" media="print">
        @page {
            size: auto;   /* auto is the initial value */
            margin: 0mm;  /* this affects the margin in the printer settings */
        }

        html {
            background-color: #FFFFFF;
            margin: 0px; /* this affects the margin on the html before sending to printer */
        }

        body {
            margin: 10mm 10mm 10mm 10mm; /* margin you want for the content */
        }
    </style>
    <link rel="stylesheet" type="text/css"
          href="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.10.21/b-1.6.3/b-flash-1.6.3/b-html5-1.6.3/b-print-1.6.3/fh-3.1.7/r-2.2.5/sc-2.0.2/datatables.min.css"/>

    <script type="text/javascript"
            src="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.10.21/b-1.6.3/b-flash-1.6.3/b-html5-1.6.3/b-print-1.6.3/fh-3.1.7/r-2.2.5/sc-2.0.2/datatables.min.js"></script>
    <style type="text/css">
        #progress {
            width: 500px;
            border: 1px solid #aaa;
            height: 20px;
        }

        #progress .bar {
            background-color: #ccc;
            height: 20px;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="https://x.loandisk.com/css/perfect-scrollbar.css">
    <link rel="stylesheet" type="text/css" href="https://x.loandisk.com/css/search_new.css">
</head>
<body>
<div class="container-fluid">
    <div class="wrapper">
        <section class="invoice">
        <div class="row">
            <div class="col-xs-4">
                <h5 class="h5 bold">
                    <?php
                    $address = $companyInfo['address'];
                    for ($i = 0; $i < strlen($address); $i++) {
                        echo $address[$i];
                        if ($address[$i] === ',') {
                            echo "<br/>";
                        }

                    } ?>
                </h5>
            </div>

            <div class="col-xs-4 ">
                <img src='<?php echo $companyInfo['image']; ?>' alt="logo"
                    class="img-responsive"/>
            </div>

            <div class="col-xs-4">
                <table class="table-borderless table-responsive">
                    <tr>
                        <th>Email: </th>
                        <td> <?php echo "&nbsp;".$companyInfo['email'] ?> </td>
                    </tr>
                    <tr>
                        <th><label for="">Web: </label></th>
                        <td><?php echo "&nbsp;".$companyInfo['website'] ?> </td>
                    </tr>
                    <tr>
                        <th><label for="">Tel: </label></th>
                        <td> <?php echo "&nbsp;".$companyInfo['mobile'] ?> </td>
                    </tr>
                </table>
            </div>
        </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="text-right">
                <div class="noprint">
                    <button href="" class="btn btn-warning" id="back"><i class="fa fa-mail-reply-all">&nbsp;&nbsp;</i>Back</button>
                    <button class="btn btn-success print" id="print"><i class="fa fa-print">&nbsp;&nbsp;</i>Print</button>
                </div>
            </div>
        </div>
        
        <div class="col-xs-12">
            <div class="text-center">
                <h2 class="page_title_print"> Loan Repayment Schedule </h2>
            </div>
        </div>
        <div class="col-xs-6">
            <div class="text-left">
                <div class="print_h2">
                    <?php
                    if (isset($clientinfo['title']))
                        echo $clientinfo['title'] . "&nbsp";

                    if (isset($clientinfo['fname']))
                        echo $clientinfo['fname'] . "&nbsp";

                    if (isset($clientinfo['lname']))
                        echo $clientinfo['lname'] . "&nbsp";
                    ?>
                </div>
                <?php echo $clientinfo['addrs1'] ?><br>
                <?php echo $clientinfo['addrs2'] ?><br>
                <?php echo $clientinfo['postal'] ?>
                <br>
                <strong>Date:<?php echo date('d/m/Y') ?></strong>
            </div>
        </div><!-- /.col -->
    </div>
    </div><!-- /.row -->
</section>
    </div>

    <div class="wrapper">
    <section class="invoice">
        <h4 class="box_title_print">Loan Terms</h4>
        <div class="row print_box">
            <div class="col-xs-6">
                <strong>Loan #</strong>
                <span class="pull-right"> <?php if (isset($loaninfo['baccount'])) {
                        echo $loaninfo['baccount'];
                    } ?> </span><br>
                <strong>Released Date</strong>
                <span class="pull-right"> <?php if (isset($loaninfo['date_release'])) {
                        echo date("d/m/Y", strtotime($loaninfo['date_release']));
                    } ?> </span><br>
                <strong>Maturity Date</strong>
                <span class="pull-right"><?php if (isset($loaninfo['loan_maturity'])) {
                        echo date("d/m/Y", strtotime($loaninfo['loan_maturity']));
                    } ?></span><br>
                <strong>Repayment Cycle</strong>
                <span class="pull-right"><?php if (isset($loaninfo['loan_payment_scheme'])) {
                        echo $loaninfo['loan_payment_scheme'];
                    } ?></span><br>
                <strong>Loan Amount</strong>
                <span class="pull-right"><?php if (isset($loaninfo['amount'])) {
                        echo number_format($loaninfo['amount'], 2, ".", ",");
                    } ?></span><br>
                <strong>Interest Rate</strong>
                <span class="pull-right"><?php if (isset($loaninfo['loan_interest'])) {
                        echo $loaninfo['loan_interest'];
                    } ?>%</span><br>
            </div><!-- /.col -->
            <div class="col-xs-6">
                <!-- <strong>Penalty Amount</strong>
                <span class="pull-right">0</span><br>  -->
                <strong>Loan Period</strong>
                <span class="pull-right"> <?php if (isset($loaninfo['loan_duration'])) {
                        echo $loaninfo['loan_duration']."&nbsp;".$loaninfo['loan_duration_period'];
                    } ?></span><br>
                <strong>Instalment</strong>
                <span class="pull-right"><?php if (isset($loaninfo['amount_topay'])) {
                        echo number_format($loaninfo['amount_topay'], 2, ".", ",");
                    } ?></span><br>

                <strong>Loan Balance</strong>
                <span class="pull-right"> <?php if (isset($loaninfo['balance'])) {
                        echo number_format($outstanding, 2, ".", ",");
                    } ?> </span><br>
            </div><!-- /.col -->
        </div>
    </section>
</div>

    <div class="wrapper">
        <section class="invoice">
            <div class="row">
                <h4 class="box_title_print">Schedule</h4>
                <div class="col-xs-12 table-responsive">
                    <table id="daily_collections"
                        class="table table-striped table-condensed">
                        <thead>
                        <tr style="background-color: #F2F8FF">
                            <th style="width: 10px">
                                <b>#</b>
                            </th>
                            <th class=""><b>Date</b></th>
                            <th class=""><b>Description</b></th>
                            <th class="text-right"><b>Principal Due</b></th>
                            <th class="text-right"><b>Interest</b></th>
                            <th class="text-right"><b>Fees</b></th>
                            <th class="text-right"><b>Instalment</b></th>
                            <th class="text-right"><b>Paid</b></th>
                            <th class="text-right"><b>Pending Due</b></th>
                            <th class="text-right"><b>Principal Balance</b></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td></td>
                            <td class="text-right">
                            </td>
                            <td class="text-right">
                            </td>
                            <td class="text-right">
                            </td>
                            <td class="text-right">
                            </td>
                            <td class="text-right">
                            </td>
                            <td class="text-right">
                            </td>
                            <td class="text-right">
                            </td>
                            <td class="text-right">
                            </td>
                            <td class="text-right"><?php echo number_format($loaninfo['amount'], 2, ".", ","); ?>
                            </td>
                        </tr>
                        <?php
                        $totalPaid = 0;
                        $selectPayments = mysqli_query($link, "SELECT * FROM payments WHERE customer = '$borrower' and account='$account'") or die (mysqli_error($link));
                        while ($payment = mysqli_fetch_assoc($selectPayments)) {
                            $totalPaid += $payment['amount_to_pay'];
                            ?>
                            <tr>
                                <td class="bg-gray"></td>
                                <th width="10%"
                                    class="bg-gray"><?php echo date("d/m/Y", strtotime(substr($payment['pay_date'], 0, 10))); ?></th>
                                <th width="15%" class="bg-gray">Payment Received</th>
                                <td class="bg-gray"></td>
                                <td class="bg-gray"></td>
                                <td class="bg-gray"></td>
                                <td class="bg-gray"></td>
                                <th class="bg-gray text-right"><?php echo number_format($payment['amount_to_pay'], 2, ".", ","); ?></th>
                                <td class="bg-gray"></td>
                                <td class="bg-gray"></td>
                            </tr>
                        <?php } ?>

                        <?php
                        //Get All Schedules of the loan
                        $count = 1;
                        $principal_total = 0;
                        $interest_total = 0;
                        $due_total = $principalTotalOwing = 0;
                        $fees_total = 0;
                        $monthToDateDueTotal = 0;
                        $getSchedule = mysqli_query($link, "SELECT * FROM pay_schedule where get_id='$loanId'");
                        ?>
                        <?php while ($schedule = mysqli_fetch_assoc($getSchedule)) { ?>

                            <?php

                            if ($count < $loan_duration && $due_total <= $totalPaid) {
                                echo "<tr class=\"success\">";  //class=success for all repayments, get interest from the paid amount
                            } else if ($count < $loan_duration && $due_total > $totalPaid) {
                                echo "<tr class=\"\">";          //class=success for all repayments, get interest from the paid amount
                            } else {
                                echo "<tr class=\"danger\">";
                            }
                            ?>
                            <td><?php echo $count; ?></td>
                            <td><?php echo date("d/m/Y", strtotime($schedule['schedule'])); ?></td>
                            <td class="">
                                <?php
                                if ($count < $loan_duration) {
                                    echo $schedule['pay_type'];
                                } else {
                                    echo $schedule['pay_type'];
                                }
                                ?>
                            </td>
                            <td class="text-right">
                                <?php
                                $principal_due = round($schedule['principal_due'], 2);
                                echo number_format($principal_due, 2, ".", ",");
                                $principal_total += $principal_due;
                                ?>
                            </td>
                            <td class="text-right">
                                <?php
                                $interest_due = round($schedule['interest'], 2);
                                echo number_format($interest_due, 2, ".", ",");
                                $interest_total += $interest_due;
                                ?>
                            </td>
                            <td class="text-right">
                                <?php
                                $fees_due = round($schedule['fees'], 2);
                                echo number_format($fees_due, 2, ".", ",");
                                $fees_total += $fees_due;
                                ?>
                            </td>
                            <td class="text-right">
                                <?php
                                $totalDue = number_format($schedule['balance'], 2, ".", ",");
                                echo $totalDue;
                                $due_total += $schedule['balance'];
                                ?>
                            </td>

                            <td class="text-right">0</td>
                            <td class="text-right">
                                <?php
                                $monthToDateDue = number_format($schedule['total_due'], 2, ".", ",");
                                echo $monthToDateDue;
                                $monthToDateDueTotal += $schedule['total_due'];
                                ?>
                            </td>

                            <td class="text-right">   <?php
                                $principal = $principal - $schedule['principal_due'];
                                echo number_format($principal, 2, ".", ",");
                                ?></td>
                            </tr>
                            <?php
                            $count++;
                        }
                        ?>

                        <tr>
                            <td></td>
                            <td class="">
                            </td>
                            <td class=""><b>Total Due</b>
                            </td>
                            <td class="text-right">
                                <b><?php echo number_format($principal_total, 2, ".", ","); ?></b>
                            </td>

                            <td class="text-right">
                                <b><?php echo number_format($interest_total, 2, ".", ","); ?></b>
                            </td>
                            <td class="text-right">
                                <b><?php echo number_format($fees_total, 2, ".", ","); ?></b>

                            <td class="text-right">
                                <b><?php echo number_format($due_total, 2, ".", ","); ?></b>
                            </td>

                            <td class="text-right"><b>-</b></td>
                            <td class="text-right"></td>
                            <td class="text-right"><b>-</b></td>

                        </tr>

                        <tr>
                            <td></td>
                            <td class=""></td>
                            <td class=""><b>Total Paid</b>
                            </td>
                            <td class="text-right">
                            </td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                            <td class="text-right"><b></b></td>
                            <td class="text-right"><b><?php echo number_format($totalPaid, 2, ".", ","); ?></b>
                            </td>
                            <td class="text-right text-bold"><b></b></td>
                            <td class="text-right"><b></b></td>
                        </tr>

                        <tr>
                            <td></td>
                            <td class=""></td>
                            <td class=""><b>Total Pending Due</b></td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                            <td class="text-right"></td>
                            <td class="text-right"><b></b></td>
                            <td class="text-right text-bold">
                                <b><?php echo number_format($expectedBalance - $totalPaid, 2, ".", ","); ?></b></td>
                            <td class="text-right"><b></b></td>
                            <td class="text-right"><b></b></td>
                        </tr>

                        </tbody>
                    </table>
                    <br><br>
                </div>
            </div>
        </section>
    </div>
</div>
<script>
    $("#pre_loader").hide();

</script>

<!-- REQUIRED JS SCRIPTS -->
<script type="text/javascript">
    $(".numeric").numeric();
    $(".positive").numeric({negative: false});
    $(".positive-integer").numeric({decimal: false, negative: false});
    $(".negative-integer").numeric({decimal: false, negative: true});
    $(".decimal-2-places").numeric({decimalPlaces: 2});
    $(".decimal-4-places").numeric({decimalPlaces: 4});
    $("#remove").click(
        function (e) {
            e.preventDefault();
            $(".numeric,.positive,.positive-integer,.decimal-2-places,.decimal-4-places").removeNumeric();
        }
    );


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
<div style="display:none">view_loan_statement</div>
</body>
</html>