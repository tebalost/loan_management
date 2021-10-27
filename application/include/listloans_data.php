<style>
    th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: #D1F9FF;
    }

</style>
<head>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.2.2/css/buttons.bootstrap.min.css">
</head>
<!-- Custom fonts for this template -->



<?php
if (isset($_POST['search'])) {
    $date1 = date("Y-m-d", strtotime(explode(" - ", $_POST['date'])[0]))." 00:00:00";
    $date2 = date("Y-m-d", strtotime(explode(" - ", $_POST['date'])[1]))." 23:59:59";
} else {
    $minDate=mysqli_fetch_assoc(mysqli_query($link,"select min(date_release) from loan_info"));
    $date1 = $minDate['min(date_release)']." 00:00:00";
    $date2 = date('Y-m-t')." 23:59:59";
}
?>
<div class="row">
    <section class="content">
        <div class="box box-success">
            <div class="box-body">


                <div class="row">
                    <div class="col-md-12">
                        <!-- Custom Tabs (Pulled to the right) -->
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#tab_1" data-toggle="tab">ALL Loans</a></li>
                                <li class="dropdown">
                                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                        Action <span class="caret"></span>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Download</a></li>
                                        <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Print</a></li>
                                        <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Email to Admin</a></li>
                                        <li role="presentation" class="divider"></li>
                                        <?php $batch = date('Ym');?>
                                        <li role="presentation"><a role="menuitem" tabindex="-1" href="viewBureauSubmittedFiles.php?batch=<?php echo $batch . "&&mid=" . base64_encode("403");?>">Current Bureau Records</a></li>
                                    </ul>
                                </li>
                                <li class="pull-right header"><i class="fa fa-th"></i>All Loans</li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_1">
                                    <div class="table-responsive">
                                        <div class="box-body">
                                            <form method="post">
                                                <a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>">
                                                    <button type="button" class="btn btn-flat btn-warning">
                                                        <i class="fa fa-mail-reply-all"></i>&nbsp;Back
                                                    </button>
                                                </a>
                                                <?php
                                                $check = mysqli_query($link, "SELECT * FROM emp_permission WHERE tid = '" . $_SESSION['tid'] . "' AND module_name = 'Loan Details'") or die ("Error" . mysqli_error($link));
                                                $get_check = mysqli_fetch_array($check);
                                                $pdelete = $get_check['pdelete'];
                                                $pcreate = $get_check['pcreate'];
                                                $pupdate = $get_check['pupdate'];

                                                $strJsonFileContents = file_get_contents('include/packages.json');
                                                $arrayOfTypes = json_decode($strJsonFileContents, true);
                                                ?>
                                                <?php echo ($pcreate == '1') ? '<a href="newloans.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("405") . '"><button type="button" class="btn btn-flat btn-success"><i class="fa fa-plus"></i>&nbsp;Add Loans</button></a>' : ''; ?>
                                                <?php
                                                $get = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
                                                $today = date('Y-m-d');
                                                $selectOverDueLoans = mysqli_query($link, "select * from loan_info where id in (SELECT get_id FROM pay_schedule where schedule<'$today' and payment<>balance) and status=''") or die (mysqli_error($link));
                                                $num = mysqli_num_rows($selectOverDueLoans);
                                                ?>

                                                <button type="button" class="btn btn-flat btn-danger"><i class="fa fa-times"></i>&nbsp;Overdue:&nbsp;<?php echo number_format($num, 0, '.', ','); ?>
                                                </button>
                                                <a href="missedpayment.php?tid=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("407"); ?>"
                                                                 class="btn btn-danger">Missed Payments <i class="fa fa-arrow-circle-right"></i></a>
                                                <a href="printloan.php?printReq=<?php echo base64_encode($date1.">".$date2);?>" class="btn btn-info btn-flat"><i
                                                            class="fa fa-print"></i>&nbsp;Print</a>
                                                <a href="exportloan.php?printReq=<?php echo base64_encode($date1.">".$date2);?>"class="btn btn-success btn-flat"><i
                                                            class="fa fa-send"></i>&nbsp;Export Excel</a>


                                            </form>
                                            <hr>
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
                                <span class="input-group">
                                  <button type="submit" name="search" class="btn bg-olive submit-button">Search!</button>
                                </span>
                                                                </div>

                                                            </div>
                                                        </div>

                                                    </div><!-- /.box -->
                                                </form>
                                            </div>
                                            <div class="well">
                                                Please note that below report shows <b>all Active loans Opened in the current Month, to view custom report, please select the date range</b>.
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-9">
                                                </div>
                                                <label for="searchInput" class="col-xs-1 control-label">Search</label>
                                                <div class="col-xs-2">
                                                <input id="searchInput" class="form-control" type="text">
                                                </div>
                                            </div>
                                            <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">


                                                <thead>
                                                <?php
                                                if (isset($_POST['delete'])) {
                                                    $idm = $_GET['id'];
                                                    $id = $_POST['selector'];
                                                    $N = count($id);
                                                    if ($id == '') {
                                                        echo '<div class="alert alert-danger" >
                                                                     <a href = "#" class = "close" data-dismiss= "alert"> &times;</a>
                                                                       Please Select rows to Delete Employee!!!&nbsp; &nbsp;&nbsp;
                                                                       </div>';
                                                    } else {
                                                        for ($i = 0; $i < $N; $i++) {
                                                            $result = mysqli_query($link, "DELETE FROM loan_info WHERE id ='$id[$i]'");
                                                            echo '<div class="alert alert-success" >
                                                                           <a href = "#" class = "close" data-dismiss= "alert"> &times;</a>
                                                                           Load was Successfully Deleted!!!&nbsp; &nbsp;&nbsp;
                                                                           </div>';
                                                        }
                                                    }
                                                }
                                                ?>

                                                <tr>
                                                    <th>Customer</th>
                                                    <th>Type</th>
                                                    <th>Release Date</th>
                                                    <th>Payment Date</th>
                                                    <th>Account</th>
                                                    <th>Principal</th>
                                                    <th>Instalment</th>
                                                    <th>Interest</th>
                                                    <th>Fees</th>
                                                    <th>Penalty</th>
                                                    <th>Arrears</th>
                                                    <th>Arrears Amount</th>
                                                    <th>Paid</th>
                                                    <th>Balance</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                $select = mysqli_query($link, "SELECT * FROM loan_info where status not in ('Pending','Pending Disbursement','DECLINED') and date_release between '$date1' and '$date2'") or die (mysqli_error($link));
                                               // $selectTotals = mysqli_query($link, "SELECT sum(amount) FROM loan_info where status not in ('Pending') and date_release between '$date1' and '$date2'") or die (mysqli_error($link));

                                                if (mysqli_num_rows($select) == 0) {
                                                    echo '<div class="alert alert-info">
                                     <a href = "#" class = "close" data-dismiss= "alert"> &times;</a>
                                    No data found yet!.....Check back later!!</div>';
                                                } else {
                                                    ?>

                                                    <?php
                                                    $arrears=$totalPayments=$principal=$expected=$totalRemaining=$arrearsAmount=$count=$totalFees=$instalments=$totalInterest=$overdueDays=0;
                                                    $provider = mysqli_fetch_assoc(mysqli_query($link, "select * from systemset"));
                                                    $companyName=$provider['trading_name'];

                                                    while ($row = mysqli_fetch_array($select)) {
                                                        $id = $row['id'];
                                                        $borrower = $row['borrower'];

                                                        //Check if the current loan is overdue or missed payment
                                                        $selectOverDueLoans = mysqli_query($link, "select * from loan_info where id in (SELECT get_id FROM pay_schedule where schedule<'$today' and payment<>balance  and get_id='$id') and status=''") or die (mysqli_error($link));

                                                        $strJsonFileContents = file_get_contents('include/packages.json');
                                                        $arrayOfTypes = json_decode($strJsonFileContents, true);
                                                        $status = $row['status'];
                                                        foreach ($arrayOfTypes['accountStatusCodes'] as $key => $value) {
                                                            if ($status == $key) {
                                                                $status = $value;
                                                            }
                                                            else if($status=="" && mysqli_num_rows($selectOverDueLoans)>0){
                                                                $status = "Missed Payment";
                                                                //Get how many are overdue with
                                                                $numDays=mysqli_fetch_assoc(mysqli_query($link,"SELECT datediff(NOW(),schedule) FROM pay_schedule where schedule<'$today' and payment<>balance  and get_id='$id'"));
                                                                $overdueDays=$numDays['datediff(NOW(),schedule)'];
                                                                $arrears+=1;
                                                                $overdueAmount=mysqli_fetch_assoc(mysqli_query($link,"select sum(balance)-sum(payment) from pay_schedule where schedule<'$today' and get_id='$id'"));
                                                                $arrearsAmount=$overdueAmount['sum(balance)-sum(payment)'];
                                                                $totalArrears+=$arrearsAmount;

                                                                //Apply Penalty Based on Settings//


                                                            }
                                                            else if($status==""){
                                                                $status = "Open and Active";
                                                            }
                                                        }

                                                        $upstatus = $row['upstatus'];
                                                        $selectin = mysqli_query($link, "SELECT fname, lname, status FROM borrowers WHERE id = '$borrower'") or die (mysqli_error($link));
                                                        $geth = mysqli_fetch_array($selectin);
                                                        $name = $geth['fname'];
                                                        $lname = $geth['lname'];
                                                        $userStatus = $geth['status'];

                                                        $collateral = mysqli_query($link, "SELECT * FROM collateral WHERE idm = '$borrower' and loan='$id'") or die (mysqli_error($link));
                                                        $getCollateral = mysqli_fetch_array($collateral);

                                                        $interest = mysqli_fetch_assoc(mysqli_query($link,"select sum(interest) from pay_schedule where get_id='$id'"));
                                                        $penalty = mysqli_fetch_assoc(mysqli_query($link,"select sum(penalty)-sum(penalty_payment) as penalty from pay_schedule where get_id='$id'"));
                                                        $allPenalty = mysqli_fetch_assoc(mysqli_query($link,"select sum(penalty)-sum(penalty_payment) as penalty from pay_schedule"));

                                                        ///Add More Checks to Verify Completion of the Loan
                                                        /// collateral
                                                        ///attachement
                                                        //fin_info
                                                        ?>
                                                        <?php
                                                        if ($upstatus == "Pending") {
                                                            ?>
                                                            <tr>

                                                                <td><?php echo $name . "&nbsp;" . $lname; ?></td>
                                                                <td>
                                                                    <?php
                                                                    $loan_product = $row['loan_product'];
                                                                    $totalLoanAmount = $row['balance'];
                                                                    $contract = $row['contract'];
                                                                    if($contract=="0"){
                                                                        $contract=rand(10000000,99999999);
                                                                        mysqli_query($link,"update loan_info set contract='$contract' where id='$id'");
                                                                    }
                                                                    $account=$row['baccount'];
                                                                    //Total Loan payment///
                                                                    $getPayments=mysqli_fetch_assoc(mysqli_query($link,"select sum(amount_to_pay) from payments where account='$account'"));

                                                                    $payments = $getPayments['sum(amount_to_pay)'];
                                                                    $balance = $totalLoanAmount-$payments;


                                                                    $product = mysqli_fetch_assoc(mysqli_query($link,"select * from products where product_id='$loan_product'"));
                                                                    $loan_product = $product['product_name'];
                                                                    echo $loan_product;
                                                                    ?>
                                                                </td>
                                                                <td><?php echo $row['date_release']; ?></td>
                                                                <td><?php echo $row['pay_date']; ?></td>
                                                                <td><?php echo $account; ?></td>
                                                                <td align="right"><?php echo number_format($row['amount'], 2, ".", ","); ?></td>
                                                                <td align="right"><?php echo number_format($row['amount_topay'], 2, ".", ","); ?></td>
                                                                <td align="right"><?php echo number_format($interest['sum(interest)'], 2, ".", ","); ?></td>
                                                                <td align="right"><?php echo number_format($row['fees'], 2, ".", ","); ?></td>
                                                                <td align="right"><?php echo number_format($penalty['penalty'], 2, ".", ","); ?></td>
                                                                <td align="right"><?php echo number_format($payments, 2, ".", ","); ?></td>
                                                                <td align="right"><?php echo number_format($balance, 2, ".", ","); ?></td>

                                                                <td>
                                                                    <span class="label label-<?php if ($status == 'Open and Active' || $status == 'Paid Up') echo 'success'; elseif ($status == 'Disapproved') echo 'danger'; else echo 'warning'; ?>"><?php echo $status; ?></span>
                                                                </td>
                                                                <td>
                                                                    <?php //echo ($pupdate == '1' && $userStatus == "Active" && mysqli_num_rows($collateral) > 0) ? '<a href="#myModal ' . $id . '"> <i data-target="#myModal' . $id . '" data-toggle="modal" class="fa fa-pencil"></i></a>' : ''; ?>
                                                                    &nbsp;
                                                                    <?php echo ($pupdate == '1') ? '<a href="viewborrowersloan.php?id=' . $borrower . '&&mid=' . base64_encode("405") . '&&loanId=' . $id . '&&contract=' . $contract . '"><i class="fa fa-eye"></i></a>' : ''; ?>
                                                                    <?php
                                                                    $se = mysqli_query($link, "SELECT * FROM attachment WHERE get_id = '$borrower'") or die (mysqli_error($link));
                                                                    while ($gete = mysqli_fetch_array($se)) {
                                                                        ?>
                                                                        <a href="<?php echo $gete['attached_file']; ?>"><i
                                                                                    class="fa fa-download"></i></a>
                                                                    <?php } ?>
                                                                </td>
                                                            </tr>
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <tr>

                                                                <td><?php echo $name . "&nbsp;" . $lname; ?></td>
                                                                <td>
                                                                    <?php
                                                                    $loan_product = $row['loan_product'];
                                                                    $totalLoanAmount = $row['balance'];
                                                                    $account=$row['baccount'];
                                                                    $contract = $row['contract'];
                                                                    $uri=base64_encode("405");
                                                                    $principal+=$row['amount'];
                                                                    $totalInterest+=$interest['sum(interest)'];
                                                                    $instalments+=$row['amount_topay'];
                                                                    $totalFees+=$row['fees'];
                                                                    if($contract=="0"){
                                                                        $contract=rand(10000000,99999999);
                                                                        mysqli_query($link,"update loan_info set contract='$contract' where id='$id'");
                                                                    }
                                                                    //Total Loan payment///
                                                                    $getPayments=mysqli_fetch_assoc(mysqli_query($link,"select sum(amount_to_pay) from payments where account='$account'"));

                                                                    $payments = $getPayments['sum(amount_to_pay)'];
                                                                    $balance = $totalLoanAmount-$payments;
                                                                    $totalPayments+=$payments;
                                                                    $totalRemaining+=$balance;
                                                                    $product = mysqli_fetch_assoc(mysqli_query($link,"select * from products where product_id='$loan_product'"));
                                                                    $loan_product = $product['product_name'];
                                                                    echo $loan_product;
                                                                    ?>
                                                                </td>
                                                                <td><?php echo $row['date_release']; ?></td>
                                                                <td><?php echo $row['pay_date']; ?></td>
                                                                <td><?php echo ($pupdate == '1') ? "<a href=viewborrowersloan.php?id=$borrower&&mid=$uri&&loanId=$id&&contract=$contract>$account</a>" : ''; ?></td>
                                                                <td align="right"><?php echo number_format($row['amount'], 2, ".", ""); ?></td>
                                                                <td align="right"><?php echo number_format($row['amount_topay'], 2, ".", ""); ?></td>
                                                                <td align="right"><?php echo number_format($interest['sum(interest)'], 2, ".", ""); ?></td>
                                                                <td align="right"><?php echo number_format($row['fees'], 2, ".", ""); ?></td>
                                                                <td align="right"><?php echo number_format($penalty['penalty'], 2, ".", ","); ?></td>
                                                                <td style="text-align: right"><?php if($status=="Missed Payment") {
                                                                        echo $overdueDays." Days";
                                                                } ?></td>
                                                                <td style="text-align: right"><?php if($status=="Missed Payment") {echo number_format($arrearsAmount, 2, ".", "");} ?></td>
                                                                <td align="right"><?php echo number_format($payments, 2, ".", ""); ?></td>
                                                                <td align="right"><?php echo number_format($balance, 2, ".", ""); ?></td>

                                                                <td>
                                                                    <span class="label label-<?php if ($status == 'Open and Active' || $status == 'Paid Up') echo 'success'; elseif ($status == 'Disapproved') echo 'danger'; else echo 'warning'; ?>"><?php echo $status; ?></span>
                                                                    <?php
                                                                    if($status=="Missed Payment"){
                                                                       echo "<br><strong><i style='color: red'><small>$overdueDays days overdue</small></i></strong>";
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <?php //echo ($pupdate == '1' && $userStatus == "Active" && mysqli_num_rows($collateral) > 0) ? '<a href="#myModal ' . $id . '"> <i data-target="#myModal' . $id . '" data-toggle="modal" class="fa fa-pencil"></i></a>' : ''; ?>
                                                                    &nbsp;
                                                                    <?php echo ($pupdate == '1') ? '<a href="viewborrowersloan.php?id=' . $borrower . '&&mid=' . base64_encode("405") . '&&loanId=' . $id . '&&contract=' . $contract . '"><i class="fa fa-eye"></i></a>' : ''; ?>
                                                                    <?php
                                                                    $se = mysqli_query($link, "SELECT * FROM attachment WHERE get_id = '$borrower'") or die (mysqli_error($link));
                                                                    while ($gete = mysqli_fetch_array($se)) {
                                                                        ?>
                                                                        <a href="<?php echo $gete['attached_file']; ?>"><i
                                                                                    class="fa fa-download"></i></a>
                                                                    <?php } ?>
                                                                </td>
                                                            </tr>
                                                        <?php }
                                                    }
                                                } ?>
                                                </tbody>
                                                <tfoot>
                                                <tr>
                                                    <th>TOTALS:</th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th></th>
                                                    <th style="text-align: right"><?php echo number_format($principal, 2, ".", ""); ?></th>
                                                    <th style="text-align: right"><?php echo number_format($instalments, 2, ".", ""); ?></th>
                                                    <th style="text-align: right"><?php echo number_format($totalInterest, 2, ".", ""); ?></th>
                                                    <th style="text-align: right"><?php echo number_format($totalFees, 2, ".", ""); ?></th>
                                                    <th style="text-align: right"><?php echo number_format($allPenalty['penalty'], 2, ".", ""); ?></th>
                                                    <th style="text-align: right"><?php echo $arrears; ?></th>
                                                    <th style="text-align: right"><?php echo number_format($totalArrears, 2, ".", ""); ?></th>
                                                    <th style="text-align: right"><?php echo number_format($totalPayments, 2, ".", ""); ?></th>
                                                    <th style="text-align: right"><?php echo number_format($totalRemaining, 2, ".", ""); ?></th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                                </tfoot>
                                            </table>

                                        </div>
                                    </div>
                                </div>

                            </div>
                            <!-- /.tab-content -->
                        </div>
                        <!-- nav-tabs-custom -->
                    </div>

                </div>
            </div>
        </div>
</div>

<style>
    body {
        margin: 2em;
    }
    ul.dt-button-collection {
        background-color: #e5e5e5;
        border: 1px solid #c0c0c0;
    }
    li.dt-button a:hover {
        background-color: transparent;
        color: #115094;
        font-weight: bold;
    }
    li.dt-button.active a,
    li.dt-button.active a:hover,
    li.dt-button.active a:focus {
        color: #337ab6;
        background-color: transparent;
        font-weight: bold;
    }
    li.dt-button.active a::before {
        content: "✔ ";
    }
    .dataTables_info {
        font-size: 0.8em;
        margin-top: -12px;
        text-align: right;
    }
    .previous a,
    .next a {
        font-weight: bold;
    }

</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.colVis.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
<script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
<script src="https://cdn.datatables.net/plug-ins/1.10.22/api/sum().js"></script>
<script>
    $(document).ready(function () {
        //Only needed for the filename of export files.
        //Normally set in the title tag of your page.document.title = 'Simple DataTable';
        //Define hidden columns
        var hCols = [1, 2, 3, 4, 11];
        // DataTable initialisation
        $('#example').append('<caption style="caption-side: bottom"><?php echo $companyName." all Loans table as ".date('d/m/Y'); ?></caption>');
        $("#example").DataTable({
            dom:
                "<'row'<'col-sm-4'B><'col-sm-2'l><'col-sm-6'p<br/>i>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12'p<br/>i>>",
            paging: true,
            autoWidth: true,
            searching: true,
            columnDefs: [
                {
                    visible: false,
                    targets: hCols
                }
            ],
            buttons: [
                {
                    extend: "colvis",
                    collectionLayout: "three-column",
                    text: function () {
                        var totCols = $("#example thead th").length;
                        var hiddenCols = hCols.length;
                        var shownCols = totCols - hiddenCols;
                        return "Columns (" + shownCols + " of " + totCols + ")";
                    },
                    prefixButtons: [
                        {
                            extend: "colvisGroup",
                            text: "Show all",
                            show: ":hidden"
                        },
                        {
                            extend: "colvisRestore",
                            text: "Restore"
                        }
                    ]
                },
                {
                    extend: "collection",
                    text: "Export",
                    buttons: [
                        {
                            text: "Excel",
                            extend: "excelHtml5",
                            footer: true,
                            exportOptions: {
                                columns: ":visible"
                            }
                        },
                        {
                            text: "CSV",
                            extend: "csvHtml5",
                            fieldSeparator: ";",
                            exportOptions: {
                                columns: ":visible"
                            }
                        }
                    ]
                }
            ],
            oLanguage: {
                oPaginate: {
                    sNext: '<span class="pagination-default">&#x276f;</span>',
                    sPrevious: '<span class="pagination-default">&#x276e;</span>'
                }
            },
            initComplete: function (settings, json) {
                // Adjust hidden columns counter text in button -->
                $("#example").on("column-visibility.dt", function (
                    e,
                    settings,
                    column,
                    state
                ) {
                    var visCols = $("#example thead tr:first th").length;
                    //Below: The minus 2 because of the 2 extra buttons Show all and Restore
                    var tblCols =
                        $(".dt-button-collection li[aria-controls=example] a").length - 2;
                    $(".buttons-colvis[aria-controls=example] span").html(
                        "Columns (" + visCols + " of " + tblCols + ")"
                    );
                    e.stopPropagation();
                });
            }
        });
    });


    $("#listingData_filter").addClass("hidden"); // hidden search input

    $("#searchInput").on("input", function (e) {
        e.preventDefault();
        $('#example').DataTable().search($(this).val()).draw();
    });

    jQuery.fn.dataTable.Api.register( 'sum()', function ( ) {
        return this.flatten().reduce( function ( a, b ) {
            if ( typeof a === 'string' ) {
                a = a.replace(/[^\d.-]/g, '') * 1;
            }
            if ( typeof b === 'string' ) {
                b = b.replace(/[^\d.-]/g, '') * 1;
            }

            return a + b;
        }, 0 );
    } );

</script>
