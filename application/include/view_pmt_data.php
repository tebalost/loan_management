<div class="box">


    <div class="box-body">
        <div class="panel panel-success">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-refresh"></i> Payment Reversal</h3>
            </div>
            <!--Check if there is a pending reversal already for this payment -->
            <?php
            $id = $_GET['id'];
            $status=mysqli_query($link,"select * from payments where id = '$id' and status='R'");
            if(mysqli_num_rows($status)==0){
            ?>
            <div class="box-body">
                <?php

                $select = mysqli_query($link, "SELECT * FROM payments WHERE id = '$id'") or die (mysqli_error($link));
                while ($get = mysqli_fetch_array($select)) {
                    $customer = $get['customer'];
                    ?>
                <form class="form-horizontal" method="post" enctype="multipart/form-data"
                      action="process_pmt.php?id=<?php echo $id; ?>">

                    <div class="box-body">

                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Account#</label>
                        <div class="col-sm-10">
                            <input name="account" type="text" readonly class="form-control"
                                   value="<?php echo $get['account']; ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Customer</label>
                        <div class="col-sm-10">
                            <?php $get = mysqli_query($link, "SELECT * FROM borrowers WHERE id = '$customer'") or die (mysqli_error($link));
                            while ($rows = mysqli_fetch_array($get)) {
                                $name = $rows['fname'] . "&nbsp;" . $rows['lname'];
                            } ?>
                            <input type="text" class="form-control" readonly value="<?php echo $name; ?>" name=""
                                   style="width: 100%;">
                            <input type="hidden" class="form-control" readonly value="<?php echo $customer; ?>"
                                   name="customer" style="width: 100%;">
                            <?php

                            ?>
                        </div>
                    </div>


                    <?php
                    $id = $_GET['id'];
                    $selected = mysqli_query($link, "SELECT * FROM payments WHERE id = '$id'") or die (mysqli_error($link));
                    while ($getin = mysqli_fetch_array($selected)) {
                        $gl_code=$getin['gl_code'];

                        $getCode=mysqli_fetch_assoc(mysqli_query($link,"select * from gl_codes where code='$gl_code'"));
                        $accountName=$getCode['name'];
                        ?>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Payment Date</label>
                            <div class="col-sm-10">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input readonly type="text" class="form-control" value="<?php echo $getin['pay_date']; ?>"
                                           name="pay_date">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Amount to Pay</label>
                            <div class="col-sm-10">
                                <input name="amount_to_pay" type="number" class="form-control"
                                       value="<?php echo $getin['amount_to_pay']; ?>" readonly required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Receiving Account</label>
                            <div class="col-sm-10">
                                <input name="toAccount" type="text" class="form-control"
                                       value="<?php echo $getin['gl_code']."-".$accountName; ?>" readonly required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Teller By</label>
                            <div class="col-sm-10">
                                <?php
                                $id = $_GET['id'];
                                $selecte = mysqli_query($link, "SELECT * FROM user WHERE id = '" . $_SESSION['tid'] . "'") or die (mysqli_error($link));
                                while ($gete = mysqli_fetch_array($selecte)) {
                                    ?>
                                    <input name="teller" type="text" class="form-control"
                                           value="<?php echo $gete['name']; ?>" readonly>
                                <?php } ?>
                            </div>
                        </div>


                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Reason *</label>
                            <div class="col-sm-10">
                                <textarea required name="remarks" class="form-control" rows="4"
                                          cols="80"><?php echo $getin['remarks']; ?></textarea>
                            </div>
                        </div>

                        </div>

                        <div align="center">
                            <div class="box-footer">
                                <button type="reset" class="btn btn-primary btn-flat"><i
                                            class="fa fa-times">&nbsp;Reset</i></button>
                                <button name="updatep" type="submit" class="btn btn-success btn-flat"><i
                                            class="fa fa-refresh">&nbsp;Reverse Payment</i></button>

                            </div>
                        </div>
                        </form>

                    <?php }
                } ?>

            </div>
            <?php } else{ ?>
            <div class="box-body">
                <div class="alert alert-warning" >
                    <a href = "#" class = "close" data-dismiss= "alert"> &times;</a>
                    Payment is now pending reversal!.&nbsp; &nbsp;&nbsp;
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>