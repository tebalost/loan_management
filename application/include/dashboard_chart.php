<?php
//Loans
$year = date('Y');
$select = mysqli_query($link, "SELECT SUM(balance), count(*), SUM(interest_value), SUM(fees),SUM(balance) FROM loan_info where status =''") or die (mysqli_error($link));
$select1 = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
$row1 = mysqli_fetch_array($select1);
$currency = $row1['currency'];
$row = mysqli_fetch_array($select);

//Select all borrowers

/*$update_address = mysqli_query($link, "select * from borrowers where member=1");
while ($row = mysqli_fetch_assoc($update_address)) {
    $physicalAddress = explode(",", $row['comment'])[0];
    $postaladdress = explode(",", $row['comment'])[1];
    $district = explode(",", $row['comment'])[2];
    $country = explode(",", $row['comment'])[3];

    $id = $row['id'];
    $surname = $row['lname'];
    $middleName = $row['middlename'];
    if ($surname == "") {
        mysqli_query($link, "update borrowers set lname='$middleName', middlename='' where id='$id'");
    }
    //echo "$physicalAddress $postaladdress  $district $country<br>";
    //mysqli_query($link,"update borrowers set country = '$country', district='$district', addrs1='$physicalAddress', addrs2='$postaladdress' where id='$id'");

}*/

$missedPayments = mysqli_query($link, "SELECT * FROM pay_schedule where schedule<'$today' and payment<>balance");
$schedule = mysqli_fetch_assoc(mysqli_query($link, "SELECT count(distinct get_id) FROM pay_schedule where schedule>'$today' and payment<>balance 
and get_id in(select id from loan_info where status='')"));
$loansOnSchedule = $schedule['count(distinct get_id)'];
$total_principal = $currency . number_format($row['SUM(amount)'], 2, ".", ",") . "</b>";
$total_loans = $currency . number_format($row['SUM(balance)'], 2, ".", ",") . "</b>";
$loans = $row['SUM(amount)'];
$allLoans = $row['count(*)'];
$interest_loans = $currency . number_format($row['SUM(interest_value)'], 2, ".", ",") . "</b>";
$fees_loans = $currency . number_format($row['SUM(fees)'], 2, ".", ",") . "</b>";

//Payments
$select = mysqli_query($link, "SELECT SUM(amount_to_pay) FROM payments") or die (mysqli_error($link));
$row = mysqli_fetch_array($select);
$collections = $currency . number_format($row['SUM(amount_to_pay)'], 2, ".", ",") . "</b>";

//Payments for open loans
$select = mysqli_query($link, "SELECT SUM(amount_to_pay) FROM payments where account in (SELECT baccount FROM loan_info where status ='')") or die (mysqli_error($link));
$row = mysqli_fetch_array($select);
$payments = $row['SUM(amount_to_pay)'];


//Get total Principal Realeased for all Loans
$year = date('Y');
$select = mysqli_query($link, "SELECT SUM(amount) FROM loan_info where status not in ('DECLINED','Pending','Pending Disbursement')") or die (mysqli_error($link));
$row = mysqli_fetch_array($select);
$allPrincipal = $currency . number_format($row['SUM(amount)'], 2, ".", ",") . "</b>";

$totalOutstanding = $currency . number_format($loans - $payments, 2, ".", ",") . "</b>";

$selectBorrowers = mysqli_fetch_assoc(mysqli_query($link, "SELECT count(*) FROM borrowers where status ='Active' and gender !='' and id in (select borrower from loan_info)")) or die (mysqli_error($link));

?>
<section class="content">

    <?php $today = date('Y-m-d'); ?>
    <div class="well text-center">Last updated on <b><?php $new_date = date('jS F Y', strtotime("$today"));
            echo $new_date . "&nbsp;" . date('h:i:s A'); ?></b>. To see the latest version, please <b>reload this
            page</b>
    </div>


    <div class="box box-info">
        <div class="box-header"><h4 class="text-bold text-primary">Loans Statuses</h4></div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-4">
                    <p class="text-center">
                        <strong><a href="<?php echo "listborrowers.php?id=" . $_SESSION['tid'] . "&&mid=" . base64_encode("405") . "&&act=pendingBorrowers"; ?>">
                                <i style="font-size: x-large" class="fa  fa-user-plus text-info"></i> Incomplete
                                Profiles</a></strong>
                    </p>
                    <div class="progress-group">
                        <span class="progress-text">Incomplete Profiles / All Active Borrowers</span>
                        <span class="progress-number"><b><?php echo $borrowers['count(*)']; ?></b> / <strong><?php echo number_format($selectBorrowers['count(*)'], 0, ".", ","); ?></strong></span>

                        <div class="progress sm">
                            <div class="progress-bar progress-bar-info" style="width: 100%"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <p class="text-center">
                        <strong><a href="<?php echo "list_pending_loans.php?id=" . $_SESSION['tid'] . "&&mid=" . base64_encode("405"); ?>">
                                <i style="font-size: x-large" class="fa fa-check-square text-green"></i> Pending
                                Approval</a></strong>
                    </p>
                    <div class="progress-group">
                        <span class="progress-text"></i>Pending Approval / Total Principal</span>
                        <span class="progress-number"><b><?php echo $pendingLoans['count(*)']; ?></b> / <strong><?php echo number_format($pendingLoans['sum(amount)'], 2, ".", ","); ?></strong></span>

                        <div class="progress sm">
                            <div class="progress-bar progress-bar-success" style="width: 100%"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <p class="text-center">
                        <strong><a href="<?php echo "list_pending_disbursements.php?id=" . $_SESSION['tid'] . "&&mid=" . base64_encode("405"); ?>">
                                <i style="font-size: x-large" class="fa fa-money text-orange"></i> Pending Disbursement</a></strong>
                    </p>
                    <div class="progress-group">
                        <span class="progress-text">Pending / Total to be disbursed</span>
                        <span class="progress-number"><b><?php echo $loansPendingCashout['count(*)']; ?></b> / <strong><?php echo number_format($loansPendingCashout['sum(amount)'], 2, ".", ","); ?></strong></span>

                        <div class="progress sm">
                            <div class="progress-bar progress-bar-warning" style="width: 100%"></div>
                        </div>
                    </div>
                </div>
                <?php if ($reversedPayments['count(*)'] > 0) { ?>
                    <div class="col-md-4">
                        <p class="text-center">
                            <strong><a href="<?php echo "listpayment.php?id=" . $_SESSION['tid'] . "&&mid=" . base64_encode("408") . "&&act=reversedPayments"; ?>">
                                    <i style="font-size: x-large" class="fa fa-refresh text-red"></i> Payments Pending
                                    Reversal</a></strong>
                        </p>
                        <div class="progress-group">
                            <span class="progress-text">To be reversed / Total Payments</span>
                            <span class="progress-number"><b><?php echo $reversedPayments['count(*)']; ?></b> / <strong><?php echo $collections; ?></strong></span>

                            <div class="progress sm">
                                <div class="progress-bar progress-bar-danger" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <?php if ($paymentsDue['count(*)'] > 0) { ?>
                    <div class="col-md-4">
                        <p class="text-center">
                            <strong><a href="<?php echo "listpayment.php?id=" . $_SESSION['tid'] . "&&mid=" . base64_encode("408") . "&&act=duePayments"; ?>">
                                    <i style="font-size: x-large" class="fa fa-paypal text-info"></i> Loans Due Today</a></strong>
                        </p>
                        <div class="progress-group">
                            <span class="progress-text">Total Due / Total Due Amount</span>
                            <span class="progress-number"><b><?php echo $paymentsDue['count(*)']; ?></b> / <strong><?php echo number_format($paymentsDue['sum(balance)-sum(payment)'],'2','.',','); ?></strong></span>

                            <div class="progress sm">
                                <div class="progress-bar progress-bar-info" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <?php if ($countOverPayments > 0) { ?>
                    <div class="col-md-4">
                        <p class="text-center">
                            <strong><a href="<?php echo "listpayment.php?id=" . $_SESSION['tid'] . "&&mid=" . base64_encode("408") . "&&act=overPayments"; ?>">
                                    <i style="font-size: x-large" class="fa fa-credit-card text-info"></i>Loans Overpaid</a></strong>
                        </p>
                        <div class="progress-group">
                            <span class="progress-text">Total Loans / Total Over Payments (LSL)</span>
                            <span class="progress-number"><b><?php echo $countOverPayments; ?></b> / <strong><?php echo number_format($overpayments, "2", ".", " "); ?></strong></span>

                            <div class="progress sm">
                                <div class="progress-bar progress-bar-info" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-user"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Borrowers</span>
                    <span class="info-box-number"> <?php
                        $select = mysqli_query($link, "SELECT * FROM borrowers") or die (mysqli_error($link));
                        $num = mysqli_num_rows($select);
                        echo $num;
                        ?> - Total
                        <?php echo ($pread == 1) ? '<a href="listborrowers.php?tid=' . $_SESSION['tid'] . '&&mid=' . base64_encode("403") . '"><i class="fa fa-fw  fa-plus-circle"></i></a>' : '<a href="#" class="small-box-footer">-------</a>'; ?></span>
                    <span class="info-box-number">
                                                <?php
                                                $select = mysqli_query($link, "SELECT * FROM borrowers where status ='Active' and gender !=''") or die (mysqli_error($link));
                                                $num = mysqli_num_rows($select);
                                                echo $num;
                                                ?> - Active
                        <?php echo ($pread == 1) ? '<a href="listborrowers.php?tid=' . $_SESSION['tid'] . '&&mid=' . base64_encode("403") . '"><i class="fa fa-fw  fa-plus-circle"></i></a>' : '<a href="#" class="small-box-footer">-------</a>'; ?>
                        </span>

                </div><!-- /.info-box-content -->
            </div><!-- /.info-box -->
        </div><!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-files-o"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Principal<br>Released</span>
                    <span class="info-box-number"><?php echo $allPrincipal; ?><a href="#" target="_blank"><small><i
                                        class="fa fa-fw fa-plus-circle"></i></small></a></span>
                </div><!-- /.info-box-content -->
            </div><!-- /.info-box -->
        </div><!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-dollar"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Collections</span>
                    <span class="info-box-number"><?php echo $collections; ?><a href="#"><small><i
                                        class="fa fa-fw fa-plus-circle"></i></small></a></span><small>**incl
                        fees</small>
                </div><!-- /.info-box-content -->
            </div><!-- /.info-box -->
        </div><!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-balance-scale"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Outstanding<br>Open Loans</span>
                    <span class="info-box-number"><?php echo $totalOutstanding; ?><a href="#"><small><i
                                        class="fa fa-fw fa-plus-circle"></i></small></a></span>
                </div><!-- /.info-box-content -->
            </div><!-- /.info-box -->
        </div><!-- /.col -->
    </div>
    <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-balance-scale"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Principal<br>Open Loans</span>
                    <span class="info-box-number"><?php echo $total_principal; ?><a href="#"><small><i
                                        class="fa fa-fw fa-plus-circle"></i></small></a></span>
                </div><!-- /.info-box-content -->
            </div><!-- /.info-box -->
        </div><!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-balance-scale"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Interest<br>Open Loans</span>
                    <span class="info-box-number"><?php echo $interest_loans; ?><a href="#"><small><i
                                        class="fa fa-fw fa-plus-circle"></i></small></a></span>
                </div><!-- /.info-box-content -->
            </div><!-- /.info-box -->
        </div><!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-balance-scale"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Fees<br>Open Loans</span>
                    <span class="info-box-number"><?php echo $fees_loans; ?>
                        <?php echo ($pread == 1) ? '<a href="fees_report.php?tid=' . $_SESSION['tid'] . '&&mid=' . base64_encode("403") . '"><i class="fa fa-fw  fa-plus-circle"></i></a>' : ''; ?>
                    </span>
                </div><!-- /.info-box-content -->
            </div><!-- /.info-box -->
        </div><!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-balance-scale"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Penalty Outstanding<br>Open Loans R</span>
                    <span class="info-box-number">0<a href="#"><small><i
                                        class="fa fa-fw fa-plus-circle"></i></small></a></span>
                </div><!-- /.info-box-content -->
            </div><!-- /.info-box -->
        </div><!-- /.col -->
    </div>
    <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-green">O</span>
                <div class="info-box-content">
                    <span class="info-box-text">Open Loans</span>
                    <span class="info-box-number"><?php echo $allLoans; ?> - Active   <?php echo ($pread == 1) ? '<a href="listloans.php?tid=' . $_SESSION['tid'] . '&&mid=' . base64_encode("405") . '"> <i class="fa fa-fw fa-plus-circle"></i></a>' : ''; ?></span>
                    <span class="info-box-number"><?php echo $total_loans; ?><?php echo ($pread == 1) ? '<a href="listloans.php?tid=' . $_SESSION['tid'] . '&&mid=' . base64_encode("405") . '"> <i class="fa fa-fw fa-plus-circle"></i></a>' : ''; ?></span>
                </div><!-- /.info-box-content -->
            </div><!-- /.info-box -->
        </div><!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-green">F</span>
                <div class="info-box-content">
                    <span class="info-box-text">Fully Paid Loans</span>
                    <span class="info-box-number">0<a href="#"><small><i
                                        class="fa fa-fw fa-plus-circle"></i></small></a></span>
                </div><!-- /.info-box-content -->
            </div><!-- /.info-box -->
        </div><!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-green">R</span>
                <div class="info-box-content">
                    <span class="info-box-text">Restructured Loans</span>
                    <span class="info-box-number">0<a href="#"><small><i
                                        class="fa fa-fw fa-plus-circle"></i></small></a></span>
                </div><!-- /.info-box-content -->
            </div><!-- /.info-box -->
        </div><!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-green">D</span>
                <div class="info-box-content">
                    <span class="info-box-text">Default Loans</span>
                    <span class="info-box-number">0<a href="#"><small><i
                                        class="fa fa-fw fa-plus-circle"></i></small></a></span>
                </div><!-- /.info-box-content -->
            </div><!-- /.info-box -->
        </div><!-- /.col -->


    </div><!-- /.row -->

    <small><b>**</b>Total Collections includes deductable fees paid so the amount might not match with total amount in
        <b><a href="#">View Repayments</a></b>.</small>

</section>

<div class="row">
    <div class="col-md-12">
        <!-- AREA CHART -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><b><span style="color: #D72828">Loans Released</span> - Monthly</b></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="chart">
                    <canvas id="loanReleasedChart" style="height: 120px; width: 515px;" width="515"
                            height="120"></canvas>
                </div>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div><!-- /.col (LEFT) -->

</div><!-- /.row -->
<div class="row">
    <div class="col-md-12">
        <!-- AREA CHART -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><b><span style="color: #3C8DBC">Loan Collections</span> - Monthly</b></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="chart">
                    <canvas id="loanCollectedChart" style="height: 120px; width: 515px;" width="515"
                            height="120"></canvas>
                </div>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div><!-- /.col (LEFT) -->
</div><!-- /.row -->
<div class="row" style="display: none">
    <div class="col-md-12">
        <!-- AREA CHART -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><b><span style="color: #3C8DBC">Loan Collections</span> vs <span
                                style="color: #D72828">Due Loans</span> - Monthly</b></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="chart">
                    <canvas id="loanDueChart" style="height: 120px; width: 515px;" width="515"
                            height="120"></canvas>
                    <small>Due Loans is based on loan schedule. If you have not included fees or penalty in loan
                        schedule, it won't be accurate.</small>
                </div>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div><!-- /.col (LEFT) -->
</div><!-- /.row -->
<div class="row" style="display: none">
    <div class="col-md-12">
        <!-- AREA CHART -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><b><span style="color: #3C8DBC">Loan Collections</span> vs <span
                                style="color: #D72828">Loans Released</span> - Monthly</b></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="chart">
                    <canvas id="loanCollectionsReleasedChart" style="height: 120px; width: 515px;" width="515"
                            height="120"></canvas>
                </div>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div><!-- /.col (LEFT) -->
</div><!-- /.row -->
<div class="row">
    <div class="col-md-12">
        <!-- AREA CHART -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><b><span style="color: #D72828">Total Outstanding Open Loans</span> - Monthly</b>
                </h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="chart">
                    <canvas id="loanOutstandingBalanceChart" style="height: 120px; width: 515px;" width="515"
                            height="120"></canvas>
                </div>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div><!-- /.col (LEFT) -->
</div><!-- /.row -->
<div class="row">
    <div class="col-md-6">
        <!-- AREA CHART -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><b><span style="color: #D72828">Total Principal Outstanding Open Loans</span>
                        - Monthly</b></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="chart">
                    <canvas id="loanPrincipalOutstandingBalanceChart" style="height: 251px; width: 515px;"
                            width="515" height="251"></canvas>
                </div>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div><!-- /.col (LEFT) -->
    <div class="col-md-6">
        <!-- AREA CHART -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><b><span style="color: #D72828">Total Interest Outstanding Open Loans</span> -
                        Monthly</b></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="chart">
                    <canvas id="loanInterestOutstandingBalanceChart" style="height: 251px; width: 515px;"
                            width="515" height="251"></canvas>

                </div>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div><!-- /.col (LEFT) -->
</div>
<div class="row">
    <div class="col-md-6">
        <!-- AREA CHART -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><b><span style="color: #D72828">Total Fees Outstanding Open Loans</span> -
                        Monthly</b></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="chart">
                    <canvas id="loanFeesPrincipalDueChart" style="height: 251px; width: 515px;" width="515"
                            height="251"></canvas>
                </div>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div><!-- /.col (LEFT) -->
    <div class="col-md-6">
        <!-- AREA CHART -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><b><span style="color: #D72828">Total Penalty Outstanding Open Loans</span> -
                        Monthly</b></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="chart">
                    <canvas id="loanPenaltyInterestDueChart" style="height: 251px; width: 515px;" width="515"
                            height="251"></canvas>

                </div>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div><!-- /.col (LEFT) -->
</div>
<div class="row">
    <div class="col-md-12">
        <!-- AREA CHART -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><b><span style="color: #D72828">Due Principal Balance</span> - Monthly</b>
                </h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="chart">
                    <canvas id="loanPrincipalBalanceChart" style="height: 120px; width: 515px;" width="515"
                            height="120"></canvas>
                </div>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div><!-- /.col (LEFT) -->
</div><!-- /.row -->

<div class="row" style="display: none">
    <div class="col-md-6">
        <!-- AREA CHART -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><b>Principal - <span style="color: #d80a0a">Due</span> vs <span
                                style="color: #00a65a">Collections</span> - Monthly</b></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="chart">
                    <canvas id="loanPrincipalDueChart" style="height: 251px; width: 515px;" width="515"
                            height="251"></canvas>
                </div>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div><!-- /.col (LEFT) -->
    <div class="col-md-6">
        <!-- AREA CHART -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><b>Interest - <span style="color: #d80a0a">Due</span> vs <span
                                style="color: #00a65a">Collections</span> - Monthly</b></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="chart">
                    <canvas id="loanInterestDueChart" style="height: 251px; width: 515px;" width="515"
                            height="251"></canvas>

                </div>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div><!-- /.col (LEFT) -->
</div>
<div class="row" style="display: none">
    <div class="col-md-6">
        <!-- AREA CHART -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><b>Fees - <span style="color: #d80a0a">Due</span> vs <span
                                style="color: #00a65a">Collections</span> - Monthly</b></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="chart">
                    <canvas id="loanFeesDueChart" style="height: 251px; width: 515px;" width="515"
                            height="251"></canvas>
                    <small>Due Fees is based on loan schedule. If fees is not included in loan schedule, it won't be
                        accurate.</small>
                </div>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div><!-- /.col (LEFT) -->
    <div class="col-md-6">
        <!-- AREA CHART -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><b>Penalty - <span style="color: #d80a0a">Due</span> vs <span
                                style="color: #00a65a">Collections</span> - Monthly</b></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="chart">
                    <canvas id="loanPenaltyDueChart" style="height: 251px; width: 515px;" width="515"
                            height="251"></canvas>
                    <small>Due Penalty is based on loan schedule. If penalty is not included in loan schedule, it
                        won't be accurate.</small>
                </div>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div><!-- /.col (LEFT) -->
</div><!-- /.row -->

<div class="row">
    <div class="col-md-12">
        <!-- AREA CHART -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><b><span style="color: #3C8DBC">Number of Open Loans(Cumulative)</span> -
                        Monthly</b></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="chart">
                    <canvas id="loanOpenChart" style="height: 120px; width: 515px;" width="515"
                            height="120"></canvas>
                </div>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div><!-- /.col (LEFT) -->
</div><!-- /.row -->
<div class="row">
    <div class="col-md-6">
        <!-- LINE CHART -->
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title"><b>Number of Loans Released - Monthly</b></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="chart">
                    <canvas id="numLoansReleasedChart" style="height: 267px; width: 515px;" width="515"
                            height="267"></canvas>
                </div>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div><!-- /.col (LEFT) -->
    <div class="col-md-6">
        <!-- LINE CHART -->
        <div class="box box-info" style="display: block">
            <div class="box-header with-border">
                <h3 class="box-title"><b>Total Repayments Collected - Monthly</b></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="chart">
                    <canvas id="numRepaymentsCollectedChart" style="height: 267px; width: 515px;" width="515"
                            height="267"></canvas>
                </div>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div><!-- /.col (LEFT) -->
</div>

<div class="row">

    <div class="col-md-6">
        <!-- LINE CHART -->
        <div class="box box-info" style="display: block">
            <div class="box-header with-border">
                <h3 class="box-title"><b>Number of Fully Paid Loans - Monthly</b></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="chart">
                    <canvas id="numLoansFullyPaidCollectedChart" style="height: 267px; width: 515px;" width="515"
                            height="267"></canvas>
                </div>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div><!-- /.col (LEFT) -->
    <div class="col-md-6">
        <!-- DONUT CHART -->
        <div class="box box-info" style="display: block">
            <div class="box-header with-border">
                <h3 class="box-title"><b>Open Loans Status - To Date</b></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <canvas id="openLoansStatusChart" style="height: 200px; width: 535px;"></canvas>
                <div class="row">
                    <div class="col-md-12">
                        <small>
                            <ul class="chart-legend clearfix list-inline">
                                <li><i class="fa fa-circle-o text-green"></i> Loans on Schedule (<?php echo $loansOnSchedule; ?> Loans)</li>
                                <li><i class="fa fa-circle-o text-blue"></i> Loans Due Today (<?php echo $paymentsDue['count(*)']; ?> Loans)</li>
                                <li><i class="fa fa-circle-o text-yellow"></i> Missed Repayments (<?php echo mysqli_num_rows($missedPayments); ?> Loans)</li>
                                <li><i class="fa fa-circle-o text-purple"></i> Loans in Arrears (0 Loans)</li>
                                <li><i class="fa fa-circle-o text-red"></i> Past Maturity (0 Loans)</li>
                            </ul>
                        </small>
                    </div>
                </div>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div>
</div><!-- /.row -->


<div class="row">
    <div class="col-md-6">
        <div class="box box-danger" style="display:block;">
            <div class="box-header with-border">
                <h3 class="box-title"><b>Rate of Return % (All Time)</b></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <p>Percentage of the <b>Total Interest, Fees, and Penalty collected</b> until today out of the <b>Total
                        Principal due</b> until today</p>
                <div class="progress-group">
                    <span class="progress-text">All Loans</span>
                    <span class="progress-number"><b>0</b>/100%</span>

                    <div class="progress sm">
                        <div class="progress-bar progress-bar-aqua" style="width: 0%"></div>
                    </div>
                </div>
                <!-- /.progress-group -->
                <div class="progress-group">
                    <span class="progress-text">Open Loans</span>
                    <span class="progress-number"><b>0</b>/100%</span>

                    <div class="progress sm">
                        <div class="progress-bar progress-bar-yellow" style="width: 0%"></div>
                    </div>
                </div>
                <!-- /.progress-group -->
                <div class="progress-group">
                    <span class="progress-text">Fully Paid Loans</span>
                    <span class="progress-number"><b>0</b>/100%</span>

                    <div class="progress sm">
                        <div class="progress-bar progress-bar-green" style="width: 0%"></div>
                    </div>
                </div>
                <!-- /.progress-group -->
                <div class="progress-group">
                    <span class="progress-text">Default Loans</span>
                    <span class="progress-number"><b>0</b>/100%</span>

                    <div class="progress sm">
                        <div class="progress-bar progress-bar-red" style="width: 0%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <!-- DONUT CHART -->
        <div class="box box-danger" style="display: block">
            <div class="box-header with-border">
                <h3 class="box-title"><b>Active Male / Female Borrowers % (All Time)</b></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <canvas id="genderChart" style="height: 200px; width: 535px;"></canvas>
            </div><!-- /.box-body -->
        </div>
    </div>
</div>


<div class="row"  style="display: none">
    <div class="col-md-12">
        <!-- LINE CHART -->
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title"><b>Borrowers Age Group - No. of <span style="color: #3C8DBC">Open</span>,
                        <span style="color: #00a65a">Fully Paid</span> and <span
                                style="color: #d80a0a">Default</span> Loans</b></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="chart">
                    <canvas id="numBorrowerAgeGroupCountLoansChart" style="height: 127px; width: 515px;" width="515"
                            height="127"></canvas>
                </div>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div><!-- /.col (LEFT) -->
</div>
<div class="row" style="display: none">
    <div class="col-md-12">
        <!-- LINE CHART -->
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title"><b>Borrowers Age Group - Rate of Recovery (<span style="color: #3C8DBC">Open Loans %</span>,
                        <span style="color: #b7bd00">Open/Fully Paid/Default Loans %</span>)
                    </b>

                </h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="chart">
                    <canvas id="numBorrowerAgeGroupOpenLoansRecoveryRateChart" style="height: 127px; width: 515px;"
                            width="515" height="127"></canvas>
                </div>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div><!-- /.col (LEFT) -->
</div>

<div class="row">

    <!-- ./col -->
    <?php
    $check = mysqli_query($link, "SELECT * FROM emp_permission WHERE tid = '" . $_SESSION['tid'] . "' AND module_name = 'Loan Details'") or die ("Error" . mysqli_error($link));
    $get_check = mysqli_fetch_array($check);
    $pcreate = $get_check['pcreate'];
    $pread = $get_check['pread'];
    if ($pcreate == '1' || $pread == '1')
    {
    ?>
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
            <div class="inner">

                <h4>
                    <?php
                    $year = date('Y');
                    $select = mysqli_query($link, "SELECT SUM(amount) FROM loan_info where status not in ('DECLINED','Pending')") or die (mysqli_error($link));
                    while ($row = mysqli_fetch_array($select)) {
                        $select1 = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
                        while ($row1 = mysqli_fetch_array($select1)) {
                            $currency = $row1['currency'];
                            echo $currency . number_format($row['SUM(amount)'], 2, ".", ",") . "</b>";
                        }
                    }
                    ?>
                </h4>
                <p>Loans</p>
                <div class="icon"><img height="80" width="80" src="../img/ass.png">
                    <i class=""></i>
                </div>
            </div>
            <div class="icon"><i class=""></i></div>
            <?php echo ($pread == 1) ? '<a href="listloans.php?tid=' . $_SESSION['tid'] . '&&mid=' . base64_encode("405") . '" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> </div>' : '<a href="#" class="small-box-footer">-------</a>'; ?>
        </div>
        <?php
        }
        else {
            echo '';
        }
        ?>
        <!-- ./col -->
        <?php
        $check = mysqli_query($link, "SELECT * FROM emp_permission WHERE tid = '" . $_SESSION['tid'] . "' AND module_name = 'Borrower Details'") or die ("Error" . mysqli_error($link));
        $get_check = mysqli_fetch_array($check);
        $pcreate = $get_check['pcreate'];
        $pread = $get_check['pread'];
        if ($pcreate == '1' || $pread == '1') {
            ?>
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-red">
                    <div class="inner">
                        <h4>
                            <?php
                            $num = $selectBorrowers['count(*)'];
                            echo $num;
                            ?> Borrowers
                        </h4>
                        <p>
                            <?php
                            $select = mysqli_query($link, "SELECT * FROM borrowers where status ='Active' and gender='Male' and gender!='' and id in (select borrower from loan_info)") or die (mysqli_error($link));
                            $numMale = mysqli_num_rows($select);
                            $malePer = round(($numMale / $num) * 100, 0);
                            echo "<b>" . $numMale . " Males</b> | ";
                            $select = mysqli_query($link, "SELECT * FROM borrowers where status ='Active' and gender='Female' and gender!='' and id in (select borrower from loan_info)") or die (mysqli_error($link));
                            $numFemale = mysqli_num_rows($select);
                            $femalePer = round(($numFemale / $num) * 100, 0);
                            echo "<b>" . $numFemale . " Females</b>";
                            ?>
                        </p>
                    </div>

                    <?php echo ($pread == 1) ? '<a href="listborrowers.php?tid=' . $_SESSION['tid'] . '&&mid=' . base64_encode("403") . '" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>' : '<a href="#" class="small-box-footer">-------</a>'; ?>
                </div>
            </div>
            <?php
        } else {
            echo '';
        }
        ?>

        <?php
        $check = mysqli_query($link, "SELECT * FROM emp_permission WHERE tid = '" . $_SESSION['tid'] . "' AND module_name = 'Employee Details'") or die ("Error" . mysqli_error($link));
        $get_check = mysqli_fetch_array($check);
        $pcreate = $get_check['pcreate'];
        $pread = $get_check['pread'];
        if ($pcreate == '1' || $pread == '1') {
            ?>
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-yellow">
                    <div class="inner">

                        <h4>
                            <?php
                            $select = mysqli_query($link, "SELECT * FROM user") or die (mysqli_error($link));
                            $num = mysqli_num_rows($select);
                            echo $num;
                            ?>
                        </h4>
                        <p>Employees</p>
                    </div>
                    <div class="icon"><img height="80" width="80" src="../img/comittee.png">
                        <i class=""></i>
                    </div>
                    <?php echo ($pread == 1) ? '<a href="listemployee.php?tid=' . $_SESSION['tid'] . '&&mid=' . base64_encode("409") . '" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>' : '<a href="#" class="small-box-footer">-------</a>'; ?>
                </div>
            </div>
            <?php
        } else {
            echo '';
        }
        ?>


        <?php
        $check = mysqli_query($link, "SELECT * FROM emp_permission WHERE tid = '" . $_SESSION['tid'] . "' AND module_name = 'General Settings'") or die ("Error" . mysqli_error($link));
        $get_check = mysqli_fetch_array($check);
        $pcreate = $get_check['pcreate'];
        $pread = $get_check['pread'];
        if ($pcreate == '1') {
            ?>
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">

                        <h4>
                            <?php
                            $select = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
                            $num = mysqli_num_rows($select);
                            echo $num;
                            ?>
                        </h4>
                        <p>Company Setup</p>
                    </div>
                    <div class="icon"><img height="80" width="80" src="../img/setting.png">
                        <i class=""></i>
                    </div>
                    <a href="system_set.php?tid=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("411"); ?>"
                       class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <?php
        } else {
            echo '';
        }
        ?>

        <?php
        $check = mysqli_query($link, "SELECT * FROM emp_permission WHERE tid = '" . $_SESSION['tid'] . "' AND module_name = 'Internal Message'") or die ("Error" . mysqli_error($link));
        $get_check = mysqli_fetch_array($check);
        $pcreate = $get_check['pcreate'];
        $pread = $get_check['pread'];
        if ($pcreate == '1' || $pread == '1') {
            ?>
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-aqua">
                    <div class="inner">

                        <h4>
                            <?php
                            $select = mysqli_fetch_assoc(mysqli_query($link, "SELECT sum(messages) FROM sms_messages")) or die (mysqli_error($link));
                            echo $select['sum(messages)'];
                            ?>
                        </h4>
                        <p>Messages</p>
                    </div>
                    <div class="icon"><img height="80" width="80" src="../img/message.png">
                        <i class=""></i>
                    </div>
                    <?php echo ($pread == 1) ? '<a href="inboxmessage.php?tid=' . $_SESSION['tid'] . '&&mid=' . base64_encode("406") . '" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>' : '<a href="#" class="small-box-footer">-------</a>'; ?>
                </div>
            </div>
            <?php
        } else {
            echo '';
        }
        ?>

        <?php
        $check = mysqli_query($link, "SELECT * FROM emp_permission WHERE tid = '" . $_SESSION['tid'] . "' AND module_name = 'Missed Payment'") or die ("Error" . mysqli_error($link));
        $get_check = mysqli_fetch_array($check);
        $pcreate = $get_check['pcreate'];
        $pread = $get_check['pread'];
        if ($pcreate == '1' || $pread == '1') {
            ?>
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-yellow">
                    <div class="inner">

                        <h4>
                            <?php
                            $today = date('Y-m-d');
                            $select = mysqli_query($link, "SELECT SUM(balance) FROM pay_schedule where schedule<'$today' and payment<>balance") or die (mysqli_error($link));
                            while ($row = mysqli_fetch_array($select)) {
                                $select1 = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
                                while ($row1 = mysqli_fetch_array($select1)) {
                                    $currency = $row1['currency'];
                                    echo $currency . number_format($row['SUM(balance)'], 2, ".", ",") . "</b>";
                                }
                            }
                            ?>
                        </h4>
                        <p>Missed Payments</p>
                    </div>
                    <div class="icon">
                        <i class=""></i>
                    </div>
                    <a href="missedpayment.php?tid=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("407"); ?>"
                       class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <?php
        } else {
            echo '';
        }
        ?>

        <!-- ./col -->
        <?php
        $check = mysqli_query($link, "SELECT * FROM emp_permission WHERE tid = '" . $_SESSION['tid'] . "' AND module_name = 'Payment'") or die ("Error" . mysqli_error($link));
        $get_check = mysqli_fetch_array($check);
        $pcreate = $get_check['pcreate'];
        $pread = $get_check['pread'];
        if ($pcreate == '1' || $pread == '1') {
            ?>
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-red">
                    <div class="inner">

                        <h4>
                            <?php
                            $select = mysqli_query($link, "SELECT SUM(amount_to_pay) FROM payments") or die (mysqli_error($link));
                            while ($row = mysqli_fetch_array($select)) {
                                $select1 = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
                                while ($row1 = mysqli_fetch_array($select1)) {
                                    $currency = $row1['currency'];
                                    echo $currency . number_format($row['SUM(amount_to_pay)'], 2, ".", ",") . "</b>";
                                }
                            }
                            ?>
                        </h4>
                        <p>Payments </p>
                        <div class="icon"><img height="80" width="80" src="../img/utility.png">
                            <i class=""></i>
                        </div>
                    </div>

                    <?php echo ($pread == 1) ? '<a href="listpayment.php?tid=' . $_SESSION['tid'] . '&&mid=' . base64_encode("408") . '" class="small-box-footer">More info<i class="fa fa-arrow-circle-right"></i></a>' : '<a href="#" class="small-box-footer">-------</a>'; ?>
                </div>
            </div>
            <?php
        } else {
            echo '';
        }
        ?>
        <!-- ./col -->

        <?php
        $check = mysqli_query($link, "SELECT * FROM emp_permission WHERE tid = '" . $_SESSION['tid'] . "' AND module_name = 'Reports'") or die ("Error" . mysqli_error($link));
        $get_check = mysqli_fetch_array($check);
        $pcreate = $get_check['pcreate'];
        $pread = $get_check['pread'];
        if ($pcreate == '1' || $pread == '1') {
            ?>
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-aqua">
                    <div class="inner">

                        <h4>
                            1
                        </h4>
                        <p>Central Bank Report</p>
                    </div>
                    <div class="icon"><img height="80" width="80" src="../img/report.png">
                        <i class=""></i>
                    </div>
                    <?php echo ($pread == 1) ? '<a href="reporting/view/central-bank-report.php?tid=' . $_SESSION['tid'] . '&&mid=' . base64_encode("406") . '" class="small-box-footer">Download <i class="fa fa-arrow-circle-right"></i></a>' : '<a href="#" class="small-box-footer">-------</a>'; ?>
                </div>
            </div>
            <?php
        } else {
            echo '';
        }
        ?>

        <section class="content">
            <div class="row">
            </div>
            <!--  Event codes starts here-->
            <div class="box box-info">
                <?php
                $check = mysqli_query($link, "SELECT * FROM emp_permission WHERE tid = '" . $_SESSION['tid'] . "' AND module_name = 'Loan Details'") or die ("Error" . mysqli_error($link));
                $get_check = mysqli_fetch_array($check);
                $pcreate = $get_check['pcreate'];
                $pread = $get_check['pread'];
                if ($pcreate == '1' || $pread == '1') {
                    ?>
                    <div class="box-body">
                        <div class="alert alert-info" align="center" class="style2" style="color: #FF0000">LOAN
                            INFORMATION CHART
                        </div>

                        <div id="chartdiv"></div>
                    </div>
                    <?php
                } else {
                    echo '';
                }
                ?>

            </div>
    </div>

    <script>
        $(function () {

            /* ChartJS
             * -------
             * Here we will create a few charts using ChartJS
             */
            Chart.defaults.global.scaleLabel = function (label) {
                return label.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            };
            Chart.defaults.global.multiTooltipTemplate = function (label) {
                return label.datasetLabel + ': ' + label.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            };
            var areaChartOptions = {
                //Boolean - If we should show the scale at all
                showScale: true,
                //Boolean - Whether grid lines are shown across the chart
                scaleShowGridLines: true,
                //String - Colour of the grid lines
                scaleGridLineColor: "rgba(0,0,0,.05)",
                //Number - Width of the grid lines
                scaleGridLineWidth: 1,
                //Boolean - Whether to show horizontal lines (except X axis)
                scaleShowHorizontalLines: true,
                //Boolean - Whether to show vertical lines (except Y axis)
                scaleShowVerticalLines: true,
                //Boolean - Whether the line is curved between points
                bezierCurve: true,
                //Number - Tension of the bezier curve between points
                bezierCurveTension: 0.3,
                //Boolean - Whether to show a dot for each point
                pointDot: true,
                //Number - Radius of each point dot in pixels
                pointDotRadius: 4,
                //Number - Pixel width of point dot stroke
                pointDotStrokeWidth: 1,
                //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
                pointHitDetectionRadius: 20,
                //Boolean - Whether to show a stroke for datasets
                datasetStroke: true,
                //Number - Pixel width of dataset stroke
                datasetStrokeWidth: 4,
                //Boolean - Whether to fill the dataset with a color
                datasetFill: true,
                //String - A legend template
                legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
                tooltipTemplate: "<%= datasetLabel %> - <%= value.toLocaleString() %>",
                //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
                maintainAspectRatio: true,
                //Boolean - whether to make the chart responsive to window resizing
                responsive: true
            };


            //-------------
            //- Loans Released CHART -
            //--------------

            // Get context with jQuery - using jQuery's .get() method.
            var loanChartCanvas = $("#loanReleasedChart").get(0).getContext("2d");
            // This will get the first returned node in the jQuery collection.
            var loanChart = new Chart(loanChartCanvas);

            <?php
            $months = $loans = $paymentsCount = $loanOutstandingBalance = $loanInterestOutstandingBalance =
            $loanPrincipalOutstandingBalance = $loanFeesDue = $numLoans = $numLoansDeclined = $cumulativeLoans = $loansPaidUp = $paymentsTotal = [];
            for ($i = 0; $i < 12; $i++) {
                //Get All Months
                $months[] = date('M Y', strtotime("-$i month"));
                $month = date('Y-m', strtotime("-$i month"));

                $lastDayOfMonth = date('Y-m-t', strtotime("-$i month"))." 23:59:59";
                //Get All Loans for the current month (Principal)
                $cummulativeLoansCount = mysqli_fetch_assoc(mysqli_query($link, "select count(*) from loan_info where id in(select loan from loan_statuses where status='' and added_date <='$lastDayOfMonth')"));
                $totalLoanCumulative = $cummulativeLoansCount['count(*)'];

                if ($totalLoanCumulative == "") {
                    $totalLoanCumulative = "0";
                }
                $cumulativeLoans[] = $totalLoanCumulative;


                //Get All Loans for the current month (Principal)
                $allLoans = mysqli_fetch_assoc(mysqli_query($link, "select sum(amount) from loan_info where id in(select loan from loan_statuses where status='' and added_date like '$month%')"));
                $totalLoan = $allLoans['sum(amount)'];
                if ($totalLoan == "") {
                    $totalLoan = "0";
                }
                $loans[] = $totalLoan;

                //Get All Loans for the current month that are fully paid
                $allLoansPaidUp = mysqli_fetch_assoc(mysqli_query($link, "select count(*) from loan_info where id in(select loan from loan_statuses where status='P' and added_date like '$month%')"));
                $totalLoanPaidUp = $allLoansPaidUp['count(*)'];
                if ($totalLoanPaidUp == "") {
                    $totalLoanPaidUp = "0";
                }
                $loansPaidUp[] = $totalLoanPaidUp;

                //Get All Loans for the current month (Count), those that are opened
                $allLoansCount = mysqli_fetch_assoc(mysqli_query($link, "select count(*) from loan_info where id in(select loan from loan_statuses where status='' and added_date like '$month%')"));
                $totalLoans = $allLoansCount['count(*)'];
                if ($totalLoans == "") {
                    $totalLoans = "0";
                }
                $numLoans[] = $totalLoans;

                //Get All Loans for the current month (Count), those that are Declined
                $allLoansCountDeclined = mysqli_fetch_assoc(mysqli_query($link, "select count(*) from loan_info where id in(select loan from loan_statuses where status='DECLINED' and added_date like '$month%')"));
                $totalLoansDeclined = $allLoansCountDeclined['count(*)'];
                if ($totalLoansDeclined == "") {
                    $totalLoansDeclined = "0";
                }
                $numLoansDeclined[] = $totalLoansDeclined;

                //Count of payments
                $allPayments = mysqli_fetch_assoc(mysqli_query($link, "select count(distinct account) from payments where pay_date like '$month%'"));
                $totalPayment = $allPayments['count(distinct account)'];
                $paymentsCount[] = $totalPayment;

                //Total of payments
                $allPaymentsTotal = mysqli_fetch_assoc(mysqli_query($link, "select sum(amount_to_pay) from payments where pay_date like '$month%'"));
                $totalPayments = $allPaymentsTotal['sum(amount_to_pay)'];
                $paymentsTotal[] = $totalPayments;

                //loanOutstandingBalanceChart (Outstanding at that month and Paid at that month)// Get from Pay Schedule
                //Schedule for the current month
                $schedule = mysqli_fetch_assoc(mysqli_query($link, "select sum(balance)-sum(payment) from pay_schedule where schedule like '$month%' and get_id in 
                (select id from loan_info where status='')"));
                $loanOutstandingBalance[] = $schedule['sum(balance)-sum(payment)'];

                //loanInterestOutstandingBalanceChart
                $schedule = mysqli_fetch_assoc(mysqli_query($link, "select sum(principal_due)-sum(principal_payment) from pay_schedule where schedule like '$month%' and get_id in 
                (select id from loan_info where status='')"));
                $loanPrincipalOutstandingBalance[] = $schedule['sum(principal_due)-sum(principal_payment)'];

                //loanInterestOutstandingBalanceChart
                $schedule = mysqli_fetch_assoc(mysqli_query($link, "select sum(interest)-sum(interest_payment) from pay_schedule where schedule like '$month%' and get_id in 
                (select id from loan_info where status='')"));
                $loanInterestOutstandingBalance[] = $schedule['sum(interest)-sum(interest_payment)'];

                //loanFeesDue
                $schedule = mysqli_fetch_assoc(mysqli_query($link, "select sum(fees)-sum(fees_payment) from pay_schedule where schedule like '$month%' and get_id in 
                (select id from loan_info where status='')"));
                $loanFeesDue[] = $schedule['sum(fees)-sum(fees_payment)'];
            }
            $loanMonths = json_encode($months);
            $loanAmounts = json_encode($loans);
            $paymentCount = json_encode($paymentsCount);
            $loanOutstandingBalanceChart=json_encode($loanOutstandingBalance);
            $loanPrincipalOutstandingBalanceChart = json_encode($loanPrincipalOutstandingBalance);
            $loanInterestOutstandingBalanceChart = json_encode($loanInterestOutstandingBalance);
            $loanFeesDueChart=json_encode($loanFeesDue);
            $numLoans=json_encode($numLoans);
            $numLoansDeclined=json_encode($numLoansDeclined);
            $cumulativeLoans=json_encode($cumulativeLoans);
            $fullyPaidUpLoans = json_encode($loansPaidUp);
            $totalPaymentsReceived = json_encode($paymentsTotal);
            ?>

            var loanChartData = {
                labels: <?php echo $loanMonths; ?>,
                datasets: [
                    {
                        label: "Loans Released",
                        fillColor: "rgba(210, 214, 222, 1)",
                        strokeColor: "rgba(215,  40, 40, 0.9)",
                        pointColor: "#D72828",
                        pointStrokeColor: "#c1c7d1",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(220,220,220,1)",
                        data: <?php echo $loanAmounts; ?>
                    },
                    {}
                ]
            };


            //Create the line chart
            var loanChartOptions = areaChartOptions;
            loanChartOptions.datasetFill = true;
            loanChart.Line(loanChartData, loanChartOptions);

            //-------------
            //- Loans Collected CHART -
            //--------------

            // Get context with jQuery - using jQuery's .get() method.
            var loanChartCanvas = $("#loanCollectedChart").get(0).getContext("2d");
            // This will get the first returned node in the jQuery collection.
            var loanChart = new Chart(loanChartCanvas);

            var loanChartData = {
                labels: <?php echo $loanMonths; ?>,
                datasets: [
                    {
                        label: "Loans Collected",
                        fillColor: "rgba(210, 214, 222, 1)",
                        strokeColor: "rgba(60,141,188,0.8)",
                        pointColor: "#3C8DBC",
                        pointStrokeColor: "rgba(60,141,188,1)",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(60,141,188,1)",
                        data: <?php echo $paymentCount; ?>
                    },
                    {}
                ]
            };

            //Create the line chart
            var loanChartOptions = areaChartOptions;
            loanChartOptions.datasetFill = true;
            loanChart.Line(loanChartData, loanChartOptions);

            //-------------
            //- Due Loans CHART -
            //--------------

            // Get context with jQuery - using jQuery's .get() method.
            var loanChartCanvas = $("#loanDueChart").get(0).getContext("2d");
            // This will get the first returned node in the jQuery collection.
            var loanChart = new Chart(loanChartCanvas);

            var loanChartData = {
                labels: <?php echo $loanMonths; ?>,
                datasets: [
                    {
                        label: "Loans Collected",
                        fillColor: "rgba(60,141,188,0.9)",
                        strokeColor: "rgba(60,141,188,0.8)",
                        pointColor: "#3C8DBC",
                        pointStrokeColor: "rgba(60,141,188,1)",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(60,141,188,1)",
                        data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                    },
                    {
                        label: "Due Loans",
                        fillColor: "rgba(210, 214, 222, 1)",
                        strokeColor: "rgba(215,  40, 40, 0.9)",
                        pointColor: "#D72828",
                        pointStrokeColor: "#c1c7d1",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(220,220,220,1)",
                        data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                    },
                    {}
                ]
            };

            //Create the line chart
            var loanChartOptions = areaChartOptions;
            loanChartOptions.datasetFill = false;
            loanChart.Line(loanChartData, loanChartOptions);


            //-------------
            //- Principal Loans Outstanding CHART -
            //--------------

            // Get context with jQuery - using jQuery's .get() method.
            var loanChartCanvas = $("#loanPrincipalOutstandingBalanceChart").get(0).getContext("2d");
            // This will get the first returned node in the jQuery collection.
            var loanChart = new Chart(loanChartCanvas);

            var loanChartData = {
                labels: <?php echo $loanMonths; ?>,
                datasets: [
                    {
                        label: "Principal Outstanding",
                        fillColor: "rgba(210, 214, 222, 1)",
                        strokeColor: "rgba(215,  40, 40, 0.9)",
                        pointColor: "#D72828",
                        pointStrokeColor: "#c1c7d1",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(220,220,220,1)",
                        data: <?php echo $loanPrincipalOutstandingBalanceChart; ?>
                    },
                    {}
                ]
            };

            //Create the line chart
            var loanChartOptions = areaChartOptions;
            loanChartOptions.datasetFill = true;
            loanChart.Line(loanChartData, loanChartOptions);

            //-------------
            //- Interest Loans Outstanding CHART -
            //--------------

            // Get context with jQuery - using jQuery's .get() method.
            var loanChartCanvas = $("#loanInterestOutstandingBalanceChart").get(0).getContext("2d");
            // This will get the first returned node in the jQuery collection.
            var loanChart = new Chart(loanChartCanvas);

            var loanChartData = {
                labels: <?php echo $loanMonths; ?>,
                datasets: [
                    {
                        label: "Interest Outstanding",
                        fillColor: "rgba(210, 214, 222, 1)",
                        strokeColor: "rgba(215,  40, 40, 0.9)",
                        pointColor: "#D72828",
                        pointStrokeColor: "#c1c7d1",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(220,220,220,1)",
                        data: <?php echo $loanInterestOutstandingBalanceChart; ?>
                    },
                    {}
                ]
            };

            //Create the line chart
            var loanChartOptions = areaChartOptions;
            loanChartOptions.datasetFill = true;
            loanChart.Line(loanChartData, loanChartOptions);

            //-------------
            //- Fees Loans Outstanding CHART -
            //--------------

            // Get context with jQuery - using jQuery's .get() method.
            var loanChartCanvas = $("#loanFeesPrincipalDueChart").get(0).getContext("2d");
            // This will get the first returned node in the jQuery collection.
            var loanChart = new Chart(loanChartCanvas);

            var loanChartData = {
                labels: <?php echo $loanMonths; ?>,
                datasets: [
                    {
                        label: "Fees Outstanding",
                        fillColor: "rgba(210, 214, 222, 1)",
                        strokeColor: "rgba(215,  40, 40, 0.9)",
                        pointColor: "#D72828",
                        pointStrokeColor: "#c1c7d1",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(220,220,220,1)",
                        data: <?php echo $loanFeesDueChart; ?>
                    },
                    {}
                ]
            };

            //Create the line chart
            var loanChartOptions = areaChartOptions;
            loanChartOptions.datasetFill = true;
            loanChart.Line(loanChartData, loanChartOptions);

            //-------------
            //- Penalty Loans Outstanding CHART -
            //--------------

            // Get context with jQuery - using jQuery's .get() method.
            var loanChartCanvas = $("#loanPenaltyInterestDueChart").get(0).getContext("2d");
            // This will get the first returned node in the jQuery collection.
            var loanChart = new Chart(loanChartCanvas);

            var loanChartData = {
                labels: <?php echo $loanMonths; ?>,
                datasets: [
                    {
                        label: "Penalty Outstanding",
                        fillColor: "rgba(210, 214, 222, 1)",
                        strokeColor: "rgba(215,  40, 40, 0.9)",
                        pointColor: "#D72828",
                        pointStrokeColor: "#c1c7d1",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(220,220,220,1)",
                        data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                    },
                    {}
                ]
            };

            //Create the line chart
            var loanChartOptions = areaChartOptions;
            loanChartOptions.datasetFill = true;
            loanChart.Line(loanChartData, loanChartOptions);

            //-------------
            //- Total Loans Outstanding CHART -
            //--------------

            // Get context with jQuery - using jQuery's .get() method.
            var loanChartCanvas = $("#loanOutstandingBalanceChart").get(0).getContext("2d");
            // This will get the first returned node in the jQuery collection.
            var loanChart = new Chart(loanChartCanvas);

            var loanChartData = {
                labels: <?php echo $loanMonths; ?>,
                datasets: [
                    {
                        label: "Total Outstanding",
                        fillColor: "rgba(210, 214, 222, 1)",
                        strokeColor: "rgba(215,  40, 40, 0.9)",
                        pointColor: "#D72828",
                        pointStrokeColor: "#c1c7d1",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(220,220,220,1)",
                        data: <?php echo $loanOutstandingBalanceChart; ?>
                    },
                    {}
                ]
            };

            //Create the line chart
            var loanChartOptions = areaChartOptions;
            loanChartOptions.datasetFill = true;
            loanChart.Line(loanChartData, loanChartOptions);

            //-------------
            //- Principal Balance Loans CHART -
            //--------------

            // Get context with jQuery - using jQuery's .get() method.
            var loanChartCanvas = $("#loanPrincipalBalanceChart").get(0).getContext("2d");
            // This will get the first returned node in the jQuery collection.
            var loanChart = new Chart(loanChartCanvas);

            var loanChartData = {
                labels: <?php echo $loanMonths; ?>,
                datasets: [
                    {
                        label: "Principal Balance",
                        fillColor: "rgba(210, 214, 222, 1)",
                        strokeColor: "rgba(215,  40, 40, 0.9)",
                        pointColor: "#D72828",
                        pointStrokeColor: "#c1c7d1",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(220,220,220,1)",
                        data: <?php echo $loanPrincipalOutstandingBalanceChart; ?>
                    },
                    {}
                ]
            };

            //Create the line chart
            var loanChartOptions = areaChartOptions;
            loanChartOptions.datasetFill = true;
            loanChart.Line(loanChartData, loanChartOptions);

            //-------------
            //-  Due vs Collections Loans BAR OPTIONS -
            //--------------
            var barChartOptions = {
                //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
                scaleBeginAtZero: true,
                //Boolean - Whether grid lines are shown across the chart
                scaleShowGridLines: true,
                //String - Colour of the grid lines
                scaleGridLineColor: "rgba(0,0,0,.05)",
                //Number - Width of the grid lines
                scaleGridLineWidth: 1,
                //Boolean - Whether to show horizontal lines (except X axis)
                scaleShowHorizontalLines: true,
                //Boolean - Whether to show vertical lines (except Y axis)
                scaleShowVerticalLines: true,
                //Boolean - If there is a stroke on each bar
                barShowStroke: true,
                //Number - Pixel width of the bar stroke
                barStrokeWidth: 2,
                //Number - Spacing between each of the X value sets
                barValueSpacing: 5,
                //Number - Spacing between data sets within X values
                barDatasetSpacing: 1,
                //String - A legend template
                legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].fillColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
                //Boolean - whether to make the chart responsive
                responsive: true,
                maintainAspectRatio: true
            };
            barChartOptions.datasetFill = false;

            //-------------
            //- Collections vs Disbursed CHART -
            //--------------
            var BarData = {
                labels: <?php echo $loanMonths; ?>,
                datasets: [
                    {
                        label: "Collections",
                        fillColor: "rgba(60,141,188,0.9)",
                        strokeColor: "rgba(60,141,188,0.8)",
                        pointColor: "#3b8bba",
                        pointStrokeColor: "rgba(60,141,188,1)",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(60,141,188,1)",
                        data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                    },
                    {
                        label: "Released",
                        fillColor: "rgba(210, 214, 222, 1)",
                        strokeColor: "rgba(210, 214, 222, 1)",
                        pointColor: "rgba(210, 214, 222, 1)",
                        pointStrokeColor: "#c1c7d1",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(220,220,220,1)",
                        data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 10000]
                    }
                ]
            };
            var barChartCanvas = $("#loanCollectionsReleasedChart").get(0).getContext("2d");
            var barChart = new Chart(barChartCanvas);
            var barChartData = BarData;
            barChartData.datasets[0].fillColor = "#3C8DBC";
            barChartData.datasets[0].strokeColor = "#3C8DBC";
            barChartData.datasets[0].pointColor = "#3C8DBC";
            barChartData.datasets[1].fillColor = "#D72828";
            barChartData.datasets[1].strokeColor = "#D72828";
            barChartData.datasets[1].pointColor = "#D72828";

            barChart.Bar(barChartData, barChartOptions);

            //-------------
            //- Principal Due Loans CHART -
            //--------------

            var BarData = {
                labels: <?php echo $loanMonths; ?>,
                datasets: [
                    {
                        label: "Due",
                        fillColor: "rgba(210, 214, 222, 1)",
                        strokeColor: "rgba(210, 214, 222, 1)",
                        pointColor: "rgba(210, 214, 222, 1)",
                        pointStrokeColor: "#c1c7d1",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(220,220,220,1)",
                        data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                    },
                    {
                        label: "Collections",
                        fillColor: "rgba(60,141,188,0.9)",
                        strokeColor: "rgba(60,141,188,0.8)",
                        pointColor: "#3b8bba",
                        pointStrokeColor: "rgba(60,141,188,1)",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(60,141,188,1)",
                        data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                    }
                ]
            };
            var barChartCanvas = $("#loanPrincipalDueChart").get(0).getContext("2d");
            var barChart = new Chart(barChartCanvas);
            var barChartData = BarData;
            barChartData.datasets[0].fillColor = "#d80a0a";
            barChartData.datasets[0].strokeColor = "#d80a0a";
            barChartData.datasets[0].pointColor = "#d80a0a";
            barChartData.datasets[1].fillColor = "#00a65a";
            barChartData.datasets[1].strokeColor = "#00a65a";
            barChartData.datasets[1].pointColor = "#00a65a";

            barChart.Bar(barChartData, barChartOptions);

            //-------------
            //- Interest Loans CHART -
            //--------------

            var BarData = {
                labels: <?php echo $loanMonths; ?>,
                datasets: [
                    {
                        label: "Due",
                        fillColor: "rgba(210, 214, 222, 1)",
                        strokeColor: "rgba(210, 214, 222, 1)",
                        pointColor: "rgba(210, 214, 222, 1)",
                        pointStrokeColor: "#c1c7d1",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(220,220,220,1)",
                        data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                    },
                    {
                        label: "Collections",
                        fillColor: "rgba(60,141,188,0.9)",
                        strokeColor: "rgba(60,141,188,0.8)",
                        pointColor: "#3b8bba",
                        pointStrokeColor: "rgba(60,141,188,1)",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(60,141,188,1)",
                        data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                    }
                ]
            };
            var barChartCanvas = $("#loanInterestDueChart").get(0).getContext("2d");
            var barChart = new Chart(barChartCanvas);
            var barChartData = BarData;
            barChartData.datasets[0].fillColor = "#d80a0a";
            barChartData.datasets[0].strokeColor = "#d80a0a";
            barChartData.datasets[0].pointColor = "#d80a0a";
            barChartData.datasets[1].fillColor = "#00a65a";
            barChartData.datasets[1].strokeColor = "#00a65a";
            barChartData.datasets[1].pointColor = "#00a65a";

            barChart.Bar(barChartData, barChartOptions);

            //-------------
            //- Fees Loans CHART -
            //--------------

            var BarData = {
                labels: <?php echo $loanMonths; ?>,
                datasets: [
                    {
                        label: "Due",
                        fillColor: "rgba(210, 214, 222, 1)",
                        strokeColor: "rgba(210, 214, 222, 1)",
                        pointColor: "rgba(210, 214, 222, 1)",
                        pointStrokeColor: "#c1c7d1",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(220,220,220,1)",
                        data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                    },
                    {
                        label: "Collections",
                        fillColor: "rgba(60,141,188,0.9)",
                        strokeColor: "rgba(60,141,188,0.8)",
                        pointColor: "#3b8bba",
                        pointStrokeColor: "rgba(60,141,188,1)",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(60,141,188,1)",
                        data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                    }
                ]
            };
            var barChartCanvas = $("#loanFeesDueChart").get(0).getContext("2d");
            var barChart = new Chart(barChartCanvas);
            var barChartData = BarData;
            barChartData.datasets[0].fillColor = "#d80a0a";
            barChartData.datasets[0].strokeColor = "#d80a0a";
            barChartData.datasets[0].pointColor = "#d80a0a";
            barChartData.datasets[1].fillColor = "#00a65a";
            barChartData.datasets[1].strokeColor = "#00a65a";
            barChartData.datasets[1].pointColor = "#00a65a";

            barChart.Bar(barChartData, barChartOptions);

            //-------------
            //- Penalty Loans CHART -
            //--------------

            var BarData = {
                labels: <?php echo $loanMonths; ?>,
                datasets: [
                    {
                        label: "Due",
                        fillColor: "rgba(210, 214, 222, 1)",
                        strokeColor: "rgba(210, 214, 222, 1)",
                        pointColor: "rgba(210, 214, 222, 1)",
                        pointStrokeColor: "#c1c7d1",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(220,220,220,1)",
                        data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                    },
                    {
                        label: "Collections",
                        fillColor: "rgba(60,141,188,0.9)",
                        strokeColor: "rgba(60,141,188,0.8)",
                        pointColor: "#3b8bba",
                        pointStrokeColor: "rgba(60,141,188,1)",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(60,141,188,1)",
                        data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                    }
                ]
            };
            var barChartCanvas = $("#loanPenaltyDueChart").get(0).getContext("2d");
            var barChart = new Chart(barChartCanvas);
            var barChartData = BarData;
            barChartData.datasets[0].fillColor = "#d80a0a";
            barChartData.datasets[0].strokeColor = "#d80a0a";
            barChartData.datasets[0].pointColor = "#d80a0a";
            barChartData.datasets[1].fillColor = "#00a65a";
            barChartData.datasets[1].strokeColor = "#00a65a";
            barChartData.datasets[1].pointColor = "#00a65a";

            barChart.Bar(barChartData, barChartOptions);

            //-------------
            //- Number of Open Loans CHART -
            //--------------

            // Get context with jQuery - using jQuery's .get() method.
            var loanChartCanvas = $("#loanOpenChart").get(0).getContext("2d");
            // This will get the first returned node in the jQuery collection.
            var loanChart = new Chart(loanChartCanvas);

            var loanChartData = {
                labels: <?php echo $loanMonths; ?>,
                datasets: [
                    {
                        label: "Number of Open Loans",
                        fillColor: "rgba(210, 214, 222, 1)",
                        strokeColor: "rgba(60,141,188,0.8)",
                        pointColor: "#3C8DBC",
                        pointStrokeColor: "rgba(60,141,188,1)",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(60,141,188,1)",
                        data: <?php echo $cumulativeLoans; ?>
                    },
                    {}
                ]
            };

            //Create the line chart
            var loanChartOptions = areaChartOptions;
            loanChartOptions.datasetFill = false;
            loanChart.Line(loanChartData, loanChartOptions);


            //-------------
            //- Num Loans Released CHART -
            //--------------
            var Data = {
                labels: <?php echo $loanMonths; ?>,
                datasets: [

                    {
                        label: "Original Loans Released",
                        fillColor: "rgba(60,141,188,0.9)",
                        strokeColor: "rgba(60,141,188,0.8)",
                        pointColor: "#3C8DBC",
                        pointStrokeColor: "rgba(60,141,188,1)",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(60,141,188,1)",
                        data: <?php echo $numLoans; ?>
                    },
                    {
                        label: "Loans Declined",
                        fillColor: "rgba(60,141,188,0.9)",
                        strokeColor: "rgba(60,141,188,0.8)",
                        pointColor: "#00000",
                        pointStrokeColor: "rgba(60,141,188,1)",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(60,141,188,1)",
                        data: <?php echo $numLoansDeclined; ?>
                    }
                ]
            };

            var barChartCanvas = $("#numLoansReleasedChart").get(0).getContext("2d");
            var barChart = new Chart(barChartCanvas);
            var barChartData = Data;
            barChartData.datasets[0].fillColor = "#D72828";
            barChartData.datasets[0].strokeColor = "#D72828";
            barChartData.datasets[0].pointColor = "#000";
            barChartData.datasets[1].fillColor = "#D4DB00";
            barChartData.datasets[1].strokeColor = "#D4DB00";
            barChartData.datasets[1].pointColor = "#000";
            var barChartOptions = {
                //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
                scaleBeginAtZero: true,
                //Boolean - Whether grid lines are shown across the chart
                scaleShowGridLines: true,
                //String - Colour of the grid lines
                scaleGridLineColor: "rgba(0,0,0,.05)",
                //Number - Width of the grid lines
                scaleGridLineWidth: 1,
                //Boolean - Whether to show horizontal lines (except X axis)
                scaleShowHorizontalLines: true,
                //Boolean - Whether to show vertical lines (except Y axis)
                scaleShowVerticalLines: true,
                //Boolean - If there is a stroke on each bar
                barShowStroke: true,
                //Number - Pixel width of the bar stroke
                barStrokeWidth: 2,
                //Number - Spacing between each of the X value sets
                barValueSpacing: 5,
                //Number - Spacing between data sets within X values
                barDatasetSpacing: 1,
                //String - A legend template
                legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].fillColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
                //Boolean - whether to make the chart responsive
                responsive: true,
                maintainAspectRatio: true
            };

            barChartOptions.datasetFill = false;
            barChart.Bar(barChartData, barChartOptions);


            //-------------
            //- Num Repayments Collected CHART -
            //--------------
            var Data = {
                labels: <?php echo $loanMonths; ?>,
                datasets: [

                    {
                        label: "Total Repayments Collected",
                        fillColor: "rgba(210, 214, 222, 1)",
                        strokeColor: "rgba(210, 214, 222, 1)",
                        pointColor: "rgba(210, 214, 222, 1)",
                        pointStrokeColor: "#c1c7d1",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(220,220,220,1)",
                        data: <?php echo $totalPaymentsReceived; ?>
                    }
                ]
            };

            var barChartCanvas = $("#numRepaymentsCollectedChart").get(0).getContext("2d");
            var barChart = new Chart(barChartCanvas);
            var barChartData = Data;
            barChartData.datasets[0].fillColor = "#3C8DBC";
            barChartData.datasets[0].strokeColor = "#3C8DBC";
            barChartData.datasets[0].pointColor = "#000";
            var barChartOptions = {
                //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
                scaleBeginAtZero: true,
                //Boolean - Whether grid lines are shown across the chart
                scaleShowGridLines: true,
                //String - Colour of the grid lines
                scaleGridLineColor: "rgba(0,0,0,.05)",
                //Number - Width of the grid lines
                scaleGridLineWidth: 1,
                //Boolean - Whether to show horizontal lines (except X axis)
                scaleShowHorizontalLines: true,
                //Boolean - Whether to show vertical lines (except Y axis)
                scaleShowVerticalLines: true,
                //Boolean - If there is a stroke on each bar
                barShowStroke: true,
                //Number - Pixel width of the bar stroke
                barStrokeWidth: 2,
                //Number - Spacing between each of the X value sets
                barValueSpacing: 5,
                //Number - Spacing between data sets within X values
                barDatasetSpacing: 1,
                //String - A legend template
                legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].fillColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
                //Boolean - whether to make the chart responsive
                responsive: true,
                maintainAspectRatio: true
            };

            barChartOptions.datasetFill = false;
            barChart.Bar(barChartData, barChartOptions);


            //-------------
            //- Num Loans Fully paid Collected CHART -
            //--------------
            var Data = {
                labels: <?php echo $loanMonths; ?>,
                datasets: [

                    {
                        label: "Number of Fully Paid Loans",
                        fillColor: "rgba(210, 214, 222, 1)",
                        strokeColor: "rgba(210, 214, 222, 1)",
                        pointColor: "rgba(210, 214, 222, 1)",
                        pointStrokeColor: "#c1c7d1",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(220,220,220,1)",
                        data: <?php echo $fullyPaidUpLoans; ?>
                    }
                ]
            };

            var barChartCanvas = $("#numLoansFullyPaidCollectedChart").get(0).getContext("2d");
            var barChart = new Chart(barChartCanvas);
            var barChartData = Data;
            barChartData.datasets[0].fillColor = "#00a65a";
            barChartData.datasets[0].strokeColor = "#00a65a";
            barChartData.datasets[0].pointColor = "#000";
            var barChartOptions = {
                //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
                scaleBeginAtZero: true,
                //Boolean - Whether grid lines are shown across the chart
                scaleShowGridLines: true,
                //String - Colour of the grid lines
                scaleGridLineColor: "rgba(0,0,0,.05)",
                //Number - Width of the grid lines
                scaleGridLineWidth: 1,
                //Boolean - Whether to show horizontal lines (except X axis)
                scaleShowHorizontalLines: true,
                //Boolean - Whether to show vertical lines (except Y axis)
                scaleShowVerticalLines: true,
                //Boolean - If there is a stroke on each bar
                barShowStroke: true,
                //Number - Pixel width of the bar stroke
                barStrokeWidth: 2,
                //Number - Spacing between each of the X value sets
                barValueSpacing: 5,
                //Number - Spacing between data sets within X values
                barDatasetSpacing: 1,
                //String - A legend template
                legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].fillColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
                //Boolean - whether to make the chart responsive
                responsive: true,
                maintainAspectRatio: true
            };

            barChartOptions.datasetFill = false;
            barChart.Bar(barChartData, barChartOptions);


            //-------------
            //- Borrower Age Group Count Loans CHART -
            //--------------
            var Data = {
                labels: ["18-25 age", "26-30 age", "31-35 age", "36-40 age", "41-45 age", "46-50 age", "51-55 age", "56-60 age", "61-70 age", "71+ age"],
                datasets: [

                    {
                        label: "Open Loans",
                        fillColor: "rgba(60,141,188,0.8)",
                        strokeColor: "rgba(60,141,188,0.8)",
                        pointColor: "#3C8DBC",
                        pointStrokeColor: "rgba(60,141,188,0.8)",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(60,141,188,0.8)",
                        data: [0, 0, 0, 0, 1, 0, 0, 0, 0, 0]
                    },
                    {
                        label: "Fully Paid",
                        fillColor: "rgba(60,141,188,0.8)",
                        strokeColor: "rgba(60,141,188,0.8)",
                        pointColor: "#3C8DBC",
                        pointStrokeColor: "rgba(60,141,188,0.8)",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(60,141,188,0.8)",
                        data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                    },
                    {
                        label: "Default",
                        fillColor: "rgba(60,141,188,0.9)",
                        strokeColor: "rgba(60,141,188,0.9)",
                        pointColor: "#d80a0a",
                        pointStrokeColor: "rgba(60,141,188,0.9)",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(60,141,188,0.9)",
                        data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                    }
                ]
            };


            var barChartCanvas = $("#numBorrowerAgeGroupCountLoansChart").get(0).getContext("2d");
            var barChart = new Chart(barChartCanvas);
            var barChartData = Data;
            barChartData.datasets[0].fillColor = "#3C8DBC";
            barChartData.datasets[0].strokeColor = "#3C8DBC";
            barChartData.datasets[0].pointColor = "#000";

            barChartData.datasets[1].fillColor = "#00a65a";
            barChartData.datasets[1].strokeColor = "#00a65a";
            barChartData.datasets[1].pointColor = "#000";

            barChartData.datasets[2].fillColor = "#d80a0a";
            barChartData.datasets[2].strokeColor = "#d80a0a";
            barChartData.datasets[2].pointColor = "#000";
            var barChartOptions = {
                //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
                scaleBeginAtZero: true,
                //Boolean - Whether grid lines are shown across the chart
                scaleShowGridLines: true,
                //String - Colour of the grid lines
                scaleGridLineColor: "rgba(0,0,0,.05)",
                //Number - Width of the grid lines
                scaleGridLineWidth: 1,
                //Boolean - Whether to show horizontal lines (except X axis)
                scaleShowHorizontalLines: true,
                //Boolean - Whether to show vertical lines (except Y axis)
                scaleShowVerticalLines: true,
                //Boolean - If there is a stroke on each bar
                barShowStroke: true,
                //Number - Pixel width of the bar stroke
                barStrokeWidth: 2,
                //Number - Spacing between each of the X value sets
                barValueSpacing: 5,
                //Number - Spacing between data sets within X values
                barDatasetSpacing: 1,
                //String - A legend template
                legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].fillColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
                //Boolean - whether to make the chart responsive
                responsive: true,
                maintainAspectRatio: true
            };

            barChartOptions.datasetFill = false;
            barChart.Bar(barChartData, barChartOptions);


            //-------------
            //- Borrower Age Group Open Loans Recovery Loans CHART -
            //--------------
            var Data = {
                labels: ["18-25 age", "26-30 age", "31-35 age", "36-40 age", "41-45 age", "46-50 age", "51-55 age", "56-60 age", "61-70 age", "71+ age"],
                datasets: [
                    {
                        label: "Branch #1 - Open Loans%",
                        fillColor: "rgba(60,141,188,0.8)",
                        strokeColor: "rgba(60,141,188,0.8)",
                        pointColor: "#3C8DBC",
                        pointStrokeColor: "rgba(60,141,188,0.8)",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(60,141,188,0.8)",
                        data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                    },
                    {
                        label: "Branch #1 - Open/Fully Paid/Default%",
                        fillColor: "rgba(60,141,188,0.8)",
                        strokeColor: "rgba(60,141,188,0.8)",
                        pointColor: "#3C8DBC",
                        pointStrokeColor: "rgba(60,141,188,0.8)",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(60,141,188,0.8)",
                        data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                    },
                ]
            };


            var barChartCanvas = $("#numBorrowerAgeGroupOpenLoansRecoveryRateChart").get(0).getContext("2d");
            var barChart = new Chart(barChartCanvas);
            var barChartData = Data;
            barChartData.datasets[0].fillColor = "#3C8DBC";
            barChartData.datasets[0].strokeColor = "#3C8DBC";
            barChartData.datasets[0].pointColor = "#000";
            barChartData.datasets[1].fillColor = "#b7bd00";
            barChartData.datasets[1].strokeColor = "#b7bd00";
            barChartData.datasets[1].pointColor = "#000";
            var barChartOptions = {
                //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
                scaleBeginAtZero: true,
                //Boolean - Whether grid lines are shown across the chart
                scaleShowGridLines: true,
                //String - Colour of the grid lines
                scaleGridLineColor: "rgba(0,0,0,.05)",
                //Number - Width of the grid lines
                scaleGridLineWidth: 1,
                //Boolean - Whether to show horizontal lines (except X axis)
                scaleShowHorizontalLines: true,
                //Boolean - Whether to show vertical lines (except Y axis)
                scaleShowVerticalLines: true,
                //Boolean - If there is a stroke on each bar
                barShowStroke: true,
                //Number - Pixel width of the bar stroke
                barStrokeWidth: 2,
                //Number - Spacing between each of the X value sets
                barValueSpacing: 5,
                //Number - Spacing between data sets within X values
                barDatasetSpacing: 1,
                //String - A legend template
                legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].fillColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
                //Boolean - whether to make the chart responsive
                responsive: true
            };
            barChartOptions.datasetFill = false;
            barChart.Bar(barChartData, barChartOptions);

            //-------------
            //- PIE CHART -
            //-------------

            var pieOptions = {
                //Boolean - Whether we should show a stroke on each segment
                segmentShowStroke: true,
                //String - The colour of each segment stroke
                segmentStrokeColor: "#fff",
                //Number - The width of each segment stroke
                segmentStrokeWidth: 2,
                //Number - The percentage of the chart that we cut out of the middle
                percentageInnerCutout: 50, // This is 0 for Pie charts
                //Number - Amount of animation steps
                animationSteps: 50,
                //String - Animation easing effect
                animationEasing: "easeOutBounce",
                //Boolean - Whether we animate the rotation of the Doughnut
                animateRotate: true,
                //Boolean - Whether we animate scaling the Doughnut from the centre
                animateScale: false,
                //Boolean - whether to make the chart responsive to window resizing
                responsive: true,
                // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
                maintainAspectRatio: true,
                //String - A legend template
                legendTemplate: "<ul><li>Werewrew</li></ul>"
            };

            //-------------
            //- GENDER CHART -
            //-------------
            // Get context with jQuery - using jQuery's .get() method.
            var pieChartCanvas = $("#genderChart").get(0).getContext("2d");
            var pieChart = new Chart(pieChartCanvas);
            var PieData = [
                {
                    value: <?php echo $malePer; ?>,
                    color: "#00B4F0",
                    highlight: "#00B4F0",
                    label: "Male %"
                },
                {
                    value: <?php echo $femalePer; ?>,
                    color: "#FF17E4",
                    highlight: "#FF17E4",
                    label: "Female %"
                }
            ];

            //Create pie or douhnut chart
            // You can switch between pie and douhnut using the method below.
            pieChart.Doughnut(PieData, pieOptions);

            //-------------
            //- Open Loan Composition -
            //-------------
            // Get context with jQuery - using jQuery's .get() method.

            var pieOptions = {
                //Boolean - Whether we should show a stroke on each segment
                segmentShowStroke: true,
                //String - The colour of each segment stroke
                segmentStrokeColor: "#fff",
                //Number - The width of each segment stroke
                segmentStrokeWidth: 2,
                //Number - The percentage of the chart that we cut out of the middle
                percentageInnerCutout: 0, // This is 0 for Pie charts
                //Number - Amount of animation steps
                animationSteps: 50,
                //String - Animation easing effect
                animationEasing: "easeOutBounce",
                //Boolean - Whether we animate the rotation of the Doughnut
                animateRotate: true,
                //Boolean - Whether we animate scaling the Doughnut from the centre
                animateScale: false,
                //Boolean - whether to make the chart responsive to window resizing
                responsive: true,
                // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
                maintainAspectRatio: true,
                //String - A legend template
                legendTemplate: "<ul><li>Werewrew</li></ul>"
            };
            var pieChartCanvas = $("#openLoansStatusChart").get(0).getContext("2d");
            var pieChart = new Chart(pieChartCanvas);
            var PieData = [
                {
                    value: <?php echo $loansOnSchedule; ?>,
                    color: "#00DB1A",
                    highlight: "#00DB1A",
                    label: "Loans on Schedule"
                },
                {
                    value: <?php echo $paymentsDue['count(*)']; ?>,
                    color: "#7AA4FF",
                    highlight: "#7AA4FF",
                    label: "Loans Due Today"
                },
                {
                    value: <?php echo mysqli_num_rows($missedPayments); ?>,
                    color: "#D4DB00",
                    highlight: "#D4DB00",
                    label: "Loans with Missed Repayment"
                },
                {
                    value: 0,
                    color: "#C390D4",
                    highlight: "#C390D4",
                    label: "Loans in Arrears"
                },
                {
                    value: 0,
                    color: "#DB0000",
                    highlight: "#DB0000",
                    label: "Loans Past Maturity"
                }
            ];

            //Create pie or douhnut chart
            // You can switch between pie and douhnut using the method below.
            pieChart.Pie(PieData, pieOptions);

        });
    </script>
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
    <script type="text/javascript">
        $('#form').on('submit', function (e) {

            $('.submit-button').prop('disabled', true);
            $('.submit-button').html('<i class="fa fa-spinner fa-spin"></i> Please wait..');
            return true;
        });
    </script>