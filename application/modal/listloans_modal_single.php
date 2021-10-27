<?php
$select = mysqli_query($link, "SELECT * FROM loan_info") or die (mysqli_error($link));
while ($row = mysqli_fetch_array($select)) {
    $id = $row['id'];
    $borrower = $row['borrower'];

    //Get names of borrower
    $names = mysqli_fetch_assoc(mysqli_query($link, "select fname, lname, phone from borrowers where id='$borrower'"));
    $fname = $names['fname'];
    $lname = $names['lname'];
    $phone = $names['phone'];
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

                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label" style="color:#FF0000">Update Status</label>
                            <div class="col-sm-10">
                                <select name="Status" class="form-control" multiple="multiple" data-placeholder="Status"
                                        style="width: 100%;">
                                    <!-----<select class="form-control select2 " multiple="multiple" type="text" name="Status" >---
                                    FIXME ---
                                    Add Loan Statuses
                                            --Processing
                                            --Open
                                            --Defauleted
                                            --Credit Counseling
                                            --Sequenstrate
                                            --Collection Agency
                                            --Debt Review
                                            --Fraud
                                            --Investigation
                                            --Legal
                                            --Write-Off
                                            --Denied
                                            --NOt Taken Up
                                    --->
                                    <option></option>
                                    <option value="Approved">Approved</option>
                                    <option value="Disapproved">Disapproved</option>
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

                    $content = "PFS: Hi $fname $lname, Your loan application have been '$Status_save', Please report to Pulamaliboho Offices for next steps.";

                    $url = "https://api.smsportal.com/api5/http5.aspx?Type=sendparam&username=serumula&password=5erumul@2020&numto=266$phone&data1=$content";
                    $contents = file_get_contents("$url");

                    mysqli_query($link, "UPDATE loan_info SET status='$Status_save', modified_date='$loan_update', modified_by='$tid' WHERE id = '$UserID'") or die(mysqli_error());
                    echo "<script>window.location='viewborrowers.php?id=" . $borrower . "'; </script>";


                }
                ?>
                </form>

                </p>
            </div>
        </div>
    </div>
<?php } ?>