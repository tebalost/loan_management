<form class="form-horizontal" method="post" enctype="multipart/form-data" action="process_loan_info.php">
    <div class="box-body">
        <h5 class="text-red text-bold">Borrower Information:</h5>
        <div class="form-group">
            <label for="" class="col-sm-3 control-label">Borrower</label>
            <div class="col-sm-6">
                <select name="borrower" class="customer select2" style="width: 100%;">
                    <option selected="selected">--Select--</option>
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
            <label for="inputLoanType" class="col-sm-3 control-label">Loan Account Type</label>
            <div class="col-sm-6">
                <select class="form-control" name="loanProduct" id="inputLoanType" required>
                    <option value="">--Select--</option>
                    <option value ="T">Student Loan</option>
                    <option value ="P">Personal loan</option>
                    <option value ="M">One Month Personal Loan</option>
                    <!--                                  <option value ="B">	Building Loan                           </option>
                   <option value ="C">	Credit Card                             </option>
                   <option value ="D">	Debt Recovery                           </option>
                   <option value ="E">	Single Credit Facility Open - Services  </option>
                   <option value ="G">	Garage                                  </option>
                   <option value ="H">	Home loan                               </option>
                   <option value ="I">	Instalment                              </option>
                   <option value ="L">	Life Insurance                          </option>
                   <option value ="N">	Secured Pension / Policy Backed Lending </option>
                   <option value ="O">	Open - Limitless                        </option>
                   <option value ="R">	Revolving Credit Store Cards            </option>
                   <option value ="S">	Short Term Insurance                    </option>
                   <option value ="U">	Utility                                 </option>
                   <option value ="V">	Overdraft                               </option>
                   <option value ="W">	Rentals Asset                           </option>
                   <option value ="X">	Rentals Property                        </option>
                   <option value ="Y">	Vehicle Asset Finance                   </option>
                   <option value ="Z">	Revolving Non Store Card                </option>-->
                </select>
            </div>

        </div>

        <div class="form-group">
            <label for="" class="col-sm-3 control-label">Account</label>
            <div class="col-sm-6">
                <?php
                $account = '013' . rand(1000000, 10000000);
                ?>
                <input name="account" type="text" class="form-control" value="<?php echo $account; ?>"
                       placeholder="Account Number" readonly>

            </div>
        </div>


        <div class="form-group">
            <label for="" class="col-sm-3 control-label">Reason for Loan</label>
            <div class="col-sm-6">
                <select name="loanReason" class="form-control"  required>
                    <option value="">--Select--</option>
                    <option value="D">Crisis Loan: Death / Funeral</option>
                    <option value="E">Crisis Loan: Medical</option>
                    <option value="G">Crisis Loan: Income Loss</option>
                    <option value="I">Crisis Loan: Theft or Fire</option>
                    <option value="S">Study Loan: Loan to fund formal studies at a recognised institution</option>
                    <option value="R">Consolidation Loan: A loan resulting from the Debt Consolidation</option>
                    <option value="O">Other: A loan other than the ones stipulated above</option>
                    <option value="J">Small Business: A loan to a sole proprietor</option>
                    <!--                        <option value="C">Crisis Loan: Other Emergency</option>
                    <option value="F">Other Asset acquisition financing</option>
                    <option value="H">Home Loans: New property acquisition or upgrades to existing property</option>-->
                </select>
            </div>

        </div>

        <div class="form-group">
            <label for="" class="col-sm-3 control-label">Application Date</label>
            <div class="col-sm-6">
                <input name="date_application" type="text" value="<?php echo date('Y-m-d') ?>"
                       class="form-control pull-right" readonly>
            </div>
        </div>

        <div class="form-group">
            <label for="" class="col-sm-3 control-label">Agent</label>
            <div class="col-sm-6">
                <?php
                $tid = $_SESSION['tid'];
                $sele = mysqli_query($link, "SELECT * from user WHERE id = '$tid'") or die (mysqli_error($link));
                while ($row = mysqli_fetch_array($sele)) {
                    ?>
                    <input name="agent" type="text" class="form-control"
                           value="<?php echo $row['name']; ?>" readonly>
                <?php } ?>
            </div>
        </div>

        <?php
        $get = mysqli_query($link, "SELECT * FROM loan_settings order by id") or die (mysqli_error($link));
        $settings = mysqli_fetch_assoc($get);
        $defaultInterestRate = $settings['interest_rate'];
        $minimumLoan = $settings['minimum_loan'];
        $maximumLoan = $settings['maximum_loan'];
        $interstMethod = $settings['interest_method'];
        $defaultDuration = $settings['default_duration'];

        ?>

        <div class="panel panel-default">
            <div class="panel-body bg-gray-light text-bold"><i class="fa fa-money"></i> Loan Terms (required
                fields): <a href="#" class="show_hide_advance_settings">Show</a></div>
        </div>
        <div class="slidingDivAdvanceSettings" style="display: none;">
            <h5 class="text-red text-bold">Principal:</h5>
            <div class="form-group">
                <label for="inputDisbursedById" class="col-sm-3 control-label">Disbursed By</label>
                <div class="col-sm-6">
                    <select class="form-control" name="loan_disbursed_by_id" id="inputDisbursedById"
                            required>
                        <option value="Cash">Cash</option>
                        <option value="Cheque">Cheque</option>
                        <option value="Wire Transfer">Mobile Money</option>
                        <option value="Online Transfer">Online Transfer</option>
                    </select>
                </div>
            </div>
            <div class="form-group">

                <label for="inputLoanPrincipalAmount" class="col-sm-3 control-label">Principal
                    Amount</label>
                <div class="col-sm-6">
                    <input type="number" min="<?php echo $minimumLoan; ?>"
                           max="<?php echo $maximumLoan; ?>" name="principalAmount"
                           class="form-control decimal-2-places"
                           id="principalAmount" placeholder="Principal Amount" required
                           value="">

                </div>
            </div>
            <div class="form-group">
                <label for="inputLoanReleasedDate" class="col-sm-3 control-label">Loan Release
                    Date</label>
                <div class="col-sm-6">
                    <input type="date" name="loan_released_date" id="releaseDate"
                           onchange="updateLoan()" min="<?php echo date('Y-m-d'); ?>"
                           class="form-control date_select"
                           placeholder="dd/mm/yyyy" required>
                </div>
            </div>
            <hr>
            <h5 class="text-red text-bold">Interest:</h5>
            <div class="form-group">
                <label for="inputLoanInterestMethod" class="col-sm-3 control-label">Interest
                    Method</label>
                <div class="col-sm-6">
                    <select class="form-control" name="loan_interest_method"
                            id="inputLoanInterestMethod" required onChange="enableDisableMethod();">
                        <option value="">--Select Method--</option>
                        <option <?php if ($interstMethod == "Flat Rate") {
                            echo "selected";
                        } ?> value="Flat Rate"> Flat Rate
                        </option>
                        <option <?php if ($interstMethod == "Interest-Only") {
                            echo "selected";
                        } ?> value="Interest-Only">Interest-Only
                        </option>
                        <option <?php if ($interstMethod == "Compound Interest") {
                            echo "selected";
                        } ?> value="Compound Interest">Compound Interest
                        </option>

                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="inputLoanInterestType" class="col-sm-3 control-label">Interest Type</label>
                <div class="col-sm-6">
                    <div class="radio">
                        <label>
                            <input type="radio" name="loan_interest_type"
                                   id="inputInterestTypePercentage" value="percentage"
                                   onclick="checkITPRRadio()" checked> I want Interest to be percentage
                            % based

                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="inputLoanInterest" id="inputLoanInterestLabel"
                       class="col-sm-3 control-label">Loan Interest %</label>
                <div class="col-sm-3">
                    <input type="text" name="loan_interest" class="form-control decimal-4-places"
                           id="inputLoanInterest" value="<?php echo $defaultInterestRate; ?>"
                           placeholder=" %" required>

                </div>
                <div class="col-sm-3">
                    <select class="form-control" name="loan_interest_period" id="inputInterestPeriod"
                            onChange="check();">
                        <option value="Day" <?php if ($settings['payment_cycle'] == "Daily") {
                            echo "selected";
                        } ?>>Per Day
                        </option>
                        <option value="Week" <?php if ($settings['payment_cycle'] == "Weekly") {
                            echo "selected";
                        } ?>>Per Week
                        </option>
                        <option value="Month" <?php if ($settings['payment_cycle'] == "Monthly") {
                            echo "selected";
                        } ?>>Per Month
                        </option>
                        <option value="Lump-Sum" <?php if ($settings['payment_cycle'] == "Lump-Sum") {
                            echo "selected";
                        } ?>>Lump-Sum
                        </option>
                    </select>
                </div>
            </div>
            <hr>
            <h5 class="text-red text-bold">Duration:</h5>
            <div class="form-group">

                <label for="inputLoanDuration" class="col-sm-3 control-label">Loan Duration</label>
                <div class="col-sm-3">
                    <input class="form-control positive-integer" name="loan_duration"
                           id="inputLoanDuration" value="<?php echo $defaultDuration; ?>" type="number"
                           min="1" max="730" required
                           onChange="setNumofRep();" oninput="setNumofRep();">


                </div>
                <div class="col-sm-3">
                    <select class="form-control" name="loan_duration_period"
                            id="inputLoanDurationPeriod" required onChange="setNumofRep();">
                        <option value=""></option>
                        <option value="Days" <?php if ($settings['payment_cycle'] == "Daily") {
                            echo "selected";
                        } ?>>Days
                        </option>
                        <option value="Weeks" <?php if ($settings['payment_cycle'] == "Weekly") {
                            echo "selected";
                        } ?>>Weeks
                        </option>
                        <option value="Months" <?php if ($settings['payment_cycle'] == "Monthly") {
                            echo "selected";
                        } ?>>Months
                        </option>
                        <option value="Lump-Sum" <?php if ($settings['payment_cycle'] == "Lump-Sum") {
                            echo "selected";
                        } ?>>Lump-Sum
                        </option>
                    </select>
                </div>
            </div>
            <hr>
            <h5 class="text-red text-bold">Repayments:</h5>
            <div class="form-group">
                <label for="inputLoanPaymentSchemeId" class="col-sm-3 control-label">Repayment
                    Cycle</label>
                <div class="col-sm-6">
                    <select class="form-control" name="loan_payment_scheme"
                            id="inputLoanPaymentSchemeId" required
                            onChange=" disableNumRepayments(); setNumofRep();">
                        <option selected="selected">--Select Method--</option>
                        <option <?php if ($settings['payment_cycle'] == "Daily") {
                            echo "selected";
                        } ?>>Daily
                        </option>
                        <option <?php if ($settings['payment_cycle'] == "Weekly") {
                            echo "selected";
                        } ?>>Weekly
                        </option>
                        <option <?php if ($settings['payment_cycle'] == "Monthly") {
                            echo "selected";
                        } ?>>Monthly
                        </option>
                        <option <?php if ($settings['payment_cycle'] == "Lump-Sum") {
                            echo "selected";
                        } ?>>Lump-Sum
                        </option>
                    </select>
                </div>

            </div>


            <div class="form-group">
                <label for="inputLoanNumOfRepayments" class="col-sm-3 control-label">Number of
                    Repayments</label>
                <div class="col-sm-3">
                    <input class="form-control positive-integer" name="loan_num_of_repayments"
                           id="inputLoanNumOfRepayments" value="<?php echo $defaultDuration ?>"
                           type="text" min="1" max="2000"
                           required onChange="removeNumRepaymentsMessage()"
                           oninput="removeNumRepaymentsMessage();" readonly>

                </div>

                <div class="col-sm-6" id="inputLoanNumOfRepaymentsChanged">
                </div>
            </div>

        </div>

        <div class="panel panel-default">
            <div class="panel-body bg-gray-light text-bold"><i class="fa fa-user"></i> Guarantor Information:
                <a href="#" class="show_hide_automated_payments">Show</a></div>
        </div>

        <div class="slidingDivAutomatedPayments" style="display: none;">
            <div class="form-group">
                <label for="" class="col-sm-3 control-label">Gurantor's
                    Passport / ID</label>
                <div class="col-sm-6">
                    <input type='file' name="image"  class="btn btn-info" onChange="readURL(this);">
                </div>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-3 control-label">Relationship</label>
                <div class="col-sm-6">
                    <input name="grela" type="text" class="form-control" placeholder="Relationship"
                           required>
                </div>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-3 control-label">Guarantor's Name</label>
                <div class="col-sm-6">
                    <input name="g_name" type="text" class="form-control" required
                           placeholder="Guarantor's Name">
                </div>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-3 control-label">Guarantor's Phone
                    Number</label>
                <div class="col-sm-6">
                    <input name="g_phone" type="number" class="form-control" required
                           placeholder="Guarantor's Phone Number">
                </div>
            </div>


            <div class="form-group">
                <label for="" class="col-sm-3 control-label">Guarantor's
                    Address</label>
                <div class="col-sm-6">
                    <textarea name="gaddress" class="form-control" rows="2" cols="80"></textarea>
                </div>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-3 control-label">Status</label>
                <div class="col-sm-6">
                    <input name="guaratorStatus" type="text" class="form-control" value="Pending"
                           readonly="readonly">
                </div>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-3 control-label">Remarks</label>
                <div class="col-sm-6">
                                    <textarea name="guarantorRemarks" class="form-control" rows="2"
                                              cols="80"></textarea>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-body bg-gray-light text-bold">Loan Status and Fees: <a
                    class="btn btn-primary btn-xs pull-right collapsed" data-toggle="collapse"
                    data-target="#loan_fee_schedule" aria-expanded="false">Help</a></div>
        </div>
        <div class="form-group">
            <label for="inputStatusId" class="col-sm-3 control-label">Loan Status</label>
            <div class="col-sm-6">
                <select class="form-control" name="loanStatus" id="inputStatusId">
                    <option value="Pending" selected>Pending</option>
                </select>
            </div>
        </div>
        <!-- Get the Insurance Fee if Available -->
        <?php
        $insurance = $settings['loan_insurance'];
        $count=1;
        if ($insurance >= 0) {
            ?>
            <div class="form-group">
                <label for="" class="col-sm-3 control-label">Loan Insurance (<?php echo $insurance."%"; ?>)</label>
                <div class="col-sm-6">
                    <input type="hidden" name="insuranceRate" id="insuranceRate" value="<?php echo $insurance; ?>">
                    <input type="hidden" name="loanFees[<?php echo $count; ?> ][category]" value="Insurance Fee">
                    <input type="number" name="loanFees[<?php echo $count; ?> ][fee]" step="0.01" class="form-control" id="insuranceFee" readonly>
                </div>
            </div>

        <?php } ?>
        <div class="panel panel-default">
            <div class="panel-body bg-gray-light text-bold"><i class="fa fa-money"></i> Payment Information:
                <a href="#" class="show_hide_extended_loan">Show</a></div>
        </div>

        <div class="slidingDivExtendedLoan" style="display: none;">

            <div class="form-group">
                <label for="" class="col-sm-3 control-label">Starting Balance</label>
                <div class="col-sm-6">
                    <input name="currentBalance" type="text" class="form-control" id="currentBalance"
                           readonly>
                </div>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-3 control-label">Initial Payment Date</label>
                <!--Auto Get the initial Dat -->
                <div class="col-sm-6">
                    <div class="input-group date">
                        <input name="pay_date"
                               type="date"
                               id="repaymentDate"
                               value="<?php
                               $today = date("Y-m-d");
                               $date = strtotime("$today");

                               if ($settings['payment_cycle'] == "Monthly") {
                                   $firstDate = "1 Month";
                               } else if ($settings['payment_cycle'] == "Weekly") {
                                   $firstDate = "1 Week";
                               } else if ($settings['payment_cycle'] == "Daily") {
                                   $firstDate = "1 Day";
                               }

                               $loanFirstRepayment = date("Y-m-d", strtotime("+$firstDate", $date));
                               echo $loanFirstRepayment;
                               ?>"
                               class="form-control pull-right">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="" class="col-sm-3 control-label">Initial Amount to Pay</label>
                <div class="col-sm-6">
                    <input name="amount_topay" id="initialAmount" type="number" step="0.01"
                           class="form-control"
                           placeholder="Amount to Pay">
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
                                    <textarea name="repayment_remark" class="form-control" rows="2"
                                              cols="80"></textarea>
                </div>
            </div>
        </div>

    </div>
    <div align="center">
        <div class="box-footer">
            <button type="reset" class="btn btn-primary btn-flat"><i class="fa fa-times">&nbsp;Reset</i>
            </button>
            <button name="save_loan" type="submit" class="btn btn-success btn-flat"><i
                    class="fa fa-save">&nbsp;Save</i></button>

        </div>
    </div>
</form>