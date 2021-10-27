<?php
require('../config/connect.php');
$getCompanyInfo = mysqli_query($link, "SELECT * FROM systemset") or die(mysqli_error($link));
$companyInfo = mysqli_fetch_assoc($getCompanyInfo);

$minDate=mysqli_fetch_assoc(mysqli_query($link,"select min(date_release) from loan_info"));

$search = mysqli_query($link, "SELECT * FROM systemset");
$get_searched = mysqli_fetch_array($search);

//get all fees for the year for all loans that were disbursed
//Payments for open loans



    $date1 = explode(">",base64_decode($_GET['printReq']))[0];
    $date2 = explode(">",base64_decode($_GET['printReq']))[1];

/*and fee_name !='Interest'*/
//Profit and loss for the date selected
//Get Net Profit or Loss for the selected period
$allCodesIncome = mysqli_query($link, "SELECT * from gl_codes where code between '30001' and '30010'") or die (mysqli_error($link));
$allCodesIncomeRetained = mysqli_query($link, "SELECT * from gl_codes where code between '30001' and '30010'") or die (mysqli_error($link));
$allCodesExpenses = mysqli_query($link, "SELECT * from gl_codes where code between '40001' and '43010'") or die (mysqli_error($link));
$allCodesExpensesRetained = mysqli_query($link, "SELECT * from gl_codes where code between '40001' and '43010'") or die (mysqli_error($link));

$totalIncome = $retainedIncome = $retainedExpenditure = $totalExpenditure = 0;
while ($codes = mysqli_fetch_assoc($allCodesIncome)) {
    $glCode = $codes['code'];
    $name = $codes['name'];
    $type = $codes['type'];

    $allTransactions = mysqli_fetch_assoc(mysqli_query($link, "SELECT sum(credit)-sum(debit) FROM journal_transactions  where account between '30001' and '30010' and account='$glCode' and date between '$date1' and '$date2'"));
    $balanceIncome = $allTransactions['sum(credit)-sum(debit)'];

    $totalIncome+=$balanceIncome;
}
//Retained Income
//echo "Income:".$totalIncome."<br>";

while ($codes = mysqli_fetch_assoc($allCodesIncomeRetained)) {
    $glCode = $codes['code'];
    $name = $codes['name'];
    $type = $codes['type'];

    $allTransactions = mysqli_fetch_assoc(mysqli_query($link, "SELECT sum(credit)-sum(debit) FROM journal_transactions  where account between '30001' and '30010' and account='$glCode' and date < '$date1'"));
    $balanceRetainedIncome = $allTransactions['sum(credit)-sum(debit)'];

    $retainedIncome+=$balanceRetainedIncome;
}
//echo "Retained Income:".$retainedIncome."<br>";
//Expenditure
while ($codes = mysqli_fetch_assoc($allCodesExpenses)) {
    $glCode = $codes['code'];
    $name = $codes['name'];
    $type = $codes['type'];

    $allTransactions = mysqli_fetch_assoc(mysqli_query($link, "SELECT sum(debit)-sum(credit) FROM journal_transactions  where account between '40000' and '43010' and account='$glCode' and date between '$date1' and '$date2'"));
    $balanceExpenses = $allTransactions['sum(debit)-sum(credit)'];

    $totalExpenditure+=$balanceExpenses;
}
//Retained Income
//echo "Expenditure:".$totalExpenditure."<br>";

while ($codes = mysqli_fetch_assoc($allCodesExpensesRetained)) {
    $glCode = $codes['code'];
    $name = $codes['name'];
    $type = $codes['type'];

    $allTransactions = mysqli_fetch_assoc(mysqli_query($link, "SELECT sum(debit)-sum(credit) FROM journal_transactions  where account between '40000' and '43010' and account='$glCode' and date < '$date1'"));
    $balanceRetainedExpenses = $allTransactions['sum(debit)-sum(credit)'];

    $retainedExpenditure+=$balanceRetainedExpenses;
}
//echo "Retained Expenditure:".$retainedExpenditure."<br>";
$retainedEarnings = $retainedIncome-$retainedExpenditure;


/*
 * NON-CURRENT ASSETS - PROPERTY PLANT AND EQUIPMENT 10001 - 10005, Long-Term Loans
 * CURRENT ASSETS (Receivables) 12001 - 12005, CASH AND CASH EQUIVALENTS, Short-Term Loans (11002)
 * CURRENT LIABILITIES
 * LONG-TERM LIABILITIES
 * INCOME
 * OPERATING EXPENSES
 * OTHER OPERATING EXPENSES
 * EQUITY
 * */
//Loans
$shortTerm = mysqli_fetch_assoc(mysqli_query($link,"select balance from gl_codes where name='Short-Term Loans'"));//Total Principal
$shortTermLoan=$shortTerm['balance'];

$longTerm = mysqli_fetch_assoc(mysqli_query($link,"select balance from gl_codes where name='Long-Term Loans' and portfolio='LOAN PORTFOLIO'"));//Total Principal
$longTermLoan=$longTerm['balance'];

//Property Plant and Building
$longTerm = mysqli_fetch_assoc(mysqli_query($link,"select sum(balance) from gl_codes where code between '10001' and '10010'"));//Total Principal
$property=$longTerm['sum(balance)'];

//Receivables
$longTerm = mysqli_fetch_assoc(mysqli_query($link,"select sum(balance) from gl_codes where code between '12001' and '12010'"));//Total Principal
$receivables=$longTerm['sum(balance)'];

//Cash Equivalents
$cashEquivalents = mysqli_fetch_assoc(mysqli_query($link,"select sum(balance) from gl_codes where code between '13001' and '13010'"));//Total Principal
$cash=$cashEquivalents['sum(balance)'];

//Liabilities
$longTermLiabilities = mysqli_fetch_assoc(mysqli_query($link,"select sum(balance) from gl_codes where code between '21001' and '21010'"));//Total Long Term Liabilities
$longLiability=mysqli_query($link,"select name, sum(balance) from gl_codes where code between '21001' and '21010' group by name");

$currentLiabilities = mysqli_fetch_assoc(mysqli_query($link,"select sum(balance) from gl_codes where code between '20001' and '20010'"));//Total Current Liabilities
$currentLiability=mysqli_query($link,"select name, sum(balance) from gl_codes where code between '20001' and '20010' group by name");

$totalLiabilities=$longTermLiabilities['sum(balance)']+$currentLiabilities['sum(balance)'];


//Non Current Assets
$nonCurrentAssets = mysqli_fetch_assoc(mysqli_query($link,"select sum(balance) from gl_codes where code between '10001' and '10010'"));//Total Non-Current Assets
$longLiability=mysqli_query($link,"select name, sum(balance) from gl_codes where code between '21001' and '21010' group by name");

$currentLiabilities = mysqli_fetch_assoc(mysqli_query($link,"select sum(balance) from gl_codes where code between '20001' and '20010'"));//Total Current Liabilities
$currentLiability=mysqli_query($link,"select name, sum(balance) from gl_codes where code between '20001' and '20010' group by name");

$totalLiabilities=$longTermLiabilities['sum(balance)']+$currentLiabilities['sum(balance)'];

$totalCurrentAssets=$cash+$receivables+$shortTermLoan;
$totalNonCurrentAssets=$longTermLoan+$property;
$totalAssets=$totalNonCurrentAssets+$totalCurrentAssets;

$totalEquity=0;
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
                <h2 class="page_title_print">Balance Sheet <?php echo date_format($newDate1,"d/m/Y")."-".str_replace(["23:59:59"]," ",date_format($newDate2,"d/m/Y")); ?></h2>
            </div>

        </div>
    </div>
</section>



<div class="wrapper">
    <section class="invoice">
        <div class="row">
            <div class="col-sm-6">
                <table class="table table-bordered table-condensed table-hover">
                    <tbody>
                    <tr style="background-color: #F2F8FF">
                        <td colspan="3" class="text-center"><b>ASSETS</b></td>
                    </tr>
                    <tr>
                        <td class="text-bold text-blue"><b>Current Assets:</b></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Short-Term Loans</b></td>
                        <td style="text-align:right"><input type="text"  style="text-align: right" name="current_loans_outstanding" id="inputCurrentLoansOutstanding" placeholder="" value="<?php echo number_format($shortTermLoan, 2, ".", ","); ?>" class="balance_sheet_input decimal-2-places" onkeyup="update_balance_sheet()"></td>
                    </tr>

                    <tr>
                        <td><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Receivables</b></td>
                        <td style="text-align:right"><input type="text"  style="text-align: right" name="current_loans_past_due" id="inputCurrentLoansPastDue" placeholder="" value="<?php echo number_format($receivables, 2, ".", ","); ?>" class="balance_sheet_input decimal-2-places" onkeyup="update_balance_sheet()"></td>

                    </tr>
                    <tr>
                        <td><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cash and Cash Equvalents</b></td>
                        <td style="text-align:right; border-bottom: 1px solid #000"><input style="text-align: right" type="text" name="current_loans_restructured" id="inputCurrentLoansRestructured" placeholder="" value="<?php echo number_format($cash, 2, ".", ","); ?>" class="balance_sheet_input decimal-2-places" onkeyup="update_balance_sheet()"></td>

                    </tr>
                    <tr>
                        <td><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total Current Assets</b></td>
                        <td style="text-align:right;" class="text-bold"><?php echo number_format($totalCurrentAssets, 2, ".", ","); ?></td>

                    </tr>
                    <tr>
                        <td class="text-bold text-blue"><b>Non-Current Assets:</b></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Property Plant and Building</b></td>
                        <td style="text-align:right"><input type="text"  style="text-align: right" name="current_loans_outstanding" id="inputCurrentLoansOutstanding" placeholder="" value="<?php echo number_format($property, 2, ".", ","); ?>" class="balance_sheet_input decimal-2-places" onkeyup="update_balance_sheet()"></td>
                    </tr>

                    <tr>
                        <td><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Long-Term Loans</b></td>
                        <td style="text-align:right"><input type="text"  style="text-align: right" name="current_loans_past_due" id="inputCurrentLoansPastDue" placeholder="" value="<?php echo number_format($longTermLoan, 2, ".", ","); ?>" class="balance_sheet_input decimal-2-places" onkeyup="update_balance_sheet()"></td>

                    </tr>

                    <tr>
                        <td><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total Non-Current Assets</b></td>
                        <td style="text-align:right;" class="text-bold"><?php echo number_format($totalNonCurrentAssets, 2, ".", ","); ?></td>

                    </tr>
                    <tr class="active">
                        <td style=""><b>Total Assets</b></td>

                        <td style="text-align:right;" class="text-bold"><div id="TotalCurrentAssets"><?php echo number_format($totalAssets, 2, ".", ","); ?></div></td>
                    </tr>
                    <tr>
                        <td class="text-blue"><b>Investments:</b></td>
                        <td></td>

                    </tr>
                    <tr class="active">
                        <td style=""><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total Investments</b></td>
                        <td style="text-align:right;" class="text-bold"><div id="TotalInvestments">0</div></td>
                    </tr>
                    <tr>
                        <td class="text-blue"><b>Fixed Assets:</b></td>
                        <td></td>

                    </tr>
                    <tr class="active">
                        <td style=""><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total Fixed Assets</b></td>
                        <td style="text-align:right;" class="text-bold"><div id="TotalFixedAssets">0</div></td>
                    </tr>
                    <tr>
                        <td class="text-blue"><b>Intangible Assets:</b></td>
                        <td></td>

                    </tr>
                    <tr class="active">
                        <td style=""><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Total Intangible Assets</b></td>
                        <td style="text-align:right;" class="text-bold"><div id="TotalIntangibleAssets">0</div></td>
                    </tr>


                    </tbody></table>
            </div>

            <div class="col-sm-6">
                <table class="table table-bordered table-condensed table-hover">
                    <tbody><tr style="background-color: #F2F8FF">
                        <td colspan="3" class="text-center"><b>LIABILITY AND EQUITY</b></td>
                    </tr>

                    <tr>
                        <td class="text-blue" colspan="2"><b>LIABILITIES</b></td>
                    </tr>
                    <tr>
                        <td class="text-bold text-blue"><b>Current Liabilities:</b></td>
                        <td></td>
                    </tr>
                    <?php while ($currentLiable=mysqli_fetch_assoc($currentLiability)){ ?>
                        <tr>
                            <td><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $currentLiable['name']; ?></b></td>
                            <td style="text-align:right"><input type="text"  style="text-align: right" name="client_savings" id="inputClientSavings" placeholder="" value="<?php echo number_format($currentLiable['sum(balance)'],"2",".",","); ?>" class="balance_sheet_input decimal-2-places" onkeyup="update_balance_sheet()"></td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td class="text-bold text-blue"><b>Long-Term Liabilities:</b></td>
                        <td></td>
                    </tr>
                    <?php while ($longtermLiable=mysqli_fetch_assoc($longLiability)){ ?>
                        <tr>
                            <td><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $longtermLiable['name']; ?></b></td>
                            <td style="text-align:right"><input type="text"  style="text-align: right" name="client_savings" id="inputClientSavings" placeholder="" value="<?php echo $longtermLiable['sum(balance)']; ?>" class="balance_sheet_input decimal-2-places" onkeyup="update_balance_sheet()"></td>
                        </tr>
                    <?php } ?>

                    <tr class="info">
                        <td><b>TOTAL LIABILITIES</b></td>
                        <td style="text-align:right;" class="text-bold"><div id="TotalLiabilities"><?php echo number_format($totalLiabilities,"2",".",","); ?></div></td>
                    </tr>
                    <tr>
                        <td class="text-blue" colspan="2"><br><b>EQUITY</b></td>
                    </tr>
                    <?php

                    $netProfitLoss=$totalIncome-$totalExpenditure;
                    if($netProfitLoss<0){
                        $netProfitLossView="(".number_format((-1)*$netProfitLoss,"2",".",",").")";
                    }else{
                        $netProfitLossView=number_format($netProfitLoss,"2",".",",");
                    }
                    ?>
                    <tr>
                        <td><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Net Profit/(Loss)</b></td>
                        <td style="text-align:right"><input type="text" name="net_surplus" style="text-align: right"  id="inputNetSurplus" placeholder="" value="<?php echo $netProfitLossView; ?>" class="balance_sheet_input decimal-2-places" onkeyup="update_balance_sheet()"></td>
                    </tr>
                    <?php
                    $equity=mysqli_query($link,"select * from gl_codes where code between '50001' and '50010'");
                    while($row=mysqli_fetch_assoc($equity)){
                        if($row['name']=="Retained Earnings"){
                            $value=$retainedEarnings;
                        }else{
                            $value=$row['balance'];
                        }
                        $totalEquity+=$value;

                        if($value<0){
                            $equityView="(".number_format((-1)*$value,"2",".",",").")";
                        }else{
                            $equityView=number_format($value,"2",".",",");
                        }
                        ?>

                        <tr>
                            <td><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $row['name']; ?></b></td>
                            <td style="text-align:right;"><input type="text"  style="text-align: right" name="current_loans_outstanding" id="inputCurrentLoansOutstanding" placeholder="" value="<?php echo $equityView; ?>" class="balance_sheet_input decimal-2-places" onkeyup="update_balance_sheet()"></td>
                        </tr>
                    <?php }


                    $totalEquity += $netProfitLoss;
                    $totalEquityLiabilities = $totalEquity + $totalLiabilities;
                    ?>

                    <tr class="info">
                        <td><b>TOTAL EQUITY</b></td>
                        <td style="text-align:right;" class="text-bold"><div id="TotalEquity"><?php echo number_format($totalEquity,"2",".",","); ?></div></td>
                    </tr>
                    </tbody></table>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <table class="table table-bordered table-condensed table-hover">
                    <tbody><tr class="info">
                        <td><b>TOTAL ASSETS</b></td>
                        <td style="text-align:right;" class="text-bold"><div id="TotalAssets"><?php echo number_format($totalAssets, 2, ".", ","); ?></div></td>
                    </tr>
                    </tbody></table>
            </div>
            <div class="col-sm-6">
                <table class="table table-bordered table-condensed table-hover">
                    <tbody><tr class="info">
                        <td><b>TOTAL LIABILITIES AND EQUITY</b></td>
                        <td style="text-align:right;" class="text-bold"><div id="TotalLiabilitiesEquity"><?php echo number_format($totalEquityLiabilities,"2",".",","); ?></div></td>
                    </tr>
                    </tbody></table>
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