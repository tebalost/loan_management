<?php
error_reporting(0);

//FIXME, On product change, get the charges from AJAX using accountType ....

$get = mysqli_query($link, "SELECT * FROM loan_settings order by id") or die (mysqli_error($link));
$loan_fees = mysqli_fetch_assoc(mysqli_query($link, "select * from loanfees"));
$fees = json_decode($loan_fees['loan_fees'], true);
$isInsurance = $loan_fees['insurance_on_total_loan'];

$query = mysqli_query($link, "SELECT (scoring) FROM systemset");
$scoring = mysqli_fetch_array($query, MYSQLI_ASSOC);

$settings = mysqli_fetch_assoc($get);
$minimumLoan = $settings['minimum_loan'];
$maximumLoan = $settings['maximum_loan'];
$defaultDuration = $settings['default_duration'];
$defaultInterestRate = $settings['interest_rate'];
//$insurance = $settings['loan_insurance'];
$interestMethod = $settings['interest_method'];

$getFees = mysqli_query($link, "select * from loan_additional_settings where percentage >0 order by id");
$feesTotal = 0;
$insuranceFee = 0;

$totalFixed = 0;


////To Control Minimum Input on the Form////
$totalLoan = $minLoanAllowed + ($minLoanAllowed * $defaultInterestRate / 100) + ($minLoanAllowed * $feesTotal / 100) + $totalFixed;
$insuranceAmount = round($totalLoan * $insuranceFee / 100, 2);
$totalLoanAmount = $totalLoan + $insuranceAmount;
$disposableIncome = round($totalLoanAmount / $defaultDuration, 2);


$amountToPay = $disposableIncome;
?>
<div class="box">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/css/ion.rangeSlider.min.css"/>
    <style>
        #panel {
            display: none;
        }
    </style>
    <style rel="stylesheet" href="style.scss"></style>
    <style>
        .slidecontainer {
            width: 100%;
        }

        .slider {
            -webkit-appearance: none;
            width: 100%;
            height: 25px;
            background: #d3d3d3;
            outline: none;
            opacity: 0.7;
            -webkit-transition: .2s;
            transition: opacity .2s;
        }

        .slider:hover {
            opacity: 1;
        }

        .slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 25px;
            height: 25px;
            background: #4CAF50;
            cursor: pointer;
        }

        .slider::-moz-range-thumb {
            width: 25px;
            height: 25px;
            background: #4CAF50;
            cursor: pointer;
        }

    </style>
    <div class="box-body">
        <div class="panel panel-success">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-money"></i>&nbsp;New Loan Calculator</h3>
            </div>
            <div class="box-body">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">

                        <li class="<?php if (!isset($_POST['continue'])) {
                            echo "active";
                        } ?>"><a href="#tab_1" data-toggle="tab">Loan Calculator</a></li>
                        <?php if (isset($_POST['continue'])) {
                            $accountType = $_POST['loanProduct'];
                            $loanReason = $_POST['loanReason'];

                            $getProduct = mysqli_query($link, "SELECT * FROM products where product_name='$accountType'") or die (mysqli_error($link));
                            $config = mysqli_fetch_assoc($getProduct);
                            $productConfig = json_decode($config['product_configuration'], true);


                            $product = $productConfig['productName'];


                            ?>
                            <li class="active"><a href="#tab_2" data-toggle="tab"><strong><?php echo $product; ?> - Calculator</strong></a></li>
                        <?php } ?>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane <?php if (!isset($_POST['continue'])) {
                            echo "active";
                        } ?>" id="tab_1">

                <script src="https://code.jquery.com/jquery-1.11.0.min.js"
                        integrity="sha256-spTpc4lvj4dOkKjrGokIrHkJgNA0xMS98Pw9N7ir9oI="
                        crossorigin="anonymous"></script>
                <script>
                    $(document).ready(function () {

                        $("a.submit[form='myForm']").click(function () {

                            document.getElementById("myForm").submit();

                        });

                    });
                </script>
                <?php if (!isset($_GET['newSearch'])) {
                    $newSearch = mysqli_real_escape_string($link, $_GET['newSearch']);

                    ?>

                    <hr>
                    <h5 class="text-red text-bold">Product Information:</h5>
                    <form class="form-horizontal" method="post" enctype="multipart/form-data">





                        <div class="form-group">
                            <label for="inputLoanType" class="col-sm-3 control-label">Account Type *</label>
                            <div class="col-sm-6">
                                <select class="form-control" name="loanProduct" id="accountType">
                                    <option value="" disabled selected>--Select--</option>
                                    <?php
                                    $select = mysqli_query($link, "SELECT * FROM products") or die (mysqli_error($link));

                                    while ($row = mysqli_fetch_array($select)) {
                                        $productConfig = json_decode($row['product_configuration'], true);
                                        ?>
                                        <option value="<?php echo $productConfig['productName']; ?>"><?php echo $row['product_name']; ?></option>
                                        <?php
                                    }

                                    ?>

                                </select>

                            </div>

                        </div>


                        <div align="center">
                            <div class="box-footer">
                                <button name="continue" type="submit" class="btn btn-success btn-flat"><i
                                            class="fa fa-save">&nbsp;Continue</i></button>
                                <button type="reset" class="btn btn-primary btn-flat"><i
                                            class="fa fa-times">&nbsp;Reset</i>
                                </button>

                            </div>
                        </div>

                    </form>
                <?php } ?>
            </div>

            <div class="tab-pane <?php if (isset($_POST['continue'])) {
                echo "active";
            } ?>" id="tab_2">
                <?php
                $accountType = $_POST['loanProduct'];
                $loanReason = $_POST['loanReason'];

                $getProduct = mysqli_query($link, "SELECT * FROM products where product_name='$accountType'") or die (mysqli_error($link));
                $config = mysqli_fetch_assoc($getProduct);
                $productConfig = json_decode($config['product_configuration'], true);


                $maxLoanAllowed = $productConfig['maxLoanPrincipalAmount'];
                $minLoanAllowed = $productConfig['minLoanPrincipalAmount'];
                $defaultDuration = $productConfig['defaultLoanDuration'];
                $defaultInterestRate = $productConfig['defaultLoanInterest'];
                $maxLoanDuration = $productConfig['maxLoanDuration'];
                $minLoanDuration = $productConfig['minLoanDuration'];
                $productCode = $productConfig['productCode'];
                $compuscanAccountType = $productConfig['accountType'];


                //Loan Terms
                $interestPeriod = $productConfig['interestPeriod'];
                $loanDurationPeriod = $productConfig['loanDurationPeriod'];

                //Get Fees Total///
                foreach ($productConfig['productPercentageFees'] as $key => $value) {
                    $feesTotal += $value['percentage'];
                }

                $interestMethod = $productConfig['loanInterestMethod'];

                if (isset($_POST['basicInfo']['employer'])) {
                    $employer = $_POST['basicInfo']['employer'];
                } else {
                    $employer = "";
                }
                if (isset($_POST['basicInfo']['frequency'])) {
                    $frequency = $_POST['basicInfo']['frequency'];
                } else {
                    $frequency = "";
                }
                if (isset($_POST['basicInfo']['occupation'])) {
                    $occupation = $_POST['basicInfo']['occupation'];
                } else {
                    $occupation = "";
                }
                if (isset($_POST['basicInfo']['salary'])) {
                    $salary = $_POST['basicInfo']['salary'];
                } else {
                    $salary = "0";
                }
                if (isset($_POST['basicInfo']['principalAmount'])) {
                    $loan = $_POST['basicInfo']['principalAmount'];
                } else {
                    $loan = "0";
                }
                if (isset($_POST['basicInfo']['membership'])) {
                    $member = $_POST['basicInfo']['membership'];
                } else {
                    $member = "0";
                }

                if (isset($_POST['id'])) {
                    $id = $_POST['id'];
                    $disposableIncome = $_POST['basicInfo']['disposableIncome']; //Minimum Balance to pay

                    $employeeCode = $_POST['basicInfo']['employeeCode'];


                    mysqli_query($link, "update borrowers set salary='$salary',disposable_income='$disposableIncome', income_frequency='$frequency', occupation='$occupation', employer='$employer' where id='$id'");
                    //mysqli_query($link,"update fin_info set mincome='$salary', frequency='$frequency', occupation='$occupation' where get_id='$id'");
                } else {
                    $id = "";
                }
                if ($id == "") {
                    $_SESSION['basicInfo'] = $_POST['basicInfo'];
                    $firstName = $_POST['basicInfo']['firstname'];
                    $lastName = $_POST['basicInfo']['lastname'];
                    $disposableIncome = 500000;
                    $employeeCode = $_POST['basicInfo']['employeeCode'];
                    $membership=$_POST['basicInfo']['membership'];

                    $sessionId = session_id();

                    //Check if employee Code Exists
                    $get = mysqli_query($link, "select * from temp_borrowers where emp_code = '$employeeCode'");

                    $session = session_id();
                    if (mysqli_num_rows($get) == 0) {
                        //echo "insert into temp_borrowers values (0,'$firstName','$lastName','$employer','$salary','$disposableIncome','$session','$tid','$employeeCode')";
                        $insert = mysqli_query($link, "insert into temp_borrowers values (0,'$firstName','$lastName','$employer','$salary','$disposableIncome','$session','$tid','$employeeCode')") or die (mysqli_error($link));
                    }
                    //$insert = mysqli_query($link, "insert into borrowers (id, fname, lname, employer, salary, disposable_income,created_by, emp_code, status, occupation) values (0,'$firstName','$lastName','$employer','$salary','$disposableIncome','$tid','$employeeCode','Partial','$occupation')") or die (mysqli_error($link));
                    $newId = mysqli_fetch_assoc(mysqli_query($link, "select * from temp_borrowers where emp_code='$employeeCode' and fname='$firstName'  and lname='$lastName'"));

                }
                ?>

                <form class="form-horizontal" method="post" enctype="multipart/form-data"
                      action="process_loan_info.php">
                    <!--//Information of the New Borrower -->
                    <input type="hidden" name="newName" value="<?php echo $_POST['basicInfo']['firstname']; ?>">
                    <input type="hidden" name="newSurname" value="<?php echo $_POST['basicInfo']['lastname']; ?>">
                    <input type="hidden" name="salary" value="<?php echo $_POST['basicInfo']['salary']; ?>">
                    <input type="hidden" name="disposableIncome"
                           value="<?php echo $_POST['basicInfo']['disposableIncome']; ?>">
                    <input type="hidden" name="newCode" value="<?php echo $_POST['basicInfo']['employeeCode']; ?>">
                    <input type="hidden" name="employer" value="<?php echo $_POST['basicInfo']['employer']; ?>">
                    <input type="hidden" name="dateOfBirth" value="<?php echo $_POST['basicInfo']['dateOfBirth']; ?>">
                    <input type="hidden" name="membership" value="<?php echo $_POST['basicInfo']['membership']; ?>">
                    <input type="hidden" name="productCode" value="<?php echo $_POST['basicInfo']['productCode']; ?>">
                    <input type="hidden" name="incomeFrequency" value="<?php echo $_POST['basicInfo']['frequency']; ?>">
                    <input type="hidden" name="compuscanAccountType" value="<?php echo $compuscanAccountType; ?>">
                    <div class="box-body">

                        <div class="panel panel-default">
                            <div class="panel-body bg-gray-light text-bold"><i class="fa fa-money"></i>
                                Interest and Terms:
                                <a href="#" class="show_hide_loan_terms">Hide</a></div>
                        </div>

                        <div class="slidingDivLoanTerms" style="display: block;">
                            <h5 class="text-red text-bold">Interest:</h5>
                            <div class="form-group">
                                <label for="inputLoanInterestMethod" class="col-sm-3 control-label">Interest
                                    Method</label>
                                <div class="col-sm-6">
                                    <input type="hidden" name="loan_interest_method"
                                           value="<?php echo $interestMethod; ?>">
                                    <select class="form-control" disabled="disabled" name="loan_interest_method"
                                            id="inputLoanInterestMethod" required
                                            onChange="enableDisableMethod();">
                                        <option value="">--Select Method--</option>
                                        <option <?php if ($interestMethod == "FLAT_RATE") {
                                            echo "selected";
                                        } ?> value="FLAT_RATE"> Flat Rate
                                        </option>
                                        <option <?php if ($interestMethod == "INTEREST_ONLY") {
                                            echo "selected";
                                        } ?> value="INTEREST_ONLY">Interest-Only
                                        </option>
                                        <option <?php if ($interestMethod == "COMPOUND_INTEREST") {
                                            echo "selected";
                                        } ?> value="COMPOUND_INTEREST">Compound Interest
                                        </option>
                                        <option <?php if ($interestMethod == "REDUCING_RATE_EQUAL_INSTALLMENTS") {
                                            echo "selected";
                                        } ?> value="REDUCING_RATE_EQUAL_INSTALLMENTS">Reducing Balance - Equal
                                            Installments
                                        </option>
                                        <option <?php if ($interestMethod == "REDUCING_RATE_EQUAL_PRINCIPAL") {
                                            echo "selected";
                                        } ?> value="REDUCING_RATE_EQUAL_PRINCIPAL">Reducing Balance - Equal Principal
                                        </option>

                                    </select>
                                </div>
                            </div>

                            <input type="hidden" name="loan_interest_type" value="percentage">
                            <div class="form-group">
                                <label for="inputLoanInterest" id="inputLoanInterestLabel"
                                       class="col-sm-3 control-label">Loan Interest %</label>
                                <div class="col-sm-3">
                                    <input type="number" step="0.01" name="loan_interest"
                                           class="form-control decimal-4-places"
                                           id="inputLoanInterest"
                                           value="<?php echo $defaultInterestRate; ?>"
                                           placeholder="Loan Interest %" required>

                                </div>
                                <div class="col-sm-3">
                                    <?php
                                    if ($productConfig['interestPeriod'] == "03") {
                                        $loan_interest_period = "Per Month";
                                    }
                                    ?>
                                    <input type="hidden" name="loan_interest_period"
                                           value="<?php echo $loan_interest_period; ?>">
                                    <select class="form-control" name="loan_interest_period" disabled
                                            id="inputInterestPeriod"
                                            onChange="check();">

                                        <option value="Month" <?php if ($settings['loanDurationPeriod'] == "03") {
                                            echo "selected";
                                        } ?>>Per Month
                                        </option>

                                    </select>
                                </div>
                            </div>
                            <hr>
                            <h5 class="text-red text-bold">Duration:</h5>
                            <div class="form-group">

                                <label for="inputLoanDuration" class="col-sm-3 control-label">Loan Duration </label>
                                <div class="col-sm-3">
                                    <input class="form-control positive-integer" name="loan_duration"
                                           id="inputLoanDuration" value="<?php echo $defaultDuration; ?>"
                                           type="number"
                                           min="<?php echo $minLoanDuration; ?>" max="<?php echo $maxLoanDuration; ?>"
                                           required>


                                </div>
                                <div class="col-sm-3">
                                    <?php
                                    if ($productConfig['interestPeriod'] == "03") {
                                        $loan_duration_period = "Months";
                                    }
                                    ?>
                                    <input type="hidden" name="loan_duration_period"
                                           value="<?php echo $loan_duration_period; ?>">
                                    <select class="form-control" name="loan_duration_period" disabled
                                            id="inputLoanDurationPeriod" required onChange="setNumofRep();">
                                        <option value="Months" <?php if ($productConfig['loanDurationPeriod'] == "03") {
                                            echo "selected";
                                        } ?>>Months
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <hr>
                            <h5 class="text-red text-bold">Repayments:</h5>
                            <div class="form-group">
                                <label for="inputLoanPaymentSchemeId" class="col-sm-3 control-label">Repayment
                                    Frequency</label>
                                <div class="col-sm-6">
                                    <?php
                                    if ($productConfig['loanDurationPeriod'] == "03") {
                                        $loan_payment_scheme = "Monthly";
                                    }
                                    ?>
                                    <input type="hidden" name="loan_payment_scheme"
                                           value="<?php echo $loan_payment_scheme; ?>">
                                    <select class="form-control" name="loan_payment_scheme" disabled
                                            id="inputLoanPaymentSchemeId" required
                                            onChange=" disableNumRepayments(); setNumofRep();">

                                        <option value="03" <?php if ($productConfig['loanDurationPeriod'] == "03") {
                                            echo "selected";
                                        } ?>>Monthly
                                        </option>

                                    </select>
                                </div>

                            </div>


                            <div class="form-group">
                                <label for="inputLoanNumOfRepayments" class="col-sm-3 control-label">Number of
                                    Repayments</label>
                                <div class="col-sm-3">
                                    <input class="form-control positive-integer"
                                           name="loan_num_of_repayments"
                                           id="inputLoanNumOfRepayments"
                                           value="<?php echo $defaultDuration ?>"
                                           type="text" min="1" max="2000"
                                           required
                                           readonly>

                                </div>

                                <div class="col-sm-6" id="inputLoanNumOfRepaymentsChanged">
                                </div>
                            </div>
                        </div>
                        <?php
                        if ($userOrigin == "cdas") {
                            if ($affordability == 0.0) {
                                if (isset($_POST['basicInfo']['disposableIncome'])) {
                                    $disposableIncome = $_POST['basicInfo']['disposableIncome'];
                                }
                            } else {
                                $disposableIncome = $affordability;
                            }
                            // echo "<h3 class=\"primary\">running for user in db and cdas</h3>";
                        } else {
                            if (isset($_POST['basicInfo']['disposableIncome'])) {
                                $disposableIncome = $_POST['basicInfo']['disposableIncome'];
                            }

                        }
                        $disposableIncome = $maxLoanAllowed;
                        $loan=$minLoanAllowed;
                        $totalLoanAmount = ($disposableIncome * $defaultDuration) + $totalFixed;        ////Review this formula

                        $principalAmount = roundToPartial($totalLoanAmount / (1 + ($defaultInterestRate / 100) + ($feesTotal / 100)), 10);
                        $principalAmountRecommended = roundToPartial($totalLoanAmount / (1 + ($defaultInterestRate / 100) + ($feesTotal / 100)), 10);
                        $requiredPrincipalAmount = $principalAmount;
                        if ($loan < $totalLoanAmount) {
                            $requiredPrincipalAmount = $loan;
                        }

                        if ($interestMethod === "COMPOUND_INTEREST") {
                            //COMPOUND_INTEREST,
                            $loanAmount = $loan;
                            $monthlyRate = $defaultInterestRate / 100;
                            $months = $defaultDuration;
                            function calculateInterest($loanAmount, $monthlyRate, $months)
                            {
                                $calInterest = $monthlyRate * $loanAmount;
                                if ($months > 0) {
                                    $loanAmount = ($calInterest + $loanAmount) * (1 - (1 / $months));
                                    $months = $months - 1;
                                    $calInterest = $calInterest + calculateInterest($loanAmount, $monthlyRate, $months);
                                }
                                return $calInterest;
                            }

                            $interestValue = calculateInterest($loanAmount, $monthlyRate, $months);
                        } else {
                            $interestValue = $requiredPrincipalAmount * ($defaultInterestRate / 100);
                        }
                        $otherFees = ($requiredPrincipalAmount * ($feesTotal / 100)) + $totalFixed;

                        $insuranceAmount = round(($requiredPrincipalAmount + $interestValue + $otherFees) * $insuranceFee / 100, 2);
                        $totalLoanAmount = $requiredPrincipalAmount + $interestValue + $otherFees + $insuranceAmount;
                        $amountToPay = round($totalLoanAmount / $defaultDuration, 2);

                        $amountToPayPrincipal = round($requiredPrincipalAmount / $defaultDuration, 2);
                        $principal_due = round($requiredPrincipalAmount / $defaultDuration, 2);
                        //Get New Values After Calculations, Possibility of Decimal Places
                        $totalDuePrincipal = $principal_due * $defaultDuration;

                        //Get the Differences
                        $totalDuePrincipalDiff = $principalAmount - $totalDuePrincipal;
                        $dueTotal = $amountToPay * $defaultDuration;
                        $balance = 0;
                        if ($principalAmount >= $maxLoanAllowed) {

                            $principalAmount = $maxLoanAllowed;
                            $requiredPrincipalAmount = $loan;
                            if ($interestMethod === "COMPOUND_INTEREST") {
                                $loanAmount = $loan;
                                $monthlyRate = $defaultInterestRate / 100;
                                $months = $defaultDuration;
                                $interestValue = calculateInterest($loanAmount, $monthlyRate, $months);
                            } else if ($interestMethod === "REDUCING_RATE_EQUAL_INSTALLMENTS") {
                                $totalInterst = 0;
                                function PMT($rate = 0, $nper = 0, $pv = 0, $fv = 0, $type = 0)
                                {
                                    if ($rate > 0) {
                                        return (-$fv - $pv * pow(1 + $rate, $nper)) / (1 + $rate * $type) / ((pow(1 + $rate, $nper) - 1) / $rate);
                                    } else {
                                        return (-$pv - $fv) / $nper;
                                    }
                                }

                                $rate = $defaultInterestRate / 100; // rate = 10%
                                $nper = $defaultDuration; // months
                                $pv = $requiredPrincipalAmount; //Principal Amount
                                $fv = 0; //Expected Balance at the end
                                $payment = round(PMT($rate, $nper, -$pv, $fv), 2);//EMI
                                for ($i = 0; $i < 3; $i++) {
                                    if($i==0) {
                                        $interestValue = ($pv * $rate);
                                        $totalInterst+=$interestValue;
                                        $principal = $payment - $interestValue;
                                    }else{
                                        //Get the New Principal
                                        $pv = $pv-$principal;
                                        $interestValue = ($pv*$rate);
                                        $totalInterst+=$interestValue;
                                        $principal = $payment - $interestValue;
                                    }
                                }
                                $interestValue=$totalInterst;
                            } else {
                                $interestValue = $requiredPrincipalAmount * ($defaultInterestRate / 100);
                            }

                            $otherFees = ($requiredPrincipalAmount * ($feesTotal / 100)) + $totalFixed;;
                            $insuranceAmount = round(($requiredPrincipalAmount + $interestValue + $otherFees) * $insuranceFee / 100, 2);

                            $totalLoanAmount = $requiredPrincipalAmount + $interestValue + $otherFees + $insuranceAmount;
                            $amountToPay = round($totalLoanAmount / $defaultDuration, 2);

                        }

                        function roundToPartial($value, $roundTo)
                        {
                            return round($value / $roundTo) * $roundTo;
                        }

                        ?>

                        <div class="panel panel-default">
                            <div class="panel-body bg-gray-light text-bold"><i class="fa fa-paypal"></i> Loan Offer
                                Details
                                (required fields): <a href="#" class="show_hide_advance_settings">Hide</a></div>
                        </div>

                        <div class="slidingDivAdvanceSettings" style="display: block;">
                            <h5 class="text-red text-bold">Principal:</h5>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <input type="text" class="js-range-slider" name="my_range" id="myRange"
                                           value="<?php echo number_format($requiredPrincipalAmount, 2, ".", ","); ?>"/>


                                    <div class="col-sm-6">
                                        <div class="slidecontainer">
                                            <h4><i class="fa fa-info-circle"></i> New Loan Amount: <b><span
                                                            id="newPrincipal"></span></b></h4>
                                            <hr>
                                        </div>
                                    </div>

                                    <!--jQuery-->
                                    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
                                    <!--Plugin JavaScript file-->
                                    <script src="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/js/ion.rangeSlider.min.js"></script>
                                    <?php if ($principalAmountRecommended > $loan) { ?>
                                        <script>
                                            $(".js-range-slider").ionRangeSlider({
                                                //Double controls with Max Fixed...
                                                type: "double",
                                                min: <?php echo $minLoanAllowed; ?>,
                                                max: <?php echo $maxLoanAllowed; ?>,
                                                from: <?php echo $loan; ?>,
                                                to: <?php echo $principalAmountRecommended; ?>,
                                                grid: true,
                                                from_shadow: true,   // highlight restriction for FROM handle
                                                skin: "round",
                                                step: 10,
                                                grid_num: 10,        // default 4 (set number of grid cells)
                                                grid_snap: false,
                                                to_fixed: true

                                            });
                                        </script>

                                    <?php }else{ ?>
                                        <script>
                                            $(".js-range-slider").ionRangeSlider({

                                                type: "double",
                                                min: <?php echo $minLoanAllowed; ?>,
                                                max: <?php echo $maxLoanAllowed; ?>,
                                                //from: <?php //echo $principalAmount; ?>,
                                                grid: true,
                                                from: <?php echo $minLoanAllowed; ?>,      // set min position for FROM handle (replace FROM to TO to change handle)
                                                to: <?php echo $principalAmount; ?>,      // set max position for FROM handle
                                                to_fixed: true,
                                                step: 10,
                                                skin: "round",
                                                from_shadow: true   // highlight restriction for FROM handle
                                            });
                                        </script>
                                    <?php } ?>
                                    <!-- End Range Slider -->
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">

                                    <label for="inputLoanPrincipalAmount" class="col-sm-3 control-label">Principal
                                        Amount</label>
                                    <div class="col-sm-6">
                                        <script>
                                            $(".readonly").keyup(function (e) {
                                                e.preventDefault();
                                            });
                                        </script>
                                        <input type="number" min="<?php echo $minLoanAllowed; ?>"
                                               max="<?php echo $principalAmount; ?>" name="principalAmount"
                                               class="form-control decimal-2-places"
                                               oninput="setNumofRep()"
                                               value="<?php echo roundToPartial($requiredPrincipalAmount, 10); ?>"
                                               id="principalAmount" placeholder="Principal Amount" readonly required>

                                    </div>
                                </div>

                                <!--   Loan system charge    -->
                                <?php
                                    include_once "include/systemFee.php";
                                    $systemLoanfee = loanCharge($minLoanAllowed,2.8, $minLoanAllowed);
                                ?>
                                <div class="form-group">
                                    <label for="" class="col-sm-3 control-label"> System Charge </label>
                                    <div class="col-sm-6">
                                        <input type="hidden" name="loanFeesGL[]" value="12001">
                                        <input type="number"
                                               name="loanFees[System Charge on loan]"
                                               class="form-control decimal-2-places"
                                               value="<?php echo round($systemLoanfee, 2) ?>"
                                               id="loanCharge"
                                               step="0.01"
                                               readonly required>
                                    </div>
                                </div>

                                <div class="form-group">

                                    <label for="" class="col-sm-3 control-label">Interest:
                                        (<?php echo $defaultInterestRate . "%"; ?>)
                                        <span style="color: red" id="newInterestRate"></span></label>
                                    <div class="col-sm-6">
                                        <input type="hidden" name="interestRate" id="interestRate"
                                               value="<?php echo $defaultInterestRate; ?>">
                                        <input type="number"
                                               name="loanFees[Interest]"
                                               step="0.01"
                                               value="<?php echo round($interestValue, 2); ?>"
                                               class="form-control"
                                               id="interestValue"
                                               readonly>
                                        <!---...Dynamically Create ID's based on saved data... Then Call them here-->
                                    </div>
                                </div>
                                <!--Check if Insurance is calculated the if yes, ignore this part -->
                                <?php if ($isInsurance == 0) { ?>
                                    <input type="hidden" name="InsuranceRate" id="InsuranceRate" value="0">

                                    <?php
                                }
                                $count = 1;
                                $fixedFees = 0;
                                $percentageFees = 0;
                                foreach ($productConfig['fixedFees'] as $key => $value) {

                                    $minLoan = $value['minLoan'];
                                    $maxLoan = $value['maxLoan'];
                                    if ($requiredPrincipalAmount >= $minLoan && $requiredPrincipalAmount <= $maxLoan) {


                                        if ($value['chargeTerm'] === "FREQUENCY") {
                                            $fixedFees += $value['feeAmount'];
                                            $fixedFees = $fixedFees * $defaultDuration;
                                        } else {
                                            $fixedFees += $value['feeAmount'];
                                        }



                                        ?>
                                        <div class="form-group">

                                            <label for=""
                                                   class="col-sm-3 control-label"><?php echo $value['feeName']; ?></label>
                                            <div class="col-sm-6">
                                                <input type="hidden"
                                                       name="<?php str_replace(" ", "_", $value['feeName']); ?>Rate"
                                                       id="<?php echo str_replace(" ", "_", $value['feeName']); ?>Rate"
                                                       value="<?php echo $fixedFees; ?>">
                                                <input type="number"
                                                       name="loanFees[<?php echo $value['feeDescription']; ?>]"
                                                       step="0.01"
                                                       value="<?php echo $fixedFees; ?>"
                                                       class="form-control"
                                                       id="<?php echo str_replace(" ", "_", $value['feeDescription']); ?>Fee"
                                                       readonly>
                                            </div>
                                        </div>
                                    <?php }
                                }
                                $insuranceFee = 0;
                                foreach ($productConfig['productPercentageFees'] as $key => $value) {
                                    ?>
                                    <?php
                                    $totalLoanAmount = $requiredPrincipalAmount + $interestValue + $allOtherFees;
                                    //$amountToPay = round($totalLoanAmount / $defaultDuration, 2);
                                    //echo "$allOtherFees<br>";
                                    ?>
                                    <div class="form-group">
                                        <?php 
                                        if($value['chargeTerm'] !== "PRINCIPAL_DUE_INTEREST_DUE_FEES_DUE"){
                                        ?>
                                        <label for=""
                                               class="col-sm-3 control-label"><?php echo $value['feeDescription']; ?>
                                            (<?php echo round($value['percentage'], 1) . "%"; ?>)</label>
                                        <div class="col-sm-6">
                                            <?php
                                            if ($value['chargeTerm'] === "FREQUENCY") {
                                                $percentage = $value['percentage'] * $defaultDuration;
                                            } else {
                                                $percentage = $value['percentage'];
                                            }
                                            ?>

                                            <input type = "hidden" name="">
                                            <input type="hidden"
                                                   name="<?php str_replace(" ", "_", $value['feeDescription']); ?>Rate"
                                                   id="<?php echo str_replace(" ", "_", $value['feeDescription']); ?>Rate"
                                                   value="<?php echo $percentage; ?>">
                                            <?php
                                            $currentFee = round($requiredPrincipalAmount * ($percentage / 100), 2);

                                            $fixedMin = $value['minFixedAmount'];
                                            $fixedMax = $value['maxFixedAmount'];

                                            ?>
                                            <input type="number"
                                                   name="loanFees[<?php echo $value['feeDescription']; ?>]"
                                                   step="0.01"
                                                <?php
                                                if ($value['minFixedAmount'] !== "" && $value['maxFixedAmount'] !== "") {
                                                    if ($currentFee > $fixedMax) {
                                                        $currentFee = $fixedMax; ?>
                                                        value="<?php echo $fixedMax; ?>"
                                                    <?php } else if ($currentFee < $fixedMin) {
                                                        $currentFee = $fixedMin; ?>
                                                        value="<?php echo $fixedMin; ?>"
                                                    <?php } else { ?>
                                                        value="<?php echo $currentFee; ?>"
                                                    <?php }
                                                } else { ?>
                                                    value="<?php echo $currentFee; ?>"
                                                <?php } ?>
                                                   class="form-control"
                                                   id="<?php echo str_replace(" ", "_", $value['feeDescription']); ?>Fee"
                                                   readonly>
                                            <?php $allOtherFees += $currentFee; ?>
                                        </div>
                                        <?php } ?>
                                    </div>

                                <?php } ?>
                                <?php
                                //For a Charge that includes all other fees
 foreach ($productConfig['productPercentageFees'] as $key => $value) {
    ?>
    <?php
    $totalLoanAmount = $requiredPrincipalAmount + $interestValue + $allOtherFees;
    //$amountToPay = round($totalLoanAmount / $defaultDuration, 2);
    //echo "$allOtherFees<br>";
    ?>
    <div class="form-group">
        <?php 
        if($value['chargeTerm'] == "PRINCIPAL_DUE_INTEREST_DUE_FEES_DUE"){
        ?>
        <label for=""
               class="col-sm-3 control-label"><?php echo $value['feeDescription']; ?>
            (<?php echo round($value['percentage'], 1) . "%"; ?>)</label>
        <div class="col-sm-6">
            <?php
            if ($value['chargeTerm'] === "FREQUENCY") {
                $percentage = $value['percentage'] * $defaultDuration;
            } else {
                $percentage = $value['percentage'];
            }
            ?>

            <input type = "hidden" name="">
            <input type="hidden"
                   name="<?php str_replace(" ", "_", $value['feeDescription']); ?>Rate"
                   id="<?php echo str_replace(" ", "_", $value['feeDescription']); ?>Rate"
                   value="<?php echo $percentage; ?>">
            <?php
            $currentFee = round(($requiredPrincipalAmount + $interestValue + $allOtherFees + $systemLoanfee) * ($percentage / 100), 2);

            $fixedMin = $value['minFixedAmount'];
            $fixedMax = $value['maxFixedAmount'];

            ?>
            <input type="number"
                   name="loanFees[<?php echo $value['feeDescription']; ?>]"
                   step="0.01"
                <?php
                if ($value['minFixedAmount'] !== "" && $value['maxFixedAmount'] !== "") {
                    if ($currentFee > $fixedMax) {
                        $currentFee = $fixedMax; ?>
                        value="<?php echo $fixedMax; ?>"
                    <?php } else if ($currentFee < $fixedMin) {
                        $currentFee = $fixedMin; ?>
                        value="<?php echo $fixedMin; ?>"
                    <?php } else { ?>
                        value="<?php echo $currentFee; ?>"
                    <?php }
                } else { ?>
                    value="<?php echo $currentFee; ?>"
                <?php } ?>
                   class="form-control"
                   id="<?php echo str_replace(" ", "_", $value['feeDescription']); ?>Fee"
                   readonly>
            <?php $allOtherFees += $currentFee; ?>
        </div>
        <?php } ?>
    </div>

<?php }

                                $totalLoanAmount = $requiredPrincipalAmount + $interestValue + $fixedFees + $allOtherFees + $systemLoanfee;
                                //echo "$requiredPrincipalAmount + $interestValue + $fixedFees + $allOtherFees";
                                $amountToPay = round($totalLoanAmount / $defaultDuration, 2);
                                ?>
                            </div>


                            <input class="form-control" name="loanStatus" type="hidden" value="Pending"
                                   id="inputStatusId">
                            <!-- Get the Insurance Fee if Available -->
                            <div class="col-lg-6">

                                <div class="form-group">
                                    <label for="" class="col-sm-3 control-label">Starting Balance</label>
                                    <div class="col-sm-6">
                                        <input name="currentBalance" type="number" class="form-control"
                                               id="currentBalance"
                                               step="0.01"
                                               value="<?php echo round($totalLoanAmount, 2); ?>"
                                               readonly>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="" class="col-sm-3 control-label">Instalment </label>
                                    <div class="col-sm-6">
                                        <input name="amount_topay" id="initialAmount" type="number" step="0.01" readonly
                                               class="form-control"
                                               placeholder="Amount to Pay"
                                               value="<?php echo $amountToPay; ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputLoanReleasedDate" class="col-sm-3 control-label">Release Date
                                        *</label>
                                    <div class="col-sm-6">
                                        <input type="date" name="loan_released_date" id="releaseDate"
                                               onchange="updateLoan()" min="<?php echo date('Y-m-d'); ?>"
                                               class="form-control date_select"
                                               placeholder="dd/mm/yyyy" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="" class="col-sm-3 control-label">Initial Payment Date *</label>
                                    <!--Auto Get the initial Dat -->
                                    <div class="col-sm-6">
                                        <input name="pay_date"
                                               type="date"
                                               id="repaymentDate"
                                               min="<?php echo date('Y-m-d'); ?>"
                                               value="<?php
                                               $today = date("Y-m-d");
                                               $date = strtotime("$today");

                                               if ($productConfig['loanDurationPeriod'] == "03") {
                                                   $firstDate = "1 Month";
                                               } else if ($productConfig['loanDurationPeriod'] == "01") {
                                                   $firstDate = "1 Week";
                                               } else if ($productConfig['loanDurationPeriod'] == "02") {
                                                   $firstDate = "2 Weeks";
                                               }

                                               $loanFirstRepayment = date("Y-m-d", strtotime("+$firstDate", $date));
                                               echo $loanFirstRepayment;
                                               ?>"
                                               class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            <!-- /.tab-pane -->
        </div>

        <!-- /.tab-pane -->
    </div>
</div>

<script src="https://code.jquery.com/jquery-1.9.1.js" integrity="sha256-e9gNBsAcA0DBuRWbm0oZfbiCyhjLrI6bmqAl5o+ZjUA="
        crossorigin="anonymous"></script>
<script>
    var loanProductByloanReason = {
        T: ["Study Loan: Loan to fund formal studies at a recognised institution"],
        H: ["Home Loans: New property acquisition or upgrades to existing property"],
        P: ["Crisis Loan: Death / Funeral", "Crisis Loan: Income Loss", "Crisis Loan: Theft or Fire", "Crisis Loan: Medical", "Crisis Loan: Other Emergency", "Financing of fixed or moveable asset other than property", "Consolidation Loan: A loan resulting from the Debt Consolidation", "Small Business: A loan to a sole proprietor", "Other: A loan other than the ones stipulated above"],
        M: ["Crisis Loan: Death / Funeral", "Crisis Loan: Income Loss", "Crisis Loan: Theft or Fire", "Crisis Loan: Medical", "Crisis Loan: Other Emergency", "Financing of fixed or moveable asset other than property", "Consolidation Loan: A loan resulting from the Debt Consolidation", "Small Business: A loan to a sole proprietor", "Other: A loan other than the ones stipulated above"]
    }

    function changeProduct(value) {
        if (value.length == 0) document.getElementById("loanReason").innerHTML = "<option></option>";

        else {
            var catOptions = "";
            for (loanReasonId in loanProductByloanReason[value]) {
                catOptions += "<option>" + loanProductByloanReason[value][loanReasonId] + "</option>";
            }
            document.getElementById("loanReason").innerHTML = catOptions;
        }
    }
</script>

<script>
    function loanCharge(min, charge, loan) {
        let lowerBound = min;
        let backetRange = 1;
        var upperBound = 0;

        while (loan > upperBound){
            lowerBound = min * Math.pow(2, (backetRange - 1));
            backetRange += 1;
            upperBound = min * Math.pow(2, (backetRange - 1));
        }

        return lowerBound * (charge / 100);
    }

    var slider = document.getElementById("myRange");
    var output = document.getElementById("newPrincipal");
    output.innerHTML = slider.value.split(";")[0];

    var interestRate = document.getElementById("interestRate");
    var loanDuration = document.getElementById("inputLoanDuration");
    var fee = document.getElementById('loanCharge');

    slider.oninput = function () {
        output.innerHTML = this.value.split(";")[0];
        $("#principalAmount").val(this.value.split(";")[0]);

        var principalAmount = parseInt(document.getElementById("principalAmount").value.split(";")[0]);
        var inputLoanInterestMethod = document.getElementById("inputLoanInterestMethod").value;
        var inputLoanDuration = document.getElementById("inputLoanDuration").value;
        var interestValue = document.getElementById("interestRate").value / 100;
        var interest = 0;
        //console.log(principalAmount + " : " + interestValue + " : " + inputLoanDuration + " : " + inputLoanInterestMethod);
        if (inputLoanInterestMethod === "COMPOUND_INTEREST") {
            function calculateInterest(principalAmount, interestValue, inputLoanDuration) {
                var calInterest = interestValue * principalAmount;
                if (inputLoanDuration > 0) {
                    principalAmount = (calInterest + principalAmount) * (1 - (1 / inputLoanDuration));
                    inputLoanDuration = inputLoanDuration - 1;
                    calInterest = calInterest + calculateInterest(principalAmount, interestValue, inputLoanDuration);
                }
                return calInterest;
            }

            interest = calculateInterest(principalAmount, interestValue, inputLoanDuration);

        }
        else if(inputLoanInterestMethod === "REDUCING_RATE_EQUAL_INSTALLMENTS"){
            var type = 0;
            var totalInterst =0;
            var principal = 0;

            var rate = interestValue; // rate = 10%
            var nper = inputLoanDuration; // months
            var pv = principalAmount; //Principal Amount
            var fv = 0; //Expected Balance at the end
            var payment = parseFloat((-fv - pv * Math.pow(1 + rate, nper)) / (1 + rate * type) / ((Math.pow(1 + rate, nper) - 1) / rate));//EMI

            for (var i = 0; i < inputLoanDuration; i++) {
                if(i==0) {
                    interest = (pv * rate);
                    totalInterst+=interest;
                    principal = (payment*-1) - interest;
                }else{
                    //Get the New Principal
                    pv = pv-principal;
                    interest = (pv*rate);
                    totalInterst+=interest;
                    principal = (payment*-1) - interest;
                }
            }
            interest=totalInterst;
            console.log(principalAmount);
        }
        else {
            interest = parseFloat(principalAmount * ((document.getElementById("interestRate").value) / 100));
        }

        var otherFees = 0;
        var feesTotal = 0;
        var fixedAmount = 0;
        <?php

        $feesTotal = 0;
        foreach($productConfig['productPercentageFees'] as $key => $value) {
       
        if($value['chargeTerm'] !== "PRINCIPAL_DUE_INTEREST_DUE_FEES_DUE"){
        ?>


        var chargeTerm = "<?php echo $value['chargeTerm']; ?>";
        var percentage = "<?php echo $value['percentage']; ?>";
        console.log(chargeTerm);
        if(chargeTerm=="PRINCIPAL") {
            var <?php echo str_replace(" ", "_", $value['feeDescription']); ?>Rate = parseFloat((principalAmount)
                * ((document.getElementById("<?php echo str_replace(" ", "_", $value['feeDescription']);?>Rate").value) / 100));
        }
        else if(chargeTerm=="FREQUENCY") {
            var <?php echo str_replace(" ", "_", $value['feeDescription']); ?>Rate = parseFloat((principalAmount)
                * (percentage / 100) * inputLoanDuration);
        }
        else{
            var <?php echo str_replace(" ", "_", $value['feeDescription']); ?>Rate = parseFloat((principalAmount)
                * ((document.getElementById("<?php echo str_replace(" ", "_", $value['feeDescription']);?>Rate").value) / 100));
        }

        var rounded<?php echo str_replace(" ", "_", $value['feeDescription']); ?> = (<?php echo str_replace(" ", "_", $value['feeDescription']); ?>Rate).toFixed(2);

        var minFixedAmount = parseInt("<?php echo $value['minFixedAmount']; ?>");
        var maxFixedAmount = parseInt("<?php echo $value['maxFixedAmount']; ?>");
        var minLoanAllowed = parseInt("<?php echo $minLoanAllowed; ?>")
        var controlledCharge = <?php echo str_replace(" ", "_", $value['feeDescription']); ?>Rate;

        var loanChargeFee = loanCharge(minLoanAllowed, 2.8, principalAmount)
        feesTotal += parseFloat(<?php echo str_replace(" ", "_", $value['feeDescription']); ?>Rate);
        $('#loanCharge').prop('readonly',false);
        $('#loanCharge').val(loanChargeFee.toFixed(2));
        $('#loanCharge').prop('readonly',true);

        if (minFixedAmount !== "" && maxFixedAmount !== "") {
            if (controlledCharge < minFixedAmount) {
                $("#<?php echo str_replace(" ", "_", $value['feeDescription']); ?>Fee").val(minFixedAmount);
                controlledCharge = minFixedAmount;
            } else if (controlledCharge > maxFixedAmount) {
                $("#<?php echo str_replace(" ", "_", $value['feeDescription']); ?>Fee").val(maxFixedAmount);
                controlledCharge = maxFixedAmount;
            } else {
                $("#<?php echo str_replace(" ", "_", $value['feeDescription']); ?>Fee").val(rounded<?php echo str_replace(" ", "_", $value['feeDescription']); ?>);
            }
            otherFees += controlledCharge;
        } else {
            $("#<?php echo str_replace(" ", "_", $value['feeDescription']); ?>Fee").val(rounded<?php echo str_replace(" ", "_", $value['feeDescription']); ?>);
            otherFees += controlledCharge;
        }
        <?php }
        }?>
        //For the Charge that PRINCIPAL_DUE_INTEREST_DUE_FEES_DUE
        feesTotal=feesTotal+loanChargeFee+interest;
        console.log(feesTotal);
        <?php 
        foreach($productConfig['productPercentageFees'] as $key => $value) {
            $feesTotal += $value['percentage'];
            if($value['chargeTerm'] == "PRINCIPAL_DUE_INTEREST_DUE_FEES_DUE"){
            ?>
    
    
            var chargeTerm = "<?php echo $value['chargeTerm']; ?>";
            var percentage = "<?php echo $value['percentage']; ?>";
            console.log(chargeTerm);
            if(chargeTerm=="PRINCIPAL") {
                var <?php echo str_replace(" ", "_", $value['feeDescription']); ?>Rate = parseFloat((principalAmount)
                    * ((document.getElementById("<?php echo str_replace(" ", "_", $value['feeDescription']);?>Rate").value) / 100));
            }
        
                var <?php echo str_replace(" ", "_", $value['feeDescription']); ?>Rate = parseFloat((principalAmount+feesTotal)
                    * ((document.getElementById("<?php echo str_replace(" ", "_", $value['feeDescription']);?>Rate").value) / 100));
        
    
            var rounded<?php echo str_replace(" ", "_", $value['feeDescription']); ?> = (<?php echo str_replace(" ", "_", $value['feeDescription']); ?>Rate).toFixed(2);
    
            var minFixedAmount = parseInt("<?php echo $value['minFixedAmount']; ?>");
            var maxFixedAmount = parseInt("<?php echo $value['maxFixedAmount']; ?>");
            var minLoanAllowed = parseInt("<?php echo $minLoanAllowed; ?>")
            var controlledCharge = <?php echo str_replace(" ", "_", $value['feeDescription']); ?>Rate;
    
            var loanChargeFee = loanCharge(minLoanAllowed, 2.8, principalAmount)
            $('#loanCharge').prop('readonly',false);
            $('#loanCharge').val(loanChargeFee.toFixed(2));
            $('#loanCharge').prop('readonly',true);
    
            if (minFixedAmount !== "" && maxFixedAmount !== "") {
                if (controlledCharge < minFixedAmount) {
                    $("#<?php echo str_replace(" ", "_", $value['feeDescription']); ?>Fee").val(minFixedAmount);
                    controlledCharge = minFixedAmount;
                } else if (controlledCharge > maxFixedAmount) {
                    $("#<?php echo str_replace(" ", "_", $value['feeDescription']); ?>Fee").val(maxFixedAmount);
                    controlledCharge = maxFixedAmount;
                } else {
                    $("#<?php echo str_replace(" ", "_", $value['feeDescription']); ?>Fee").val(rounded<?php echo str_replace(" ", "_", $value['feeDescription']); ?>);
                }
                otherFees += controlledCharge;
            } else {
                $("#<?php echo str_replace(" ", "_", $value['feeDescription']); ?>Fee").val(rounded<?php echo str_replace(" ", "_", $value['feeDescription']); ?>);
                otherFees += controlledCharge;
            }
            <?php }
            }

        foreach($productConfig['fixedFees'] as $key => $value) {
        $fixedFee=0;
        if ($value['chargeTerm'] === "FREQUENCY") {
            $fixedFee += $value['feeAmount'];
            $fixedFees = $fixedFee * $defaultDuration;
        }else{
            $fixedFees = $value['feeAmount'];
        }
        ?>
        var minLoan = parseInt("<?php echo $value['minLoan']; ?>");
        var maxLoan = parseInt("<?php echo $value['maxLoan']; ?>");
        var fixedCharge = 0;



        if (principalAmount >= minLoan && principalAmount <= maxLoan) {
            $("#<?php echo str_replace(" ", "_", $value['feeDescription']); ?>Fee").val(<?php echo $fixedFees; ?>);
            otherFees +=<?php echo $fixedFees; ?>;
        }
        <?php } ?>

        var insuranceRate = parseFloat((principalAmount + interest + otherFees) * ((document.getElementById("InsuranceRate").value) / 100));
        //log("Principal: " + principalAmount + " Interest: " + interest + " Other Fees: " + otherFees)
        //futureValue = investment * (Math.pow(1 + monthlyRate, months) - 1) / monthlyRate;
        var currentBalance = (principalAmount + interest + otherFees).toFixed(2);// + insuranceAmount;

        var interestAmount = currentBalance / $("#inputLoanNumOfRepayments").val();
        var initialAmount = interestAmount.toFixed(2);
        var roundedInsurance = insuranceRate.toFixed(2);
        var interestFinalAmount = interest.toFixed(2);

        $("#currentBalance").val(currentBalance);
        $("#initialAmount").val(initialAmount);
        $("#insuranceFee").val(roundedInsurance);
        $("#interestValue").val(interestFinalAmount);
    }

    loanDuration.oninput = function() {
        //output.innerHTML = this.value.split(";")[0];
        $("#inputLoanDuration").val(this.value);
        $("#inputLoanNumOfRepayments").val(this.value);

        var principalAmount = parseInt(document.getElementById("principalAmount").value.split(";")[0]);
        var inputLoanInterestMethod = document.getElementById("inputLoanInterestMethod").value;
        var inputLoanDuration = document.getElementById("inputLoanDuration").value;
        var interestValue = document.getElementById("interestRate").value / 100;
        var interest = 0;
        var feesTotal = 0;

        console.log(inputLoanInterestMethod);
        //console.log(principalAmount + " : " + interestValue + " : " + inputLoanDuration + " : " + inputLoanInterestMethod);
        if (inputLoanInterestMethod === "COMPOUND_INTEREST") {
            function calculateInterest(principalAmount, interestValue, inputLoanDuration) {
                var calInterest = interestValue * principalAmount;
                if (inputLoanDuration > 0) {
                    principalAmount = (calInterest + principalAmount) * (1 - (1 / inputLoanDuration));
                    inputLoanDuration = inputLoanDuration - 1;
                    calInterest = calInterest + calculateInterest(principalAmount, interestValue, inputLoanDuration);
                }
                return calInterest;
            }

            interest = calculateInterest(principalAmount, interestValue, inputLoanDuration);

        }
        else if(inputLoanInterestMethod === "REDUCING_RATE_EQUAL_INSTALLMENTS"){
            var type = 0;
            var totalInterst =0;
            var principal = 0;

            var rate = interestValue; // rate = 10%
            var nper = inputLoanDuration; // months
            var pv = principalAmount; //Principal Amount
            var fv = 0; //Expected Balance at the end
            var payment = parseFloat((-fv - pv * Math.pow(1 + rate, nper)) / (1 + rate * type) / ((Math.pow(1 + rate, nper) - 1) / rate));//EMI

            for (var i = 0; i < inputLoanDuration; i++) {
                if(i==0) {
                    interest = (pv * rate);
                    totalInterst+=interest;
                    principal = (payment*-1) - interest;

                }else{
                    //Get the New Principal
                    pv = pv-principal;
                    interest = (pv*rate);
                    totalInterst+=interest;
                    principal = (payment*-1) - interest;
                }
            }
            interest=totalInterst;
        }
        else {
            interest = parseFloat(principalAmount * ((document.getElementById("interestRate").value) / 100));
        }

        var otherFees = 0;
        var fixedAmount = 0;
        <?php

        $feesTotal = 0;
        foreach($productConfig['productPercentageFees'] as $key => $value) {
        $feesTotal += $value['percentage'];
        if($value['chargeTerm'] !== "PRINCIPAL_DUE_INTEREST_DUE_FEES_DUE"){
        ?>
        var chargeTerm = "<?php echo $value['chargeTerm']; ?>";
        var percentage = "<?php echo $value['percentage']; ?>";
        console.log(chargeTerm);
        if(chargeTerm=="PRINCIPAL") {
            var <?php echo str_replace(" ", "_", $value['feeDescription']); ?>Rate = parseFloat((principalAmount)
                * ((document.getElementById("<?php echo str_replace(" ", "_", $value['feeDescription']);?>Rate").value) / 100));
        }
        else if(chargeTerm=="FREQUENCY") {
            var <?php echo str_replace(" ", "_", $value['feeDescription']); ?>Rate = parseFloat((principalAmount)
                * (percentage / 100) * inputLoanDuration);
        }
        else{
            var <?php echo str_replace(" ", "_", $value['feeDescription']); ?>Rate = parseFloat((principalAmount)
                * ((document.getElementById("<?php echo str_replace(" ", "_", $value['feeDescription']);?>Rate").value) / 100));

        }

        var rounded<?php echo str_replace(" ", "_", $value['feeDescription']); ?> = (<?php echo str_replace(" ", "_", $value['feeDescription']); ?>Rate).toFixed(2);

        console.log(rounded<?php echo str_replace(" ", "_", $value['feeDescription']); ?>);

        var minFixedAmount = parseInt("<?php echo $value['minFixedAmount']; ?>");
        var maxFixedAmount = parseInt("<?php echo $value['maxFixedAmount']; ?>");
        var minLoanAllowed = parseInt("<?php echo $minLoanAllowed; ?>")
        var controlledCharge = <?php echo str_replace(" ", "_", $value['feeDescription']); ?>Rate;

        var loanChargeFee = loanCharge(minLoanAllowed, 2.8, principalAmount)
        $('#loanCharge').val(loanChargeFee.toFixed(2));


        if (minFixedAmount !== "" && maxFixedAmount !== "") {
            if (controlledCharge < minFixedAmount) {
                $("#<?php echo str_replace(" ", "_", $value['feeDescription']); ?>Fee").val(minFixedAmount);
                controlledCharge = minFixedAmount;
            } else if (controlledCharge > maxFixedAmount) {
                $("#<?php echo str_replace(" ", "_", $value['feeDescription']); ?>Fee").val(maxFixedAmount);
                controlledCharge = maxFixedAmount;
            } else {
                $("#<?php echo str_replace(" ", "_", $value['feeDescription']); ?>Fee").val(rounded<?php echo str_replace(" ", "_", $value['feeDescription']); ?>);
            }
            otherFees += controlledCharge;
        } else {
            $("#<?php echo str_replace(" ", "_", $value['feeDescription']); ?>Fee").val(rounded<?php echo str_replace(" ", "_", $value['feeDescription']); ?>);
            otherFees += controlledCharge;
        }
        <?php }
        
        }?> 
        //For the Charge that PRINCIPAL_DUE_INTEREST_DUE_FEES_DUE
        feesTotal=feesTotal+loanChargeFee+interest;
        console.log(feesTotal);
        <?php 
        foreach($productConfig['productPercentageFees'] as $key => $value) {
            $feesTotal += $value['percentage'];
            if($value['chargeTerm'] == "PRINCIPAL_DUE_INTEREST_DUE_FEES_DUE"){
            ?>
    
    
            var chargeTerm = "<?php echo $value['chargeTerm']; ?>";
            var percentage = "<?php echo $value['percentage']; ?>";
            console.log(chargeTerm);
            if(chargeTerm=="PRINCIPAL") {
                var <?php echo str_replace(" ", "_", $value['feeDescription']); ?>Rate = parseFloat((principalAmount)
                    * ((document.getElementById("<?php echo str_replace(" ", "_", $value['feeDescription']);?>Rate").value) / 100));
            }
        
                var <?php echo str_replace(" ", "_", $value['feeDescription']); ?>Rate = parseFloat((principalAmount+feesTotal)
                    * ((document.getElementById("<?php echo str_replace(" ", "_", $value['feeDescription']);?>Rate").value) / 100));
        
    
            var rounded<?php echo str_replace(" ", "_", $value['feeDescription']); ?> = (<?php echo str_replace(" ", "_", $value['feeDescription']); ?>Rate).toFixed(2);
    
            var minFixedAmount = parseInt("<?php echo $value['minFixedAmount']; ?>");
            var maxFixedAmount = parseInt("<?php echo $value['maxFixedAmount']; ?>");
            var minLoanAllowed = parseInt("<?php echo $minLoanAllowed; ?>")
            var controlledCharge = <?php echo str_replace(" ", "_", $value['feeDescription']); ?>Rate;
    
            var loanChargeFee = loanCharge(minLoanAllowed, 2.8, principalAmount)
            $('#loanCharge').prop('readonly',false);
            $('#loanCharge').val(loanChargeFee.toFixed(2));
            $('#loanCharge').prop('readonly',true);
    
            if (minFixedAmount !== "" && maxFixedAmount !== "") {
                if (controlledCharge < minFixedAmount) {
                    $("#<?php echo str_replace(" ", "_", $value['feeDescription']); ?>Fee").val(minFixedAmount);
                    controlledCharge = minFixedAmount;
                } else if (controlledCharge > maxFixedAmount) {
                    $("#<?php echo str_replace(" ", "_", $value['feeDescription']); ?>Fee").val(maxFixedAmount);
                    controlledCharge = maxFixedAmount;
                } else {
                    $("#<?php echo str_replace(" ", "_", $value['feeDescription']); ?>Fee").val(rounded<?php echo str_replace(" ", "_", $value['feeDescription']); ?>);
                }
                otherFees += controlledCharge;
            } else {
                $("#<?php echo str_replace(" ", "_", $value['feeDescription']); ?>Fee").val(rounded<?php echo str_replace(" ", "_", $value['feeDescription']); ?>);
                otherFees += controlledCharge;
            }
            <?php }
            }

        foreach($productConfig['fixedFees'] as $key => $value) {
        $fixedFee=0;
        if ($value['chargeTerm'] === "FREQUENCY") {
            $fixedFee += $value['feeAmount'];
            $fixedFees = $fixedFee * $defaultDuration;
        }else{
            $fixedFees = $value['feeAmount'];
        }
        ?>
        var minLoan = parseInt("<?php echo $value['minLoan']; ?>");
        var maxLoan = parseInt("<?php echo $value['maxLoan']; ?>");
        var fixedCharge = 0;



        if (principalAmount >= minLoan && principalAmount <= maxLoan) {
            $("#<?php echo str_replace(" ", "_", $value['feeDescription']); ?>Fee").val(<?php echo $fixedFees; ?>);
            otherFees +=<?php echo $fixedFees; ?>;
        }
        <?php } ?>

        var insuranceRate = parseFloat((principalAmount + interest + otherFees) * ((document.getElementById("InsuranceRate").value) / 100));
        //log("Principal: " + principalAmount + " Interest: " + interest + " Other Fees: " + otherFees)
        //futureValue = investment * (Math.pow(1 + monthlyRate, months) - 1) / monthlyRate;
        var currentBalance = (principalAmount + loanChargeFee + interest + otherFees).toFixed(2);// + insuranceAmount;

        var interestAmount = currentBalance / $("#inputLoanNumOfRepayments").val();
        var initialAmount = interestAmount.toFixed(2);
        var roundedInsurance = insuranceRate.toFixed(2);
        var interestFinalAmount = interest.toFixed(2);

        console.log(chargeTerm);

        $("#currentBalance").val(currentBalance);
        $("#initialAmount").val(initialAmount);
        $("#insuranceFee").val(roundedInsurance);
        $("#interestValue").val(interestFinalAmount);
    }
</script>


<!--Principal and balance -->
<script>
    $(document).ready(function () {
        function updateLoan() {

            var maximumLoan = parseFloat("<?php echo $maxLoanAllowed; ?>");
            if ($("#requiredLoan").val() >= maximumLoan) {
                $("#requiredLoan").val(maximumLoan);
            }

            //var loan = parseFloat($("#requiredLoan").val();
            var interestRate = parseFloat("<?php echo $defaultInterestRate; ?>");
            var insuranceFee = parseFloat("<?php echo $insuranceFee; ?>");
            var feesTotal = parseFloat("<?php echo $feesTotal; ?>");
            var totalFixed = parseFloat("<?php echo $totalFixed; ?>");
            var totalLoan = parseFloat($("#requiredLoan").val()) + parseFloat($("#requiredLoan").val() * interestRate / 100) + parseFloat($("#requiredLoan").val() * feesTotal / 100) + parseFloat(totalFixed);
            var insuranceAmount = parseFloat(totalLoan * insuranceFee / 100);
            var totalLoanAmount = parseFloat(totalLoan + insuranceAmount);
            var disposableIncome = totalLoanAmount /<?php echo $defaultDuration; ?>;
            var disposableIncomeValue = disposableIncome.toFixed(2);
            if ($("#disposableIncome").val() < disposableIncome) {
                $("#disposableIncome").val(disposableIncomeValue);
            } else {

            }
            //console.log("Loan: " + $("#requiredLoan").val() + "\ninterest: " + parseFloat($("#requiredLoan").val() * interestRate / 100) + "\nFees: " + parseFloat($("#requiredLoan").val() * feesTotal / 100) + "\ntotalFixed: " + totalFixed + "\ntotalLoan: " + totalLoan + "\ninsuranceAmount: " + insuranceAmount + "\ntotalLoanAmount: " + totalLoanAmount);
        }

        $(document).on("change, keyup", "#requiredLoan", updateLoan);
    });
</script>


<script>
    $(document).ready(function () {
        function getTerms() {

            var account = document.getElementById(accountType).value;
            <?php
            $getConfig = mysqli_fetch_assoc(mysqli_query($link, "select * from products where accountType = '<script>document.writeln(account);</script>'"));
            $productConfig = json_decode($row['product_configuration'], true);
            ?>
            var response = "<?php echo $productConfig;?>";
            $("#disposableIncome").val(disposableIncomeValue);

            //console.log("Loan: " + $("#requiredLoan").val() + "\ninterest: " + parseFloat($("#requiredLoan").val() * interestRate / 100) + "\nFees: " + parseFloat($("#requiredLoan").val() * feesTotal / 100) + "\ntotalFixed: " + totalFixed + "\ntotalLoan: " + totalLoan + "\ninsuranceAmount: " + insuranceAmount + "\ntotalLoanAmount: " + totalLoanAmount);
        }

        $(document).on("change, keyup", "#accountType", getTerms);
    });
</script>

<!--get Loan Maturity Date-->

<!-- REQUIRED JS SCRIPTS -->
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
<script>
    $(function () {
        //Initialize Select2 Elements
        $(".select2").select2({
            placeholder: "Choose Borrower or Search by Name"


        });
    });
</script>
<script>
    $(document).ready(function () {
        $(".slidingDivAdvanceSettings").show();
        $('.show_hide_advance_settings').click(function (e) {
            $(".slidingDivAdvanceSettings").slideToggle("fast");
            var val = $(this).text() == "Hide" ? "Show" : "Hide";
            $(this).hide().text(val).fadeIn("fast");
            e.preventDefault();
        });
    });
</script>
<script>
    $(document).ready(function () {
        $(".slidingDivAutomatedPayments").show();
        $('.show_hide_automated_payments').click(function (e) {
            $(".slidingDivAutomatedPayments").slideToggle("fast");
            var val = $(this).text() == "Hide" ? "Show" : "Hide";
            $(this).hide().text(val).fadeIn("fast");
            e.preventDefault();
        });
    });
</script>
<script>
    function enableDisablePostingPeriod() {
        var inputPaymentPostingPeriod = document.getElementById("inputPaymentPostingPeriod");
        if (document.getElementById("inputAutomaticPaymentsYes").checked) {
            inputPaymentPostingPeriod.disabled = false;
        } else if (document.getElementById("inputAutomaticPaymentsNo").checked) {
            inputPaymentPostingPeriod.disabled = true;
            document.getElementById("inputPaymentPostingPeriod").selectedIndex = 0;
        }
    }
</script>
<script>
    $(document).ready(function () {
        $(".slidingDivExtendedLoan").show();
        $('.show_hide_extended_loan').click(function (e) {
            $(".slidingDivExtendedLoan").slideToggle("fast");
            var val = $(this).text() == "Hide" ? "Show" : "Hide";
            $(this).hide().text(val).fadeIn("fast");
            e.preventDefault();
        });
    });
</script>

<script>
    $(document).ready(function () {
        $(".slidingDivLoanTerms").show();
        $('.show_hide_loan_terms').click(function (e) {
            $(".slidingDivLoanTerms").slideToggle("fast");
            var val = $(this).text() == "Hide" ? "Show" : "Hide";
            $(this).hide().text(val).fadeIn("fast");
            e.preventDefault();
        });
    });
</script>

<script>
    $(function () {
        //Initialize Select2 Elements
        $(".guarantor_select").select2({
            placeholder: "Choose Borrower or Guarantor or Search by Name"

            , allowClear: true
        });
    });
</script>

<script>
    $('#inputLoanDuration').TouchSpin({
        min: 1,
        max: 730
    });
    $('#inputLoanNumOfRepayments, #inputAmRecurringPeriod').TouchSpin({
        min: 1,
        max: 2000
    });
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

            defaultDate: '26/06/2020', showTrigger: '#calImg',
            yearRange: 'c-20:c+20', showTrigger: '#calImg',

            dateFormat: 'dd/mm/yyyy',
            minDate: '01/01/1980'
        });
    });

</script>
<script>
    $(function () {
        $("#inputBorrowerId").change(function () {
            var id = $(this).val();
            $.get("generate_loan_unique_number.php", {"bid": id}, function (data) {
                data = JSON.parse(data);
                if (data !== null) {
                    $("#inputLoanApplicationId").val(data.loan_number);
                } else {
                }
            });
        });
    });
</script>

<script>

    function check() {
        var inputLoanDurationPeriod = document.getElementById("inputLoanDurationPeriod");
        var loan_interest_period_value = document.getElementById("inputInterestPeriod").value;
        var loan_duration_period_value = "";

        if (loan_interest_period_value == "Day")
            loan_duration_period_value = "Days";

        else if (loan_interest_period_value == "Week")
            loan_duration_period_value = "Weeks";

        else if (loan_interest_period_value == "Month")
            loan_duration_period_value = "Months";

        else if (loan_interest_period_value == "Year")
            loan_duration_period_value = "Years";

        selectItemByValue(inputLoanDurationPeriod, loan_duration_period_value);
    }


    function selectItemByValue(elmnt, value) {
        for (var i = 0; i < elmnt.options.length; i++) {
            if (elmnt.options[i].value == value)
                elmnt.selectedIndex = i;
        }
    }

    function setNumofRep() {
        var inputLoanDuration = document.getElementById("inputLoanDuration").value;
        var inputLoanDurationPeriod = document.getElementById("inputLoanDurationPeriod").value;
        var inputLoanPaymentSchemeId = document.getElementById("inputLoanPaymentSchemeId");
        var inputLoanPaymentSchemeIdText = inputLoanPaymentSchemeId.options[inputLoanPaymentSchemeId.selectedIndex].text;
        var inputLoanNumOfRepayments = document.getElementById("inputLoanNumOfRepayments");

        var inputLoanInterest = document.getElementById("inputLoanInterest").value;

        var currentBalance = parseFloat(document.getElementById("currentBalance").value);
        var initialAmount = parseFloat(currentBalance / inputLoanDuration).toFixed(2);
        var principalAmount = parseFloat(document.getElementById("principalAmount").value);
        var newInterest = parseFloat(principalAmount * inputLoanInterest / 100);


        var otherFees = 0;
        var fixedAmount = 0;
        <?php

        $feesTotal = 0;
        foreach($fees['loanCharges'] as $key => $value) {
        if ($value['isInsurance'] == "1") {
        //Work Out on where there is no Insurance Fee
    }
        else{ if ($value['isPenalty'] == "0") {
        $feesTotal += $value['percentage'];
        ?>
        var <?php echo str_replace(" ", "_", $value['description']); ?>Rate = parseFloat((principalAmount) * ((document.getElementById("<?php echo str_replace(" ", "_", $value['description']);?>Rate").value) / 100));
        fixedAmount = parseFloat(<?php echo $value['fixedAmount']; ?>);
        var rounded<?php echo str_replace(" ", "_", $value['description']); ?> = (<?php echo str_replace(" ", "_", $value['description']); ?>Rate + fixedAmount).toFixed(2);
        otherFees += <?php echo str_replace(" ", "_", $value['description']); ?>Rate + fixedAmount;
        $("#<?php echo str_replace(" ", "_", $value['description']); ?>Fee").val(rounded<?php echo str_replace(" ", "_", $value['description']); ?>);
        <?php
        }
        }
        }
        ?>
        var insuranceRate = parseFloat((principalAmount + newInterest + otherFees) * (parseFloat(document.getElementById("InsuranceRate").value) / 100));

        var currentBalance = (principalAmount + newInterest + otherFees + fixedAmount).toFixed(2);// + insuranceAmount;

        //log("Principal: " + principalAmount + "newInterest: " + newInterest + "other Fees: " + otherFees + "Insurance Rate: " + insuranceRate);
        newInterest = newInterest.toFixed(2);
        insuranceRate = insuranceRate.toFixed(2);
        //console.log(inputLoanInterest);
        if (!isNaN(initialAmount))
            $("#initialAmount").val(initialAmount);
        $("#interestValue").val(newInterest);
        $("#insuranceFee").val(insuranceRate);
        $("#currentBalance").val(currentBalance);
        document.getElementById("newInterestRate").innerHTML = " - New Rate: " + inputLoanInterest;

        if (inputLoanDurationPeriod != "") {
            var totalRepayments = 0;
            var yearly = 0;
            var monthly = 0;
            var weekly = 0;
            var daily = 0;

            if (inputLoanPaymentSchemeIdText == "Daily") {
                yearly = 360;
                monthly = 30;
                biweekly = 14;
                weekly = 7;
                daily = 1;
                <?php
                //Calculate Maturity Date Add Days
                ?>
            } else if (inputLoanPaymentSchemeIdText == "Weekly") {
                yearly = 52;
                monthly = 4;
                biweekly = 2;
                weekly = 1;
                daily = 1 / 7;
                <?php
                //Calculate Maturity Date, Add Weeks
                ?>
            } else if (inputLoanPaymentSchemeIdText == "Biweekly") {
                yearly = 26;
                monthly = 2;
                biweekly = 1;
                weekly = 1 / 2;
                daily = 1 / 14;
            } else if (inputLoanPaymentSchemeIdText == "Monthly") {
                yearly = 12;
                monthly = 1;
                biweekly = 1 / 2;
                weekly = 1 / 4;
                daily = 1 / 30;
                <?php
                //Calculate Maturity Date Add Months

                ?>
            } else if (inputLoanPaymentSchemeIdText == "Bimonthly") {
                yearly = 6;
                monthly = 1 / 2;
                biweekly = 1 / 4;
                weekly = 1 / 8;
                daily = 1 / 60;
            } else if (inputLoanPaymentSchemeIdText == "Quarterly") {
                yearly = 4;
                monthly = 1 / 3;
                biweekly = 1 / 6;
                weekly = 1 / 12;
                daily = 1 / 90;
            } else if (inputLoanPaymentSchemeIdText == "Every 4 Months") {
                yearly = 3;
                monthly = 1 / 4;
                biweekly = 1 / 8;
                weekly = 1 / 16;
                daily = 1 / 120;
            } else if (inputLoanPaymentSchemeIdText == "Semi-Annual") {
                yearly = 2;
                monthly = 1 / 6;
                biweekly = 1 / 12;
                weekly = 1 / 24;
                daily = 1 / 180;
            } else if (inputLoanPaymentSchemeIdText == "Yearly") {
                yearly = 1;
                monthly = 1 / 12;
                biweekly = 1 / 24;
                weekly = 1 / 38;
                daily = 1 / 360;
            } else {
                if (inputLoanPaymentSchemeIdText != '') {
                    var res = inputLoanPaymentSchemeIdText.split("-");
                    if (res[1] == 'days') {
                        yearly = 360 / res[0];
                        monthly = 30 / res[0];
                        biweekly = 14 / res[0];
                        weekly = 7 / res[0];
                        daily = 1 / res[0];
                    } else if (res[1] != '') {
                        var res_count = res.length;

                        yearly = 12 * res_count;
                        monthly = res_count;
                        biweekly = 8 / res[0];
                        weekly = 4 / res[0];
                        daily = 1 / res[0];
                    }
                } else {
                    yearly = 1;
                    monthly = 1;
                    weekly = 1;
                    daily = 1;
                }
            }

            if (inputLoanDurationPeriod == "Days") {
                totalRepayments = inputLoanDuration * daily;
            }
            if (inputLoanDurationPeriod == "Weeks") {
                totalRepayments = inputLoanDuration * weekly;
            }
            if (inputLoanDurationPeriod == "Months") {
                totalRepayments = inputLoanDuration * monthly;
            }
            if (inputLoanDurationPeriod == "Years") {
                totalRepayments = inputLoanDuration * yearly;
            }
            totalRepayments = Math.floor(totalRepayments);

            if (inputLoanPaymentSchemeIdText == "Lump-Sum")
                totalRepayments = 1;

            if (totalRepayments > 0)
                inputLoanNumOfRepayments.value = totalRepayments;

            if (inputLoanPaymentSchemeIdText != "")
                $("#inputLoanNumOfRepaymentsChanged").html("<span class=\"label label-danger\">&larr; Number of Repayments Updated!</span>");
        }
    }

    function removeNumRepaymentsMessage() {
        $("#inputLoanNumOfRepaymentsChanged").html("");
    }

    function disableNumRepayments() {
        var inputLoanNumOfRepayments = document.getElementById("inputLoanNumOfRepayments");
        var inputLoanPaymentSchemeId = document.getElementById("inputLoanPaymentSchemeId");
        var inputLoanPaymentSchemeIdText = inputLoanPaymentSchemeId.options[inputLoanPaymentSchemeId.selectedIndex].text;
        if (inputLoanPaymentSchemeIdText == "Lump-Sum") {
            inputLoanNumOfRepayments.value = 1;
        }
    }

    function first_repayment_pro_rata_click() {
        var LoanFirstRepaymentAmountProRata = document.getElementById("LoanFirstRepaymentAmountProRata");
        var inputLoanDoNotAdjustRemainingProRata = document.getElementById("inputLoanDoNotAdjustRemainingProRata");
        var inputLoanFeesProRata = document.getElementById("inputLoanFeesProRata");
        var inputLoanInterestMethod = document.getElementById("inputLoanInterestMethod").value;
        if (LoanFirstRepaymentAmountProRata.checked) {
            $("#inputFirstRepaymentAmount").prop('disabled', true);
            $("#inputFirstRepaymentAmount").val('');

            $("#inputLoanFeesProRata").prop('disabled', false);

            if ((inputLoanInterestMethod == "flat_rate") || (inputLoanInterestMethod == "interest_only")) {
                $("#inputLoanDoNotAdjustRemainingProRata").prop('disabled', false);
            }
        } else {
            $("#inputFirstRepaymentAmount").prop('disabled', false);

            $("#inputLoanDoNotAdjustRemainingProRata").prop('checked', false);
            $("#inputLoanDoNotAdjustRemainingProRata").prop('disabled', true);

            $("#inputLoanFeesProRata").prop('checked', false);
            $("#inputLoanFeesProRata").prop('disabled', true);
        }
    }

    function enableNumRepayments() {
        $("#inputLoanNumOfRepayments").removeAttr("disabled");
    }

    function enableDisableMethod() {
        var inputLoanInterestMethod = document.getElementById("inputLoanInterestMethod").value;
        var inputLoanPaymentSchemeId = document.getElementById("inputLoanPaymentSchemeId");

        if (inputLoanInterestMethod == "flat_rate") {
            $("#inputFirstRepaymentAmount").prop('disabled', false);
            $("#inputLastRepaymentAmount").prop('disabled', false);

            var LoanFirstRepaymentAmountProRata = document.getElementById("LoanFirstRepaymentAmountProRata");
            if (LoanFirstRepaymentAmountProRata.checked) {
                $("#inputLoanFeesProRata").prop('disabled', false);
                $("#inputLoanDoNotAdjustRemainingProRata").prop('disabled', false);
            }
        } else {
            $("#inputFirstRepaymentAmount").prop('disabled', true);
            $("#inputLastRepaymentAmount").prop('disabled', true);
            if (inputLoanInterestMethod == "interest_only") {
                var LoanFirstRepaymentAmountProRata = document.getElementById("LoanFirstRepaymentAmountProRata");
                if (LoanFirstRepaymentAmountProRata.checked) {
                    $("#inputLoanFeesProRata").prop('disabled', false);
                    $("#inputLoanDoNotAdjustRemainingProRata").prop('disabled', false);
                }
            } else {
                $("#inputLoanDoNotAdjustRemainingProRata").prop('checked', false);
                $("#inputLoanDoNotAdjustRemainingProRata").prop('disabled', true);
            }
        }

        var inputLoanPaymentSchemeId = document.getElementById("inputLoanPaymentSchemeId");

        for (i = 0; i < inputLoanPaymentSchemeId.length; i++) {
            var repayment = inputLoanPaymentSchemeId.options[i].text;
            if (((inputLoanInterestMethod != "flat_rate") && (inputLoanInterestMethod != "interest_only") && (inputLoanInterestMethod != "compound_interest")) && (repayment == "Lump-Sum")) {
                inputLoanPaymentSchemeId.options[i].disabled = true;
                inputLoanPaymentSchemeId.options[i].selected = false;
            } else {
                inputLoanPaymentSchemeId.options[i].disabled = false;
            }
        }
        var inputLoanInterestMethod = document.getElementById("inputLoanInterestMethod").value;
        if ((inputLoanInterestMethod == "flat_rate") || (inputLoanInterestMethod == "interest_only")) {
            document.getElementById("inputInterestTypeFixed").disabled = false;
        } else {
            document.getElementById("inputInterestTypeFixed").disabled = true;
            document.getElementById("inputInterestTypePercentage").checked = true;
        }
        checkITPRRadio();
    }

    function checkITPRRadio() {
        var inputLoanInterestLabel = document.getElementById("inputLoanInterestLabel");
        var inputLoanInterest = document.getElementById("inputLoanInterest");
        if (document.getElementById("inputInterestTypePercentage").checked) {
            inputLoanInterestLabel.innerHTML = "Loan Interest %";
            inputLoanInterest.placeholder = "%";
        } else if (document.getElementById("inputInterestTypeFixed").checked) {
            inputLoanInterestLabel.innerHTML = "Loan Interest Amount";
            inputLoanInterest.placeholder = "Amount";
        }
    }

    $('input[type=radio][name=after_maturity_extend_loan]').on('change', function () {
        enableDisableExtendLoan();
        checkAMRadio();
    });

    function enableDisableExtendLoan() {
        if ($("#inputExtendLoanYes").prop("checked")) {
            $('input[name="after_maturity_percentage_or_fixed"]').prop('disabled', false);
            $('#inputAmCalculateInterestOn').prop('disabled', false);
            $('#inputAmInterest').prop('disabled', false);
            $('#inputAmLoanPaymentSchemeId').prop('disabled', false);
            $('#inputAmRecurringPeriod').prop('disabled', false);
            $('input[name="after_maturity_include_fees"]').prop('disabled', false);
        } else if ($("#inputExtendLoanNo").prop("checked")) {
            $('input[name="after_maturity_percentage_or_fixed"]').prop('disabled', true);
            $('#inputAmCalculateInterestOn').prop('disabled', true);
            $('#inputAmInterest').prop('disabled', true);
            $('#inputAmLoanPaymentSchemeId').prop('disabled', true);
            $('#inputAmRecurringPeriod').prop('disabled', true);
            $('input[name="after_maturity_include_fees"]').prop('disabled', true);
        }
    }

    function checkAMRadio() {
        var val = $("input[name=after_maturity_percentage_or_fixed]:checked").val();
        if (val == "percentage") {
            $("#inputAMCalculateInterestOnLabel").text("Calculate Interest on");
            $("#inputAMInterestOrFixedLabel").text("Loan Interest Rate After Maturity %");
            $("#inputAmInterest").removeClass('decimal-2-places');
            $("#inputAmInterest").addClass('decimal-4-places');
            $(".decimal-4-places").numeric({decimalPlaces: 4});
        } else if (val == "fixed") {
            $("#inputAMCalculateInterestOnLabel").text("Calculate Interest if there is");
            $("#inputAMInterestOrFixedLabel").text("Loan Interest Amount After Maturity");
            $("#inputAmInterest").removeClass('decimal-4-places');
            $("#inputAmInterest").addClass('decimal-2-places');

            $(".decimal-2-places").numeric({decimalPlaces: 2});
        }
    }

    $('input[type=radio][name=after_maturity_percentage_or_fixed]').on('change', function () {
        checkAMRadio();
    });
    enableDisableExtendLoan();
    checkAMRadio();
</script>
<div style="display:none">add_loan</div>

<script>
    function bankingDetails(name) {
        if (name == 'Online Transfer')
            document.getElementById('div_banking_details').innerHTML = '<div class="col-sm-6"><div class="form-group">\n' +
                '                            <label for="" class="col-sm-5 control-label">Account Holder</label>\n' +
                '                            <div class="col-sm-7"><input type="text" placeholder="Account Holder" class="form-control" name="recipient[accountName]" id="accountName" required></div></div></div>' +
                '                            <div class="col-sm-6"><div class="form-group"><label for="" class="col-sm-5 control-label">Account Type</label>\n' +
                '                            <div class="col-sm-7"><select name="recipient[accountType]" class="form-control" id="accountType" required><option value="" disabled>Select</option><option>Savings</option><option>Current</option><option>Cheque</option></select></div></div></div>' +
                '<div class="col-sm-6"><div class="form-group">\n' +
                '                            <label for="" class="col-sm-5 control-label">Bank Name</label>\n' +
                '                            <div class="col-sm-7"><select class="form-control" name="recipient[bankName]" id="bankName" required><option>--Select--</option><option>First National Bank</option><option>Nedbank</option><option>Postbank</option><option>Standard Lesotho Bank</option></select></div></div></div>' +
                '                            <div class="col-sm-6"><div class="form-group"><label for="" class="col-sm-5 control-label">Branch Name</label>\n' +
                '                            <div class="col-sm-7"><input type="text" placeholder="Branch Name" class="form-control" name="recipient[branchName]" id="branchName" required></div></div></div>' +
                '<div class="col-sm-6"><div class="form-group">\n' +
                '                            <label for="" class="col-sm-5 control-label">Account Number</label>\n' +
                '                            <div class="col-sm-7"><input type="number" placeholder="Account No." min="0" class="form-control" name="recipient[accountNumber]" id="bankAccountNumber" required></div></div></div>' +
                '                            <div class="col-sm-6"><div class="form-group"><label for="" class="col-sm-5 control-label">Branch Code</label>\n' +
                '                            <div class="col-sm-7"><input type="number" placeholder="Branch Code" min="0" class="form-control" name="recipient[branchCode]" id="branchCode" required></div></div></div>';

        else if (name == 'Mobile Money')
            document.getElementById('div_banking_details').innerHTML = '<div class="form-group">\n' +
                '                            <label for="" class="col-sm-3 control-label">Mobile Money Service</label>\n' +
                '                            <div class="col-sm-6"><select name ="recipient[serviceProvider]"  class="form-control"  required><option>--Select--</option><option>Ecocash</option><option>Smartel Money</option><option>Vodacom Mpesa</option></select></div></div>' +
                '<div class="form-group">\n' +
                '                            <label for="" class="col-sm-3 control-label">Mobile Money Number</label>\n' +
                '                            <div class="col-sm-6"><input type="text" placeholder="Mobile Money Reference" class="form-control" name="recipient[accountNumber]" id="reference" required></div></div>\n';
        else document.getElementById('div_banking_details').innerHTML = '';
    }
</script>
