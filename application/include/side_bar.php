<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <?php
                $id = $_SESSION['tid'];
                $call = mysqli_query($link, "SELECT * FROM user WHERE id = '$id'");
                $bureau = mysqli_query($link, "SELECT * FROM systemset where bureau_submission='1'");
                if (mysqli_num_rows($call) == 0)
                {
                    echo "<script>alert('Data Not Found!'); </script>";
                }
                else
                {
                while ($row = mysqli_fetch_assoc($call))
                {

                ?>
                <?php if($row['image']!==""){ ?>
                    <img src="../<?php echo $row['image']; ?>" class="img-circle" alt="User Image">
                <?php }else{ ?>
                    <img src="../img/user.png" class="img-circle" alt="User Image">
                <?php } ?>
            </div>
            <div class="pull-left info">
                <p><?php echo $row ['username'];
                    $_SESSION['username'] = $row ['username'];//superuser
                    ?></p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                <?php }
                } ?>
            </div>
        </div>
        <!-- search form -->

        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->

        <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>
            <?php
            if (isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("401"))) {
                ?>
                <li class="active"><a
                            href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><i
                                class="fa fa-dashboard"></i> <span>Dashbord</span></a></li>
                <?php
            } else {
                ?>
                <li><a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><i
                                class="fa fa-dashboard"></i> <span>Dashbord</span></a></li>
            <?php } ?>


            <?php
            if (isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("400"))) {
                $check = mysqli_query($link, "SELECT * FROM emp_permission WHERE tid = '" . $_SESSION['tid'] . "' AND module_name = 'Payment'") or die ("Error" . mysqli_error($link));
                $get_check = mysqli_fetch_array($check);
                $pcreate = $get_check['pcreate'];
                $pread = $get_check['pread'];
                ?>
                <li class="active"><a
                            href="banking.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><i
                                class="fa fa-bank"></i> <span>Bank/Mobile Account Status</span></a></li>

                <?php
            } else {
                $check = mysqli_query($link, "SELECT * FROM emp_permission WHERE tid = '" . $_SESSION['tid'] . "' AND module_name = 'Payment'") or die ("Error" . mysqli_error($link));
                $get_check = mysqli_fetch_array($check);
                $pcreate = $get_check['pcreate'];
                $pread = $get_check['pread'];
                ?>
                <li><a href="banking.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>"><i
                                class="fa fa-bank"></i> <span>Bank/Mobile Account Status</span></a></li>
            <?php } ?>

            <?php
            if (isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("403"))) {
                $check = mysqli_query($link, "SELECT * FROM emp_permission WHERE tid = '" . $_SESSION['tid'] . "' AND module_name = 'Borrower Details'") or die ("Error" . mysqli_error($link));
                $get_check = mysqli_fetch_array($check);
                $pcreate = $get_check['pcreate'];
                $pread = $get_check['pread'];
                ?>
                <?php echo ($pcreate == 1) ? '<li class="treeview active"><a href="#"><i class="fa fa-users"></i> <span>Borrowers</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
                <?php /*echo ($pcreate == 1) ? '<li class="active"><a href="newborrowers.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("403") . '"><i class="fa fa-circle-o"></i> New Borrowers</a></li>' : ''; */?>
                <?php echo ($pread == 1) ? '<li><a href="listborrowers.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("403") . '"><i class="fa fa-circle-o"></i>List Borrowers</a></li>' : ''; ?>
                <?php echo ($pcreate == 1) ? '</ul></li>' : ''; ?>
                <?php
            } else {
                $check = mysqli_query($link, "SELECT * FROM emp_permission WHERE tid = '" . $_SESSION['tid'] . "' AND module_name = 'Borrower Details'") or die ("Error" . mysqli_error($link));
                $get_check = mysqli_fetch_array($check);
                $pcreate = $get_check['pcreate'];
                $pread = $get_check['pread'];
                ?>
                <?php echo ($pcreate == 1) ? '<li class="treeview"><a href="#"><i class="fa fa-users"></i> <span>Borrowers</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
                <?php /*echo ($pcreate == 1) ? '<li class="active"><a href="newborrowers.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("403") . '"><i class="fa fa-circle-o"></i> New Borrowers</a></li>' : ''; */?>
                <?php echo ($pread == 1) ? '<li><a href="listborrowers.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("403") . '"><i class="fa fa-circle-o"></i>List Borrowers</a></li>' : ''; ?>
                <?php echo ($pcreate == 1) ? '</ul></li>' : ''; ?>
            <?php } ?>


            <?php
            if (isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("405"))) {
                $check = mysqli_query($link, "SELECT * FROM emp_permission WHERE tid = '" . $_SESSION['tid'] . "' AND module_name = 'Loan Details'") or die ("Error" . mysqli_error($link));
                $get_check = mysqli_fetch_array($check);
                $pcreate = $get_check['pcreate'];
                $pread = $get_check['pread'];
                ?>
                <?php echo ($pcreate == 1) ? '<li class="treeview active"><a href="#"><i class="fa fa-dollar"></i> <span>Loans</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
                <?php echo ($pcreate == 1) ? '<li class="active"><a href="affordability_calculator.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("405") . '"><i class="fa fa-circle-o"></i> New Loan</a></li>' : ''; ?>
                <?php echo ($pread == 1) ? '<li><a href="loancalculator.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("405") . '"><i class="fa fa-circle-o"></i>Loan Calculator</a></li>' : ''; ?>
                <?php echo ($pread == 1) ? '<li><a href="affordability_calculator.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("405") . '"><i class="fa fa-circle-o"></i>Affordability Calculator</a></li>' : ''; ?>
                <?php echo ($pread == 1) ? '<li><a href="listloans.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("405") . '"><i class="fa fa-circle-o"></i>List Loans</a></li>' : ''; ?>
                <?php echo ($pread == 1) ? '<li><a href="list_pending_loans.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("405") . '"><i class="fa fa-circle-o"></i>Pending Applications</a></li>' : ''; ?>
                <?php echo ($pread == 1 && mysqli_num_rows($bureau)>0) ? '<li><a href="bureausubmissions.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("405") . '"><i class="fa fa-circle-o"></i>Bureau Submissions</a></li>' : ''; ?>
                <?php echo ($pread == 1 && mysqli_num_rows($bureau)>0) ? '<li><a href="credit_life_cover.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("405") . '"><i class="fa fa-circle-o"></i>Credit Life Cover</a></li>' : ''; ?>
                <?php echo ($pcreate == 1) ? '</ul></li>' : ''; ?>
                <?php
            } else {
                $check = mysqli_query($link, "SELECT * FROM emp_permission WHERE tid = '" . $_SESSION['tid'] . "' AND module_name = 'Loan Details'") or die ("Error" . mysqli_error($link));
                $get_check = mysqli_fetch_array($check);
                $pcreate = $get_check['pcreate'];
                $pread = $get_check['pread'];
                ?>
                <?php echo ($pcreate == 1) ? '<li class="treeview"><a href="#"><i class="fa fa-dollar"></i> <span>Loans</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
                <?php echo ($pcreate == 1) ? '<li class="active"><a href="affordability_calculator.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("405") . '"><i class="fa fa-circle-o"></i> New Loan</a></li>' : ''; ?>
                <?php echo ($pread == 1) ? '<li><a href="loancalculator.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("405") . '"><i class="fa fa-circle-o"></i>Loan Calculator</a></li>' : ''; ?>
                <?php echo ($pread == 1) ? '<li><a href="affordability_calculator.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("405") . '"><i class="fa fa-circle-o"></i>Affordability Calculator</a></li>' : ''; ?>
                <?php echo ($pread == 1) ? '<li><a href="listloans.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("405") . '"><i class="fa fa-circle-o"></i>List Loans</a></li>' : ''; ?>
                <?php echo ($pread == 1) ? '<li><a href="list_pending_loans.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("405") . '"><i class="fa fa-circle-o"></i>Pending Applications</a></li>' : ''; ?>
                <?php echo ($pread == 1  && mysqli_num_rows($bureau)>0) ? '<li><a href="bureausubmissions.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("405") . '"><i class="fa fa-circle-o"></i>Bureau Submissions</a></li>' : ''; ?>
                <?php echo ($pread == 1 && mysqli_num_rows($bureau)>0) ? '<li><a href="credit_life_cover.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("405") . '"><i class="fa fa-circle-o"></i>Credit Life Cover</a></li>' : ''; ?>
                <?php echo ($pcreate == 1) ? '</ul></li>' : ''; ?>
            <?php } ?>

            <?php
            if (isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("408"))) {
                $check = mysqli_query($link, "SELECT * FROM emp_permission WHERE tid = '" . $_SESSION['tid'] . "' AND module_name = 'Payment'") or die ("Error" . mysqli_error($link));
                $get_check = mysqli_fetch_array($check);
                $pcreate = $get_check['pcreate'];
                $pread = $get_check['pread'];
                ?>
                <?php echo ($pcreate == 1) ? '<li class="treeview active"><a href="#"><i class="fa fa-credit-card"></i> <span>Payments</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
                <?php echo ($pcreate == 1) ? '<li class="active"><a href="newpayments.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("408") . '"><i class="fa fa-circle-o"></i> New Payment</a></li>' : ''; ?>
                <?php echo ($pcreate == 1) ? '<li><a href="internal_transfer.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("408") . '"><i class="fa fa-circle-o"></i> Internal Transfers</a></li>' : ''; ?>
                <?php echo ($pcreate == 1) ? '<li><a href="banking.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("408") . '"><i class="fa fa-circle-o"></i>Bank/Mobile Accounts</a></li>' : ''; ?>
                <?php echo ($pread == 1) ? '<li><a href="listpayment.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("408") . '"><i class="fa fa-circle-o"></i>List Payments</a></li>' : ''; ?>
                <?php echo ($pread == 1) ? '<li><a href="missedpayment.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("408") . '"><i class="fa fa-circle-o"></i>Missed Payments</a></li>' : ''; ?>
                <?php echo ($pread == 1) ? '<li><a href="duepayment.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("408") . '"><i class="fa fa-circle-o"></i>Due/Overdue Payments</a></li>' : ''; ?>
                <?php echo ($pcreate == 1) ? '</ul></li>' : ''; ?>
                <?php
            } else {
                $check = mysqli_query($link, "SELECT * FROM emp_permission WHERE tid = '" . $_SESSION['tid'] . "' AND module_name = 'Payment'") or die ("Error" . mysqli_error($link));
                $get_check = mysqli_fetch_array($check);
                $pcreate = $get_check['pcreate'];
                $pread = $get_check['pread'];
                ?>
                <?php echo ($pcreate == 1) ? '<li class="treeview"><a href="#"><i class="fa fa-credit-card"></i> <span>Payments</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
                <?php echo ($pcreate == 1) ? '<li class="active"><a href="newpayments.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("408") . '"><i class="fa fa-circle-o"></i> New Payment</a></li>' : ''; ?>
                <?php echo ($pcreate == 1) ? '<li><a href="internal_transfer.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("408") . '"><i class="fa fa-circle-o"></i> Internal Transfers</a></li>' : ''; ?>
                <?php echo ($pcreate == 1) ? '<li><a href="banking.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("408") . '"><i class="fa fa-circle-o"></i>Bank/Mobile Accounts</a></li>' : ''; ?>
                <?php echo ($pread == 1) ? '<li><a href="listpayment.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("408") . '"><i class="fa fa-circle-o"></i>List Payments</a></li>' : ''; ?>
                <?php echo ($pread == 1) ? '<li><a href="missedpayment.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("408") . '"><i class="fa fa-circle-o"></i>Missed Payments</a></li>' : ''; ?>
                <?php echo ($pread == 1) ? '<li><a href="duepayment.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("408") . '"><i class="fa fa-circle-o"></i>Due/Overdue Payments</a></li>' : ''; ?>
                <?php echo ($pcreate == 1) ? '</ul></li>' : ''; ?>
            <?php } ?>
            <?php
            if (isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("414"))) {
                $check = mysqli_query($link, "SELECT * FROM emp_permission WHERE tid = '" . $_SESSION['tid'] . "' AND module_name = 'Reports'") or die ("Error" . mysqli_error($link));
                $get_check = mysqli_fetch_array($check);
                $pcreate = $get_check['pcreate'];
                $pread = $get_check['pread'];
                ?>
                <?php echo ($pcreate == 1) ? '<li class="treeview active"><a href="#"><i class="fa fa-line-chart"></i> <span>Reports</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
                <?php echo ($pread == 1) ? '<li><a href="#?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("414") . '"><i class="fa fa-circle-o"></i>Arrears Aging Analysis</a></li>' : ''; ?>
                <?php echo ($pread == 1) ? '<li><a href="#?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("414") . '"><i class="fa fa-circle-o"></i>Collections</a></li>' : ''; ?>
                <?php echo ($pread == 1) ? '<li><a href="disbursement_report.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("414") . '"><i class="fa fa-circle-o"></i>Disbursements</a></li>' : ''; ?>
                <?php echo ($pread == 1) ? '<li><a href="fees_report.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("414") . '"><i class="fa fa-circle-o"></i>Fees</a></li>' : ''; ?>
                <?php echo ($pread == 1) ? '<li><a href="#?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("414") . '"><i class="fa fa-circle-o"></i>Outstanding</a></li>' : ''; ?>
                <?php echo ($pread == 1) ? '<li><a href="products_report.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("414") . '"><i class="fa fa-circle-o"></i>Products Reports</a></li>' : ''; ?>
                <?php echo ($pcreate == 1) ? '</ul></li>' : ''; ?>
                <?php
            } else {
                $check = mysqli_query($link, "SELECT * FROM emp_permission WHERE tid = '" . $_SESSION['tid'] . "' AND module_name = 'Reports'") or die ("Error" . mysqli_error($link));
                $get_check = mysqli_fetch_array($check);
                $pcreate = $get_check['pcreate'];
                $pread = $get_check['pread'];
                ?>
                <?php echo ($pcreate == 1) ? '<li class="treeview"><a href="#"><i class="fa fa-line-chart"></i> <span>Reports</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>

                <?php echo ($pread == 1) ? '<li><a href="#?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("414") . '"><i class="fa fa-circle-o"></i>Arrears Aging Analysis</a></li>' : ''; ?>
                <?php echo ($pread == 1) ? '<li><a href="#?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("414") . '"><i class="fa fa-circle-o"></i>Collections</a></li>' : ''; ?>
                <?php echo ($pread == 1) ? '<li><a href="disbursement_report.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("414") . '"><i class="fa fa-circle-o"></i>Disbursements</a></li>' : ''; ?>
                <?php echo ($pread == 1) ? '<li><a href="fees_report.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("414") . '"><i class="fa fa-circle-o"></i>Fees</a></li>' : ''; ?>
                <?php echo ($pread == 1) ? '<li><a href="#?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("414") . '"><i class="fa fa-circle-o"></i>Outstanding</a></li>' : ''; ?>
                <?php echo ($pread == 1) ? '<li><a href="products_report.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("414") . '"><i class="fa fa-circle-o"></i>Products Reports</a></li>' : ''; ?>
                <?php echo ($pcreate == 1) ? '</ul></li>' : ''; ?>
            <?php } ?>

            <?php
            if (isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("416"))) {
                $check = mysqli_query($link, "SELECT * FROM emp_permission WHERE tid = '" . $_SESSION['tid'] . "' AND module_name = 'Reports'") or die ("Error" . mysqli_error($link));
                $get_check = mysqli_fetch_array($check);
                $pcreate = $get_check['pcreate'];
                $pread = $get_check['pread'];
                ?>
                <?php echo ($pcreate == 1) ? '<li class="treeview active"><a href="#"><i class="fa fa-clock-o"></i> <span>Scheduler</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
                <?php echo ($pcreate == 1) ? '<li><a href="add_schedule.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("416") . '"><i class="fa fa-circle-o"></i>Add New</a></li>' : ''; ?>
                <?php echo ($pread == 1) ? '<li><a href="schedule_list.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("416") . '"><i class="fa fa-circle-o"></i>View All</a></li>' : ''; ?>
                <?php echo ($pcreate == 1) ? '</ul></li>' : ''; ?>
                <?php
            } else {
                $check = mysqli_query($link, "SELECT * FROM emp_permission WHERE tid = '" . $_SESSION['tid'] . "' AND module_name = 'Reports'") or die ("Error" . mysqli_error($link));
                $get_check = mysqli_fetch_array($check);
                $pcreate = $get_check['pcreate'];
                $pread = $get_check['pread'];
                ?>
                <?php echo ($pcreate == 1) ? '<li class="treeview"><a href="#"><i class="fa fa-clock-o"></i> <span>Scheduler</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
                <?php echo ($pcreate == 1) ? '<li><a href="add_schedule.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("416") . '"><i class="fa fa-circle-o"></i>Add New</a></li>' : ''; ?>
                <?php echo ($pread == 1) ? '<li><a href="schedule_list.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("416") . '"><i class="fa fa-circle-o"></i>View All</a></li>' : ''; ?>
                <?php echo ($pcreate == 1) ? '</ul></li>' : ''; ?>
            <?php } ?>

            <?php
            if (isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("415"))) {
                $check = mysqli_query($link, "SELECT * FROM emp_permission WHERE tid = '" . $_SESSION['tid'] . "' AND module_name = 'Accounting'") or die ("Error" . mysqli_error($link));
                $get_check = mysqli_fetch_array($check);
                $pcreate = $get_check['pcreate'];
                $pread = $get_check['pread'];
                ?>
                <?php echo ($pcreate == 1) ? '<li class="treeview active"><a href="#"><i class="fa fa-bar-chart"></i> <span>Accounting</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
                <?php echo ($pcreate == 1) ? '<li class="active"><a href="chart_accounts.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("415") . '"><i class="fa fa-circle-o"></i>Chart of Accounts</a></li>' : ''; ?>
                <?php echo ($pcreate == 1) ? '<li class="active"><a href="income_statement.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("415") . '"><i class="fa fa-circle-o"></i> Income Statement</a></li>' : ''; ?>
                <?php echo ($pcreate == 1) ? '<li><a href="journals.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("415") . '"><i class="fa fa-circle-o"></i>Journals</a></li>' : ''; ?>
                <?php echo ($pcreate == 1) ? '<li class="active"><a href="profit_loss.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("415") . '"><i class="fa fa-circle-o"></i>Profit / Loss</a></li>' : ''; ?>
                <?php echo ($pread == 1) ? '<li><a href="balance_sheet.php#?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("415") . '"><i class="fa fa-circle-o"></i>Balance Sheet</a></li>' : ''; ?>
                <?php echo ($pread == 1) ? '<li><a href="cash_flow_monthly.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("415") . '"><i class="fa fa-circle-o"></i>Cash Flow Monthly</a></li>' : ''; ?>
                <?php echo ($pread == 1) ? '<li><a href="#?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("415") . '"><i class="fa fa-circle-o"></i>Accounting Integration</a></li>' : ''; ?>
                <?php echo ($pcreate == 1) ? '</ul></li>' : ''; ?>
                <?php
            } else {
                $check = mysqli_query($link, "SELECT * FROM emp_permission WHERE tid = '" . $_SESSION['tid'] . "' AND module_name = 'Accounting'") or die ("Error" . mysqli_error($link));
                $get_check = mysqli_fetch_array($check);
                $pcreate = $get_check['pcreate'];
                $pread = $get_check['pread'];
                ?>
                <?php echo ($pcreate == 1) ? '<li class="treeview"><a href="#"><i class="fa fa-bar-chart"></i> <span>Accounting</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
                <?php echo ($pcreate == 1) ? '<li class="active"><a href="chart_accounts.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("415") . '"><i class="fa fa-circle-o"></i>Chart of Accounts</a></li>' : ''; ?>
                <?php echo ($pcreate == 1) ? '<li class="active"><a href="income_statement.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("415") . '"><i class="fa fa-circle-o"></i> Income Statement</a></li>' : ''; ?>
                <?php echo ($pcreate == 1) ? '<li><a href="journals.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("415") . '"><i class="fa fa-circle-o"></i>Journals</a></li>' : ''; ?>
                <?php echo ($pcreate == 1) ? '<li class="active"><a href="profit_loss.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("415") . '"><i class="fa fa-circle-o"></i>Profit / Loss</a></li>' : ''; ?>
                <?php echo ($pread == 1) ? '<li><a href="balance_sheet.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("415") . '"><i class="fa fa-circle-o"></i>Balance Sheet</a></li>' : ''; ?>
                <?php echo ($pread == 1) ? '<li><a href="cash_flow_monthly.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("415") . '"><i class="fa fa-circle-o"></i>Cash Flow Monthly</a></li>' : ''; ?>
                <?php echo ($pread == 1) ? '<li><a href="#?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("415") . '"><i class="fa fa-circle-o"></i>Accounting Integration</a></li>' : ''; ?>
                <?php echo ($pcreate == 1) ? '</ul></li>' : ''; ?>
            <?php } ?>

            <?php
/*            if (isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("410"))) {
                $check = mysqli_query($link, "SELECT * FROM emp_permission WHERE tid = '" . $_SESSION['tid'] . "' AND module_name = 'Savings Account'") or die ("Error" . mysqli_error($link));
                $get_check = mysqli_fetch_array($check);
                $pcreate = $get_check['pcreate'];
                $pread = $get_check['pread'];
                */?><!--
                <?php /*echo ($pcreate == 1) ? '<li class="treeview active"><a href="#"><i class="fa fa-money"></i> <span>Savings Account</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; */?>
                <?php /*echo ($pread == 1) ? '<li><a href="customer.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("410") . '"><i class="fa fa-circle-o"></i>Customers</a></li>' : ''; */?>
                <?php /*echo ($pcreate == 1) ? '<li><a href="deposit.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("410") . '"><i class="fa fa-circle-o"></i>Deposit Money</a></li>' : ''; */?>
                <?php /*echo ($pcreate == 1) ? '<li><a href="withdraw.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("410") . '"><i class="fa fa-circle-o"></i>Withdraw Money</a></li>' : ''; */?>
                <?php /*echo ($pread == 1) ? '<li><a href="transaction.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("410") . '"><i class="fa fa-circle-o"></i>All Transaction</a></li>' : ''; */?>
                <?php /*echo ($pcreate == 1) ? '</ul></li>' : ''; */?>
                <?php
/*            } else {
                $check = mysqli_query($link, "SELECT * FROM emp_permission WHERE tid = '" . $_SESSION['tid'] . "' AND module_name = 'Savings Account'") or die ("Error" . mysqli_error($link));
                $get_check = mysqli_fetch_array($check);
                $pcreate = $get_check['pcreate'];
                $pread = $get_check['pread'];
                */?>
                <?php /*echo ($pcreate == 1) ? '<li class="treeview"><a href="#"><i class="fa fa-money"></i> <span>Savings Account</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; */?>
                <?php /*echo ($pread == 1) ? '<li><a href="customer.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("410") . '"><i class="fa fa-circle-o"></i>Customers</a></li>' : ''; */?>
                <?php /*echo ($pcreate == 1) ? '<li><a href="deposit.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("410") . '"><i class="fa fa-circle-o"></i>Deposit Money</a></li>' : ''; */?>
                <?php /*echo ($pcreate == 1) ? '<li><a href="withdraw.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("410") . '"><i class="fa fa-circle-o"></i>Withdraw Money</a></li>' : ''; */?>
                <?php /*echo ($pread == 1) ? '<li><a href="transaction.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("410") . '"><i class="fa fa-circle-o"></i>All Transaction</a></li>' : ''; */?>
                <?php /*echo ($pcreate == 1) ? '</ul></li>' : ''; */?>
            --><?php /*} */?>
            <?php
            if (isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("409"))) {
                $check = mysqli_query($link, "SELECT * FROM emp_permission WHERE tid = '" . $_SESSION['tid'] . "' AND module_name = 'Employee Details'") or die ("Error" . mysqli_error($link));
                $get_check = mysqli_fetch_array($check);
                $pcreate = $get_check['pcreate'];
                $pread = $get_check['pread'];
                ?>
                <?php echo ($pcreate == 1) ? '<li class="treeview active"><a href="#"><i class="fa fa-user"></i> <span>Employee</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
                <?php echo ($pcreate == 1) ? '<li class="active"><a href="newemployee.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("409") . '"><i class="fa fa-circle-o"></i> New Employee</a></li>' : ''; ?>
                <?php echo ($pread == 1) ? '<li><a href="listemployee.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("409") . '"><i class="fa fa-circle-o"></i>List Employee</a></li>' : ''; ?>
                <?php echo ($pcreate == 1) ? '</ul></li>' : ''; ?>
                <?php
            } else {
                $check = mysqli_query($link, "SELECT * FROM emp_permission WHERE tid = '" . $_SESSION['tid'] . "' AND module_name = 'Employee Details'") or die ("Error" . mysqli_error($link));
                $get_check = mysqli_fetch_array($check);
                $pcreate = $get_check['pcreate'];
                $pread = $get_check['pread'];
                ?>
                <?php echo ($pcreate == 1) ? '<li class="treeview"><a href="#"><i class="fa fa-user"></i> <span>Employee</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
                <?php echo ($pcreate == 1) ? '<li class="active"><a href="newemployee.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("409") . '"><i class="fa fa-circle-o"></i> New Employee</a></li>' : ''; ?>
                <?php echo ($pread == 1) ? '<li><a href="listemployee.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("409") . '"><i class="fa fa-circle-o"></i>List Employee</a></li>' : ''; ?>
                <?php echo ($pcreate == 1) ? '</ul></li>' : ''; ?>
            <?php } ?>
            <?php
/*            if (isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("404"))) {
                $check = mysqli_query($link, "SELECT * FROM emp_permission WHERE tid = '" . $_SESSION['tid'] . "' AND module_name = 'Employee Wallet'") or die ("Error" . mysqli_error($link));
                $get_check = mysqli_fetch_array($check);
                $pread = $get_check['pread'];
                */?><!--
                <?php /*echo ($pread == 1) ? '<li class="active"><a href="mywallet.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("404") . '"><i class="fa fa-google-wallet"></i> <span>My Wallet</span></a></li>' : ''; */?>
                <?php
/*            } else {
                $check = mysqli_query($link, "SELECT * FROM emp_permission WHERE tid = '" . $_SESSION['tid'] . "' AND module_name = 'Employee Wallet'") or die ("Error" . mysqli_error($link));
                $get_check = mysqli_fetch_array($check);
                $pread = $get_check['pread'];
                */?>
                <?php /*echo ($pread == 1) ? '<li><a href="mywallet.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("404") . '"><i class="fa fa-google-wallet"></i> <span>My Wallet</span></a></li>' : ''; */?>
            --><?php /*} */?>

            <?php
            if (isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("406"))) {
                $check = mysqli_query($link, "SELECT * FROM emp_permission WHERE tid = '" . $_SESSION['tid'] . "' AND module_name = 'Internal Message'") or die ("Error" . mysqli_error($link));
                $get_check = mysqli_fetch_array($check);
                $pcreate = $get_check['pcreate'];
                $pread = $get_check['pread'];
                ?>
                <?php echo ($pcreate == 1) ? '<li class="treeview active"><a href="#"><i class="fa fa-comments-o"></i> <span>Message</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
                <?php echo ($pcreate == 1) ? '<li class="active"><a href="newmessage.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("406") . '"><i class="fa fa-circle-o"></i> New Message</a></li>' : ''; ?>
                <?php echo ($pread == 1) ? '<li><a href="inboxmessage.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("406") . '"><i class="fa fa-circle-o"></i>Inbox Message</a></li>' : ''; ?>
                <?php echo ($pread == 1) ? '<li><a href="outboxmessage.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("406") . '"><i class="fa fa-circle-o"></i>Outbox Message</a></li>' : ''; ?>
                <?php echo ($pcreate == 1) ? '</ul></li>' : ''; ?>
                <?php
            } else {
                $check = mysqli_query($link, "SELECT * FROM emp_permission WHERE tid = '" . $_SESSION['tid'] . "' AND module_name = 'Internal Message'") or die ("Error" . mysqli_error($link));
                $get_check = mysqli_fetch_array($check);
                $pcreate = $get_check['pcreate'];
                $pread = $get_check['pread'];
                ?>
                <?php echo ($pcreate == 1) ? '<li class="treeview"><a href="#"><i class="fa fa-comments-o"></i> <span>Message</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
                <?php echo ($pcreate == 1) ? '<li class="active"><a href="newmessage.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("406") . '"><i class="fa fa-circle-o"></i> New Message</a></li>' : ''; ?>
                <?php echo ($pread == 1) ? '<li><a href="inboxmessage.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("406") . '"><i class="fa fa-circle-o"></i>Inbox Message</a></li>' : ''; ?>
                <?php echo ($pread == 1) ? '<li><a href="outboxmessage.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("406") . '"><i class="fa fa-circle-o"></i>Outbox Message</a></li>' : ''; ?>
                <?php echo ($pcreate == 1) ? '</ul></li>' : ''; ?>
            <?php } ?>

            <?php
/*            if (isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("402"))) {
                $check = mysqli_query($link, "SELECT * FROM emp_permission WHERE tid = '" . $_SESSION['tid'] . "' AND module_name = 'Email Panel'") or die ("Error" . mysqli_error($link));
                $get_check = mysqli_fetch_array($check);
                $pcreate = $get_check['pcreate'];
                $pread = $get_check['pread'];
                */?><!--
                <?php /*echo ($pcreate == 1) ? '<li class="treeview active"><a href="#"><i class="fa fa-envelope-o"></i> <span>Email Panel</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; */?>
                <?php /*echo ($pcreate == 1) ? '<li class="active"><a href="newemail.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("402") . '"><i class="fa fa-circle-o"></i> Send Email</a></li>' : ''; */?>
                <?php /*echo ($pread == 1) ? '<li><a href="listemail.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("402") . '"><i class="fa fa-circle-o"></i>List Email</a></li>' : ''; */?>
                <?php /*echo ($pcreate == 1) ? '</ul></li>' : ''; */?>
                <?php
/*            } else {
                $check = mysqli_query($link, "SELECT * FROM emp_permission WHERE tid = '" . $_SESSION['tid'] . "' AND module_name = 'Email Panel'") or die ("Error" . mysqli_error($link));
                $get_check = mysqli_fetch_array($check);
                $pcreate = $get_check['pcreate'];
                $pread = $get_check['pread'];
                */?>
                <?php /*echo ($pcreate == 1) ? '<li class="treeview"><a href="#"><i class="fa fa-envelope-o"></i> <span>Email Panel</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; */?>
                <?php /*echo ($pcreate == 1) ? '<li class="active"><a href="newemail.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("402") . '"><i class="fa fa-circle-o"></i> Send Email</a></li>' : ''; */?>
                <?php /*echo ($pread == 1) ? '<li><a href="listemail.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("402") . '"><i class="fa fa-circle-o"></i>List Email</a></li>' : ''; */?>
                <?php /*echo ($pcreate == 1) ? '</ul></li>' : ''; */?>
            --><?php /*} */?>


            <?php
            if (isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("413"))) {
                $check = mysqli_query($link, "SELECT * FROM emp_permission WHERE tid = '" . $_SESSION['tid'] . "' AND module_name = 'Module Permission'") or die ("Error" . mysqli_error($link));
                $get_check = mysqli_fetch_array($check);
                $pcreate = $get_check['pcreate'];
                $pread = $get_check['pread'];
                ?>
                <?php echo ($pcreate == 1) ? '<li class="treeview active"><a href="#"><i class="fa fa-cogs"></i> <span>Module Permission</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
                <?php echo ($pcreate == 1) ? '<li><a href="add_permission.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("413") . '"><i class="fa fa-circle-o"></i>Add Permission</a></li>' : ''; ?>
                <?php echo ($pread == 1) ? '<li><a href="permission_list.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("413") . '"><i class="fa fa-circle-o"></i>Module Permission List</a></li>' : ''; ?>
                <?php echo ($pcreate == 1) ? '</ul></li>' : ''; ?>
                <?php
            } else {
                $check = mysqli_query($link, "SELECT * FROM emp_permission WHERE tid = '" . $_SESSION['tid'] . "' AND module_name = 'Module Permission'") or die ("Error" . mysqli_error($link));
                $get_check = mysqli_fetch_array($check);
                $pcreate = $get_check['pcreate'];
                $pread = $get_check['pread'];
                ?>
                <?php echo ($pcreate == 1) ? '<li class="treeview"><a href="#"><i class="fa fa-cogs"></i> <span>Module Permission</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
                <?php echo ($pcreate == 1) ? '<li><a href="add_permission.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("413") . '"><i class="fa fa-circle-o"></i>Add Permission</a></li>' : ''; ?>
                <?php echo ($pread == 1) ? '<li><a href="permission_list.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("413") . '"><i class="fa fa-circle-o"></i>Module Permission List</a></li>' : ''; ?>
                <?php echo ($pcreate == 1) ? '</ul></li>' : ''; ?>
            <?php } ?>


            <?php
            if (isset($_GET['mid']) && (trim($_GET['mid']) == base64_encode("411"))) {
                $check = mysqli_query($link, "SELECT * FROM emp_permission WHERE tid = '" . $_SESSION['tid'] . "' AND module_name = 'General Settings'") or die ("Error" . mysqli_error($link));
                $get_check = mysqli_fetch_array($check);
                $pcreate = $get_check['pcreate'];
                $pread = $get_check['pread'];
                ?>
                <?php echo ($pcreate == 1) ? '<li class="treeview active"><a href="#"><i class="fa fa-gear"></i> <span>General Settings</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
                <?php echo ($pcreate == 1) ? '<li><a href="system_set.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("411") . '"><i class="fa fa-circle-o"></i>Company Setup</a></li>' : ''; ?>
                <?php echo ($pread == 1) ? '<li><a href="loansettings.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("411") . '"><i class="fa fa-circle-o"></i>Loan Settings</a></li>' : ''; ?>
                <?php echo ($pcreate == 1) ? '<li><a href="sms.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("411") . '"><i class="fa fa-circle-o"></i>SMS Gateway Settings</a></li>' : ''; ?>
                <?php if($_SESSION['username']=="superadmin"){ ?>
                <?php echo ($pread == 1) ? '<li><a href="backupdatabase.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("411") . '"><i class="fa fa-circle-o"></i>Backup Database</a></li>' : ''; ?>
                <?php } ?>
                <?php echo ($pcreate == 1) ? '</ul></li>' : ''; ?>
                <?php
            } else {
                $check = mysqli_query($link, "SELECT * FROM emp_permission WHERE tid = '" . $_SESSION['tid'] . "' AND module_name = 'General Settings'") or die ("Error" . mysqli_error($link));
                $get_check = mysqli_fetch_array($check);
                $pcreate = $get_check['pcreate'];
                $pread = $get_check['pread'];
                ?>
                <?php echo ($pcreate == 1) ? '<li class="treeview"><a href="#"><i class="fa fa-gear"></i> <span>General Settings</span><span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a><ul class="treeview-menu">' : ''; ?>
                <?php echo ($pcreate == 1) ? '<li><a href="system_set.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("411") . '"><i class="fa fa-circle-o"></i>Company Setup</a></li>' : ''; ?>
                <?php echo ($pread == 1) ? '<li><a href="loansettings.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("411") . '"><i class="fa fa-circle-o"></i>Loan Settings</a></li>' : ''; ?>
                <?php echo ($pcreate == 1) ? '<li><a href="sms.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("411") . '"><i class="fa fa-circle-o"></i>SMS Gateway Settings</a></li>' : ''; ?>
                <?php echo ($pread == 1) ? '<li><a href="backupdatabase.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("411") . '"><i class="fa fa-circle-o"></i>Backup Database</a></li>' : ''; ?>
                <?php echo ($pcreate == 1) ? '</ul></li>' : ''; ?>
            <?php } ?>


            <li>
                <a href="../logout.php">
                    <i class="fa fa-sign-out"></i> <span>Logout</span>
                </a>
            </li>


    </section>
    <!-- /.sidebar -->
</aside>