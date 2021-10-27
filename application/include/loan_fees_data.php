<?php
//get all fees for the year for all loans that were disbursed
//Payments for open loans
if (isset($_POST['search'])) {
    $date1 = date("Y-m-d", strtotime(explode(" - ", $_POST['date'])[0]));
    $date2 = date("Y-m-d", strtotime(explode(" - ", $_POST['date'])[1]));
} else {
    $date1 = date('Y-m-01');
    $date2 = date('Y-m-t');
}
/*and fee_name !='Interest'*/
$allFees = mysqli_query($link, "SELECT fee_name, gl_code, sum(fee_amount) FROM loan_fees where loan in 
(SELECT id FROM loan_info where status not in ('DECLINED','Pending','Pending Disbursement')  and 
application_date between '$date1' and '$date2')  group by fee_name, gl_code") or die (mysqli_error($link));

$individual_fees = mysqli_query($link, "SELECT fname, lname, baccount, date_release, amount, fees FROM loan_info, borrowers 
WHERE borrower=borrowers.id and loan_info.status not in
 ('DECLINED','Pending','Pending Disbursement')  and loan_info.application_date between '$date1' and '$date2' ");

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
    Please note that below fee tables shows <b>all fees paid or fees that you have not collected</b>.
</div>

<div class="box box-info">
    <div class="box-body">
        <div class="col-xs-12">
            <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table id="example2"
                                   class="table table-bordered table-condensed table-hover dataTable no-footer"
                                   role="grid">
                                <thead>
                                <tr style="background-color: #F2F8FF" role="row">
                                    <th rowspan="1" colspan="1">Fees</th>
                                    <th rowspan="1" colspan="1" align="center">GL Code</th>
                                    <th class="text-right" rowspan="1" colspan="1">All Released Loans</th>
                                </tr>
                                </thead>
                                <tbody>

                                <?php
                                $fees_total = $count = 0;
                                while ($fees = mysqli_fetch_assoc($allFees)) {
                                    $fee_name = $fees['fee_name'];
                                    $allLoans = mysqli_query($link, "SELECT * FROM loan_fees  where fee_name='$fee_name' and date_added between '$date1' and '$date2'");
                                    ?>
                                    <tr role="row" class="even">
                                        <td class="text-bold">

                                            <div class="panel-group">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">
                                                        <h4 class="panel-title">
                                                            <a data-toggle="collapse"
                                                               href="#collapse<?php echo $count; ?>"><?php echo $fee_name; ?></a>
                                                        </h4>
                                                    </div>
                                                    <div id="collapse<?php echo $count; ?>"
                                                         class="panel-collapse collapse">
                                                        <table class="table table-bordered table-condensed table-hover dataTable no-footer"
                                                               role="grid">
                                                            <?php
                                                            while ($loans = mysqli_fetch_assoc($allLoans)){
                                                            $loan = $loans['loan'];
                                                            //get the b account
                                                            $bacc=mysqli_fetch_assoc(mysqli_query($link,"select baccount from loan_info where id='$loan'"));
                                                            $baccount=$bacc['baccount'];
                                                            $borrower = mysqli_fetch_assoc(mysqli_query($link, "select fname, lname from borrowers where id  in (select borrower from loan_info where id='$loan' and loan_info.status not in
 ('DECLINED','Pending','Pending Disbursement')  and loan_info.application_date between '$date1' and '$date2')"));
                                                            if (isset($borrower['fname'])){
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $loans['date_added']; ?></td>
                                                                <td> <?php echo $baccount." - ".$borrower['fname'] . "&nbsp;" . $borrower['lname'] . "</td><td align='right'><b>" . number_format($loans['fee_amount'], 2, ".", ",");; ?></b></td>
                                                                <?php }
                                                                } ?>
                                                        </table>
                                                        <div class="panel-footer" align="right">
                                                            <strong><?php echo number_format($fees['sum(fee_amount)'], 2, ".", ","); ?></strong>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td align="center"><?php echo $fees['gl_code']; ?></td>
                                        <td class="text-right">
                                            <strong><?php echo number_format($fees['sum(fee_amount)'], 2, ".", ","); ?></strong>
                                        </td>
                                    </tr>
                                    <?php $fees_total += $fees['sum(fee_amount)'];
                                    $count++;
                                } ?>
                                <tr class="danger odd" role="row">
                                    <td><b>Total Fees (All Loans)</b></td>
                                    <td style="text-align:right">
                                    <td style="text-align:right">
                                        <b><?php echo number_format($fees_total, 2, ".", ","); ?></b></td>
                                </tr>


                                </tbody>
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

<div class="box box-info">
    <div class="box-body">
        <br>
        <div class="col-sm-12 table-responsive">
            <table id="customerFees" class="table table-bordered table-striped">
                <thead>
                <tr style="background-color: #F2F8FF">
                    <th>Borrower</th>
                    <th>Loan#</th>
                    <th>Released</th>
                    <th>Principal</th>
                    <th>Fees Due</th>
                    <th>Paid Fees</th>
                    <th>Pending</th>
                </tr>
                </thead>

                <tbody>
                <?php
                $totalFees = 0;
                $totalPrincipal = 0;
                while ($fees = mysqli_fetch_assoc($individual_fees)) { ?>
                    <tr>
                        <td><?php echo $fees['fname'] . "&nbsp;" . $fees['lname']; ?></td>
                        <td><?php echo $fees['baccount']; ?></td>
                        <td><?php echo $fees['date_release']; ?></td>
                        <td class="text-right"><?php echo number_format($fees['amount'], 2, ".", ","); ?></td>
                        <td class="text-right"><?php echo number_format($fees['fees'], 2, ".", ","); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <?php $totalFees += $fees['fees'];
                    $totalPrincipal += $fees['amount'];
                } ?>
                </tbody>
                <tfoot class="bg-gray">
                <tr>
                    <th style="text-align:right" rowspan="1" colspan="1"></th>
                    <th style="text-align:right" rowspan="1" colspan="1"></th>
                    <th style="text-align:right" rowspan="1" colspan="1"></th>
                    <th style="text-align:right"
                        class="text-right"><?php echo number_format($totalPrincipal, 2, ".", ","); ?></th>
                    <th style="text-align:right"
                        class="text-right"><?php echo number_format($totalFees, 2, ".", ","); ?></th>
                    <th style="text-align:right" class="text-right">0.00</th>
                    <th style="text-align:right" class="text-right">0.00</th>
                </tr>
                </tfoot>
            </table>

        </div>
        <script type="text/javascript" language="javascript">

            $(document).ready(function () {

                var dataTable = $('#view-fees-report').DataTable({
                    "info": false,
                    "dom": '<"pull-left"f>r<"pull-right"l>tip',
                    "autoWidth": true,
                    "lengthMenu": [[20, 50, 100, 250, 500, 2500], [20, 50, 100, 250, 500, "All (Slow)"]],
                    "iDisplayLength": 20,
                    "processing": true,
                    "serverSide": true,
                    "language": {
                        "processing": "<img src='#'> Processing..",
                        "searchPlaceholder": "Search loans",
                        "emptyTable": "No data found. No loans found.",
                        search: ""
                    },
                    "columnDefs": [
                        {
                            "targets": [4, 5, 6], // column or columns numbers
                            "orderable": false
                        },

                        {
                            className: "text-right",
                            "targets": [4, 5, 6]
                        }
                    ],
                    "ajax": {
                        url: "https://x.loandisk.com/reports/process_fees_report.php?page_current=fees_report&", // json datasource
                        type: "post",  // method  , by default get
                        error: function () {  // error handling
                            $(".view-fees-report-error").html("");
                            $("#view-fees-report").append('<tbody class="borrowers-list-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                            $("#view-fees-report-processing").css("display", "none");

                        }
                    },
                    stateSave: true,
                    "footerCallback": function (row, data, start, end, display) {
                        var api = this.api(), data;

                        // Remove the formatting to get integer data for summation
                        var intVal = function (i) {
                            return typeof i === 'string' ?
                                i.replace(/[\$,]|<(\w+)\b[^<>]*>[\s\S]*?<\/\1>|<br\s*[\/]?>/g, '') * 1 :
                                typeof i === 'number' ?
                                    i : 0;
                        };
                        // Total over this page
                        pageTotal4 = api
                            .column(4, {page: 'current'})
                            .data()
                            .reduce(function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);

                        // Update footer
                        $(api.column(4).footer()).html(
                            '' + pageTotal4.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ''
                        );
                        // Total over this page
                        pageTotal5 = api
                            .column(5, {page: 'current'})
                            .data()
                            .reduce(function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);

                        // Update footer
                        $(api.column(5).footer()).html(
                            '' + pageTotal5.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ''
                        );
                        // Total over this page
                        pageTotal6 = api
                            .column(6, {page: 'current'})
                            .data()
                            .reduce(function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0);

                        // Update footer
                        $(api.column(6).footer()).html(
                            '' + pageTotal6.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ''
                        );
                    }
                });

                var buttons = new $.fn.dataTable.Buttons(dataTable, {
                    buttons:
                        [
                            {
                                extend: 'collection',
                                text: 'Export Data',
                                buttons: [
                                    {
                                        text: 'Print',
                                        extend: 'print',
                                        exportOptions: {
                                            columns: ':visible:not(.not-export-col)'
                                        },
                                        footer: true
                                    },
                                    {
                                        text: 'Copy',
                                        extend: 'copyHtml5',
                                        exportOptions: {
                                            columns: ':visible:not(.not-export-col)'
                                        },
                                        footer: true
                                    },
                                    {
                                        text: 'Excel',
                                        extend: 'excelHtml5',
                                        exportOptions: {
                                            columns: ':visible:not(.not-export-col)'
                                        },
                                        footer: true
                                    },
                                    {
                                        text: 'CSV',
                                        extend: 'csvHtml5',
                                        exportOptions: {
                                            columns: ':visible:not(.not-export-col)'
                                        },
                                        footer: true
                                    }
                                ]
                            }
                        ]
                }).container().appendTo($('#export_button'));


                $("#view-fees-report").unbind().on('click', '.confirm_action', function (e) {
                    e.preventDefault();
                    var href_value = $(this).attr('href');
                    var confirm_text = $(this).attr('actionconfirm');
                    $.confirm({
                        title: 'Please Confirm',
                        content: 'Are you sure you want to ' + confirm_text + '?',
                        type: 'green',
                        buttons: {
                            confirm: function () {
                                window.location = href_value;
                                return true;
                            },
                            cancel: function () {
                                return true;
                            }
                        }
                    });
                });
            });
        </script>

        <script>
            $("#pre_loader").hide();
            $("#search_template").show();
        </script>