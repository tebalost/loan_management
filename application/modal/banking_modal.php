<?php
$select = mysqli_query($link, "SELECT * FROM bank_accounts") or die (mysqli_error($link));
while ($row = mysqli_fetch_array($select)) {
    $id = $row['id'];
     $account= $row['accountNumber'];
    $bank = $row['bankName'];
    ?>

    <div class="modal fade" id="myModal<?php echo $id; ?>" role="dialog">
        <div class="modal-dialog" id="printableArea">
            <!-- Modal content-->
            <div class="modal-content" style="width: 1000px;">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <legend style="color: red;">
                        All Accounts Statements
                    </legend>
                </div>
                <div class="modal-body">
                    <?php
                    $search = mysqli_query($link, "SELECT * FROM systemset");
                    $get_searched = mysqli_fetch_array($search);
                    ?>
                    <div align="center" style="color: orange;"><h4><strong><?php echo $get_searched['name']; ?></strong>
                        </h4>
                        <h3><?php echo "$account - $bank"; ?></h3>
                    </div>
                    <hr>
                    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css">
                    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.6/css/responsive.bootstrap4.min.css">

                    <table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                        <th>Date</th>
                        <th>Transaction ID</th>
                        <th>Transaction Details</th>
                        <th>Debit</th>
                        <th>Credit</th>
                        <th>Balance</th>
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
                           <td><?php echo $debit; ?></td>
                            <td><?php echo $credit; ?></td>
                            <td><?php echo number_format($row['balance'], 2, ".", ","); ?></td>
                        <tr>
                            <?php } ?>
                    </table>
                    <script type="text/javascript" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
                    <script type="text/javascript" src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
                    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.6/js/dataTables.responsive.min.js"></script>
                    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.6/js/responsive.bootstrap4.min.js"></script>
                    <script>
                        $(document).ready(function() {
                            var table = $('#example').DataTable( {
                                responsive: true
                            } );

                            new $.fn.dataTable.FixedHeader( table );
                        } );
                    </script>
                    <div class="box-footer">
                        <a type="button" href="account-statement.php?account=<?php echo $account?>" class="btn btn-warning pull-right"><i
                                    class="fa fa-print"></i> Print</a>
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
<?php } ?>