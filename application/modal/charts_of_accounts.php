
<div class="modal fade" id="c" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div id="printarea">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <strong><h4 class="modal-title" align="center">Create a New Ledger Account</h4></strong></div>
                <div class="modal-body">
                    <form class="form-horizontal" method="post" enctype="multipart/form-data">
                        <div class="box-body">

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="Accounts_type">Account Type <span class="required" style="color: red">*</span></label>
                                <div class="col-sm-10">
                                        <select class="form-control" name="type" id="Accounts_type">
                                                <option value="">Select Account Type</option>
                                                <option value="NON-CURRENT ASSETS">NON-CURRENT ASSETS</option>
                                                <option value="CURRENT ASSETS">CURRENT ASSETS</option>
                                                <option value="CURRENT LIABILITIES">CURRENT LIABILITIES</option>
                                                <option value="INCOME">INCOME</option>
                                                <option value="OPERATING EXPENSES">OPERATING EXPENSES</option>
                                            <option value="OTHER OPERATING EXPENSES">OTHER OPERATING EXPENSES</option>
                                            <option value="EQUITY">EQUITY</option>

                                        </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="Accounts_type">GL Group <span class="required" style="color: red">*</span></label>
                                <div class="col-sm-10">
                                    <select class="form-control"
                                            name="glGroup"
                                            onchange="get_code();"
                                            id="gl_group">
                                        <option value="">Select GL Group</option>
                                        <option value="PROPERTY PLANT AND EQUIPMENT">PROPERTY PLANT AND EQUIPMENT</option>
                                        <option value="RECEIVABLES">RECEIVABLES</option>
                                        <option value="CASH AND CASH EQUIVALENTS">CASH AND CASH EQUIVALENTS</option>
                                        <option value="PAYABLES">PAYABLES</option>
                                        <option value="LONG-TERM LIABILITIES">LONG-TERM LIABILITIES</option>
                                        <option value="INCOME">INCOME</option>
                                        <option value="OPERATING EXPENSES">OPERATING EXPENSES</option>
                                        <option value="WAGES AND SALARIES">WAGES AND SALARIES</option>
                                        <option value="OTHER OPERATING EXPENSES">OTHER OPERATING EXPENSES</option>
                                        <option value="BAD DEBTS WRITTEN OFF">BAD DEBTS WRITTEN OFF</option>
                                        <option value="EQUITY">EQUITY</option>

                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Name</label>
                                <div class="col-sm-10">
                                    <input type="text" name="name" class="form-control">
                                </div>
                            </div>

                            <!--                         <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">GL Code</label>
                                <div class="col-sm-10">
                                    <input type="text" name="Accounts[glCode]" class="form-control">
                                </div>
                            </div>-->
                            <div id="get_code"></div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="Accounts_usage">Account Usage <span class="required">*</span></label>
                                <div class="col-sm-10">
                                    <select class="form-control" name="usage" id="Accounts_usage">
                                        <option value="">Select Account Usage</option>
                                        <option value="2">GL Group</option>
                                        <option value="1">GL Account</option>
                                    </select>
                                </div>
                            </div>
                        </div>


                        <hr>
                        <div align="right">
                            <button type="submit" class="btn btn-success btn-flat" name="saveCharts"><i
                                        class="fa fa-save"></i>&nbsp;Create
                            </button>
                            <button type="button" class="btn btn-danger btn-flat" data-dismiss="modal">&nbsp;Close
                            </button>
                        </div>
                        <?php
                        if(isset($_POST['saveCharts'])) {
                            try {
                                    //mysqli_query($link,"delete from gl_codes");

                                        $gl = $_POST['glCode'];
                                        $name = $_POST['name'];
                                        $type = $_POST['type'];
                                        $usage = $_POST['usage'];

                                        $existingAcc = mysqli_query($link, "select * from gl_codes where code='$gl'");
                                        if(mysqli_num_rows($existingAcc)==0) {
                                            $save = mysqli_query($link, "insert into gl_codes values(0,'$gl','$name','$type','$type','0')");
                                        }else{
                                            $save = mysqli_query($link, "update gl_codes set code='$gl',name='$name',type='$type',portfolio='$type')");
                                        }





                            } catch (UnexpectedValueException $ex) {
                                echo "<script>alert('Invalid Values Entered!!'); </script>";
                            }
                        }
                        ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<?php
$tid = $_SESSION['tid'];
$select = mysqli_query($link, "SELECT * FROM mywallet") or die (mysqli_error($link));
while ($row = mysqli_fetch_array($select)) {
    $id = $row['id'];
    $amt = $row['Amount'];
    $desc = $row['Desc'];
    $wtype = $row['wtype'];
    $tdate = $row['tdate'];
    ?>
    <div class="modal modal-danger" id="d<?php echo $id; ?>" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div id="printarea">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" style=" color:#FFFFFF">&times;</button>
                        <strong><h4 class="modal-title" style="color:#FFFFFF" align="center">Delete Confirmation</h4>
                        </strong>
                    </div>
                    <div class="modal-body">

                        <div align="center" style="color: #FFFFFF"
                        <strong>Are you sure you want to delete the row selected&nbsp;?</strong></div>
                    <hr>
                    <a href="del_wallet.php?id=<?php echo $id; ?>">
                        <button type="button" class="btn btn-info btn-flat  btn-outline"><i class="fa fa-trash"></i>Yes
                        </button>
                    </a>
                    <button type="button" class="btn btn-danger btn-flat btn-outline" data-dismiss="modal">No</button>
                </div>
            </div>

        </div>
    </div>
    </div>
<?php } ?>
<script type="text/javascript">
    function get_code() { // Call to ajax function
        var gl_group = $('#gl_group').val();
        var dataString = "gl_group="+gl_group;
        //console.log(gl_group);
        $.ajax({
            type: "POST",
            url: "getcodes.php", // Name of the php files
            data: dataString,
            success: function(html)
            {
                $("#get_code").html(html);
            }
        });
    }
</script>
