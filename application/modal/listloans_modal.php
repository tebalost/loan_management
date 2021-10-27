<?php
$select = mysqli_query($link, "SELECT * FROM loan_info") or die (mysqli_error($link));
while ($row = mysqli_fetch_array($select)) {
    $id = $row['id'];
    ?>

    <div class="modal fade" id="myModal<?php echo $id; ?>" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <legend>Change Loan Status</legend>
                </div>
                <div class="modal-body">
                    <p>


                    <form class="form-horizontal" method="post" enctype="multipart/form-data">

                        <input type="hidden" value="<?php echo $id; ?>" name="userid">
                        <?php
                        //Check status of the Loan,
                        $status = mysqli_fetch_assoc(mysqli_query($link, "select status from loan_info where id='$id'"));
                        $loanStatus = $status['status'];

                        ?>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label" style="color:#FF0000">Update Status</label>
                            <div class="col-sm-10">
                                <select name="Status" class="form-control" multiple="multiple" data-placeholder="Status"
                                        style="width: 100%;">
                                    <option></option>
                                    <?php if ($loanStatus == "Pending") { ?>
                                        <option value="">Approve</option>
                                        <option value="DECLINED">Decline</option>
                                    <?php } else { ?>
                                        <option value="C">Account Closed</option>
                                        <option value="D">Dispute</option>
                                        <option value="E">Terms Extended</option>
                                        <option value="L">Handed Over</option>
                                        <option value="P">Paid Up</option>
                                        <option value="T">Early Settlement</option>
                                        <option value="V">Cooling Off Settlement</option>
                                        <option value="W">Written Off</option>
                                        <option value="T">Paid Up Default</option>
                                        <option value="Z">Deceased</option>
                                    <?php } ?>
                                </select>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="update_status" class="btn btn-flat btn-success"><i
                                        class="icon-save"></i>&nbsp;Update
                            </button>
                            <button class="btn btn-flat btn-danger" data-dismiss="modal" aria-hidden="true"><i
                                        class="icon-remove icon-large"></i> Close
                            </button>
                        </div>
                </div>

                <?php
                if (isset($_POST['update_status'])) {

                    $Status_save = $_POST['Status'];
                    $UserID = $_POST['userid'];
                    $loan_update = date('Y-m-d H:i:s');
                    $tid = $_SESSION['tid'];


                    mysqli_query($link, "UPDATE loan_info SET status='$Status_save', modified_date = '$loan_update', modified_by='$tid' WHERE id = '$UserID'") or die(mysqli_error());
                    echo "<script>window.location='listloans.php?id=" . $_SESSION['tid'] . "'; </script>";


                }

                ?>
            </div>
        </div>
    </div>
<?php } ?>