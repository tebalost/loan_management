<div class="box">
    <div class="box-body">
        <div class="panel panel-success">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-dollar"></i> New Payment</h3>
            </div>
            <div class="box-body">

                <form class="form-horizontal" method="post" enctype="multipart/form-data" action="process_payment.php">

                    <div class="box-body">
                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label">Customer</label>
                            <div class="col-sm-6">
                                <select class="customer select2" name="customer" style="width: 100%;">
                                    <option selected="selected">--Select Customer--</option>
                                    <?php
                                    $get = mysqli_query($link, "SELECT * FROM borrowers order by id") or die (mysqli_error($link));
                                    while ($rows = mysqli_fetch_array($get)) {
                                        echo '<option value="' . $rows['id'] . '">' . $rows['fname'] . '&nbsp;' . $rows['lname'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label">Customer Account#</label>
                            <div class="col-sm-6">
                                <select class="account select2" name="account" style="width: 100%;" id="customerAccount">
                                    <option selected="selected">--Select Customer Account--</option>
                                    <?php
                                    $getin = mysqli_query($link, "SELECT * FROM loan_info order by borrower") or die (mysqli_error($link));
                                    while ($row = mysqli_fetch_array($getin)) {
                                        echo '<option value="' . $row['baccount'] . '">' . $row['baccount'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label">Loan</label>
                            <div class="col-sm-6">

                                <select class="loan form-control"  name="loan" id="currentBalance">
                                    <option selected="selected">--Select Loan--</option>
                                    <?php
                                    $get = mysqli_query($link, "SELECT * FROM loan_info order by id") or die (mysqli_error($link));
                                    while ($rows = mysqli_fetch_array($get)) {
                                        $strJsonFileContents = file_get_contents('include/packages.json');
                                        $arrayOfTypes = json_decode($strJsonFileContents, true);
                                        $loan_product = $rows['loan_product'];
                                        foreach ($arrayOfTypes['accountType'] as $key => $value) {
                                            if ($loan_product == $key) {
                                                $loan_product = $value;
                                            }
                                        }
                                        echo '<option value="' . $rows['amount_topay'] . '">' . $loan_product."(" . $rows['amount'] . "-" . "&nbsp;" . "bal:" . $rows['amount_topay'] . ")" . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label">Payment Date</label>
                            <div class="col-sm-6">
                                    <input type="text" class="form-control pull-right"
                                           value="<?php echo date('Y-m-d'); ?>" name="pay_date" readonly>

                            </div>
                        </div>
                        <div class="form-group">
                            <label for="paymentMethod" class="col-sm-3 control-label">Payment Method *</label>
                            <div class="col-sm-6">
                                <select class="form-control" name="paymentMethod"
                                        id="paymentMethod"
                                        required>
                                    <option value="">Select</option>
                                    <option value="01">Payroll Deduction</option>
                                    <option value="02">Differed Payment</option>
                                    <option value="03">Staff Account</option>
                                    <option value="04">Under Administration</option>
                                    <option value="05">Judgement Granted</option>
                                    <option value="06">Debt Restructured </option>
                                    <option value="07">Voluntary Debt Consolidation</option>
                                    <option value="08">Debt Rescheduled</option>
                                    <option value="09">Forced Reduction of Overdraft</option>
                                    <option value="10">In-Excess</option>
                                    <option value="11">Pending Registration</option>
                                    <option value="00">Other</option>
                                </select>
                            </div>
                        </div>
                        <?php $getAccounts = mysqli_query($link,"select * from bank_accounts"); ?>
                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label">Receiving Account *</label>
                            <div class="col-sm-6">
                                <select name="toAccount" class="form-control" required>
                                    <option value="" selected disabled>Select receiving account</option>
                                    <?php while($row=mysqli_fetch_assoc($getAccounts)){ ?>
                                        <option value="<?php echo $row['gl_code']."-".$row['accountNumber']; ?>"><?php echo $row['gl_code']."-".$row['accountNumber']." - ".$row['bankName']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="amountToPay" class="col-sm-3 control-label">Amount to Pay</label>
                            <div class="col-sm-6">
                                <input name="paid_amount"
                                       type="number"
                                       min="0"
                                       id="amountToPay"
                                       maxlength="10"
                                       step="0.01"
                                       oninput="maxLengthCheck(this)"
                                       class="form-control"
                                       placeholder="Amount to Pay"
                                       required>
                            </div>
                            <script>
                                // This is an old version, for a more recent version look at
                                // https://jsfiddle.net/DRSDavidSoft/zb4ft1qq/2/
                                function maxLengthCheck(object) {
                                    if (object.value.length > object.maxLength)
                                        object.value = object.value.slice(0, object.maxLength)
                                }
                            </script>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label">Payment Reference</label>
                            <div class="col-sm-6">
                                <input name="reference" type="text"
                                       class="form-control"
                                       placeholder="Payment Reference" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label">Teller By</label>
                            <div class="col-sm-6">
                                <?php
                                $tid = $_SESSION['tid'];
                                $sele = mysqli_query($link, "SELECT * from user WHERE id = '$tid'") or die (mysqli_error($link));
                                while ($row = mysqli_fetch_array($sele)) {
                                    ?>
                                    <input name="teller" type="text" class="form-control"
                                           value="<?php echo $row['name']; ?>" readonly>
                                <?php } ?>
                            </div>
                        </div>


                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label">Remarks</label>
                            <div class="col-sm-6">
                                <textarea name="remarks" class="form-control" rows="4" cols="80"></textarea>
                            </div>
                        </div>

                    </div>

                    <div align="right">
                        <div class="box-footer">
                            <button type="reset" class="btn btn-primary btn-flat"><i class="fa fa-times">&nbsp;Reset</i>
                            </button>
                            <button name="save" type="submit" class="btn btn-success btn-flat"><i class="fa fa-save">&nbsp;Make Payment</i></button>

                        </div>
                    </div>
                </form>


            </div>
        </div>
    </div>
</div>




<script>
/*
    $("#currentBalance, #amountToPay").keyup(function () {
        update();
    });

    function update() {
        var currentBalance = document.getElementById("currentBalance").value;
        $("#amountToPay").val(currentBalance);
    }
*/

</script>

<script type="text/javascript">
    $(".numeric").numeric();
    $(".positive").numeric({ negative: false });
    $(".positive-integer").numeric({ decimal: false, negative: false });
    $(".negative-integer").numeric({ decimal: false, negative: true });
    $(".decimal-2-places").numeric({ decimalPlaces: 2 });
    $(".decimal-4-places").numeric({ decimalPlaces: 4 });
    $("#remove").click(
        function(e)
        {
            e.preventDefault();
            $(".numeric,.positive,.positive-integer,.decimal-2-places,.decimal-4-places").removeNumeric();
        }
    );
</script>
<script>
    $(document).ready(function () {
        $(".slidingDivAdvanceSettings").hide();
        $('.show_hide_advance_settings').click(function (e) {
            $(".slidingDivAdvanceSettings").slideToggle("fast");
            var val = $(this).text() == "Hide" ? "Show" : "Hide";
            $(this).hide().text(val).fadeIn("fast");
            e.preventDefault();
        });
    });
</script>
<script type="text/javascript">
    $('#form').on('submit', function(e) {

        $('.submit-button').prop('disabled', true);
        $('.submit-button').html('<i class="fa fa-spinner fa-spin"></i> Please wait..');
        return true;
    });
</script>
<script>
    $(function() {
        $('.date_select').datepick({

            defaultDate: '10/10/2020', showTrigger: '#calImg',
            yearRange: 'c-20:c+20', showTrigger: '#calImg',

            dateFormat: 'dd/mm/yyyy',
            minDate: '01/01/1980'
        });
    });

    function checkManualComposition()
    {
        var inputManualCompositionCheck = document.getElementById("inputManualCompositionCheck");
        var inputPrincipalRepaymentAmount = document.getElementById("inputPrincipalRepaymentAmount");
        var inputInterestRepaymentAmount = document.getElementById("inputInterestRepaymentAmount");
        var inputFeesRepaymentAmount = document.getElementById("inputFeesRepaymentAmount");
        var inputPenaltyRepaymentAmount = document.getElementById("inputPenaltyRepaymentAmount");
        if(inputManualCompositionCheck.checked)
        {
            inputPrincipalRepaymentAmount.disabled = false;
            inputInterestRepaymentAmount.disabled = false;
            inputFeesRepaymentAmount.disabled = false;
            inputPenaltyRepaymentAmount.disabled = false;
        }
        else{
            inputPrincipalRepaymentAmount.disabled = true;
            inputInterestRepaymentAmount.disabled = true;
            inputFeesRepaymentAmount.disabled = true;
            inputPenaltyRepaymentAmount.disabled = true;
            updatesum();
        }
    }

    function updatesum()
    {
        var inputRepaymentAmountTotal = 0;
        var inputPrincipalRepaymentAmount = document.getElementById("inputPrincipalRepaymentAmount").value;
        if (inputPrincipalRepaymentAmount == "")
            inputPrincipalRepaymentAmount = 0;

        var inputInterestRepaymentAmount = document.getElementById("inputInterestRepaymentAmount").value;
        if (inputInterestRepaymentAmount == "")
            inputInterestRepaymentAmount = 0;

        var inputFeesRepaymentAmount = document.getElementById("inputFeesRepaymentAmount").value;
        if (inputFeesRepaymentAmount == "")
            inputFeesRepaymentAmount = 0;

        var inputPenaltyRepaymentAmount = document.getElementById("inputPenaltyRepaymentAmount").value;
        if (inputPenaltyRepaymentAmount == "")
            inputPenaltyRepaymentAmount = 0;

        inputRepaymentAmountTotal = parseFloat(inputPrincipalRepaymentAmount)*100  + parseFloat(inputInterestRepaymentAmount)*100 + parseFloat(inputFeesRepaymentAmount)*100  + parseFloat(inputPenaltyRepaymentAmount)*100;

        document.getElementById("RepaymentAmountTotal").innerHTML = numberWithCommas((inputRepaymentAmountTotal / 100).toFixed(2));
    }
    function numberWithCommas(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
    function enableDisableAdjustManual() {
        if ($('#inputAdjustRemainingSchedule').is(":checked"))
        {
            $('#inputAdjustRemainingScheduleProRata').prop('disabled', false);
            $('#inputManualCompositionCheck').prop('disabled', true);
        }
        else if ($('#inputManualCompositionCheck').is(":checked"))
        {
            $('#inputAdjustRemainingScheduleProRata').prop('disabled', true);
            $('#inputAdjustRemainingScheduleProRata').prop('checked', false);
            $('#inputAdjustRemainingSchedule').prop('disabled', true);
        }
        else
        {
            $('#inputManualCompositionCheck').prop('disabled', false);
            $('#inputAdjustRemainingSchedule').prop('disabled', false);
            $('#inputAdjustRemainingScheduleProRata').prop('disabled', true);
            $('#inputAdjustRemainingScheduleProRata').prop('checked', false);
        }
    }
    $('#inputAdjustRemainingSchedule, #inputManualCompositionCheck').change(function() {
        enableDisableAdjustManual();
    });

    $(function ()
    {
        updatesum();
        enableDisableAdjustManual();
    });


</script>