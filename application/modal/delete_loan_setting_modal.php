<?php
$select = mysqli_query($link, "SELECT * FROM loan_settings order by id") or die (mysqli_error($link));
while ($row = mysqli_fetch_array($select)) {
    $id = $row['id'];
    ?>

    <div class="modal fade" id="myModal<?php echo $id; ?>" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <legend>Delete Loan Setting</legend>
                </div>
                <div class="modal-body">
                    <p>


                        <form class="form-horizontal" method="post" enctype="multipart/form-data">

                            <input type="hidden" value="<?php echo $id; ?>" name="settingId">

                            <div class="form-group">
                                <div class="col-sm-6">
                    <p>Are you sure you want to delete this setting?</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" name="deleteRecord" class="btn btn-flat btn-success"><i
                            class="icon-save"></i>&nbsp;Yes
                </button>
                <button class="btn btn-flat btn-danger" data-dismiss="modal" aria-hidden="true"><i
                            class="icon-remove icon-large"></i> No
                </button>
            </div>
        </div>

        <?php
        if (isset($_POST['deleteRecord'])) {

            $settingId = $_POST['settingId'];

            mysqli_query($link, "delete from loan_settings WHERE id = '$settingId'") or die(mysqli_error());
            echo "<script>window.location='loansettings_form.php?id=" . $_SESSION['tid'] . "'; </script>";


        }
        ?>
        </form>

        </p>
    </div>
    </div>
    </div>
<?php } ?>