<?php
$select = mysqli_query($link, "SELECT * FROM gl_codes") or die (mysqli_error($link));
while ($row = mysqli_fetch_array($select)) {
    $id = $row['id'];
    $account = $row['code'];
    $name = $row['name'];

    ?>

    <!--<div class="modal fade" id="myModal<?php /*echo $id; */?>" role="dialog">
        <div class="modal-dialog" id="printableArea">
            <!-- Modal content--
            <div class="modal-content" style="width: 1000px;">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <legend style="color: red;">
                        GL Accounts Statements
                    </legend>
                </div>
                <div class="modal-body">
                    <?php
/*                    $search = mysqli_query($link, "SELECT * FROM systemset");
                    $get_searched = mysqli_fetch_array($search);
                    */?>
                    <div align="center" style="color: orange;"><h4><strong><?php /*echo $get_searched['name']; */?></strong>
                        </h4>
                        <h3><?php /*echo "$account - $name"; */?></h3>
                    </div>
                    <hr>

                    <table id="example1" class="table table-bordered table-striped">
                        <th>Date</th>
                        <th>Transaction ID</th>
                        <th>Transaction Details</th>
                        <th>Debit</th>
                        <th>Credit</th>
                        <th>Balance</th>
                        <?php
/*                        $accounts = mysqli_query($link, "select * from journal_transactions where account='$account'");
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
                        */?>
                        <tr>
                            <td><?php /*echo $row['date']; */?></td>
                            <td><?php /*echo $row['tx_id']; */?></td>
                            <td><?php /*echo $row['transaction']; */?></td>
                           <td><?php /*echo $debit; */?></td>
                            <td><?php /*echo $credit; */?></td>
                            <td><?php /*echo number_format($row['balance'], 2, ".", ","); */?></td>
                        <tr>
                            <?php /*} */?>
                    </table>

                    <div class="box-footer">
                        <button type="button" onclick="window.print();" class="btn btn-warning pull-right"><i
                                    class="fa fa-print"></i>Print
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>-->
<?php } ?>