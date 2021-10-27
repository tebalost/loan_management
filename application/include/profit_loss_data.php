<?php
//get all fees for the year for all loans that were disbursed
//Payments for open loans
if (isset($_POST['search'])) {
    $date1 = date("Y-m-d", strtotime(explode(" - ", $_POST['date'])[0])) . " 00:00:00";
    $date2 = date("Y-m-d", strtotime(explode(" - ", $_POST['date'])[1])) . " 23:59:59";
} else {
    $date1 = date('Y-m-01') . " 00:00:00";
    $date2 = date('Y-m-t') . " 23:59:59";
}
/*and fee_name !='Interest'*/
?>
<div id="search_template" style="" data-select2-id="search_template">
    <form action="" class="form-horizontal" method="post" enctype="multipart/form-data">
        <input type="hidden" name="search" value="1">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Date Range</h3>
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
                </div>
                <div class="row">
                    <div class="col-xs-5">
                                <span class="input-group-btn">
                                  <button type="submit" name="search" class="btn bg-olive btn-flat">Search!</button>
                                </span>
                    </div>

                </div>
            </div>

        </div><!-- /.box -->
    </form>
</div>
<div class="row">
    <div class="col-xs-6">
        <div id="export_button">
            <div class="dt-buttons btn-group">
                <a type="button" href="profit-loss.php?printReq=<?php echo base64_encode($date1.">".$date2);?>" class="btn btn-warning pull-right"><i
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
    <div class="box-body table-responsive no-padding">
        <div class="col-sm-12">
            <div id="reports_table_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                <div class="row">
                    <div class="col-sm-6"></div>
                    <div class="col-sm-6"></div>
                </div>
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
                                    <?php for ($i = 0; $i < 6; $i++) { ?>
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
                                    <?php for ($i = 0; $i < 6; $i++) {
                                        $date1=date('Y-m-', strtotime("-$i month"))."01";
                                        $date2=date('Y-m-t', strtotime("-$i month"));
                                        $interestTransactions = mysqli_fetch_assoc(mysqli_query($link, "select sum(interest_payment) from pay_schedule where payment_tx_id in (select tx_id from payments where pay_date between '$date1' and '$date2')"));
                                        ?>
                                        <td align="right"><?php echo number_format($interestTransactions['sum(interest_payment)'],"2",".",", "); ?></td>
                                    <?php } ?>
                                </tr>
                                <tr role="row" class="odd">
                                    <td class="text-bold">Loan Fees Repayments</td>
                                    <td align="right"><?php echo number_format($repayments['sum(fees_payment)'],'2','.',", "); ?></td>
                                    <?php for ($i = 0; $i < 6; $i++) {
                                        $date1=date('Y-m-', strtotime("-$i month"))."01";
                                        $date2=date('Y-m-t', strtotime("-$i month"));
                                        $feesTransactions = mysqli_fetch_assoc(mysqli_query($link, "select sum(fees_payment) from pay_schedule where payment_tx_id in (select tx_id from payments where pay_date between '$date1' and '$date2')"));
                                        ?>
                                        <td align="right"><?php echo number_format($feesTransactions['sum(fees_payment)'],"2",".",", "); ?></td>
                                    <?php } ?>
                                </tr>
                                <tr role="row" class="odd">
                                    <td class="text-bold">Penalty Repayments</td>
                                    <td align="right"><?php echo number_format($repayments['sum(penalty_payment)'],'2','.',", ");; ?></td>
                                    <?php for ($i = 0; $i < 6; $i++) {
                                        $date1=date('Y-m-', strtotime("-$i month"))."01";
                                        $date2=date('Y-m-t', strtotime("-$i month"));
                                        $penaltyTransactions = mysqli_fetch_assoc(mysqli_query($link, "select sum(penalty_payment) from pay_schedule where payment_tx_id in (select tx_id from payments where pay_date between '$date1' and '$date2')"));
                                        ?>
                                        <td align="right"><?php echo number_format($penaltyTransactions['sum(penalty_payment)'],"2",".",", "); ?></td>
                                    <?php } ?>
                                </tr>
                                <tr role="row" class="odd">
                                    <td class="text-bold bg-gray">TOTAL PROFIT</td>
                                    <td align="right" style="font-weight:bold"><?php echo number_format($total_income,"2",".",", "); ?></td>
                                    <?php for ($i = 0; $i < 6; $i++) {
                                        $date1=date('Y-m-', strtotime("-$i month"))."01";
                                        $date2=date('Y-m-t', strtotime("-$i month"));
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
                                    <td align="right" style="font-weight:bold"></td>
                                    <td align="right" style="font-weight:bold"></td>
                                    <td align="right" style="font-weight:bold"></td>
                                </tr>
                                <?php
                                $total_expenses = $row['sum(debit)-sum(credit)'];
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


                                    <?php for ($i = 0; $i < 6; $i++) {
                                        $date1=date('Y-m-', strtotime("-$i month"))."01";
                                        $date2=date('Y-m-t', strtotime("-$i month"));
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
                                    <?php for ($i = 0; $i < 6; $i++) {
                                        $date1=date('Y-m-', strtotime("-$i month"))."01";
                                        $date2=date('Y-m-t', strtotime("-$i month"));
                                        $monthTransactions = mysqli_fetch_assoc(mysqli_query($link, "SELECT account, sum(debit)-sum(credit) FROM journal_transactions  where account between '40000' and '43010' and date between '$date1' and '$date2'"));
                                        ?>
                                        <td align="right" style="font-weight:bold"><?php echo number_format($monthTransactions['sum(debit)-sum(credit)'],"2",".",", "); ?></td>
                                    <?php } ?>
                                </tr>
                                <tr class="bg-gray even" role="row">
                                    <td class="text-bold">Gross Profit (G) = P - E</td>
                                    <td style="font-weight:bold" align="right"><?php echo number_format($netProfit,"2",".",", ") ?></td>
                                    <?php for ($i = 0; $i < 6; $i++) {
                                        $date1=date('Y-m-', strtotime("-$i month"))."01";
                                        $date2=date('Y-m-t', strtotime("-$i month"));
                                        $incomeTransactions = mysqli_fetch_assoc(mysqli_query($link, "select sum(interest_payment), sum(fees_payment), sum(penalty_payment) from pay_schedule where payment_tx_id in (select tx_id from payments where pay_date between '$date1' and '$date2')"));
                                        $income=$incomeTransactions['sum(interest_payment)'] + $incomeTransactions['sum(fees_payment)'] + $incomeTransactions['sum(penalty_payment)'];

                                        $date1=date('Y-m-', strtotime("-$i month"))."01";
                                        $date2=date('Y-m-t', strtotime("-$i month"));
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
                                    <td align="right">0</td>
                                    <td align="right">0</td>
                                    <td align="right">0</td>
                                </tr>
                                <tr class="bg-gray even" role="row">
                                    <td class="text-bold">Net Income (N) = G - O</td>
                                    <td align="right" class="text-bold"><?php echo number_format($netProfit,"2",".",", ") ?></td>
                                    <?php for ($i = 0; $i < 6; $i++) {
                                        $date1=date('Y-m-', strtotime("-$i month"))."01";
                                        $date2=date('Y-m-t', strtotime("-$i month"));
                                        $incomeTransactions = mysqli_fetch_assoc(mysqli_query($link, "select sum(interest_payment), sum(fees_payment), sum(penalty_payment) from pay_schedule where payment_tx_id in (select tx_id from payments where pay_date between '$date1' and '$date2')"));
                                        $income=$incomeTransactions['sum(interest_payment)'] + $incomeTransactions['sum(fees_payment)'] + $incomeTransactions['sum(penalty_payment)'];

                                        $date1=date('Y-m-', strtotime("-$i month"))."01";
                                        $date2=date('Y-m-t', strtotime("-$i month"));
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
                <div class="row">
                    <div class="col-sm-5"></div>
                    <div class="col-sm-7"></div>
                </div>
            </div>

        </div>
    </div>
</div>
<script type="text/javascript" language="javascript">
    $(document).ready(function () {
        var dataTable = $('#reports_table').DataTable({
            "paging": false,
            "fixedHeader": {
                "header": false,
                "footer": false
            },
            "lengthChange": true,
            "searching": false,
            "ordering": false,
            "info": false,
            "autoWidth": true,


            "order": [[0, "asc"]],
            "drawCallback": function (settings) {
                $("#reports_table").wrap("<div class='table-responsive'></div>");
            }
        });
        var buttons = new $.fn.dataTable.Buttons(dataTable, {
            "buttons": [
                {
                    extend: 'collection',
                    text: 'Export Data',
                    buttons: [
                        'copyHtml5',
                        'excelHtml5',
                        'csvHtml5',
                        'print'
                    ]
                }
            ]
        }).container().appendTo($('#export_button'));

    });
</script>
<script>
    $("#pre_loader").hide();
    $("#search_template").show();
</script>