
<?php
$tid = $_SESSION['tid'];
$select = mysqli_query($link, "SELECT * FROM payments") or die (mysqli_error($link));
while ($row = mysqli_fetch_array($select)) {
    $id = $row['id'];
    $amt = $row['Amount'];
    $desc = $row['Desc'];
    $wtype = $row['wtype'];
    $tdate = $row['tdate'];
    ?>
    <div class="modal fade" id="d<?php echo $id; ?>" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div id="printarea">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" style=" color:#FFFFFF">&times;</button>
                        <strong><h4 class="modal-title" style="color: #ff0000" align="center"><b>Reversal Cancellation Confirmation</b></h4>
                        </strong>
                    </div>
                    <div class="modal-body">
                        <?php
                        $search = mysqli_query($link, "SELECT * FROM systemset");
                        $get_searched = mysqli_fetch_array($search);
                        ?>
                        <div align="center">
                            <img src="<?php echo $get_searched['image']; ?>">
                        </div>
                        <div align="center" style="color: #000000"
                        <strong>Are you sure you dont want to proceed with this payment reversal&nbsp;?</strong></div>
                    <hr>
                    <a href="del_payment.php?id=<?php echo $id; ?>">
                        <button type="button" class="btn btn-success btn-flat  btn-success"><i class="fa fa-check-square-o"></i> Yes
                        </button>
                    </a>
                    <button type="button" class="btn btn-danger btn-flat btn-danger" data-dismiss="modal"> No</button>
                </div>
            </div>

        </div>
    </div>
    </div>
<?php } ?>