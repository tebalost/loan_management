<?php
require('../config/connect.php');
error_reporting(0);
$getCompanyInfo = mysqli_query($link, "SELECT * FROM systemset") or die(mysqli_error($link));
$companyInfo = mysqli_fetch_assoc($getCompanyInfo);

$minDate=mysqli_fetch_assoc(mysqli_query($link,"select min(date_release) from loan_info"));

$search = mysqli_query($link, "SELECT * FROM systemset");
$get_searched = mysqli_fetch_array($search);

    $date1 = explode(">",base64_decode($_GET['printReq']))[0]." 00:00:00";
    $date2 = explode(">",base64_decode($_GET['printReq']))[1]." 23:59:59";

$allCodesIncome = mysqli_query($link, "SELECT * from gl_codes where code between '30001' and '30010'") or die (mysqli_error($link));
$allCodesExpenses = mysqli_query($link, "SELECT * from gl_codes where code between '40001' and '43010'") or die (mysqli_error($link));
$allCodesReceivables = mysqli_query($link, "SELECT * from gl_codes where code between '12001' and '12010'") or die (mysqli_error($link));
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
            <img src='<?php echo $companyInfo['image']; ?>' style="max-width: 283px; max-height: 93px" alt="logo"
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
                <?php
                    $newDate=str_replace([" 00:00:00"]," ",$date1);
                    $newDate1=date_create("$newDate");

                $newDate0=str_replace(["23:59:59"]," ",$date2);
                    $newDate2=date_create("$newDate0");
                $startDate=date_format($newDate1,"d/m/Y");
                $endDate=date_format($newDate2,"d/m/Y");
                ?>
                <h2 class="page_title_print">Income Statement for Period <?php echo $startDate; ?>-<?php echo $endDate; ?></h2>
            </div>

        </div>
    </div>
</section>



<div class="wrapper">
    <section class="invoice">
        <div class="row">
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table id=""
                           class="table table-bordered table-condensed table-hover dataTable no-footer"
                           role="grid">
                        <thead>
                        <tr style="background-color: #F2F8FF" role="row">
                            <th rowspan="1" colspan="1">Income</th>
                            <th class="text-right" rowspan="1" colspan="1"><?php echo date('Y'); ?></th>
                        </tr>
                        <tr>
                            <td colspan=""></td>
                        </tr>
                        </thead>
                        <tbody>

                        <!--Received Income-->
                        <?php
                        $receivables_total = $rec_count = $totalReceivable = 0;
                        while ($codes = mysqli_fetch_assoc($allCodesReceivables)) {
                            $glCode = $codes['code'];
                            $name = $codes['name'];
                            $type = $codes['type'];
                            //$balanceIncome = $codes['balance'];

                            $allTransactions = mysqli_query($link, "SELECT * FROM journal_transactions  where account between '12001' and '12010' and account='$glCode' and date between '$date1' and '$date2' and credit>0");

                            $receivables = mysqli_fetch_assoc(mysqli_query($link, "SELECT sum(credit) FROM journal_transactions  where account between '12001' and '12010' and account='$glCode' and date between '$date1' and '$date2' and credit>0"));
                            $balanceReceivables = $receivables['sum(credit)'];
                            ?>


                            <tr role="row" class="even">
                                <td class="text-bold">
                                    <?php echo $name; ?>
                                </td>
                                <td class="text-right"><b><?php echo number_format($balanceReceivables, 2, ".", ","); ?></b></td>

                            </tr>
                            <?php //$fees_total += $fees['sum(fee_amount)'];
                            $count++;
                            $totalReceivable+=$balanceReceivables;
                        } ?>


                        </tbody>
                        <tr>
                            <th style="font-style: italic; font-size: larger">GROSS INCOME</th>
                            <td colspan="3" align="right" style="font-size: larger; font-style: italic"><strong><?php echo number_format($totalReceivable, 2, ".", ","); ?></strong></td>
                        </tr>
                    </table>
                    <table id=""
                           class="table table-bordered table-condensed table-hover dataTable no-footer"
                           role="grid">
                        <thead>
                        <tr style="background-color: #F2F8FF" role="row">
                            <th rowspan="1" colspan="1">Expenditure</th>
                            <th class="text-right" rowspan="1" colspan="1"></th>
                        </tr>
                        <tr>
                            <td colspan=""></td>
                        </tr>
                        </thead>
                        <tbody>

                        <?php

                        while ($codes = mysqli_fetch_assoc($allCodesExpenses)) {
                            $glCode = $codes['code'];
                            $name = $codes['name'];
                            $type = $codes['type'];
                            $balanceExpenses = $codes['balance'];

                            $allTransactions = mysqli_query($link, "SELECT * FROM journal_transactions  where account between '40000' and '43010' and account='$glCode'  and date between '$date1' and '$date2'");
                            $expenses = mysqli_fetch_assoc(mysqli_query($link, "SELECT sum(debit)-sum(credit) FROM journal_transactions  where account between '40000' and '43010' and account='$glCode'  and date between '$date1' and '$date2'"));
                            $balanceExpenses = $expenses['sum(debit)-sum(credit)'];
                            ?>
                            <tr role="row" class="even">
                                <td class="text-bold">
                                    <?php echo $name; ?>
                                </td>
                                <td class="text-right"><b><?php echo number_format($balanceExpenses, 2, ".", ","); ?></b></td>

                            </tr>
                            <?php //$fees_total += $fees['sum(fee_amount)'];
                            $count++;
                            $totalExpenditure+=$balanceExpenses;
                        } ?>


                        </tbody>
                        <tr>
                            <th style="font-style: italic; font-size: larger">TOTAL EXPENDITURE</th>
                            <td colspan="3" align="right" style="font-size: larger; font-style: italic"><strong><?php echo number_format($totalExpenditure, 2, ".", ","); ?></strong></td>
                        </tr>
                        <tr><td colspan="4" align="right" style="font-size: larger; font-style: italic; text-decoration-line: underline; text-decoration-style: double;"><b><?php echo number_format($totalReceivable-$totalExpenditure, 2, ".", ","); ?></b></td></tr>

                    </table>
                </div>
            </div>
        </div>
    </section>
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
</script>
<div style="display:none">view_loan_statement</div>
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
</body>
</html>
