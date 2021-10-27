<?php
$select = mysqli_query($link, "SELECT * FROM products") or die (mysqli_error($link));
while ($row = mysqli_fetch_array($select)) {
    $id = $row['product_id'];
    ?>

    <div class="modal fade" id="myModal<?php echo $id; ?>" role="dialog">
        <div class="modal-dialog" id="printableArea" style="width:970px;">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <?php
                    $search = mysqli_query($link, "SELECT * FROM systemset");
                    $get_searched = mysqli_fetch_array($search);

                    $productConfig=json_decode($row['product_configuration'],true);

                    ?>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <legend style="color: red;">
                        <?php echo $get_searched['name']; ?>
                    </legend>
                </div>
                <div class="modal-body">
                    <?php
                    $search = mysqli_query($link, "SELECT * FROM systemset");
                    $get_searched = mysqli_fetch_array($search);

                    $productConfig=json_decode($row['product_configuration'],true);

                    ?>
                    <div align="center" style="color: orange;">
                        <h4>
                            <strong>

                                <?php echo $row['product_name']; ?> Fees
                            </strong>
                        </h4>
                    </div>

                    <table id="example1" class="table table-bordered table-striped">
                        <tr>
                            <th>Product Type:</th>
                            <td style="color: black;"><?php echo $productConfig['productType']; ?></td>
                        </tr>

                        <tr>
                            <th>Minimum Loan Principal Amount:</th>
                            <td style="color: black;"><?php echo number_format($productConfig['minLoanPrincipalAmount'], 2, ".", ","); ?></td>
                        </tr>
                        <tr>
                            <th>Maximum Loan Principal Amount:</th>
                            <td style="color: black;"><?php echo number_format($productConfig['maxLoanPrincipalAmount'], 2, ".", ","); ?></td>
                        </tr>
                        <tr>
                            <th>Interest:</th>
                            <td style="color: black;"><?php echo $productConfig['defaultLoanInterest']; ?>% per <?php echo $productConfig['interestPeriod']; ?></td>
                        </tr>
                        <tr>
                            <th>Duration:</th>
                            <td style="color: black;">
                                Min: <?php echo $productConfig['minLoanDuration']." ".$productConfig['loanDurationPeriod'];; ?>,
                                Def: <?php echo $productConfig['defaultLoanDuration']." ".$productConfig['loanDurationPeriod'];; ?>
                                Max: <?php echo $productConfig['maxLoanDuration']." ".$productConfig['loanDurationPeriod']; ?>
                            </td>
                        </tr>

                        <tr style="text-align: center"><td colspan="4" style="text-align: center"><strong>FEES (FLAT)</strong></td></tr>
                        <tr>
                            <th>Description</th>
                            <th>Minimum</th>
                            <th>Maximum</th>
                            <th>Fee</th>
                        </tr>
                        <?php foreach ($productConfig['fixedFees'] as $key => $value){ ?>

                        <tr>
                            <td><?php echo $value['feeName']; ?></td>
                            <td><?php echo number_format($value['minLoan'], 2, ".", ","); ?></td>
                            <td><?php echo number_format($value['maxLoan'], 2, ".", ","); ?></td>
                            <td><?php echo $value['feeAmount']; ?></td>
                        </tr>
                        <?php } ?>
                        <tr style="text-align: center"><td colspan="5" style="text-align: center"><strong>FEES (Percentage Based)</strong></td></tr>
                        <tr>
                            <th>Description</th>
                            <th>Minimum</th>
                            <th>Maximum</th>
                            <th>Fee</th>
                            <th>Mode</th>
                        </tr>
                        <?php foreach ($productConfig['productPercentageFees'] as $key => $value){ ?>

                            <tr>
                                <td><?php echo $value['feeDescription']; ?></td>
                                <td><?php echo $value['minFixedAmount']; ?></td>
                                <td><?php echo $value['maxFixedAmount']; ?></td>
                                <td><?php echo $value['percentage']; ?>%</td>
                                <td><?php echo $value['chargeTerm']; ?></td>
                            </tr>
                        <?php } ?>
                    </table>

                    <div class="box-footer">
                        <button type="button" class="btn btn-warning pull-md-right" data-toggle="modal" data-dismiss="modal" data-target="#modal-info">
                            Close
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
<?php } ?>