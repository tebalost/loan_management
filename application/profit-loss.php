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
                    $newDate=str_replace(["00:00:00"]," ",$date1);
                    $newDate1=date_create("$newDate");

                $newDate0=str_replace(["00:00:00"]," ",$date2);
                    $newDate2=date_create("$newDate0")
                ?>
                <h2 class="page_title_print">Profit/Loss for 3 months</h2>
            </div>

        </div>
    </div>
</section>



<div class="wrapper">
    <section class="invoice">
        <div class="row">
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table id="reports_table"
                           class="table table-bordered table-condensed table-hover dataTable no-footer"
                           style="background: rgb(255, 255, 255); width: 100%;" role="grid">
                        <thead>
                        <tr style="background: #CCC;" role="row">
                            <td style="font-weight: bold; width: 233px;" class="sorting_disabled" rowspan="1"
                                colspan="1">Profit / Loss Statement
                            </td>
                            <th><small>All Time</small></th>
                            <?php for ($i = 0; $i < 3; $i++) { ?>
                                <th><small>01 <?php echo date('M', strtotime("-$i month")); ?>
                                        - <?php echo date('t M Y', strtotime("-$i month")); ?></small></th>
                            <?php } ?>
                        </tr>
                        </thead>
                        <tbody>

                        <tr role="row" class="odd">
                            <td class="text-bold bg-green">Operating Profit (P)</td>
                            <?php
                            //Get total Repayments
                            $repayments=mysqli_fetch_assoc(mysqli_query($link,"select sum(interest_payment), sum(fees_payment), sum(penalty_payment) from pay_schedule"));
                            $total_income=$repayments['sum(interest_payment)']+$repayments['sum(fees_payment)']+$repayments['sum(penalty_payment)'];
                            ?>
                            <td align="right" style="font-weight:bold"></td>
                            <td align="right" style="font-weight:bold"></td>
                            <td align="right" style="font-weight:bold"></td>
                            <td align="right" style="font-weight:bold"></td>
                        </tr>
                        <tr role="row" class="even">
                            <td class="text-bold">Interest Repayments</td>
                            <td align="right"><?php echo number_format($repayments['sum(interest_payment)'],'2','.',", "); ?></td>
                            <?php
                            //Get total Repayments
                            $repayments=mysqli_fetch_assoc(mysqli_query($link,"select sum(interest_payment), sum(fees_payment), sum(penalty_payment) from pay_schedule"));
                            $total_income=$repayments['sum(interest_payment)']+$repayments['sum(fees_payment)']+$repayments['sum(penalty_payment)'];
                            ?>
                            <?php for ($i = 0; $i < 3; $i++) {
                                $date1=date('Y-m-', strtotime("-$i month"))."01";
                                $date2=date('Y-m-t', strtotime("-$i month"))." 23:59:59";
                                $interestTransactions = mysqli_fetch_assoc(mysqli_query($link, "select sum(interest_payment) from pay_schedule where payment_tx_id in (select tx_id from payments where pay_date between '$date1' and '$date2')"));
                                ?>
                                <td align="right"><?php echo number_format($interestTransactions['sum(interest_payment)'],"2",".",", "); ?></td>
                            <?php } ?>
                        </tr>
                        <tr role="row" class="odd">
                            <td class="text-bold">Loan Fees Repayments</td>
                            <td align="right"><?php echo number_format($repayments['sum(fees_payment)'],'2','.',", "); ?></td>
                            <?php for ($i = 0; $i < 3; $i++) {
                                $date1=date('Y-m-', strtotime("-$i month"))."01";
                                $date2=date('Y-m-t', strtotime("-$i month"))." 23:59:59";
                                $feesTransactions = mysqli_fetch_assoc(mysqli_query($link, "select sum(fees_payment) from pay_schedule where payment_tx_id in (select tx_id from payments where pay_date between '$date1' and '$date2')"));
                                ?>
                                <td align="right"><?php echo number_format($feesTransactions['sum(fees_payment)'],"2",".",", "); ?></td>
                            <?php } ?>
                        </tr>
                        <tr role="row" class="odd">
                            <td class="text-bold">Penalty Repayments</td>
                            <td align="right"><?php echo number_format($repayments['sum(penalty_payment)'],'2','.',", ");; ?></td>
                            <?php for ($i = 0; $i < 3; $i++) {
                                $date1=date('Y-m-', strtotime("-$i month"))."01";
                                $date2=date('Y-m-t', strtotime("-$i month"))." 23:59:59";
                                $penaltyTransactions = mysqli_fetch_assoc(mysqli_query($link, "select sum(penalty_payment) from pay_schedule where payment_tx_id in (select tx_id from payments where pay_date between '$date1' and '$date2')"));
                                ?>
                                <td align="right"><?php echo number_format($penaltyTransactions['sum(penalty_payment)'],"2",".",", "); ?></td>
                            <?php } ?>
                        </tr>
                        <tr role="row" class="odd">
                            <td class="text-bold bg-gray">TOTAL PROFIT</td>
                            <td align="right" style="font-weight:bold"><?php echo number_format($total_income,"2",".",", "); ?></td>
                            <?php for ($i = 0; $i < 3; $i++) {
                                $date1=date('Y-m-', strtotime("-$i month"))."01";
                                $date2=date('Y-m-t', strtotime("-$i month"))." 23:59:59";
                                $incomeTransactions = mysqli_fetch_assoc(mysqli_query($link, "select sum(interest_payment), sum(fees_payment), sum(penalty_payment) from pay_schedule where payment_tx_id in (select tx_id from payments where pay_date between '$date1' and '$date2')"));
                                $income=$incomeTransactions['sum(interest_payment)'] + $incomeTransactions['sum(fees_payment)'] + $incomeTransactions['sum(penalty_payment)'];
                                ?>
                                <td align="right" style="font-weight:bold"><?php echo number_format($income,"2",".",", "); ?></td>
                            <?php } ?>
                        </tr>
                        <tr role="row" class="even">
                            <td class="text-bold bg-red">Operating Expenses (E)</td>
                            <?php
                            //All Operating Expenses
                            // echo date('M', strtotime("-$i month"));
                            // date('t M', strtotime("-$i month")); </small></th>

                            $allTransactions = mysqli_query($link, "SELECT account, sum(debit)-sum(credit) FROM journal_transactions  where account between '40000' and '43010' group by account");
                            ?>
                            <td align="right" style="font-weight:bold"></td>
                            <td align="right" style="font-weight:bold"></td>
                            <td align="right" style="font-weight:bold"></td>
                            <td align="right" style="font-weight:bold"></td>
                        </tr>
                        <?php
                        $total_expenses = 0;
                        $monthExpenses=0;
                        while($row=mysqli_fetch_assoc($allTransactions)){
                            //Get the account Name
                            $account=$row['account'];
                            $accountName=mysqli_fetch_assoc(mysqli_query($link,"select name from gl_codes where code='$account'"));
                            $name=$accountName['name'];
                            $total_expenses+=$row['sum(debit)-sum(credit)']
                            ?>
                            <tr role="row" class="odd">
                                <td class="text-bold"><?php echo $name; ?></td>
                                <td align="right"><?php echo number_format($row['sum(debit)-sum(credit)'],"2",".",", "); ?></td>


                                <?php for ($i = 0; $i < 3; $i++) {
                                    $date1=date('Y-m-', strtotime("-$i month"))."01";
                                    $date2=date('Y-m-t', strtotime("-$i month"))." 23:59:59";
                                    $monthTransactions = mysqli_fetch_assoc(mysqli_query($link, "SELECT account, sum(debit)-sum(credit) FROM journal_transactions  where account between '40000' and '43010' and account='$account' and date between '$date1' and '$date2' group by account"));
                                    ?>
                                    <td align="right"><?php echo number_format($monthTransactions['sum(debit)-sum(credit)'],"2",".",", "); ?></td>
                                <?php } ?>
                            </tr>
                        <?php }
                        $netProfit = $total_income-$total_expenses;
                        ?>
                        <tr role="row" class="odd">
                            <td class="text-bold bg-gray">TOTAL OPERATING EXPENSES</td>
                            <td align="right" style="font-weight:bold"><?php echo number_format($total_expenses,"2",".",", "); ?></td>
                            <?php for ($i = 0; $i < 3; $i++) {
                                $date1=date('Y-m-', strtotime("-$i month"))."01";
                                $date2=date('Y-m-t', strtotime("-$i month"))." 23:59:59";
                                $monthTransactions = mysqli_fetch_assoc(mysqli_query($link, "SELECT account, sum(debit)-sum(credit) FROM journal_transactions  where account between '40000' and '43010' and date between '$date1' and '$date2'"));
                                ?>
                                <td align="right" style="font-weight:bold"><?php echo number_format($monthTransactions['sum(debit)-sum(credit)'],"2",".",", "); ?></td>
                            <?php } ?>
                        </tr>
                        <tr class="bg-gray even" role="row">
                            <td class="text-bold">Gross Profit (G) = P - E</td>
                            <td style="font-weight:bold" align="right"><?php echo number_format($netProfit,"2",".",", ") ?></td>
                            <?php for ($i = 0; $i < 3; $i++) {
                                $date1=date('Y-m-', strtotime("-$i month"))."01";
                                $date2=date('Y-m-t', strtotime("-$i month"));
                                $incomeTransactions = mysqli_fetch_assoc(mysqli_query($link, "select sum(interest_payment), sum(fees_payment), sum(penalty_payment) from pay_schedule where payment_tx_id in (select tx_id from payments where pay_date between '$date1' and '$date2')"));
                                $income=$incomeTransactions['sum(interest_payment)'] + $incomeTransactions['sum(fees_payment)'] + $incomeTransactions['sum(penalty_payment)'];

                                $date1=date('Y-m-', strtotime("-$i month"))."01";
                                $date2=date('Y-m-t', strtotime("-$i month"))." 23:59:59";
                                $monthTransactionsExpenses = mysqli_fetch_assoc(mysqli_query($link, "SELECT account, sum(debit)-sum(credit) FROM journal_transactions  where account between '40000' and '43010' and date between '$date1' and '$date2'"));
                                $expenses=$monthTransactionsExpenses['sum(debit)-sum(credit)'];
                                $monthGrossProfit=$income-$expenses;
                                ?>
                                <td align="right" style="font-weight:bold"><?php echo number_format($monthGrossProfit,"2",".",", "); ?></td>
                            <?php } ?>
                        </tr>
                        <tr role="row" class="odd">
                            <td class="bg-red text-bold">Other Expense (O)</td>
                            <td align="right" style="font-weight:bold"></td>
                            <td align="right" style="font-weight:bold"></td>
                            <td align="right" style="font-weight:bold"></td>
                            <td align="right" style="font-weight:bold"></td>
                        </tr>
                        <tr role="row" class="even">
                            <td class="text-bold">Savings Interest</td>
                            <td align="right">0</td>
                            <td align="right">0</td>
                            <td align="right">0</td>
                            <td align="right">0</td>
                        </tr>
                        <tr role="row" class="odd">
                            <td class="text-bold">Default Loans *</td>
                            <td align="right">0</td>
                            <td align="right">0</td>
                            <td align="right">0</td>
                            <td align="right">0</td>
                        </tr>
                        <tr class="bg-gray even" role="row">
                            <td class="text-bold">Net Income (N) = G - O</td>
                            <td align="right" class="text-bold"><?php echo number_format($netProfit,"2",".",", ") ?></td>
                            <?php for ($i = 0; $i < 3; $i++) {
                                $date1=date('Y-m-', strtotime("-$i month"))."01";
                                $date2=date('Y-m-t', strtotime("-$i month"));
                                $incomeTransactions = mysqli_fetch_assoc(mysqli_query($link, "select sum(interest_payment), sum(fees_payment), sum(penalty_payment) from pay_schedule where payment_tx_id in (select tx_id from payments where pay_date between '$date1' and '$date2')"));
                                $income=$incomeTransactions['sum(interest_payment)'] + $incomeTransactions['sum(fees_payment)'] + $incomeTransactions['sum(penalty_payment)'];

                                $date1=date('Y-m-', strtotime("-$i month"))."01";
                                $date2=date('Y-m-t', strtotime("-$i month"))." 23:59:59";
                                $monthTransactionsExpenses = mysqli_fetch_assoc(mysqli_query($link, "SELECT account, sum(debit)-sum(credit) FROM journal_transactions  where account between '40000' and '43010' and date between '$date1' and '$date2'"));
                                $expenses=$monthTransactionsExpenses['sum(debit)-sum(credit)'];
                                $monthGrossProfit=$income-$expenses;
                                ?>
                                <td align="right" style="font-weight:bold"><?php echo number_format($monthGrossProfit,"2",".",", "); ?></td>
                            <?php } ?>
                        </tr>
                        </tbody>
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