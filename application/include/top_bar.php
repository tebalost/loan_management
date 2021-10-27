<header class="main-header">
    <!-- Logo -->
    <a href="application/dashboard.php" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><strong><?php echo $row ['abb']; ?></strong></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><strong><?php echo $row ['name']; ?></strong></span>
    </a>

    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <?php $tid = $_SESSION['tid'];
                $select = mysqli_query($link, "SELECT  count(*) FROM message WHERE msg_to = '$tid' and status=0") or die (mysqli_error($link));
                $row = mysqli_fetch_array($select);
                $count = $row['count(*)'];

                $select1 = mysqli_query($link, "SELECT * FROM systemset") or die (mysqli_error($link));
                while ($row1 = mysqli_fetch_array($select1)) {
                    $currency = $row1['currency'];
                    $_SESSION['currency'] = $currency;
                }

                ?>
                <li class="dropdown messages-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-envelope-o"></i>
                        <span class="label label-success"><?php echo $count; ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">You have <?php echo $count; ?> messages</li>
                        <li>
                            <!-- inner menu: contains the actual data -->
                            <ul class="menu">
                                <?php
                                $tid = $_SESSION['tid'];
                                $select = mysqli_query($link, "SELECT * FROM message WHERE msg_to = '$tid' and status=0") or die (mysqli_error($link));
                                if (mysqli_num_rows($select) == 0) {

                                } else {
                                    while ($row = mysqli_fetch_array($select)) {
                                        $messageId = $row['id'];
                                        $subject = $row['subject'];
                                        $recipient_id = $row['msg_to'];
                                        $date = $row['date_time'];
                                        $sender = $row['sender_id'];

                                        $select1 = mysqli_query($link, "SELECT * FROM user WHERE id = '$sender'") or die (mysqli_error($link));
                                        while ($row1 = mysqli_fetch_array($select1)) {
                                            $id = $row1['id'];
                                            $sender_name = $row1['name'];

                                            $image = $row1['image'];

                                            ?>
                                            <!-- inner menu: contains the actual data -->

                                            <li><!-- start message -->
                                                <a href="view_msg.php?id=<?php echo $messageId; ?>">
                                                    <div class="pull-left">
                                                        <?php if ($image !== "") { ?>
                                                            <img src="../<?php echo $image; ?>" class="img-circle"
                                                                 alt="User Image">
                                                        <?php } else { ?>
                                                            <img src="../img/user.png" class="img-circle"
                                                                 alt="User Image">
                                                        <?php } ?>
                                                    </div>
                                                    <h4>
                                                        <?php echo $sender_name; ?>
                                                        <small><i class="fa fa-clock-o"></i><?php echo $date; ?></small>
                                                    </h4>
                                                    <p> <?php echo $subject; ?></p>
                                                </a>
                                            </li>
                                            <!-- end message -->


                                        <?php }
                                    }
                                } ?>
                                <!-- end message -->

                            </ul>
                        </li>

                    </ul>
                </li>
                <!-- Notifications: style can be found in dropdown.less -->
                <?php
                $today = date('Y-m-d');

                $borrowers = mysqli_fetch_assoc(mysqli_query($link,"select count(*) from borrowers where status='Partial'"));
                $pendingLoans = mysqli_fetch_assoc(mysqli_query($link,"select count(*), sum(amount) from loan_info where status='Pending'"));
                $loansPendingCashout = mysqli_fetch_assoc(mysqli_query($link,"select count(*), sum(amount) from loan_info where status='Pending Disbursement'"));
                $paymentsDue = mysqli_fetch_assoc(mysqli_query($link,"select sum(balance)-sum(payment), count(*) from pay_schedule where schedule='$today' and payment<balance"));
                $reversedPayments = mysqli_fetch_assoc(mysqli_query($link,"select count(*) from payments where status='R'"));
                $overPayments = mysqli_query($link,"select * from loan_info where status='P'");
                $countOverPayments= $overpayments = 0;
                while($row=mysqli_fetch_assoc($overPayments)){
                    $balance=$row['balance'];
                    $baccount=$row['baccount'];
                    //Get the totalPaid
                    $paid=mysqli_fetch_assoc(mysqli_query($link,"select sum(amount_to_pay) from payments where account='$baccount'"));

                    $totalPaid=$paid['sum(amount_to_pay)'];
                    $dfference=$totalPaid-$balance;
                    if($totalPaid>$balance){
                        $countOverPayments+=1;
                        $overpayments+=$dfference;
                    }
                }
                ?>
                <li class="dropdown notifications-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bell-o"></i>
                        <span class="label label-warning"><?php echo $pendingLoans['count(*)']+$borrowers['count(*)']+$paymentsDue['count(*)']+$reversedPayments['count(*)'] + $loansPendingCashout['count(*)'] + $countOverPayments; ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!--Get the pending users and Pending Loans      -->


                        <li class="header">You have <?php echo $pendingLoans['count(*)']+$borrowers['count(*)']+$paymentsDue['count(*)']+$reversedPayments['count(*)'] + $loansPendingCashout['count(*)'] + $countOverPayments; ?> Actions</li>
                        <li>
                            <!-- inner menu: contains the actual data -->
                            <ul class="menu">
                                <?php if($pendingLoans['count(*)']!=="0"){ ?>
                                <li>
                                    <a title="List All loans pending Approval" href="<?php echo "list_pending_loans.php?id=".$_SESSION['tid'] . "&&mid=" . base64_encode("405"); ?>">
                                        <i class="fa fa-money text-green"></i><b><?php echo $pendingLoans['count(*)']; ?></b> loans pending Approval
                                    </a>
                                </li>
                                <?php } ?>
                                <?php if($borrowers['count(*)']!=="0"){ ?>
                                <li>
                                    <a href="<?php echo "listborrowers.php?id=".$_SESSION['tid'] . "&&mid=" . base64_encode("405")."&&act=pendingBorrowers"; ?>">
                                        <i class="fa fa-users text-aqua"></i> <b><?php echo $borrowers['count(*)']; ?></b> new borrowers pending activation
                                    </a>
                                </li>
                                <?php } ?>
                                <?php if($paymentsDue['count(*)']!=="0"){ ?>
                                <li>
                                    <a href="<?php echo "listpayment.php?id=".$_SESSION['tid'] . "&&mid=" . base64_encode("408")."&&act=duePayments"; ?>">
                                        <i class="fa fa-paypal text-red"></i> <b><?php echo $paymentsDue['count(*)']; ?></b> Payments due today
                                    </a>
                                </li>
                                <?php } ?>
                                <?php if($reversedPayments['count(*)']!=="0"){ ?>
                                    <li>
                                        <a href="<?php echo "listpayment.php?id=".$_SESSION['tid'] . "&&mid=" . base64_encode("408")."&&act=reversedPayments"; ?>">
                                            <i class="fa fa-refresh text-red"></i> <b><?php echo $reversedPayments['count(*)']; ?></b> Payments pending reversal
                                        </a>
                                    </li>
                                <?php } ?>
                                <?php if($loansPendingCashout['count(*)']!=="0"){ ?>
                                    <li>
                                        <a href="<?php echo "list_pending_disbursements.php?id=".$_SESSION['tid'] . "&&mid=" . base64_encode("405"); ?>">
                                            <i class="fa fa-money text-orange"></i><b><?php echo $loansPendingCashout['count(*)']; ?></b> loans pending disbursement
                                        </a>
                                    </li>
                                <?php } ?>
                                <?php if($countOverPayments!=="0"){ ?>
                                    <li>
                                        <a href="<?php echo "listpayment.php?id=".$_SESSION['tid'] . "&&mid=" . base64_encode("408")."&&act=overPayments"; ?>">
                                            <i class="fa fa-money text-orange"></i><b><?php echo $countOverPayments; ?></b> loans overpaid
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </li>

                    </ul>
                </li>
                <!-- Tasks: style can be found in dropdown.less -->
                <?php
                /*<li class="dropdown tasks-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-flag-o"></i>
                        <span class="label label-danger">3</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">You have 3 tasks for today</li>
                        <li>
                            <!-- inner menu: contains the actual data -->
                            <ul class="menu">
                                <li><!-- Task item -->
                                <li><!-- Task item -->
                                    <a href="#">
                                        <h3>
                                            List daily tasks
                                            <small class="pull-right">20%</small>
                                        </h3>
                                        <div class="progress xs">
                                            <div class="progress-bar progress-bar-aqua" style="width: 20%"
                                                 role="progressbar"
                                                 aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                                                <span class="sr-only">20% Complete</span>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <!-- end task item -->
                                <li><!-- Task item -->
                                    <a href="#">
                                        <h3>
                                            All loans checked
                                            <small class="pull-right">40%</small>
                                        </h3>
                                        <div class="progress xs">
                                            <div class="progress-bar progress-bar-green" style="width: 40%"
                                                 role="progressbar"
                                                 aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                                                <span class="sr-only">40% Complete</span>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <!-- end task item -->
                                <li><!-- Task item -->
                                    <a href="#">
                                        <h3>
                                            More tasks I need to do for the day
                                            <small class="pull-right">60%</small>
                                        </h3>
                                        <div class="progress xs">
                                            <div class="progress-bar progress-bar-red" style="width: 60%"
                                                 role="progressbar"
                                                 aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                                                <span class="sr-only">60% Complete</span>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <!-- end task item -->
                                <li><!-- Task item -->
                                    <a href="#">
                                        <h3>
                                            Make reports on missing payments
                                            <small class="pull-right">80%</small>
                                        </h3>
                                        <div class="progress xs">
                                            <div class="progress-bar progress-bar-yellow" style="width: 80%"
                                                 role="progressbar"
                                                 aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                                                <span class="sr-only">80% Complete</span>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <!-- end task item -->
                            </ul>
                        </li>
                        <li class="footer">
                            <a href="#">View all tasks</a>
                        </li>
                    </ul>
                </li>*/
                ?>
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <?php
                        $id = $_SESSION['tid'];
                        $call = mysqli_query($link, "SELECT * FROM user WHERE id = '$id'");
                        if (mysqli_num_rows($call) == 0)
                        {
                            echo "<script>alert('Data Not Found!'); </script>";
                        }
                        else
                        {
                        while ($row = mysqli_fetch_assoc($call))
                        {
                        ?>

                        <?php if ($row['image'] !== "") { ?>
                            <img src="../<?php echo $row['image']; ?>" class="user-image" alt="User Image">
                        <?php } else { ?>
                            <img src="../img/user.png" class="user-image" alt="User Image">
                        <?php } ?>
                        <span class="hidden-xs"><?php echo $row ['name']; ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <?php if ($row['image'] !== "") { ?>
                                <img src="../<?php echo $row['image']; ?>" class="img-circle" alt="User Image">
                            <?php } else { ?>
                                <img src="../img/user.png" class="img-circle" alt="User Image">
                            <?php } ?>
                            <p>
                                <?php echo 'Username: ' . $row ['username'];
                                $_SESSION['username'] = $row ['username'];
                                ?>
                            </p>
                            <?php }
                            } ?>
                        </li>

                        <!-- Menu Body -->
                        <li class="user-body">
                            <div class="row">
                                <div class="col-xs-4 text-center">

                                    <a href="profile.php?id=<?php echo $_SESSION['tid']; ?>">Profile</a>

                                </div>
                                <?php
                                $check = mysqli_query($link, "SELECT * FROM emp_permission WHERE tid = '" . $_SESSION['tid'] . "' AND module_name = 'Borrower Details'") or die ("Error" . mysqli_error($link));
                                while ($get_check = mysqli_fetch_array($check)) {
                                    $pread = $get_check['pread'];
                                    ?>
                                    <?php echo ($pread == 1) ? '<div class="col-xs-4 text-center"><a href="listborrowers.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("403") . '">Borrowers</a></div>' : ''; ?>
                                <?php } ?>
                                <?php
                                $check = mysqli_query($link, "SELECT * FROM emp_permission WHERE tid = '" . $_SESSION['tid'] . "' AND module_name = 'Internal Message'") or die ("Error" . mysqli_error($link));
                                while ($get_check = mysqli_fetch_array($check)) {
                                    $pread = $get_check['pread'];
                                    ?>
                                    <?php echo ($pread == 1) ? '<div class="col-xs-4 text-center"><a href="inboxmessage.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("406") . '">Mailbox</a></div>' : ''; ?>
                                <?php } ?>
                            </div>
                            <!-- /.row -->
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <?php
                            $check = mysqli_query($link, "SELECT * FROM emp_permission WHERE tid = '" . $_SESSION['tid'] . "' AND module_name = 'Internal Message'") or die ("Error" . mysqli_error($link));
                            while ($get_check = mysqli_fetch_array($check)) {
                                $pcreate = $get_check['pcreate'];
                                ?>
                                <?php echo ($pcreate == 1) ? '<div class="pull-left"><a href="newmessage.php?id=' . $_SESSION['tid'] . '&&mid=' . base64_encode("406") . '" class="btn btn-info btn-flat">New Message</a></div>' : ''; ?>
                            <?php } ?>
                            <div class="pull-right">
                                <a href="../logout.php" class="btn btn-warning btn-flat"><i class="fa fa-sign-out"></i>Sign
                                    out</a>
                            </div>
                        </li>
                    </ul>
                </li>
                <!-- Control Sidebar Toggle Button -->
                <?php
                if ($_SESSION['username'] == "superadmin") {
                    echo '<li><a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a></li>';
                }
                ?>
            </ul>
        </div>
    </nav>
</header>



