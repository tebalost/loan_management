
<?php
if(isset($_POST['savebank_accounts'])){
    foreach ($_POST['bank'] as $key => $value) {
        $name = $value['bankName'];
        $accountNumber = $value['accountNumber'];
        $transactionType = $value['transactionType'];
        $permitted_chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $txID = substr(str_shuffle($permitted_chars), 0, 10);
        $gl_code = $value['gl_code'];
        $source_gl_code=$value['source_gl_code'];
        if($value['addFunds']!="") {
            $deposit = $value['addFunds'];
        }else{
            $deposit=0;
        }
        //Get the current balance
        if($transactionType!="") {
            mysqli_query($link, "update bank_accounts set transactionType='$transactionType', gl_code='$gl_code', source_gl_code='$source_gl_code' where accountNumber='$accountNumber'");
        }
        $balance=mysqli_query($link,  "select * from bank_accounts where accountNumber='$accountNumber'");

        //Balance of the Depositing GL
        $balanceSource=mysqli_query($link,  "select balance from gl_codes where code='$source_gl_code'");

        if(mysqli_num_rows($balance)==0) {
            $bal = mysqli_fetch_assoc($balance);
            $balSource = mysqli_fetch_assoc($balanceSource);
            $currentBalance=$bal['balance'];
            $currentBalanceSource=$balSource['balance'];
            $finalBalance=$currentBalance+$deposit;
            $finalBalanceSource=$currentBalanceSource+$deposit;

            $bankingInfo = mysqli_query($link, "INSERT into bank_accounts values(0,'$name','$accountNumber','$deposit','$transactionType','$gl_code','$source_gl_code')") or die(mysqli_error($link));
            if($value['addFunds']!="") {
                //Debit Transaction
                $bankingInfo = mysqli_query($link, "update gl_codes set balance='$finalBalance' where code='$gl_code'");
                $transaction = mysqli_query($link, "INSERT into system_transactions values (0,NOW(),'$accountNumber','Deposit','$currentBalance','$deposit','','$deposit','$tid','','$txID')");
                $journal = mysqli_query($link, "INSERT into journal_transactions values (0,NOW(),'$gl_code','Deposit from $source_gl_code','$currentBalance','$deposit','','$deposit','$tid','$txID','','')");

                //Credit Transaction
                $bankingInfo = mysqli_query($link, "update gl_codes set balance='$finalBalanceSource' where code='$source_gl_code'");
                $journal = mysqli_query($link, "INSERT into journal_transactions values (0,NOW(),'$source_gl_code','Deposit to $gl_code','$currentBalance','','$deposit','$deposit','$tid','$txID','','')");
            }
        }else{
            $bal = mysqli_fetch_assoc($balance);
            $balSource = mysqli_fetch_assoc($balanceSource);
            $currentBalance=$bal['balance'];
            $currentBalanceSource=$balSource['balance'];
            $finalBalance=$currentBalance+$deposit;
            $finalBalanceSource=$currentBalanceSource+$deposit;
            $bankingInfo = mysqli_query($link, "update bank_accounts set balance='$finalBalance' where accountNumber='$accountNumber'");
            $bankingInfo = mysqli_query($link, "update gl_codes set balance='$finalBalance' where code='$gl_code'");
            if($value['addFunds']!="") {
                $transaction = mysqli_query($link, "INSERT into system_transactions values (0,NOW(),'$accountNumber','Deposit','$currentBalance','$deposit','','$finalBalance','$tid','','$txID')");
                $journal = mysqli_query($link, "INSERT into journal_transactions values (0,NOW(),'$gl_code','Deposit from $source_gl_code','$currentBalance','$deposit','','$finalBalance','$tid','$txID','','')");
                $journal_source = mysqli_query($link, "INSERT into journal_transactions values (0,NOW(),'$source_gl_code','Deposit to $gl_code','$currentBalanceSource','','$deposit','$finalBalanceSource','$tid','$txID','','')");
            }
        }
    }
    if ($bankingInfo) {
        echo '<div class="alert alert-success" >
                                        <a href = "#" class = "close" data-dismiss= "alert"> &times;</a>
                                         Successfully saved banking information!&nbsp; &nbsp;&nbsp;
                                           </div>';
    } else {
        echo '<div class="alert alert-warning" >
                                        <a href = "#" class = "close" data-dismiss= "alert"> &times;</a>
                                         Unable to save the banking details!&nbsp; &nbsp;&nbsp;
                                           </div>';
    }
}
?>
<div class="row">

    <section class="content">

          <div class="box box-info">
            <div class="box-body">
                <div class="table-responsive">
                    <div class="box-body">
                        <form method="post">
                            <a href="dashboard.php?id=<?php echo $_SESSION['tid']; ?>&&mid=<?php echo base64_encode("401"); ?>">
                                <button type="button" class="btn btn-flat btn-warning"><i
                                            class="fa fa-mail-reply-all"></i>&nbsp;Back
                                </button>
                            </a>

                            <a href="printpayment.php" target="_blank" class="btn btn-primary btn-flat"><i
                                        class="fa fa-print"></i>&nbsp;Print Summary</a>
                            <a href="excelpayment.php" target="_blank" class="btn btn-success btn-flat"><i
                                        class="fa fa-send"></i>&nbsp;Export Excel</a>

                            <hr>
                            <style>
                                th {
                                    padding-top: 12px;
                                    padding-bottom: 12px;
                                    text-align: left;
                                    background-color: #D1F9FF;
                                }
                            </style>

                            <div class="panel panel-default">
                                <div class="panel-body bg-gray-light text-bold"><i class="fa fa-bank"></i>
                                    Company Bank Accounts <a href="#" class="show_hide_bank_settings">&nbsp;Show</a>
                                </div>
                            </div>

                            <div class="slidingDivBankSettings" style="display: none;">
                                <form action="" method="post">
                                    <div class="box-body">

                                        <div class="table-responsive" data-pattern="priority-columns">

                                            <table cellspacing="0" id="bank_accounts"
                                                   class="table table-small-font table-bordered table-striped">

                                                <thead>
                                                <tr>
                                                    <th width="2%"><input id="checkAll_banking"
                                                                          class="formcontrol"
                                                                          type="checkbox">
                                                    </th>
                                                    <th>Bank Name</th>
                                                    <th>Account Number</th>
                                                    <th>GL Code</th>
                                                    <th>Used For</th>
                                                    <th>Balance</th>
                                                    <th>Add Funds</th>
                                                    <th>Source</th>
                                                </tr>
                                                </thead>

                                                <tbody>

                                                <?php
                                                //Get all Settings
                                                $count = 0;
                                                $bank_accounts = mysqli_query($link, "SELECT * FROM bank_accounts");
                                                while ($bankInfo = mysqli_fetch_assoc($bank_accounts)) {
                                                    $id = $bankInfo['id'];
                                                    $idm = $_GET['id'];
                                                    ?>
                                                    <tr>
                                                        <td width="30"><input id="optionsCheckbox"
                                                                              class="uniform_on"
                                                                              name="selector[]"
                                                                              type="checkbox"
                                                                              value="<?php echo $id; ?>"
                                                            >
                                                        </td>

                                                        <td>
                                                            <select name="bank[<?php echo $count; ?> ][bankName]"
                                                                    class="form-control" required>
                                                                <option value="">Select a bank&hellip;
                                                                </option>
                                                                <option <?php if ($bankInfo['bankName'] == "First National Bank") {
                                                                    echo "selected";
                                                                } ?> value="First National Bank">First National Bank
                                                                </option>
                                                                <option <?php if ($bankInfo['bankName'] == "Postbank") {
                                                                    echo "selected";
                                                                } ?> value="Postbank">Postbank
                                                                </option>
                                                                <option <?php if ($bankInfo['bankName'] == "Nedbank") {
                                                                    echo "selected";
                                                                } ?> value="Nedbank Lesotho">Nedbank Lesotho
                                                                </option>
                                                                <option <?php if ($bankInfo['bankName'] == "Standard Lesotho Bank") {
                                                                    echo "selected";
                                                                } ?> value="Standard Lesotho Bank">Standard Lesotho Bank
                                                                </option>
                                                                <option <?php if ($bankInfo['bankName'] == "Vodacom M-pesa") {
                                                                    echo "selected";
                                                                } ?> value="Vodacom M-pesa">Vodacom M-pesa
                                                                </option>
                                                                <option <?php if ($bankInfo['bankName'] == "Ecocash") {
                                                                    echo "selected";
                                                                } ?> value="Ecocash">Ecocash
                                                                </option>
                                                            </select>
                                                        </td>

                                                        <td>

                                                            <input
                                                                    name="bank[<?php echo $count; ?> ][accountNumber]"
                                                                    type="number"
                                                                    class="form-control"
                                                                    placeholder="Account Number"
                                                                    value="<?php echo $bankInfo['accountNumber']; ?>"
                                                            >

                                                        </td>
                                                        <td>
                                                            <select name="bank[<?php echo $count; ?> ][gl_code]" class="form-control select2" style="width: 100%" required>
                                                                <option value="" selected disabled>Select</option>
                                                                <?php
                                                                $cash = mysqli_query($link,"select * from gl_codes where portfolio = 'CASH AND CASH EQUIVALENTS'");

                                                                while($row = mysqli_fetch_assoc($cash)){?>
                                                                    <option value="<?php echo $row['code'] ?>" <?php if($bankInfo['gl_code']==$row['code']){ echo "selected"; } ?> ><?php echo $row['code'] ?>&nbsp;-&nbsp;<?php echo $row['name'] ?></option>
                                                                <?php  }
                                                                ?>
                                                            </select>
                                                        </td>

                                                        <td>
                                                            <select name="bank[<?php echo $count; ?> ][transactionType]"
                                                                    class="form-control" required>
                                                                <option value="">Disbursement type&hellip;
                                                                </option>
                                                                <option <?php if ($bankInfo['transactionType'] == "Online Transfer") {
                                                                    echo "selected";
                                                                } ?> value="Online Transfer">Online Transfer
                                                                </option>
                                                                <option <?php if ($bankInfo['transactionType'] == "Mobile Money") {
                                                                    echo "selected";
                                                                } ?> value="Mobile Money">Mobile Money
                                                                </option>
                                                            </select>
                                                        </td>

                                                        <td>

                                                            <input
                                                                    name="bank[<?php echo $count; ?> ][balance]"
                                                                    type="number"
                                                                    step="0.01"
                                                                    readonly
                                                                    class="form-control"
                                                                    placeholder="Account Number"
                                                                    value="<?php echo $bankInfo['balance']; ?>"
                                                            >

                                                        </td>
                                                        <td>

                                                            <input
                                                                    name="bank[<?php echo $count; ?> ][addFunds]"
                                                                    type="number"
                                                                    step="0.01"
                                                                    min="1"
                                                                    class="form-control"
                                                                    placeholder="Add Funds"
                                                                    maxlength="8"
                                                                    value="">

                                                        </td>
                                                        <td>
                                                            <select name="bank[<?php echo $count; ?> ][source_gl_code]" class="form-control select2" style="width: 100%" required>
                                                                <option value="" selected disabled>Select</option>
                                                                <?php
                                                                $allCodes = mysqli_query($link,"select * from gl_codes");
                                                                while($row = mysqli_fetch_assoc($allCodes)){?>
                                                                    <option value="<?php echo $row['code'] ?>" <?php if($bankInfo['source_gl_code']==$row['code']){ echo "selected"; } ?> ><?php echo $row['code'] ?>&nbsp;-&nbsp;<?php echo $row['name'] ?></option>
                                                                <?php  }
                                                                ?>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <?php $count ++; } ?>

                                                <tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div align="left">
                                        <button id="addRows_banking" type="button"
                                                class="btn btn-success"><i class="fa fa-plus">&nbsp;Add
                                                Bank Account</i></button>
                                        <button name="delrow" type="submit" id="removeRows_banking" class="btn btn-danger">
                                            <i
                                                    class="fa fa-trash">&nbsp;Delete Account</i></button>

                                    </div>
                                    <div class="box-footer" align="center">
                                        <button type="submit" class="btn btn-info"
                                                name="savebank_accounts">
                                            <i class="fa fa-save">&nbsp;Update Banking Information</i>
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <?php if(!isset($_GET['act'])){ ?>
                            <table id="example" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th><input type="checkbox" id="select_all"/></th>
                                    <th>Bank</th>
                                    <th>Account Number</th>
                                    <th>Used For</th>
                                    <th>Today Opening Balance</th>
                                    <th><strong>Running Balance</strong></th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $tid = $_SESSION['tid'];
                                $select = mysqli_query($link, "SELECT * FROM bank_accounts") or die (mysqli_error($link));

                                if (mysqli_num_rows($select) == 0) {
                                    echo "<div class='alert alert-info'>No data found yet!.....Check back later!!</div>";
                                } else {
                                    while ($row = mysqli_fetch_array($select)) {
                                            $id=$row['id'];
                                            $account=$row['accountNumber'];
                                            $today=date('Y-m-d');

                                            $first_transation = mysqli_fetch_assoc(mysqli_query($link, "SELECT min(date) FROM system_transactions where account='$account' and date>'$today'"));
                                            $time=$first_transation['min(date)'];
                                            $today_opening_balance = mysqli_fetch_assoc(mysqli_query($link, "SELECT opening_balance FROM system_transactions where date='$time' and account='$account'"));
                                            if($time==""){
                                                $opening_balance = $row['balance'];
                                            }else{
                                                $opening_balance=$today_opening_balance['opening_balance'];
                                            }
                                            ?>
                                            <tr>
                                                <td><input id="optionsCheckbox" class="checkbox" name="selector[]"
                                                           type="checkbox" value="<?php echo $id; ?>"></td>
                                                <td><?php echo $row['bankName']; ?></td>
                                                <td><?php echo $row['accountNumber']; ?></td>
                                                <td><?php echo $row['transactionType']; ?></td>
                                                <td align="right"><strong><?php echo number_format($opening_balance, 2, ".", ","); ?></strong></td>
                                                <td align="right"><strong><?php echo number_format($row['balance'], 2, ".", ","); ?></strong></td>
                                                <td>
                                                <a href="#myModal <?php echo $id; ?>"> <i class="fa fa-eye" data-target="#myModal<?php echo $id; ?>" data-toggle="modal"></i>
                                                </td>
                                            </tr>
                                        <?php
                                    }
                                } ?>
                                </tbody>
                            </table>
                            <?php } ?>

                        </form>
                    </div>

                </div>
            </div>

        </div>

        <div class="box box-info">
            <div class="box-body">
                <div class="alert alert-info" align="center" class="style2" style="color: #FFFFFF">NUMBER OF BANK/MOBILE ACCOUNTS:&nbsp;
                    <?php
                    $call3 = mysqli_query($link, "SELECT * FROM bank_accounts ");
                    $num3 = mysqli_num_rows($call3);
                    ?>
                    <?php echo $num3; ?>

                </div>
                <!--FIXME, add the accounts to the chart---Work on the JSON to loop through the accounts -->
                <div id="accounts"></div>
            </div>
        </div>

</div>
<script>
    var chart1 = AmCharts.makeChart("accounts", {
        "type": "pie",
        "theme": "light",
        "dataProvider": [
            <?php
            $banks = mysqli_query($link, 'SELECT * from bank_accounts');
            $i=0;
            while($row=mysqli_fetch_assoc($banks))
            {
            if($i<mysqli_num_rows($banks)){
            ?>
            {
                "title": " <?php echo $row['bankName']; ?>",
                "value": <?php echo $row['balance']; ?>
            },

            <?php } else{  ?>
            {
                "title": " <?php echo $banks['bankName']; ?>",
                "value": <?php echo $banks['balance']; ?>
            }
            <?php } $i++; } ?>
        ],
        "titleField": "title",
        "valueField": "value",
        "labelRadius": 5,

        "radius": "42%",
        "innerRadius": "60%",
        "labelText": "[[title]]",
        "export": {
            "enabled": true
        }
    });
</script>
<script>

    $(document).ready(function () {
        $(".slidingDivBankSettings").hide();
        $('.show_hide_bank_settings').click(function (e) {
            $(".slidingDivBankSettings").slideToggle("fast");
            var val = $(this).text() == "Hide" ? "Show" : "Hide";
            $(this).hide().text(val).fadeIn("fast");
            e.preventDefault();
        });
    });
    $(document).ready(function () {
        $(".slidingDivInternalSettings").show();
        $('.show_hide_internal_settings').click(function (e) {
            $(".slidingDivInternalSettings").slideToggle("fast");
            var val = $(this).text() == "Hide" ? "Show" : "Hide";
            $(this).hide().text(val).fadeIn("fast");
            e.preventDefault();
        });
    });
    $(document).ready(function () {
        $(".slidingDivAccountingettings").hide();
        $('.show_hide_accounting_settings').click(function (e) {
            $(".slidingDivAccountingSettings").slideToggle("fast");
            var val = $(this).text() == "Hide" ? "Show" : "Hide";
            $(this).hide().text(val).fadeIn("fast");
            e.preventDefault();
        });
    });
</script>
<script>
    $(document).ready(function () {
        $(document).on('click', '#checkAll_banking', function () {
            $(".itemRow_banking").prop("checked", this.checked);
        });
        $(document).on('click', '.itemRow_banking', function () {
            if ($('.itemRow_banking:checked').length == $('.itemRow_banking').length) {
                $('#checkAll_banking').prop('checked', true);
            } else {
                $('#checkAll_banking').prop('checked', false);
            }
        });
        var count = $(".itemRow_banking").length;
        $(document).on('click', '#addRows_banking', function () {
            var htmlRows = '';
            htmlRows += '<tr>';
            htmlRows += '<td><input class="itemRow_banking" type="checkbox" name="selector[]" value="' + count + '"></td>';
            htmlRows += '' +
                '<td>' +
                '<select name="bank[' + count + '][bankName]" class="form-control" required> ' +
                '<option value="" disabled>Select a bank&hellip;</option> ' +
                '<option value="First National Bank">First National Bank</option> ' +
                '<option value="Postbank">Postbank</option> ' +
                '<option value="Nedbank">Nedbank</option> ' +
                '<option value="Standard Lesotho Bank">Standard Lesotho Bank</option> ' +
                '<option value="Vodacom M-pesa">Vodacom M-pesa</option> ' +
                '<option value="Ecocash">Ecocash</option> ' +
                '</select>' +
                '</td>';
            htmlRows += '<td><input type="text" placeholder="Account Number" name="bank[' + count + '][accountNumber]" id="Accoumt' + count + '" class="form-control" autocomplete="off" required></td>';
            htmlRows += '<td align="center"><select class="form-control select2" style="width: 100%" name="bank[' + count + '][gl_code]" required>\n' +
                '                                                <option value="" selected disabled>Select</option>\n' +
                <?php
                $cash = mysqli_query($link,"select * from gl_codes where portfolio = 'CASH AND CASH EQUIVALENTS'");
                while($row=mysqli_fetch_assoc($cash)){ ?>
                '                                                    <option value="<?php echo $row['code']; ?>"><?php echo $row['code'] ?>&nbsp;-&nbsp;<?php echo $row['name']; ?></option>\n' +
                <?php } ?>
                '                                                </select> </td>';
            htmlRows += '' +
                '<td>' +
                '<select name="bank[' + count + '][transactionType]" class="form-control" required> ' +
                '<option value="">Disbursement Type&hellip;</option> ' +
                '<option value="Online Transfer">Online Transfer</option> ' +
                '<option value="Mobile Money">Mobile Money</option> ' +
                '</select>' +
                '</td>';
            htmlRows += '<td><input type="text" placeholder="Balance" step="0.01" name="bank[' + count + '][balance]" maxlength="8" id="balance' + count + '" min="0" class="form-control" value="0" autocomplete="off" readonly></td>';
            htmlRows += '<td><input type="text" placeholder="Add Funds" step="0.01" min="1" name="bank[' + count + '][addFunds]" maxlength="8" id="funds' + count + '" min="0" class="form-control" autocomplete="off" required></td>';
            htmlRows += '<td align="center"><select class="form-control select2" style="width: 100%" name="bank[' + count + '][source_gl_code]" required>\n' +
                '                                                <option value="" selected disabled>Select</option>\n' +
                <?php
                $allCodes= mysqli_query($link,"select * from gl_codes");
                while($row=mysqli_fetch_assoc($allCodes)){ ?>
                '                                                    <option value="<?php echo $row['code']; ?>"><?php echo $row['code'] ?>&nbsp;-&nbsp;<?php echo $row['name']; ?></option>\n' +
                <?php } ?>
                '                                                </select> </td>';
            htmlRows += '</tr>';
            $('#bank_accounts').append(htmlRows);
            count++;
        });
        $(document).on('click', '#removeRows_banking', function () {
            $(".itemRow_banking:checked").each(function () {
                $(this).closest('tr').remove();
            });
            $('#checkAll_banking').prop('checked', false);
            calculateTotal();
        });

        $(document).on('click', '.deleteRow_banking', function () {
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
