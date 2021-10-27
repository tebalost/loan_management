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
$disbursementPending = mysqli_query($link, "SELECT fname, lname, baccount, date_release, amount, loan_product, fees, interest_value, loan_disbursed_by_id,loan_duration_period,
   loan_duration FROM loan_info, borrowers 
WHERE borrower=borrowers.id and loan_info.status in
 ('Pending Disbursement')  and loan_info.application_date between '$date1' and '$date2'") or die (mysqli_error($link));

$disbursementApproved = mysqli_query($link, "SELECT loan_info.id, fname, lname, baccount, date_release, amount, loan_product, fees, interest_value, loan_disbursed_by_id,loan_duration_period,
   loan_duration FROM loan_info, borrowers 
WHERE borrower=borrowers.id and loan_info.status not in
 ('Pending Disbursement', 'DECLINED','Pending')  and loan_info.application_date between '$date1' and '$date2'");


?>
<div class="callout callout-info">The Disbursement Report shows the money that has been released to borrowers.</div>


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



<div class="box box-info">
    <div class="box-body">
        <h3>Pending Disbursement</h3>
        <div class="col-sm-12 table-responsive">
            <div id="view-disbursement-report_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                <div class="col-sm-12 table-responsive">
                    <table id="pending" class="table table-bordered table-striped">
                        <thead>
                        <tr style="background-color: #D1F9FF" role="row">
                            <th>Borrower</th>
                            <th>Loan Product</th>
                            <th>Loan#</th>
                            <th>Interest</th>
                            <th>Duration</th>
                            <th>To be Disbursed</th>
                            <th>Outstanding</th>
                            <th>Status</th>
                            <th>Disbursed by</th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php while($row=mysqli_fetch_assoc($disbursementPending)){
                            $loan_product=$row['loan_product'];
                            $product = mysqli_fetch_assoc(mysqli_query($link,"select * from products where product_id='$loan_product'"));
                            $loan_product = $product['product_name'];

                            ?>
                        <tr>
                            <td><a href="#" target="_blank"><?php echo $row['fname']."&nbsp;".$row['lname']; ?></a></td>
                            <td><?php echo $loan_product; ?></td>
                            <td><a href="#" target="_blank"><?php echo $row['baccount']; ?></a></td>
                            <td><?php echo $row['interest_value']; ?></td>
                            <td><?php echo $row['loan_duration']."&nbsp;".$row['loan_duration_period']; ?></td>
                            <td class=" text-right"><?php echo $row['amount']; ?></td>
                            <td class=" text-right"><?php ?></td>
                            <td></td>
                            <td class=" text-left"><?php echo $row['loan_disbursed_by_id']; ?></td>
                        </tr>
                        <?php } ?>
                        </tbody>
                        <tfoot class="bg-gray">
                        <tr>
                            <th style="text-align:right" rowspan="1" colspan="1"></th>
                            <th style="text-align:right" rowspan="1" colspan="1"></th>
                            <th style="text-align:right" rowspan="1" colspan="1"></th>
                            <th style="text-align:right"
                                class="text-right"></th>
                            <th style="text-align:right"
                                class="text-right"></th>
                            <th style="text-align:right" class="text-right"></th>
                            <th style="text-align:right" class="text-right"></th>
                            <th style="text-align:right" class="text-right"></th>
                            <th style="text-align:right" class="text-right"></th>
                            <th></th>
                        </tr>
                        </tfoot>
                    </table>

                </div>

            </div>
        </div>
    </div>
</div>


<div class="box box-info">
    <div class="box-body">
        <h3>Disbursed Loans</h3>
        <div class="col-sm-12 table-responsive">
            <div id="view-disbursement-report_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                <div class="col-sm-12 table-responsive">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr style="background-color: #D1F9FF" role="row">
                            <th>Disbursed Date</th>
                            <th>Borrower</th>
                            <th>Loan Product</th>
                            <th>Loan#</th>
                            <th>Interest</th>
                            <th>Duration</th>
                            <th>To be Disbursed</th>
                            <th>Outstanding</th>
                            <th>Status</th>
                            <th>Disbursed by</th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php while($row=mysqli_fetch_assoc($disbursementApproved)){
                            //Get the Disbursement Date
                            $loanId = $row['id'];
                            $date = mysqli_fetch_assoc(mysqli_query($link, "select * from loan_statuses where loan='$loanId' and status not in ('DECLINED','Pending Disbursement')"));

                            $loan_product=$row['loan_product'];
                                    $product = mysqli_fetch_assoc(mysqli_query($link,"select * from products where product_id='$loan_product'"));
                            $loan_product = $product['product_name'];

                            ?>
                            <tr>
                                <td><?php echo $date['added_date']; ?></td>
                                <td><a href="#" target="_blank"><?php echo $row['fname']."&nbsp;".$row['lname']; ?></a></td>
                                <td><?php echo $loan_product; ?></td>
                                <td><a href="#" target="_blank"><?php echo $row['baccount']; ?></a></td>
                                <td><?php echo $row['interest_value']; ?></td>
                                <td><?php echo $row['loan_duration']."&nbsp;".$row['loan_duration_period']; ?></td>
                                <td class=" text-right"><?php echo $row['amount']; ?></td>
                                <td class=" text-right"><?php ?></td>
                                <td></td>
                                <td class=" text-left"><?php echo $row['loan_disbursed_by_id']; ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                        <tfoot class="bg-gray">
                        <tr>
                            <th style="text-align:right" rowspan="1" colspan="1"></th>
                            <th style="text-align:right" rowspan="1" colspan="1"></th>
                            <th style="text-align:right" rowspan="1" colspan="1"></th>
                            <th style="text-align:right"
                                class="text-right"></th>
                            <th style="text-align:right"
                                class="text-right"></th>
                            <th style="text-align:right" class="text-right"></th>
                            <th style="text-align:right" class="text-right"></th>
                            <th style="text-align:right" class="text-right"></th>
                            <th style="text-align:right" class="text-right"></th>
                            <th></th>
                        </tr>
                        </tfoot>
                    </table>

                </div>

            </div>
        </div>
    </div>
</div>

<p><b>*</b> The asterix are those loans that are used to restructure other loans. Only the <i>Excess Amount</i> entered
    when restructing the loan is shown.</p>
<script type="text/javascript" language="javascript">

    $(document).ready(function () {

        var dataTable = $('#view-disbursement-report').DataTable({
            "dom": '<"pull-left"f>r<"pull-right"l>tip', "order": [0, 'desc'],
            "fixedHeader": {
                "header": true
            },
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
                    "targets": [4, 5, 7, 8], // column or columns numbers
                    "orderable": false
                },

                {
                    className: "text-right",
                    "targets": [6, 7, 8]
                }
            ],
            "ajax": {
                url: "#", // json datasource
                type: "post",  // method  , by default get
                error: function () {  // error handling
                    $(".view-disbursement-report-error").html("");
                    $("#view-disbursement-report").append('<tbody class="borrowers-list-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                    $("#view-disbursement-report-processing").css("display", "none");

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
                // Total over this page
                pageTotal7 = api
                    .column(7, {page: 'current'})
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                // Update footer
                $(api.column(7).footer()).html(
                    '' + pageTotal7.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + ''
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


        $("#view-disbursement-report").unbind().on('click', '.confirm_action', function (e) {
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

</script>
<script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../plugins/datatables/dataTables.bootstrap.min.js"></script>
<!-- page script --><script>
    $(function () {
        $("#approved").DataTable();
        $("#declined").DataTable();
        $("#paidUp").DataTable();
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
