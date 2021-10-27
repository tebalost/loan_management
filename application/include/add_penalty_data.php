<div class="row">
    <?php
    $productId=$_GET['productId'];
    if(isset($_POST['penalty'])) {
        $penalty = $_POST['penalty'];
        $productId = $_POST['penalty']['loanProductId'];
        $penaltyData = json_encode($penalty);
        $updatePenalty = mysqli_query($link, "update products set penalty='$penaltyData' where product_id='$productId'");

        if (!$updatePenalty) {
            echo '<div class="alert alert-danger" >
                                                                <a href = "#" class = "close" data-dismiss= "alert"> &times;</a>
                                                                Failed to update Penalty Settings!&nbsp; &nbsp;&nbsp;
                                                                </div>';
        } else {
            echo '<div class="alert alert-success" >
                                                                <a href = "#" class = "close" data-dismiss= "alert"> &times;</a>
                                                                Penalty Settings Successfully Updated!&nbsp; &nbsp;&nbsp;
                                                                </div>';
        }
        $getPenalty=mysqli_fetch_assoc(mysqli_query($link,"select * from products where product_id='$productId'"));

    }else{
        $getPenalty=mysqli_fetch_assoc(mysqli_query($link,"select * from products where product_id='$productId'"));

    }
    $storedPenalty=json_decode($getPenalty['penalty'],true);
    ?>

    <div class="box box-info">
        <div class="box-body">
            <form class="form-horizontal" method="post" enctype="multipart/form-data" id="form">
                <input type="hidden" name="edit_penalty_submitted" value="1">
                <input type="hidden" name="penalty[loanProductId]" value="<?php echo $_GET['productId']; ?>">
                <input type="hidden" name="step" value="1">
                <input type="hidden" name="back_url" value="">
                <div class="callout callout-warning">
                    <p>Penalty settings will automatically apply to all <u><b>Open loans</b></u> in this Loan Product
                        based on the below settings. Penalty will be calculated from the Loan Released Date.</p>
                </div>
                <div class="panel panel-default">
                    <div class="panel-body bg-gray text-bold">Late Repayment Penalty:</div>
                </div>

                <div class="form-group">
                    <div class="col-sm-offset-5 col-sm-7">
                        <div class="radio">
                            <label>
                                <input type="radio" name="penalty[penaltyType]" id="inputLRPenaltyPercentage"
                                       value="percentage" checked="">
                                I want Penalty to be percentage % based
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputLRCalculatePenaltyOn" id="inputLRCalculatePenaltyOnLabel"
                           class="col-sm-5 control-label">Calculate Penalty on</label>
                    <div class="col-sm-5">
                        <select class="form-control" name="penalty[penaltyCalculateOn]" id="inputLRCalculatePenaltyOn"
                                required="">;
                            <option value=""></option>
                            <option <?php if(isset($storedPenalty['penaltyCalculateOn'])&&$storedPenalty['penaltyCalculateOn']=="Overdue Principal Amount"){ echo "Selected";} ?> value="Overdue Principal Amount">Overdue Principal Amount</option>
                            <option <?php if(isset($storedPenalty['penaltyCalculateOn'])&&$storedPenalty['penaltyCalculateOn']=="Overdue (Principal + Interest) Amount"){ echo "Selected";} ?> value="Overdue (Principal + Interest) Amount">Overdue (Principal + Interest) Amount</option>
                            <option <?php if(isset($storedPenalty['penaltyCalculateOn'])&&$storedPenalty['penaltyCalculateOn']=="Overdue (Principal + Interest + Fees) Amount"){ echo "Selected";} ?> value="Overdue (Principal + Interest + Fees) Amount">Overdue (Principal + Interest + Fees) Amount</option>
                            <option <?php if(isset($storedPenalty['penaltyCalculateOn'])&&$storedPenalty['penaltyCalculateOn']=="Overdue (Principal Interest + Fees + Penalty) Amount"){ echo "Selected";} ?> value="Overdue (Principal Interest + Fees + Penalty) Amount">Overdue (Principal Interest + Fees + Penalty) Amount</option>
                        </select>
                    </div>
                </div>

                <hr>
                <h5 class="text-blue text-bold">Add Penalty after each overdue collection date:</h5>
                <div class="form-group">
                    <label for="inputLRPenaltyInterestOrFixed" id="inputLRPenaltyInterestOrFixedLabel"
                           class="col-sm-5 control-label">Penalty Interest Rate %</label>
                    <div class="col-sm-5">
                        <input type="number" step="0.00001" name="penalty[penaltyRate]"
                               class="form-control decimal-4-places" id="inputLRPenaltyInterestOrFixed" placeholder=""
                               value="<?php if(isset($storedPenalty['penaltyRate'])){ echo $storedPenalty['penaltyRate'];} ?>" required="">
                    </div>

                </div>
                <div class="form-group">
                    <label for="inputLRGracePeriod" class="col-sm-5 control-label">(optional) Grace Period
                        (days)</label>
                    <div class="col-sm-5">
                        <input type="text" name="penalty[gracePeriod]" class="form-control positive-integer"
                               id="inputLRGracePeriod" placeholder="Enter number of days" value="<?php if($storedPenalty['gracePeriod']){ echo $storedPenalty['gracePeriod'];} ?>">
                        For example, if you put 1, then a grace period of 1 day will be given and penalty will be added
                        on 2nd day.
                    </div>
                </div>

                <div class="box-footer" align="center">
                    <button type="submit" class="btn btn-info pull-md-none submit-button">Submit</button>
                </div><!-- /.box-footer -->
            </form>
        </div>
    </div>


    <script>
        $(document).ready(function () {
            enableDisableSettings();
            checkLRPRadio();
            checkAMRadio();
        });

        $('#inputLRPenaltyEnable, #inputAMPenaltyEnable').on('change', function () {
            enableDisableSettings();
        });

        $('input[type=radio][name=penalty_late_repayment_percentage_or_fixed]').on('change', function () {
            checkLRPRadio();
        });
        $('input[type=radio][name=penalty_after_maturity_percentage_or_fixed]').on('change', function () {
            checkAMRadio();
        });

        function enableDisableSettings() {
            var change_loan_schedule_radio = $('input[name="change_loan_schedule_radio"]:checked').val();


            if ($('#inputAMPenaltyEnable').is(":checked")) {
                $('#inputAMPenaltyPercentage').prop('disabled', false);
                $('#inputAMPenaltyFixed').prop('disabled', false);
                $('#inputAMCalculatePenaltyOn').prop('disabled', false);
                $('#inputAMHolidaysNo').prop('disabled', false);
                $('#inputAMHolidaysYes').prop('disabled', false);
                $('#inputAMPenaltyInterestOrFixed').prop('disabled', false);
                $('#inputAMGracePeriod').prop('disabled', false);
                $('#inputAMRecurringPenaltyInterestOrFixed').prop('disabled', false);
                $('#inputAMRecurringPeriod').prop('disabled', false);
                $('#inputAMRecurringSchemeId').prop('disabled', false);
            } else {
                $('#inputAMPenaltyPercentage').prop('disabled', true);
                $('#inputAMPenaltyFixed').prop('disabled', true);
                $('#inputAMCalculatePenaltyOn').prop('disabled', true);
                $('#inputAMHolidaysNo').prop('disabled', true);
                $('#inputAMHolidaysYes').prop('disabled', true);
                $('#inputAMPenaltyInterestOrFixed').prop('disabled', true);
                $('#inputAMGracePeriod').prop('disabled', true);
                $('#inputAMRecurringPenaltyInterestOrFixed').prop('disabled', true);
                $('#inputAMRecurringPeriod').prop('disabled', true);
                $('#inputAMRecurringSchemeId').prop('disabled', true);
            }

            if ($('#inputLRPenaltyEnable').is(":not(:checked)") && $('#inputAMPenaltyEnable').is(":not(:checked)")) {
                $('#inputGracePeriodOnceNo').prop('disabled', true);
                $('#inputGracePeriodOnceYes').prop('disabled', true);
                $('#inputPenaltyApplyDate').prop('disabled', true);
            } else {
                $('#inputGracePeriodOnceNo').prop('disabled', false);
                $('#inputGracePeriodOnceYes').prop('disabled', false);
                $('#inputPenaltyApplyDate').prop('disabled', false);
            }
        }

        function checkLRPRadio() {
            if ($('#inputLRPenaltyPercentage').is(":checked")) {
                $("#inputLRCalculatePenaltyOnLabel").text("Calculate Penalty on");

                $("#inputLRPenaltyInterestOrFixedLabel").text("Penalty Interest Rate %");
                $("#inputLRPenaltyInterestOrFixed").removeClass('decimal-2-places');
                $("#inputLRPenaltyInterestOrFixed").addClass('decimal-4-places');

                $("#inputLRRecurringPenaltyInterestOrFixedLabel").text("Recurring Penalty Interest Rate %");
                $("#inputLRRecurringPenaltyInterestOrFixed").removeClass('decimal-2-places');
                $("#inputLRRecurringPenaltyInterestOrFixed").addClass('decimal-4-places');

                $(".decimal-4-places").numeric({decimalPlaces: 4});
            } else if ($('#inputLRPenaltyFixed').is(":checked")) {
                $("#inputLRCalculatePenaltyOnLabel").text("Calculate Penalty if there is");

                $('#inputLRCalculatePenalty').prop('disabled', true);

                $("#inputLRPenaltyInterestOrFixedLabel").text("Penalty Amount");
                $("#inputLRPenaltyInterestOrFixed").removeClass('decimal-4-places');
                $("#inputLRPenaltyInterestOrFixed").addClass('decimal-2-places');

                $("#inputLRRecurringPenaltyInterestOrFixedLabel").text("Recurring Penalty Amount");
                $("#inputLRRecurringPenaltyInterestOrFixed").removeClass('decimal-4-places');
                $("#inputLRRecurringPenaltyInterestOrFixed").addClass('decimal-2-places');

                $(".decimal-2-places").numeric({decimalPlaces: 2});
            }
        }

        function checkAMRadio() {
            if ($('#inputAMPenaltyPercentage').is(":checked")) {
                $('#inputAMCalculatePenaltyOnLabel').text("Calculate Penalty on");

                $("#inputAMPenaltyInterestOrFixedLabel").text("Penalty Interest Rate %");
                $("#inputAMPenaltyInterestOrFixed").removeClass('decimal-2-places');
                $("#inputAMPenaltyInterestOrFixed").addClass('decimal-4-places');

                $("#inputAMRecurringPenaltyInterestOrFixedLabel").text("Recurring Penalty Interest Rate %");
                $("#inputAMRecurringPenaltyInterestOrFixed").removeClass('decimal-2-places');
                $("#inputAMRecurringPenaltyInterestOrFixed").addClass('decimal-4-places');

                $(".decimal-4-places").numeric({decimalPlaces: 4});
            } else if ($('#inputAMPenaltyFixed').is(":checked")) {
                $("#inputAMCalculatePenaltyOnLabel").text("Calculate Penalty if there is");

                $("#inputAMPenaltyInterestOrFixedLabel").text("Penalty Amount");
                $("#inputAMPenaltyInterestOrFixed").removeClass('decimal-4-places');
                $("#inputAMPenaltyInterestOrFixed").addClass('decimal-2-places');

                $("#inputAMRecurringPenaltyInterestOrFixedLabel").text("Recurring Penalty Amount");
                $("#inputAMRecurringPenaltyInterestOrFixed").removeClass('decimal-4-places');
                $("#inputAMRecurringPenaltyInterestOrFixed").addClass('decimal-2-places');

                $(".decimal-2-places").numeric({decimalPlaces: 2});
            }
        }
    </script>
    <script>
        $("#pre_loader").hide();

    </script>


</div>
<script type="text/javascript">
    $(".numeric").numeric();
    $(".positive").numeric({negative: false});
    $(".positive-integer").numeric({decimal: false, negative: false});
    $(".negative-integer").numeric({decimal: false, negative: true});
    $(".decimal-2-places").numeric({decimalPlaces: 2});
    $(".decimal-4-places").numeric({decimalPlaces: 4});
    $("#remove").click(
        function (e) {
            e.preventDefault();
            $(".numeric,.positive,.positive-integer,.decimal-2-places,.decimal-4-places").removeNumeric();
        }
    );
</script>
<script type="text/javascript">
    $('#form').on('submit', function (e) {

        $('.submit-button').prop('disabled', true);
        $('.submit-button').html('<i class="fa fa-spinner fa-spin"></i> Please wait..');
        return true;
    });
</script>
<script>
    $(function () {
        $('.date_select').datepick({

            defaultDate: '09/09/2020', showTrigger: '#calImg',
            yearRange: 'c-20:c+20', showTrigger: '#calImg',

            dateFormat: 'dd/mm/yyyy',
            minDate: '01/01/1980'
        });
    });

</script>
