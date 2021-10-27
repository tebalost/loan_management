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
?>
<div id="search_template" style="">
        <form action="" class="form-horizontal" method="post" enctype="multipart/form-data">
            <input type="hidden" name="search" value="1">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Select Report</h3>
                </div>
                <div class="row">
                    <div class="col-xs-11 margin">
                        <select class="form-control" name="report_type" id="inputReportType">
                            <option value="borrowers">Borrowers Report</option>
                            <option value="loans">Loans Report</option>
                            <option value="loan_products" selected="">Loan Products Report</option>
                            <option value="loan_officers">Loan Officer Report</option>
                        </select>
                    </div>

                </div>
            </div>
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
    <div class="box">
        <div class="box-body">
            <div class="row">

                <div class="col-xs-6 text-right">
                    <h4 class="text-bold">From <?php
                        $date1Format=date_create("$date1");
                        $date1Display = date_format($date1Format,"d/m/Y H:i:s");

                        $date1Format=date_create("$date2");
                        $date2Display = date_format($date1Format,"d/m/Y H:i:s");

                        echo $date1Display; ?> - <?php echo $date2Display; ?> <small>(change dates above)</small></h4>
                </div>
            </div>
            <div class="box">
                <div class="box-body">
                    <div id="reports_table_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer"><div class="row"><div class="col-sm-6"></div><div class="col-sm-6"></div></div><div class="row"><div class="col-sm-12"><div class="table-responsive"><table id="reports_table" class="table table-bordered table-condensed table-hover dataTable no-footer" role="grid">
                                        <thead>
                                        <tr style="background-color: #F0FF4D" role="row"><th class="text-right sorting_disabled" rowspan="1" colspan="1" style="width: 155px;">
                                                <small>Num Loans Released</small>
                                            </th><th class="text-right sorting_disabled" rowspan="1" colspan="1" style="width: 140px;">
                                                <small>Principal Released</small>
                                            </th><th class="text-right sorting_disabled" rowspan="1" colspan="1" style="width: 140px;">
                                                <small>Principal At Risk**</small>
                                            </th><th class="sorting_disabled" rowspan="1" colspan="1" style="width: 70px;">
                                            </th><th class="text-right sorting_disabled" rowspan="1" colspan="1" style="width: 74px;">
                                                <small>Principal</small>
                                            </th><th class="text-right sorting_disabled" rowspan="1" colspan="1" style="width: 74px;">
                                                <small>Interest</small>
                                            </th><th class="text-right sorting_disabled" rowspan="1" colspan="1" style="width: 41px;">
                                                <small>Fees</small>
                                            </th><th class="text-right sorting_disabled" rowspan="1" colspan="1" style="width: 65px;">
                                                <small>Penalty</small>
                                            </th><th class="text-right sorting_disabled" rowspan="1" colspan="1" style="width: 75px;">
                                                <small>Total</small>
                                            </th></tr>
                                        </thead>
                                        <tbody>
                                        <?php $allProducts=mysqli_query($link,"select * from products");
                                        $totalLoans = $totalPrincipal = $riskPrincipal =$totalPayments = $overallPayments = $overallRisk = 0;
                                        $totalRiskInterest=$totalRiskFees=$totalRiskPrincipal=$totalRiskPenalty=0;
                                        while($product=mysqli_fetch_assoc($allProducts)){
                                            $productId=$product['product_id'];
                                            $loans=mysqli_fetch_assoc(mysqli_query($link,"select loan_product, count(*), sum(amount) 
                                                    from loan_info where loan_product='$productId' and date_release between '$date1' and '$date2' and status not in ('Pending', 'Pending Disbursement','DECLINED') group by loan_product"));
                                            //Get Total Due from loan_schedules
                                            //Get Total Payments for these loans from loan_schedule
                                            $payments=mysqli_query($link,"select * from payments where account in 
                                                (select baccount from loan_info where loan_product='$productId' and date_release between '$date1' and '$date2' and status not in ('Pending', 'Pending Disbursement','DECLINED') group by loan_product)");

                                            //Get all loans for the current product ---$productId
                                            $schedules=mysqli_fetch_assoc(mysqli_query($link,"select sum(principal_due),sum(principal_payment), sum(interest), sum(interest_payment), sum(fees), sum(fees_payment), sum(penalty), sum(penalty_payment)   from pay_schedule where get_id in 
                                                (select id from loan_info where loan_product='$productId' and date_release between '$date1' and '$date2'  and status not in ('Pending', 'Pending Disbursement','DECLINED') group by loan_product)"));

                                            //Principal at risk
                                            $riskPrincipal=round($schedules['sum(principal_due)'],0)-$schedules['sum(principal_payment)'];
                                            $totalRiskPrincipal+=$riskPrincipal;

                                              //Interest at risk
                                            $riskInterest=$schedules['sum(interest)']-$schedules['sum(interest_payment)'];
                                            $totalRiskInterest+=$riskInterest;

                                            //Fees at risk
                                            $riskFees=$schedules['sum(fees)']-$schedules['sum(fees_payment)'];
                                            $totalRiskFees+=$riskFees;

                                            //Penalty at risk
                                            $riskPenalty=$schedules['sum(penalty)']-$schedules['sum(penalty_payment)'];
                                            $totalRiskPenalty+=$riskPenalty;
                                            ?>
                                        <tr>
                                            <td class="text-bold bg-gray text-left" colspan="9" data-search="<?php echo $product['product_name']; ?>">
                                                <?php echo $product['product_name']; ?> <small>(<a href="#c?product=<?php echo $product['product_id']; ?>" data-target= "#c<?php echo $product['product_id']; ?>" data-toggle="modal">View Logs</a></small>)<!--Show All Customers with this Loan, add product ID to the URL -->
                                            </td>
                                            <td style="display:none"></td>
                                            <td style="display:none"></td>
                                            <td style="display:none"></td>
                                            <td style="display:none"></td>
                                            <td style="display:none"></td>
                                            <td style="display:none"></td>
                                            <td style="display:none"></td>
                                            <td style="display:none"></td>
                                        </tr>
                                        <tr>
                                            <td class="" style="text-align:right" data-search="">
                                                <?php echo $loans['count(*)']; $totalLoans+=$loans['count(*)']; ?>
                                            </td>
                                            <td class="" style="text-align:right">
                                                <?php
                                                    echo number_format($loans['sum(amount)'],"2",".",",");
                                                    $totalPrincipal+=$loans['sum(amount)'];
                                                ?>
                                            </td>
                                            <td class="" style="text-align:right">
                                                <?php echo number_format(round($riskPrincipal,0),"2",".",","); ?>
                                            </td>
                                            <td class="text-bold text-red text-right">
                                                Due Loans:
                                            </td>
                                            <td class="text-bold text-red text-right">
                                                <?php echo number_format(round($riskPrincipal,0),"2",".",",");
                                                $overallRisk+=$riskPrincipal;
                                                ?>
                                            </td>
                                            <td class="text-bold text-red text-right">
                                                <?php echo number_format($riskInterest,"2",".",",");
                                                $overallDueInterest+=$riskInterest;
                                                ?>
                                            </td>
                                            <td class="text-bold text-red text-right">
                                                <?php echo number_format($riskFees,"2",".",",");
                                                $overallDueFees+=$riskFees;
                                                ?>
                                            </td>
                                            <td class="text-bold text-red text-right">
                                                <?php echo number_format($riskPenalty,"2",".",",");
                                                $overallDuePenalty+=$riskPenalty;
                                                ?>
                                            </td>
                                            <td class="text-bold text-red text-right">
                                                <?php echo number_format($riskPenalty+$riskFees+$riskInterest+$riskPrincipal,"2",".",",");
                                                $sumOfAllDues+=$riskPenalty+$riskFees+$riskInterest+$riskPrincipal;
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td data-search="">
                                            </td>
                                            <td>
                                            </td>
                                            <td>
                                            </td>
                                            <td class="text-bold text-green" style="text-align:right">
                                                Payments(<?php echo mysqli_num_rows($payments); $totalPayments+=mysqli_num_rows($payments); ?>):
                                            </td>
                                            <td class="text-bold text-green" style="text-align:right">
                                                <?php echo number_format($schedules['sum(principal_payment)'],"2",".",",");
                                                $overallPayments+=$schedules['sum(principal_payment)'];//Overall Principal Payments
                                                ?>
                                            </td>
                                            <td class="text-bold text-green" style="text-align:right">
                                                <?php echo number_format($schedules['sum(interest_payment)'],"2",".",",");
                                                $overallInterest+=$schedules['sum(interest_payment)'];//Overall Principal Payments
                                                ?>
                                            </td>
                                            <td class="text-bold text-green" style="text-align:right">
                                                <?php echo number_format($schedules['sum(fees_payment)'],"2",".",",");
                                                $overallFees+=$schedules['sum(fees_payment)'];//Overall Principal Payments
                                                ?>
                                            </td>
                                            <td class="text-bold text-green" style="text-align:right">
                                                <?php echo number_format($schedules['sum(penalty_payment)'],"2",".",",");
                                                $overallPenalty+=$schedules['sum(penalty_payment)'];//Overall Principal Payments
                                                ?>
                                            </td>
                                            <td class="text-bold text-green" style="text-align:right;">
                                                <?php echo number_format($schedules['sum(penalty_payment)'] + $schedules['sum(fees_payment)'] + $schedules['sum(interest_payment)'] + $schedules['sum(principal_payment)'],"2",".",",");
                                               $sumOfAllPayments+=$schedules['sum(penalty_payment)'] + $schedules['sum(fees_payment)'] + $schedules['sum(interest_payment)'] + $schedules['sum(principal_payment)'];//Overall Principal Payments
                                                ?>
                                            </td>
                                        </tr>
                                        <?php } ?>

                                        <tr role="row" class="odd">
                                            <td class="text-bold bg-navy disabled color-palette" colspan="9">
                                                Total
                                            </td>
                                            <td style="display:none"></td>
                                            <td style="display:none"></td>
                                            <td style="display:none"></td>
                                            <td style="display:none"></td>
                                            <td style="display:none"></td>
                                            <td style="display:none"></td>
                                            <td style="display:none"></td>
                                            <td style="display:none"></td>
                                        </tr>
                                        <tr role="row" class="even">
                                            <td style="text-align:right">
                                                <b><?php echo $totalLoans; ?></b>
                                            </td>
                                            <td style="text-align:right">
                                                <b><?php echo number_format($totalPrincipal,"2",".",","); ?></b>
                                            </td>
                                            <td style="text-align:right">
                                                <b><?php echo number_format($totalRiskPrincipal,"2",".",","); ?></b>
                                            </td>
                                            <td class="text-red text-bold" style="text-align:right">
                                                Due Loans:
                                            </td>
                                            <td class="text-red text-bold" style="text-align:right">
                                                <?php echo number_format($overallRisk,"2",".",",") ?>
                                            </td>
                                            <td class="text-red text-bold" style="text-align:right">
                                                <?php echo number_format($overallDueInterest,"2",".",",") ?>
                                            </td>
                                            <td class="text-red text-bold" style="text-align:right">
                                                <?php echo number_format($overallDueFees,"2",".",",") ?>
                                            </td>
                                            <td class="text-red text-bold" style="text-align:right">
                                                <?php echo number_format($overallDuePenalty,"2",".",",") ?>
                                            </td>
                                            <td class="text-red text-bold" style="text-align:right;">
                                                <?php echo number_format($sumOfAllDues,"2",".",",") ?>
                                            </td>
                                        </tr>
                                        <tr role="row" class="odd">
                                            <td>
                                            </td>
                                            <td>
                                            </td>
                                            <td>
                                            </td>
                                            <td class="text-green text-bold text-right">
                                                Payments(<?php echo $totalPayments; ?>):
                                            </td>
                                            <td class="text-green text-bold text-right">
                                                <?php echo number_format($overallPayments,"2",".",",") ?>
                                            </td>
                                            <td class="text-green text-bold text-right">
                                                <?php echo number_format($overallInterest,"2",".",",") ?>
                                            </td>
                                            <td class="text-green text-bold text-right">
                                                <?php echo number_format($overallFees,"2",".",",") ?>
                                            </td>
                                            <td class="text-green text-bold text-right">
                                                <?php echo number_format($overallPenalty,"2",".",",") ?>
                                            </td>
                                            <td class="text-green text-bold text-right">
                                                <?php echo number_format($sumOfAllPayments,"2",".",",") ?>

                                            </td>
                                        </tr>

                                        </tbody>
                                    </table></div></div></div><div class="row"><div class="col-sm-5"></div><div class="col-sm-7"></div></div></div>
                    <br>** <b>Principal At Risk</b> is the <b>Principal Released</b> amount after deducting <b>Principal Payments</b> for the date range selected above
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" language="javascript">
        $(document).ready(function() {
            var dataTable = $('#reports_table').DataTable( {
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


                "order": [[0, "asc" ]],
                "drawCallback": function( settings ) {
                    $("#reports_table").wrap( "<div class='table-responsive'></div>" );
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
        $( "#pre_loader" ).hide();
        $("#search_template" ).show();
    </script>
