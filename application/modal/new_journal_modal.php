
<?php
include ("../config/connect.php");
$getAccounts = mysqli_query($link, "select * from gl_codes");
$getAccounts1 = mysqli_query($link, "select * from gl_codes");
?>
<?php
/*if (isset($_POST['save'])) {
    $debit = $_POST['debit'];//Receiving Account
    $credit = $_POST['credit'];//Paying Account
    $notes = $_POST['notes'];
    $entryDate = $_POST['entryDate'];
    $debitAccount = $_POST['debitAccount'];//Debit this account and increase Balance
    $eachAccount=0;
    $debitAccounts=[];
    foreach($debitAccount as $key){
        $debitAccounts[$eachAccount]=$key;
        $eachAccount++;
    }
    $debitAccount=json_encode($debitAccounts);
    $creditAccount = $_POST['creditAccount'];//Credit this account and decrease balance
    $file = $_POST['file'];

    $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $txID = substr(str_shuffle($permitted_chars), 0, 10);

    //Get Balance of the paying account(Credited Account)
    $balanceCheck_from = mysqli_fetch_assoc(mysqli_query($link, "select balance from gl_codes where code='$creditAccount'"));//Paying Account Balance
    $from_balance = $balanceCheck_from['balance'];

    if($debit !== $credit){
        echo "<div class=\"alert alert-danger\" ><a href = \"#\" class = \"close\" data-dismiss= \"alert\"> &times;</a>
                                                    Debit and credit accounts cannot be the same!&nbsp; &nbsp;&nbsp;
                                              </div>";
    }
    else if($debitAccount == $creditAccount){
        echo "<div class=\"alert alert-danger\" ><a href = \"#\" class = \"close\" data-dismiss= \"alert\"> &times;</a>
                                                    Please Make Sure Debit amount is equal to Credit amount!&nbsp; &nbsp;&nbsp;
                                              </div>";
    }
    else if ($from_balance >= $debit) {
        $balanceCheck_to = mysqli_fetch_assoc(mysqli_query($link, "select balance from gl_codes where code='$debitAccount'"));
        $to_balance = $balanceCheck_to['balance']; /////Increase Balance of Debit and Decrease balance of credit
        $toBalance = $debit + $to_balance;
        $fromBalance = $from_balance - $debit;

        //Update Balances and save the transactions with debit and credit
        mysqli_query($link, "update gl_codes set balance = '$toBalance' where code='$debitAccount'");
        mysqli_query($link, "update gl_codes set balance = '$fromBalance' where code='$creditAccount'");

        //If the Paying account is the bank account
        if($creditAccount>=13001 && $creditAccount<=13020){
            mysqli_query($link, "update bank_accounts set balance = '$fromBalance' where gl_code='$creditAccount'");
            //Paying Account....Credit
            //Get the bank account of this code//
            $bank = mysqli_fetch_assoc(mysqli_query($link,"select * from bank_accounts where gl_code='$creditAccount'"));
            $bankAccount = $bank['accountNumber'];

            //Get the name of the debit account//Receiver
            $receiver=mysqli_fetch_assoc(mysqli_query($link,"select * from gl_codes where code='$debitAccount'"));
            $debitName=$receiver['name']; ///This can come from the form and be exploded

            $payer=mysqli_fetch_assoc(mysqli_query($link,"select * from gl_codes where code='$creditAccount'"));
            $creditName=$payer['name'];

            /*Credit*/ //$from_transaction = mysqli_query($link, "INSERT into system_transactions values (0,'$entryDate','$bankAccount','Transfer to $debitAccount - $debitName','$from_balance','0','$credit','$fromBalance','$tid','','$txID')");
            /*Debit*/ //  $to_transaction = mysqli_query($link, "INSERT into system_transactions values (0,'$entryDate','$debitAccount','Transfer from $creditAccount - $creditName','$to_balance','$debit','0','$toBalance','$tid','','$txID')");
      //  }

        //double entry

        //Receiving Account....Debit
        //$to_transaction = mysqli_query($link, "INSERT into journal_transactions values (0,'$entryDate','$debitAccount','Transfer from $creditAccount - $creditName','$to_balance','$debit','0','$toBalance','$tid','$txID','$notes','$file')");

        //Paying Account....Credit
       // $from_transaction = mysqli_query($link, "INSERT into journal_transactions values (0,'$entryDate','$creditAccount','Transfer to $debitAccount - $debitName','$from_balance','0','$credit','$fromBalance','$tid','$txID','$notes','$file')");

       // echo "<div class=\"alert alert-success\" ><a href = \"#\" class = \"close\" data-dismiss= \"alert\"> &times;</a>
      //                                              Transfer successfully completed.!&nbsp; &nbsp;&nbsp;
      //                                        </div>";
    //} else {
    //    echo "<div class=\"alert alert-danger\" ><a href = \"#\" class = \"close\" data-dismiss= \"alert\"> &times;</a>
   //                                                 Unable to transfer, insufficient funds from the transferring account!&nbsp; &nbsp;&nbsp;
   //                                           </div>";
  //  }
//}

?>
<div class="modal fade" id="c" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div id="printarea">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <strong><h4 class="modal-title" align="center">Create a New Journal Entry</h4></strong></div>
                <div class="modal-body">
                    <form action="" method="post">
                        <div class="row">

                            <div class="form-group">
                                <label for="" class="col-sm-3 control-label"><h4><strong>Debit</strong></h4></label>
                                <div class="col-sm-2">
                                    <input type="number" step="0.01" class="form-control" value="<?php echo ""; ?>" name="debit" placeholder="Debit" required>
                                </div><!-- Receiving Account-->
                                <div class="col-sm-4">
                                    <select name="debitAccount[0][code]" class="form-control select2" style="width: 100%" required>
                                        <option value="" selected disabled><h4>Select account</h4></option>
                                        <?php while ($row = mysqli_fetch_assoc($getAccounts)) { ?>
                                            <option value="<?php echo $row['code'].":".$row['name']; ?>"  <?php if($row['code']==""){echo "selected";} ?>><?php echo $row['code'] . " - " . $row['name']; ?></option>
                                        <?php } ?>
                                    </select>
                                <table width="50%" cellspacing="0" id="documents" class="table table-small-font table-bordered table-striped">
                                </table>
                                </div>
                                <div class="col-sm-2">
                                    <button id="addRows_document" type="button"
                                            class="btn btn-success"><i class="fa fa-plus">&nbsp;Select More</i></button>
                                </div>

                            </div>

                        </div>
                        <br>
                        <div class="row">
                            <div class="form-group">
                                <label for="credit" class="col-sm-3 control-label"><h4><strong>Credit</strong></h4></label>
                                <div class="col-sm-2">
                                    <input type="number" step="0.01" required class="form-control" id="credit"  value="<?php echo ""; ?>" name="credit" placeholder="Credit">
                                </div>

                                <div class="col-sm-4">
                                    <select name="creditAccount" required class="form-control select2" style="width: 100%"><!-- Paying Account-->
                                        <option value="" selected disabled><h4>Select account</h4></option>
                                        <?php while ($row = mysqli_fetch_assoc($getAccounts1)) { ?>
                                            <option value="<?php echo $row['code']; ?>" <?php if($row['code']==""){echo "selected";} ?>><?php echo $row['code'] . " - " . $row['name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <br>
                        <div class="row">
                            <div class="form-group">
                                <label for="entryDate" class="col-sm-3 control-label"><h4><strong>Entry Date</strong></h4></label>
                                <div class="col-sm-6">
                                    <input type="datetime-local" required id="entryDate" value="<?php echo ""; ?>" class="form-control" name="entryDate"
                                           placeholder="Entry Date">
                                </div>

                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="form-group">
                                <label for="notes" class="col-sm-3 control-label"><h4><strong>Notes</strong></h4></label>
                                <div class="col-xs-6">
                                <textarea class="form-control" id="notes" name="notes"
                                          placeholder="Notes"><?php echo ""; ?></textarea>
                                </div>


                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="form-group">
                                <label for="notes" class="col-sm-3 control-label"><h4><strong>Upload a File</strong></h4></label>
                                <div class="col-xs-6">
                                    <input type="file" name="file">
                                </div>


                            </div>
                        </div>
                        <div align="center">
                            <div class="box-footer">

                                <button name="save" type="submit"
                                        class="btn btn-success "><i
                                            class="fa fa-save">&nbsp;Log Journal Entry</i>
                                </button>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>




<script>
    $(document).ready(function () {
        $(document).on('click', '#checkAll_document', function () {
            $(".itemRow_document").prop("checked", this.checked);
        });
        $(document).on('click', '.itemRow_document', function () {
            if ($('.itemRow_document:checked').length == $('.itemRow_document').length) {
                $('#checkAll_document').prop('checked', true);
            } else {
                $('#checkAll_document').prop('checked', false);
            }
        });
        var count = 1;
        $(document).on('click', '#addRows_document', function () {
            var htmlRows = '';
            htmlRows += '<tr>';
            htmlRows += '<td><input type="number" name="debitAccount[' + count + '][debit]" class="form-control" placeholder="debit">';
            htmlRows += '<td align="center"><select width="100%" class=" form-control select2" name="debitAccount[' + count + '][code]">\n' +
                '                                                <option value="" selected disabled>Select</option>\n' +
                    <?php $allCodes=mysqli_query($link,"select * from gl_codes") ;
                    while($row=mysqli_fetch_assoc($allCodes)){
                ?>
                '                                                    <option value="<?php echo $row['code'].":".$row['name']; ?>"><?php echo $row['code']."-".$row['name']; ?></option>\n' +
                                <?php } ?>
                '                                                </select> </td>';
            htmlRows += '</tr>';
            $('#documents').append(htmlRows);
            count++;
        });
        $(document).on('click', '#removeRows_document', function () {
            $(".itemRow_document:checked").each(function () {
                $(this).closest('tr').remove();
            });
            $('#checkAll_document').prop('checked', false);
            calculateTotal();
        });

        $(document).on('click', '.deleteRow_document', function () {
            var id = $(this).attr("id");
            if (confirm("Are you sure you want to remove this?")) {
                $.ajax({
                    url: "action.php",
                    method: "POST",
                    dataType: "json",
                    data: {id: id, action: 'delete_row'},
                    success: function (response) {
                        if (response.status == 1) {
                            $('#' + id).closest("tr").remove();
                        }
                    }
                });
            } else {
                return false;
            }
        });
    });

</script>
