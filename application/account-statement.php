<?php
require('../config/connect.php');
$getCompanyInfo = mysqli_query($link, "SELECT * FROM systemset") or die(mysqli_error($link));
$companyInfo = mysqli_fetch_assoc($getCompanyInfo);



$search = mysqli_query($link, "SELECT * FROM systemset");
$get_searched = mysqli_fetch_array($search);
$account = $_GET['account'];


$select = mysqli_query($link, "SELECT * FROM bank_accounts WHERE accountNumber='$account'") or die (mysqli_error($link));
$backingInfo = mysqli_fetch_assoc($select);
?>


<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="https://x.loandisk.com/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="https://x.loandisk.com/dist/css/skins/skin-blue.min.css">
    <link rel="stylesheet" href="https://x.loandisk.com/plugins/timepicker/bootstrap-timepicker.min.css">
    <link rel="stylesheet" href="https://x.loandisk.com/plugins/select2/select2_new.min.css">
    <link rel="stylesheet" href="https://x.loandisk.com/dist/css/AdminLTE.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<!-- custom style for printing -->
    <link rel="stylesheet" href="custom/style.css">
    <![endif]-->
    <link rel="stylesheet" href="https://x.loandisk.com/css/style_new.css">
    <link rel="stylesheet" href="https://x.loandisk.com/css/billing_plans.css">
    <script src="https://x.loandisk.com/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <script src="https://x.loandisk.com/dist/js/jquery-confirm.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://x.loandisk.com/dist/css/jquery-confirm.min.css">
    <link rel="stylesheet" type="text/css" href="https://x.loandisk.com/css/jquery.datepick.css">
    <script type="text/javascript" src="https://x.loandisk.com/include/js/jquery.plugin.js"></script>
    <script type="text/javascript" src="https://x.loandisk.com/include/js/jquery.datepick.min.js"></script>
    <script type="text/javascript" src="https://x.loandisk.com/include/js/jquery.numeric.js"></script>
    <style type="text/css" media="print">
        @page {
            size: auto;   /* auto is the initial value */
            margin: 0mm;  /* this affects the margin in the printer settings */
        }

        html {
            background-color: #FFFFFF;
            margin: 0px; /* this affects the margin on the html before sending to printer */
        }

        body {
            margin: 10mm 10mm 10mm 10mm; /* margin you want for the content */
        }
    </style>
    <link rel="stylesheet" type="text/css"
          href="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.10.21/b-1.6.3/b-flash-1.6.3/b-html5-1.6.3/b-print-1.6.3/fh-3.1.7/r-2.2.5/sc-2.0.2/datatables.min.css"/>

    <script type="text/javascript"
            src="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.10.21/b-1.6.3/b-flash-1.6.3/b-html5-1.6.3/b-print-1.6.3/fh-3.1.7/r-2.2.5/sc-2.0.2/datatables.min.js"></script>
    <style type="text/css">
        #progress {
            width: 500px;
            border: 1px solid #aaa;
            height: 20px;
        }

        #progress .bar {
            background-color: #ccc;
            height: 20px;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="https://x.loandisk.com/css/perfect-scrollbar.css">
    <link rel="stylesheet" type="text/css" href="https://x.loandisk.com/css/search_new.css">
</head>
<body>
<section class="invoice">
    <div class="row">
        <div class="col-xs-4">
            <h5 class="h5 bold">
                <?php
                $address = $companyInfo['address'];
                for ($i = 0; $i < strlen($address); $i++) {
                    echo $address[$i];
                    if ($address[$i] === ',') {
                        echo "<br/>";
                    }

                } ?>
            </h5>
        </div>

        <div class="col-xs-4 ">
            <img src='<?php echo $companyInfo['image']; ?>' style="max-width: 283px; max-height: 93px" alt="logo"
                 class="img-responsive"/>
        </div>

        <div class="col-xs-4">
            <table class="table-borderless table-responsive">
                <tr>
                    <th>Email: </th>
                    <td> <?php echo "&nbsp;".$companyInfo['email'] ?> </td>
                </tr>
                <tr>
                    <th><label for="">Web: </label></th>
                    <td><?php echo "&nbsp;".$companyInfo['website'] ?> </td>
                </tr>
                <tr>
                    <th><label for="">Tel: </label></th>
                    <td> <?php echo "&nbsp;".$companyInfo['mobile'] ?> </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="text-right">
                <div class="noprint">
                    <button href="" class="btn btn-warning" id="back"><i class="fa fa-mail-reply-all">&nbsp;&nbsp;</i>Back</button>
                    <button class="btn btn-success print" id="print"><i class="fa fa-print">&nbsp;&nbsp;</i>Print</button>
                </div>
            </div>
        </div>
        <div class="col-xs-12">
            <div class="text-center">
                <h2 class="page_title_print">Account Statement</h2>
            </div>

        </div>
    </div>
</section>

<div align="center" style="color: orange;">
    <h3 style="margin: "><?php echo $account." - ".$backingInfo['bankName']; ?></h3>
</div>

<div class="wrapper">
    <section class="invoice">
        <div class="row">

            <h4 class="box_title_print">Statement as at <?php echo date('d/m/Y'); ?></h4>

            <div class="col-xs-12 table-responsive">
                <table id="daily_collections"
                       class="table table-striped table-condensed">
                    <thead>
                    <tr style="background-color: #F2F8FF">
                        <th class=""><b>Date</b></th>
                        <th class=""><b>Transaction ID</b></th>
                        <th class=""><b>Transaction Deatails</b></th>
                        <th class="text-right"><b>Debit</b></th>
                        <th class="text-right"><b>Credit</b></th>
                        <th class="text-right"><b>Balance</b></th>
                    </tr>
                    </thead>

                    <tbody>
                        <?php
                        $accounts = mysqli_query($link, "select * from system_transactions where account='$account'");
                        while ($row = mysqli_fetch_assoc($accounts)){
                                if ($row['debit'] == "0.00") {
                                    $debit = "";
                                } else {
                                    $debit = number_format($row['debit'], 2, ".", ",");
                                }
                                if ($row['credit'] == "0.00") {
                                    $credit = "";
                                } else {
                                    $credit = number_format($row['credit'], 2, ".", ",");
                                }
                            ?>
                            <tr>
                                <td><?php echo $row['date']; ?></td>
                                <td><?php echo $row['tx_id']; ?></td>
                                <td><?php echo $row['transaction']; ?></td>
                                <td class="text-right"><?php echo $debit; ?></td>
                                <td class="text-right"><?php echo $credit; ?></td>
                                <td class="text-right"><?php echo number_format($row['balance'], 2, ".", ","); ?></td>
                            <tr>
                            <?php } ?></tbody>
                </table>
                <br><br>
            </div>
        </div>
    </section>
</div>
<script>
    $("#pre_loader").hide();

</script>

<!-- REQUIRED JS SCRIPTS -->
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
<div style="display:none">view_loan_statement</div>
<script type="text/javascript">
    //back
    $("#back").on("click", function(e){
        e.preventDefault();
        window.history.back();
    });

    // printing
    $(document).ready(()=>{
        $('.print').click(()=>{
            window.print();
        });
        console.log('the button clicked');
    });
</script>
</body>
</html>