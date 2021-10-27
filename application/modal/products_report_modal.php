<?php
$select = mysqli_query($link, "SELECT * FROM products") or die (mysqli_error($link));
while ($row = mysqli_fetch_array($select)) {
    $id = $row['product_id'];
    ?>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css"
          href="https://cdn.datatables.net/responsive/2.2.6/css/responsive.bootstrap4.min.css">

    <div class="modal fade" id="c<?php echo $id; ?>" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div id="printarea">
                <div class="modal-content"  style="width: 1100px;">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <strong><h4 class="modal-title" align="center"><?php echo $row['product_name']; ?></h4></strong>
                    </div>
                    <div class="modal-body">
                        <table id="loans" class="table table-striped table-bordered dt-responsive nowrap"
                               style="width:100%">
                            <tr>
                                <th>Customer</th>
                                <th>Release Date</th>
                                <th>Payment Date</th>
                                <th>Account</th>
                                <th>Principal</th>
                                <th>Instalment</th>
                                <th>Interest</th>
                                <th>Fees</th>
                                <th>Penalty</th>
                                <th>Paid</th>
                                <th>Balance</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $select1 = mysqli_query($link, "SELECT * FROM loan_info where loan_product='$id' and date_release between '$date1' and '$date2' and status not in ('Pending', 'Pending Disbursement','DECLINED')") or die (mysqli_error($link));
                            while ($row = mysqli_fetch_array($select1)) {
                                $borrower = $row['borrower'];
                                $selectin = mysqli_query($link, "SELECT fname, lname, status FROM borrowers WHERE id = '$borrower'") or die (mysqli_error($link));
                                $geth = mysqli_fetch_array($selectin);
                                $name = $geth['fname'];
                                $lname = $geth['lname'];
                                ?>
                                <tr>

                                    <td><?php echo $name . "&nbsp;" . $lname; ?></td>

                                    <?php
                                    $loan_product = $row['loan_product'];
                                    $totalLoanAmount = $row['balance'];
                                    $account = $row['baccount'];
                                    //Total Loan payment///
                                    $getPayments = mysqli_fetch_assoc(mysqli_query($link, "select sum(amount_to_pay) from payments where account='$account'"));

                                    $payments = $getPayments['sum(amount_to_pay)'];
                                    $balance = $totalLoanAmount - $payments;

                                    $product = mysqli_fetch_assoc(mysqli_query($link, "select * from products where product_id='$loan_product'"));
                                    $loan_product = $product['product_name'];
                                    ?>

                                    <td><?php echo $row['date_release']; ?></td>
                                    <td><?php echo $row['pay_date']; ?></td>
                                    <td><?php echo $account; ?></td>
                                    <td align="right"><?php echo number_format($row['amount'], 2, ".", ","); ?></td>
                                    <td align="right"><?php echo number_format($row['amount_topay'], 2, ".", ","); ?></td>
                                    <td align="right"><?php echo number_format($row['interest_value'], 2, ".", ","); ?></td>
                                    <td align="right"><?php echo number_format($row['fees'], 2, ".", ","); ?></td>
                                    <td align="right">0</td>
                                    <td align="right"><?php echo number_format($payments, 2, ".", ","); ?></td>
                                    <td align="right"><?php echo number_format($balance, 2, ".", ","); ?></td>

                                    <td>
                                        <span class="label label-<?php if ($status == 'Open and Active' || $status == 'Paid Up') echo 'success'; elseif ($status == 'Disapproved') echo 'danger'; else echo 'warning'; ?>"><?php echo $status; ?></span>
                                    </td>

                                </tr>
                            <?php }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


<?php } ?>

<script type="text/javascript" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript"
        src="https://cdn.datatables.net/responsive/2.2.6/js/dataTables.responsive.min.js"></script>
<script type="text/javascript"
        src="https://cdn.datatables.net/responsive/2.2.6/js/responsive.bootstrap4.min.js"></script>
<script>

    $(document).ready(function () {
        var table = $('#loans').DataTable({
            responsive: true
        });

        new $.fn.dataTable.FixedHeader(table);
    });
</script>