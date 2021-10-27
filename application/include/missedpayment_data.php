<div class="row">


    <section class="content">
        <div class="box box-success">
            <div class="box-body">
                <div class="table-responsive">
                    <div class="box-body">
                        <form method="post">
                            <a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>">
                                <button type="button" class="btn btn-flat btn-warning"><i
                                            class="fa fa-mail-reply-all"></i>&nbsp;Back
                                </button>
                            </a>

                            <a href="newloans.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("405"); ?>">
                                <button type="button" class="btn btn-flat btn-success"><i class="fa fa-plus"></i>&nbsp;Add
                                    Loans
                                </button>
                            </a>
                            <?php
                            $get = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
                            $date = date("d/m/Y", $get);
                            $today = date('Y-m-d');
                            $select = mysqli_query($link, "select * from loan_info where id in (SELECT get_id FROM pay_schedule where schedule<'$today' and payment<>balance) and status=''") or die (mysqli_error($link));
                            $num = mysqli_num_rows($select);

                            $check = mysqli_query($link, "SELECT * FROM emp_permission WHERE tid = '" . $_SESSION['tid'] . "' AND module_name = 'Loan Details'") or die ("Error" . mysqli_error($link));
                            $get_check = mysqli_fetch_array($check);
                            $pdelete = $get_check['pdelete'];
                            $pcreate = $get_check['pcreate'];
                            $pupdate = $get_check['pupdate'];
                            ?>
                            <button type="button" class="btn btn-flat btn-danger"><i class="fa fa-times"></i>&nbsp;Overdue:&nbsp;<?php echo number_format($num, 0, '.', ','); ?>
                            </button>
                            <hr>
                            <h4 style="color: green;"><b>Missed Payments</b></h4>
                            <hr>
                            <table  id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Kind of Loan</th>
                                    <th>Account</th>
                                    <th>Principal</th>
                                    <th>Arrears</th>
                                    <th>Date Released</th>
                                    <th>Payment Date</th>
                                    <th>Last Payment Date</th>
                                    <th>Overdue By</th>
                                    <th>Penalty</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>


                                <?php
                                $lastDay = date('Y-m-d');
                                $today = date('Y-m-d');
                                function dateDifference($lastDay, $today, $differenceFormat = '%m Months %d Days')
                                {
                                    $datetime1 = date_create($lastDay);
                                    $datetime2 = date_create($today);

                                    $interval = date_diff($datetime1, $datetime2);

                                    return $interval->format($differenceFormat);
                                    //echo $interval;
                                }

                                $select = mysqli_query($link, "select * from loan_info where id in (SELECT get_id FROM pay_schedule where schedule<'$today' and payment<>balance) and status=''") or die (mysqli_error($link));
                                if (mysqli_num_rows($select) == 0) {
                                    echo "<div class='alert alert-info'>No data found yet!.....Check back later!!</div>";
                                } else {
                                    $strJsonFileContents = file_get_contents('include/packages.json');
                                    $arrayOfTypes = json_decode($strJsonFileContents, true);
                                    while ($row = mysqli_fetch_array($select)) {

                                        $id = $row['id'];
                                        $borrower = $row['borrower'];
                                        $status = $row['status'];
                                        $upstatus = $row['upstatus'];
                                        $loanAccount = $row['baccount'];
                                        $loan_product_id = $row['loan_product'];
                                        foreach ($arrayOfTypes['accountType'] as $key => $value) {
                                            if ($loan_product == $key) {
                                                $loan_product = $value;
                                            }
                                        }


                                        $getProduct = mysqli_fetch_assoc(mysqli_query($link, "select * from products where product_id='$loan_product_id'"));
                                        $loan_product = $getProduct['product_name'];
                                        $storedPenalty = json_decode($getProduct['penalty'], true);

                                        //Penalty Settings for the product of the current loan
                                        $penaltyRate = $storedPenalty['penaltyRate'];
                                        $gracePeriod = $storedPenalty['gracePeriod'];
                                        $criteria = $storedPenalty['penaltyCalculateOn'];
                                        $penaltyType = $storedPenalty['penaltyType'];
                                        if ($penaltyType == "percentage") {
                                            $penaltyRate = $storedPenalty['penaltyRate'] / 100;
                                        }

                                        //Number of Days Overdue
                                        $query=mysqli_query($link, "
                                                SELECT
                                                   sum(principal_due) - sum(principal_payment),
                                                   sum(fees) - sum(fees_payment),
                                                   sum(interest) - sum(interest_payment),
                                                   sum(penalty) - sum(penalty_payment),
                                                   sum(balance) - sum(payment),
                                                   datediff(NOW(), schedule) 
                                                FROM
                                                   pay_schedule 
                                                where
                                                   schedule < '$today' 
                                                   and payment <> balance 
                                                   and get_id = '$id'");
                                        $numDays = mysqli_fetch_assoc($query);

                                        $overdueDays = $numDays['datediff(NOW(), schedule)'] ;
                                        $penalty=0;
                                        echo $numDays['datediff(NOW(),schedule)']." ";

                                        //Check if this penalty is logged already




                                        if ($overdueDays >= $gracePeriod) {
                                            //Check the method to calculate the Penalty
                                            switch ($criteria) {
                                                case "Overdue Principal Amount":
                                                    $amountToPenalize = $numDays['sum(principal_due) - sum(principal_payment)'];
                                                    break;
                                                case "Overdue (Principal + Interest) Amount":
                                                    $amountToPenalize = $numDays['sum(principal_due) - sum(principal_payment)']
                                                        + $numDays['sum(interest) - sum(interest_payment)'];
                                                    break;
                                                case "Overdue (Principal + Interest + Fees) Amount":
                                                    $amountToPenalize = $numDays['sum(principal_due) - sum(principal_payment)']
                                                        + $numDays['sum(interest) - sum(interest_payment)']
                                                        + $numDays['sum(fees) - sum(fees_payment)'];
                                                    break;
                                                case "Overdue (Principal Interest + Fees + Penalty) Amount":
                                                    $amountToPenalize = $numDays['sum(principal_due) - sum(principal_payment)']
                                                        + $numDays['sum(interest) - sum(interest_payment)']
                                                        + $numDays['sum(fees) - sum(fees_payment)']
                                                        + $numDays['sum(penalty) - sum(penalty_payment)'];
                                                    break;
                                                default:
                                                    echo "";
                                            }
                                            $penalty=$amountToPenalize*$penaltyRate;
                                            $penaltyCheck=mysqli_query($link,"select * from penalty where loan='$id' and amount='$penalty'");

                                            /*
                                             * 12006 - Receivables
                                             * 30002 - Penalty Income
                                             * Update Loan_info to change the balance
                                             * Update Schedule, to add penalty
                                             * Add System Transaction to Affect the statement
                                             * */
                                            //Complete the double entry for Journals and Affect System Transactions for Loan Statement

                                            if(mysqli_num_rows($penaltyCheck)==0) {
                                                $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                                                $txID = substr(str_shuffle($permitted_chars), 0, 10);

                                                //Get the Opening Balances of the Penalty Fees
                                                $receivableBalance = mysqli_fetch_assoc(mysqli_query($link, "select balance from gl_codes where code='12006'"));
                                                $incomeBalance = mysqli_fetch_assoc(mysqli_query($link, "select balance from gl_codes where code='30002'"));

                                                $receivable_balance = $receivableBalance['balance'];
                                                $income_balance = $incomeBalance['balance'];

                                                //Updated Journal Balances
                                                $penaltyReceivable = $receivable_balance + $penalty;
                                                $penaltyIncome = $penalty + $income_balance;

                                                //Update the GL Account Balances
                                                //Receivables and Income
                                                mysqli_query($link, "update gl_codes set balance='$penaltyReceivable' where code='12006'");
                                                mysqli_query($link, "update gl_codes set balance='$penaltyIncome' where code='30002'");

                                                //Get the Opening Balance of the loan account
                                                $maxdate = mysqli_fetch_assoc(mysqli_query($link, "select max(pay_date) from payments where account='$loanAccount'"));
                                                $max_date = $maxdate['max(pay_date)'];

                                                $accoutBal = mysqli_fetch_assoc(mysqli_query($link, "select balance from payments where account='$loanAccount' and pay_date='$max_date'"));
                                                $start_balance = mysqli_fetch_assoc(mysqli_query($link, "select balance from loan_info where baccount='$loanAccount'"));
                                                if (isset($accoutBal['balance'])) {
                                                    $loan_balance = $accoutBal['balance'];
                                                } else {
                                                    $loan_balance = 0;
                                                }
                                                if ($loan_balance == "0") {
                                                    $loan_balance = $start_balance['balance'];////Loan Amount
                                                }
                                                //NEW LOAN Balance
                                                $newLoanBalance = $start_balance['balance'] + $penalty;

                                                $closingBalance = $loan_balance + $penalty;
                                                //System Transaction
                                                $transaction_penalty_fees = mysqli_query($link, "INSERT into system_transactions values (0,NOW(),'$loanAccount','Loan Penalty','$loan_balance','$penalty','0','$closingBalance','$tid','$id','$txID')");

                                                //Journal Entries
                                                //Journal Entries for Penalty Fees Receivables and Interest (Debit)
                                                $penalty_transaction_journal = mysqli_query($link, "INSERT into journal_transactions values (0,NOW(),'12006','Loan Penalty Fees $loanAccount','$receivable_balance','$penalty','','$penaltyReceivable','$tid','$txID','','')");

                                                //Journal Entries for Penalty Fees Income (Credit)
                                                $penalty_income_journal = mysqli_query($link, "INSERT into journal_transactions values (0,NOW(),'30002','Loan Penalty Fees $loanAccount','$income_balance','','$penalty','$penaltyIncome','$tid','$txID','','')");

                                                //Schedule
                                                //Get Balance of the current schedule then add the penalty amount
                                                $schedule_balance = mysqli_fetch_assoc(mysqli_query($link, "select total_due,balance from pay_schedule where schedule<'$today' and payment<>balance and get_id='$id'"));
                                                $adjustedBalance = $schedule_balance['total_due'] + $penalty;
                                                $newBalance = $schedule_balance['balance'] + $penalty;

                                                $update_schedule = mysqli_query($link, "update pay_schedule set penalty='$penalty', total_due='$adjustedBalance', balance='$newBalance' where schedule<'$today' and payment<>balance and get_id='$id'");

                                                //Loan_Info
                                                $update_loan_info = mysqli_query($link, "update loan_info set balance='$newLoanBalance' where baccount='$loanAccount'");

                                                $savePenalty = mysqli_query($link, "insert into penalty values (0,NOW(),'$id','$penalty')");
                                            }
                                        }


                                        $selectin = mysqli_query($link, "SELECT phone, fname, lname, id FROM borrowers WHERE id = '$borrower'") or die (mysqli_error($link));
                                        $geth = mysqli_fetch_array($selectin);
                                        $name = $geth['fname'] . "&nbsp;" . $geth['lname'];
                                        //$borrower = $geth['id'];
                                        $account = $row['baccount'];
                                        $maxDay = mysqli_fetch_assoc(mysqli_query($link, "select max(pay_date), sum(amount_to_pay) from payments where account='$account'"));
                                        $lastDay = substr($maxDay['max(pay_date)'], 0, 10);
                                        if ($lastDay == "") {
                                            $lastDay = "No payment yet";
                                        }
                                        ?>
                                        <tr>
                                            <td><?php echo $geth['phone'] . " - " . $geth['fname'] . " " . $geth['lname']; ?></td>
                                            <td><?php echo $loan_product; ?></td>
                                            <td><?php echo $account; ?></td>
                                            <td><?php echo $row['amount']; ?></td>
                                            <td align="right"><?php echo number_format($numDays['sum(balance) - sum(payment)'] , 2, ".", ","); ?></td>
                                             <td><?php echo $row['date_release']; ?></td>
                                            <td><?php echo $row['pay_date']; ?></td>
                                            <td><?php echo $lastDay; ?></td>
                                            <td><?php echo $numDays['datediff(NOW(), schedule)'] . " Days"; ?></td>
                                            <td style="color: red"><strong><?php echo $penalty; ?></strong></td>
                                            <td>
                                                <?php echo ($pupdate == '1') ? '<a href="viewborrowersloan.php?id=' . $borrower . '&&mid=' . base64_encode("405") . '&&loanId=' . $row['id'] . '"><i class="fa fa-eye"></i></a>' : ''; ?>
                                            </td>
                                        </tr>
                                    <?php }
                                } ?>
                                </tbody>
                            </table>
                            <?php
                            if (isset($_POST['delete'])) {
                                $idm = $_GET['id'];
                                $id = $_POST['selector'];
                                $N = count($id);
                                if ($id == '') {
                                    echo "<script>alert('Row Not Selected!!!'); </script>";
                                    echo "<script>window.location='listloans.php?id=" . $_SESSION['tid'] . "'; </script>";
                                } else {
                                    for ($i = 0; $i < $N; $i++) {
                                        $result = mysqli_query($link, "DELETE FROM loan_info WHERE id ='$id[$i]'");
                                        echo "<script>alert('Row Delete Successfully!!!'); </script>";
                                        echo "<script>window.location='listloans.php?id=" . $_SESSION['tid'] . "'; </script>";
                                    }
                                }
                            }
                            ?>

                        </form>

                    </div>


                </div>
            </div>
        </div>
</div>

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
        var hCols = [2, 6, 7];
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
