<?php
$select = mysqli_query($link, "SELECT * FROM payments") or die (mysqli_error($link));
while ($row = mysqli_fetch_array($select)) {
    $id = $row['id'];
    ?>

    <div class="modal fade" id="myModal<?php echo $id; ?>" role="dialog">
        <div class="modal-dialog" id="printableArea">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <legend style="color: red;">
                        Payment Receipt
                    </legend>
                </div>
                <div class="modal-body">
                    <?php
                    $search = mysqli_query($link, "SELECT * FROM systemset");
                    $get_searched = mysqli_fetch_array($search);
                    ?>
                    <div align="center">
                        <img src="<?php echo $get_searched['image']; ?>">
                    </div>

                    <table id="example1" class="table table-bordered table-striped">
                        <tr>
                            <td width="130">Transaction Date:</td>
                            <th style="color: black;"><?php echo $row['pay_date']; ?></th>
                        </tr>
                        <tr>
                            <td width="130">Transaction ID:</td>
                            <th style="color: black;"><?php echo $row['tx_id']; ?></th>
                        </tr>
                        <?php
                        $borrower = $row['customer'];
                        $get = mysqli_fetch_assoc(mysqli_query($link,"select * from borrowers where id='$borrower'"));
                        ?>
                        <tr>
                            <td width="130">Account Owner:</td>
                            <th style="color: black;"><?php echo strtoupper($get['fname'])."&nbsp;".strtoupper($get['lname']); ?><br>
                                <?php echo strtoupper($get['addrs2']) ?><br><?php echo strtoupper($get['addrs1']) ?>
                                &nbsp; </th>
                        </tr>
                        <tr>
                            <td width="130">Account Type:</td>
                            <th style="color: black;"><?php echo strtoupper($row['account']); ?>
                                &nbsp; <?php
                                //Get Loan Info//
                                $account=$row['account'];
                                $loan=mysqli_fetch_assoc(mysqli_query($link,"select * from loan_info where baccount='$account'"));

                                $strJsonFileContents = file_get_contents('include/packages.json');
                                $arrayOfTypes = json_decode($strJsonFileContents, true);
                                $loan_product = $loan['loan_product'];
                                $productName=mysqli_fetch_assoc(mysqli_query($link,"select * from products where product_id='$loan_product'"));
                                $loan_product=$productName['product_name'];
                                echo "- $loan_product";
                                 ?>
                            </th>
                        </tr>
                        <tr>
                            <td width="150">Purpose</td>
                            <th style="color: black;">Repayment</th>
                        </tr>
                        <tr>
                            <td width="150">Details</td>
                            <th style="color: black;">
                                Paid: - <?php echo $get_searched['currency'] . number_format($row['amount_to_pay'], 2, '.', ',')?><br>
                                Balance: <?php echo $get_searched['currency'] . number_format($row['balance'], 2, '.', ',')?><br>
                                Paid By:
                                <?php
                                //$arrayOfTypes = json_decode($strJsonFileContents, true);
                                $payment_method = $row['payment_method'];
                                foreach ($arrayOfTypes['paymentType'] as $key => $value) {
                                if ($payment_method == $key) {
                                $payment_method = $value;
                                }
                                } echo $payment_method;
                                ?>
                            </th>
                        </tr>
                        <tr>
                            <td></td>
                        </tr>
                        <tr>
                            <td width="130">Stamp:</td>
                            <th style="color: black;">
                                <div><?php echo ($get_searched['stamp'] == "") ? 'No Stamp Yet...' : '<img src="../image/' . $get_searched['stamp'] . '" width="80" height="80"/>'; ?></div>
                            </th>
                        </tr>
                        <tr>
                    </table>

                    <div class="box-footer">
                        <button type="button" onclick="window.print();" class="btn btn-warning pull-right"><i
                                    class="fa fa-print"></i>Print
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
<?php } ?>