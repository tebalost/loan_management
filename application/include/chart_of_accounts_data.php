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
$allCodes = mysqli_query($link, "SELECT * from gl_codes") or die (mysqli_error($link));
if(isset($_POST['saveCharts'])) {
    //mysqli_query($link,"delete from gl_codes");
    foreach ($_POST['accounting'] as $key => $value) {
        $gl = $value['glCode'];
        $name = $value['accountName'];
        $type = $value['accountType'];

        $existingAcc = mysqli_query($link, "select * from gl_codes where code='$gl'");
        if(mysqli_num_rows($existingAcc)==0) {
            $save = mysqli_query($link, "insert into gl_codes values(0,'$gl','$name','$type','$type','0')");
        }

    }

    echo '<div class="alert alert-success" >
                                        <a href = "#" class = "close" data-dismiss= "alert"> &times;</a>
                                         Successfully saved Chart of Accounts!&nbsp; &nbsp;&nbsp;
                                           </div>';
}
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
                            <table id="example1"
                                   class="table table-bordered table-condensed table-hover dataTable no-footer"
                                   role="grid">
                                <thead>
                                <tr style="background-color: #F2F8FF" role="row">
                                    <th rowspan="1" colspan="1">GL Code</th>
                                    <th rowspan="1" colspan="1" align="center">Name</th>
                                    <th rowspan="1" colspan="1">Type</th>
                                    <th class="text-right" rowspan="1" colspan="1">Amount</th>
                                </tr>
                                </thead>
                                <tbody>

                                <?php
                                $fees_total = $count = 0;
                                while ($codes = mysqli_fetch_assoc($allCodes)) {
                                    $glCode = $codes['code'];
                                    $name = $codes['name'];
                                    $type = $codes['type'];
                                    $balance = $codes['balance'];

                                    $allTransactions = mysqli_query($link, "SELECT * FROM journal_transactions  where account='$glCode'");

                                    ?>
                                    <tr role="row" class="even">
                                        <td><?php echo $glCode; ?></td>
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
                                                            <strong>Closing Balance: <?php echo number_format($balance, 2, ".", ","); ?></strong>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td align="center"><?php echo $type; ?></td>
                                        <td class="text-right"><b><?php echo number_format($balance, 2, ".", ","); ?></b></td>
                                        <td></td>
                                    </tr>
                                    <?php //$fees_total += $fees['sum(fee_amount)'];
                                    $count++;
                                } ?>


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

<!--<table class="table table-bordered table-condensed table-hover dataTable no-footer"
       role="grid">
    <?php
/*    while ($loans = mysqli_fetch_assoc($allLoans)){
    $loan = $loans['loan'];
    //get the b account
    $bacc=mysqli_fetch_assoc(mysqli_query($link,"select baccount from loan_info where id='$loan'"));
    $baccount=$bacc['baccount'];
    $borrower = mysqli_fetch_assoc(mysqli_query($link, "select fname, lname from borrowers where id  in (select borrower from loan_info where id='$loan' and loan_info.status not in
 ('DECLINED','Pending','Pending Disbursement')  and loan_info.application_date between '$date1' and '$date2')"));
    if (isset($borrower['fname'])){
    */?>
    <tr>
        <td><?php /*echo $loans['date_added']; */?></td>
        <td> <?php /*echo $baccount." - ".$borrower['fname'] . "&nbsp;" . $borrower['lname'] . "</td><td align='right'><b>" . number_format($loans['fee_amount'], 2, ".", ",");; */?></b></td>
        <?php /*}
        } */?>
</table>-->