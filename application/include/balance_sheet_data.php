<?php
//get all fees for the year for all loans that were disbursed
//Payments for open loans
if (isset($_POST['search'])) {
    $date1 = date("Y-m-d", strtotime(explode(" - ", $_POST['date'])[0]))." 00:00:00";
    $date2 = date("Y-m-d", strtotime(explode(" - ", $_POST['date'])[1]))." 23:59:59";
} else {
    $date1 = date('Y-m-01')." 00:00:00";
    $date2 = date('Y-m-t')." 23:59:59";
}
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

$longTerm = mysqli_fetch_assoc(mysqli_query($link,"select balance from gl_codes where name='Long-Term Loans' and portfolio='LOAN PORTFOLIO'"));//Total Long Term Principal
$longTermLoan=$longTerm['balance'];

//Property Plant and Building
$longTerm = mysqli_fetch_assoc(mysqli_query($link,"select sum(balance) from gl_codes where code between '10001' and '10010'"));//Total Property
$property=$longTerm['sum(balance)'];

//Receivables
$longTerm = mysqli_fetch_assoc(mysqli_query($link,"select sum(balance) from gl_codes where code between '12001' and '12010'"));//Total Receivables
$receivables=$longTerm['sum(balance)'];

//Cash Equivalents
$cashEquivalents = mysqli_fetch_assoc(mysqli_query($link,"select sum(balance) from gl_codes where code between '13001' and '13010'"));//Total Cash Equivalents
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
<div id="search_template" style="">
            <div id="search_template" style="">
                <form class="form-horizontal" method="post" enctype="multipart/form-data">
                    <div class="box box-success">
                        <div class="box-header with-border">
                            <h3 class="box-title">Select the Date of the Balance Sheet</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">

                                <div class="col-xs-5">
                                    <div class="input-group">
                                        <label>Customize report&nbsp;</label>
                                        <i class="fa fa-caret-right"></i> <input type="text" class="btn btn-default pull-md-none"
                                                                                 name="date" id="daterange-btn">
                                    </div>
                                </div>
                                <div class="col-xs-2">
                                <span class="input-group-btn">
                                  <button type="submit" name="search" class="btn bg-olive btn-flat">Search!</button>
                                </span>
                                </div>

                            </div>
                        </div>

                    </div><!-- /.box -->
                </form>
            </div>
            <div class="well">
                Please note that below fee show the <b>Assests, Equity and Liabilities</b>. Today: <?php echo date('dS F, Y') ?>
            </div>
</div>
    <script>
        function update_balance_sheet()
        {
            var current_assets = 0;
            var investments = 0;
            var fixed_assets = 0;
            var intangible_assets = 0;
            var other_assets = 0;

            for (var i = 1; i <= 0; i++) {
                var fieldid = 'inputCurrentAssets'+i;
                if(document.getElementById(fieldid))
                {
                    if (document.getElementById(fieldid).value != "")
                        current_assets = parseFloat(current_assets) + parseFloat(document.getElementById(fieldid).value)*100;
                }

                var fieldid = 'inputInvestments'+i;
                if(document.getElementById(fieldid))
                {
                    if (document.getElementById(fieldid).value != "")
                        investments = parseFloat(investments) + parseFloat(document.getElementById(fieldid).value)*100;
                }

                var fieldid = 'inputFixedAssets'+i;
                if(document.getElementById(fieldid))
                {
                    if (document.getElementById(fieldid).value != "")
                        fixed_assets = parseFloat(fixed_assets) + parseFloat(document.getElementById(fieldid).value)*100;
                }

                var fieldid = 'inputIntangibleAssets'+i;
                if(document.getElementById(fieldid))
                {
                    if (document.getElementById(fieldid).value != "")
                        intangible_assets = parseFloat(intangible_assets) + parseFloat(document.getElementById(fieldid).value)*100;
                }

                var fieldid = 'inputOtherAssets'+i;
                if(document.getElementById(fieldid))
                {
                    if (document.getElementById(fieldid).value != "")
                        other_assets = parseFloat(other_assets) + parseFloat(document.getElementById(fieldid).value)*100;
                }
            }

            var inputCurrentLoansOutstanding = document.getElementById("inputCurrentLoansOutstanding").value;
            var inputCurrentLoansPastDue = document.getElementById("inputCurrentLoansPastDue").value;
            var inputCurrentLoansRestructured = document.getElementById("inputCurrentLoansRestructured").value;
            var inputLoanLossReserve = document.getElementById("inputLoanLossReserve").value;

            if (inputCurrentLoansOutstanding == "")
                inputCurrentLoansOutstanding = 0;
            if (inputCurrentLoansPastDue == "")
                inputCurrentLoansPastDue = 0;
            if (inputCurrentLoansRestructured == "")
                inputCurrentLoansRestructured = 0;
            if (inputLoanLossReserve == "")
                inputLoanLossReserve = 0;

            var loans_outstanding = parseFloat(inputCurrentLoansOutstanding)*100 + parseFloat(inputCurrentLoansPastDue)*100 + parseFloat(inputCurrentLoansRestructured)*100;
            var net_loans_outstanding = parseFloat(loans_outstanding) - parseFloat(inputLoanLossReserve)*100;
            var total_current_assets = parseFloat(net_loans_outstanding) + parseFloat(current_assets);
            var total_assets = parseFloat(total_current_assets) + parseFloat(investments) + parseFloat(fixed_assets) + parseFloat(intangible_assets) + parseFloat(other_assets);

            var inputClientSavings = document.getElementById("inputClientSavings").value;
            var inputAccountsPayable = document.getElementById("inputAccountsPayable").value;
            var inputWagesPayable = document.getElementById("inputWagesPayable").value;
            var inputShortTermBorrowings = document.getElementById("inputShortTermBorrowings").value;
            var inputLongTermDebtCommercial = document.getElementById("inputLongTermDebtCommercial").value;
            var inputLongTermDebtConcessional = document.getElementById("inputLongTermDebtConcessional").value;
            var inputOtherAccruedExpensesPayable = document.getElementById("inputOtherAccruedExpensesPayable").value;
            var inputIncomeTaxesPayable = document.getElementById("inputIncomeTaxesPayable").value;
            var inputRestrictedRevenue = document.getElementById("inputRestrictedRevenue").value;

            if (inputClientSavings == "")
                inputClientSavings = 0;
            if (inputAccountsPayable == "")
                inputAccountsPayable = 0;
            if (inputWagesPayable == "")
                inputWagesPayable = 0;
            if (inputShortTermBorrowings == "")
                inputShortTermBorrowings = 0;
            if (inputLongTermDebtCommercial == "")
                inputLongTermDebtCommercial = 0;
            if (inputLongTermDebtConcessional == "")
                inputLongTermDebtConcessional = 0;
            if (inputOtherAccruedExpensesPayable == "")
                inputOtherAccruedExpensesPayable = 0;
            if (inputIncomeTaxesPayable == "")
                inputIncomeTaxesPayable = 0;
            if (inputRestrictedRevenue == "")
                inputRestrictedRevenue = 0;

            var total_liabilities = parseFloat(inputClientSavings)*100 + parseFloat(inputAccountsPayable)*100 + parseFloat(inputWagesPayable)*100 + parseFloat(inputShortTermBorrowings)*100 + parseFloat(inputLongTermDebtCommercial)*100 + parseFloat(inputLongTermDebtConcessional)*100 + parseFloat(inputOtherAccruedExpensesPayable)*100 + parseFloat(inputIncomeTaxesPayable)*100 + parseFloat(inputRestrictedRevenue)*100;

            var inputRetainedNetSurplus = document.getElementById("inputRetainedNetSurplus").value;
            var inputNetSurplus = document.getElementById("inputNetSurplus").value;

            if (inputRetainedNetSurplus == "")
                inputRetainedNetSurplus = 0;
            if (inputNetSurplus == "")
                inputNetSurplus = 0;

            var equities = parseFloat(inputRetainedNetSurplus)*100 + parseFloat(inputNetSurplus)*100;

            var loan_fund = parseFloat(total_assets) - parseFloat(equities) - parseFloat(total_liabilities);
            var total_equities = parseFloat(loan_fund) + parseFloat(equities);
            var total_liabilities_equities = parseFloat(total_liabilities) + parseFloat(total_equities);

            if (current_assets != 0)
                document.getElementById("CurrentAssets").innerHTML = numberWithCommas((current_assets / 100).toFixed(2));
            if (net_loans_outstanding != 0)
                document.getElementById("NetLoansOutstanding").innerHTML = numberWithCommas((net_loans_outstanding / 100).toFixed(2));
            if (total_liabilities != 0)
                document.getElementById("TotalLiabilities").innerHTML = numberWithCommas((total_liabilities / 100).toFixed(2));
            if (total_current_assets != 0)
                document.getElementById("TotalCurrentAssets").innerHTML = numberWithCommas((total_current_assets / 100).toFixed(2));
            if (investments != 0)
                document.getElementById("TotalInvestments").innerHTML = numberWithCommas((investments / 100).toFixed(2));
            if (fixed_assets != 0)
                document.getElementById("TotalFixedAssets").innerHTML = numberWithCommas((fixed_assets / 100).toFixed(2));
            if (intangible_assets != 0)
                document.getElementById("TotalIntangibleAssets").innerHTML = numberWithCommas((intangible_assets / 100).toFixed(2));
            if (other_assets != 0)
                document.getElementById("TotalOtherAssets").innerHTML = numberWithCommas((other_assets / 100).toFixed(2));
            if (total_assets != 0)
                document.getElementById("TotalAssets").innerHTML = numberWithCommas((total_assets / 100).toFixed(2));
            if (loan_fund != 0)
                document.getElementById("LoanFundCapital").innerHTML = numberWithCommas((loan_fund / 100).toFixed(2));
            if (total_equities != 0)
                document.getElementById("TotalEquity").innerHTML = numberWithCommas((total_equities / 100).toFixed(2));
            if (total_liabilities_equities != 0)
                document.getElementById("TotalLiabilitiesEquity").innerHTML = numberWithCommas((total_liabilities_equities / 100).toFixed(2));
        }
        function numberWithCommas(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }
    </script>
<div class="row">
    <div class="col-xs-6">
        <div id="export_button">
            <div class="dt-buttons btn-group">
                <a type="button" href="balance-sheet.php?printReq=<?php echo base64_encode($date1.">".$date2);?>" class="btn btn-warning pull-right"><i
                            class="fa fa-print"></i> Print</a>
            </div>
        </div>
    </div>
    <div class="col-xs-6 text-right">
        <h4 class="text-bold">From <?php
            $date1Format = date_create("$date1");
            $date1Display = date_format($date1Format, "d/m/Y H:i:s");

            $date1Format = date_create("$date2");
            $date2Display = date_format($date1Format, "d/m/Y H:i:s");

            echo $date1Display; ?> - <?php echo $date2Display; ?> <small>(change dates above)</small></h4>
    </div>
</div>
    <div class="box box-info">
        <div class="row">
            <div class="col-sm-12 text-center">
                <h2>BALANCE SHEET</h2>
            </div>
        </div>
        <div class="box-body">

            <form class="form-horizontal" method="post" enctype="multipart/form-data" name="form" id="form" target="_blank">
                <input type="hidden" name="generate_excel" value="1">

                <input type="hidden" name="to_date_heading" value="29th September, 2020">

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

            </form>
        </div>
    </div>
    <script>
        $( "#pre_loader" ).hide();
        $("#search_template" ).show();
    </script>
<script type="text/javascript">
    $(".numeric").numeric();
    $(".positive").numeric({ negative: false });
    $(".positive-integer").numeric({ decimal: false, negative: false });
    $(".negative-integer").numeric({ decimal: false, negative: true });
    $(".decimal-2-places").numeric({ decimalPlaces: 2 });
    $(".decimal-4-places").numeric({ decimalPlaces: 4 });
    $("#remove").click(
        function(e)
        {
            e.preventDefault();
            $(".numeric,.positive,.positive-integer,.decimal-2-places,.decimal-4-places").removeNumeric();
        }
    );
</script>