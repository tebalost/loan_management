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
//$allCodesIncome = mysqli_query($link, "SELECT * from gl_codes where code between '30001' and '30010'") or die (mysqli_error($link));
$allCodesExpenses = mysqli_query($link, "SELECT * from gl_codes where code between '40001' and '43010'") or die (mysqli_error($link));
$allCodesReceivables = mysqli_query($link, "SELECT * from gl_codes where code between '12001' and '12010'") or die (mysqli_error($link));

?>

<div id="search_template" style="">
    <form class="form-horizontal" method="post" enctype="multipart/form-data">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Date Range for report customization</h3>
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
    Please note that below detailed income statement contails <b>Income Receivables and Expenditure</b>.
</div>


<div class="box box-info">
    <div class="box-body">
        <div class="row">
            <div class="col-xs-6">
                <div id="export_button">
                    <div class="dt-buttons btn-group">
                        <a type="button" href="income-statement.php?printReq=<?php echo base64_encode($date1.">".$date2);?>" class="btn btn-warning pull-right"><i
                                    class="fa fa-print"></i> Print</a>
                    </div>
                </div>
            </div>
            <div class="col-xs-6 text-right">
                <h4 class="text-bold">From <?php
                    $date1Format=date_create("$date1");
                    $date1Display = date_format($date1Format,"d/m/Y H:i:s");

                    $date1Format=date_create("$date2");
                    $date2Display = date_format($date1Format,"d/m/Y H:i:s");

                    echo $date1Display; ?> - <?php echo $date2Display; ?> <small>(change dates above)</small></h4>
            </div>
        </div>
        <div class="col-xs-12">
            <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
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

                                            <div class="panel-group">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">
                                                        <h4 class="panel-title">
                                                            <a data-toggle="collapse"
                                                               href="#collapse<?php echo $count; ?>"><?php echo $name; ?></a>
                                                        </h4>
                                                    </div>
                                                    <div id="collapse<?php echo $count; ?>"
                                                         class="panel-collapse collapse">
                                                        <table class="table table-bordered table-condensed table-hover dataTable no-footer"
                                                               role="grid">
                                                            <th>Date</th>
                                                            <th>Transaction</th>
                                                            <th>Debit</th>
                                                            <th>Credit</th>
                                                            <?php
                                                            while ($account = mysqli_fetch_assoc($allTransactions)){
                                                                if ($account['debit'] == "0.00") {
                                                                    $debit = "";
                                                                } else {
                                                                    $debit = number_format($account['debit'], 2, ".", ",");
                                                                }
                                                                if ($account['credit'] == "0.00") {
                                                                    $credit = "";
                                                                } else {
                                                                    $credit = number_format($account['credit'], 2, ".", ",");
                                                                }
                                                                ?>
                                                                <tr>
                                                                    <td><?php echo $account['date']; ?></td>
                                                                    <td> <?php echo $account['transaction']; ?></td>
                                                                    <td><?php echo $debit; ?></td>
                                                                    <td><?php echo $credit; ?></td>
                                                                </tr>
                                                            <?php }
                                                            ?>
                                                        </table>
                                                        <div class="panel-footer" align="right">
                                                            <strong>Closing Balance: <?php echo number_format($balanceReceivables, 2, ".", ","); ?></strong>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-right"><b><?php echo number_format($balanceReceivables, 2, ".", ","); ?></b></td>

                                    </tr>
                                    <?php //$fees_total += $fees['sum(fee_amount)'];
                                    $count++;
                                    $totalReceivable+=$balanceReceivables;
                                } ?>

                                </tbody>

                                <tfoot>
                                <tr>
                                    <th style="font-style: italic; font-size: larger">GROSS INCOME</th>
                                    <td colspan="3" align="right" style="font-size: larger; font-style: italic"><strong><?php echo number_format($totalReceivable, 2, ".", ","); ?></strong></td>
                                </tr>
                                </tfoot>
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

                                            <div class="panel-group">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">
                                                        <h4 class="panel-title">
                                                            <a data-toggle="collapse"
                                                               href="#collapse<?php echo $count; ?>"><?php echo $name; ?></a>
                                                        </h4>
                                                    </div>
                                                    <div id="collapse<?php echo $count; ?>"
                                                         class="panel-collapse collapse">
                                                        <table class="table table-bordered table-condensed table-hover dataTable no-footer"
                                                               role="grid">
                                                            <th>Date</th>
                                                            <th>Transaction</th>
                                                            <th>Debit</th>
                                                            <th>Credit</th>
                                                            <?php
                                                            while ($account = mysqli_fetch_assoc($allTransactions)){
                                                                if ($account['debit'] == "0.00") {
                                                                    $debit = "";
                                                                } else {
                                                                    $debit = number_format($account['debit'], 2, ".", ",");
                                                                }
                                                                if ($account['credit'] == "0.00") {
                                                                    $credit = "";
                                                                } else {
                                                                    $credit = number_format($account['credit'], 2, ".", ",");
                                                                }
                                                                ?>
                                                                <tr>
                                                                    <td><?php echo $account['date']; ?></td>
                                                                    <td> <?php echo $account['transaction']; ?></td>
                                                                    <td><?php echo $debit; ?></td>
                                                                    <td><?php echo $credit; ?></td>
                                                                </tr>
                                                            <?php }
                                                            ?>
                                                        </table>
                                                        <div class="panel-footer" align="right">
                                                            <strong>Closing Balance: <?php echo number_format($balanceExpenses, 2, ".", ","); ?></strong>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-right"><b><?php echo number_format($balanceExpenses, 2, ".", ","); ?></b></td>

                                    </tr>
                                    <?php //$fees_total += $fees['sum(fee_amount)'];
                                    $count++;
                                    $totalExpenditure+=$balanceExpenses;
                                } ?>


                                </tbody>
                                <tfoot>
                                <tr>
                                    <th style="font-style: italic; font-size: larger">TOTAL EXPENDITURE</th>
                                    <td colspan="3" align="right" style="font-size: larger; font-style: italic"><strong><?php echo number_format($totalExpenditure, 2, ".", ","); ?></strong></td>
                                </tr>
                                <tr><td colspan="4" align="right" style="font-size: larger; font-style: italic; text-decoration-line: underline; text-decoration-style: double;"><b><?php echo number_format(($totalReceivable+$totalReceivable)-$totalExpenditure, 2, ".", ","); ?></b></td></tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<br>
<script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../plugins/datatables/dataTables.bootstrap.min.js"></script>
<!-- page script -->
<script>
    $(function () {
        $("#allFees").DataTable();
        $("#customerFees").DataTable();
        $("#others").DataTable();
        $('#pending').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": true
        });
    });
</script>
