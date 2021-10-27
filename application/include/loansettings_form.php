<?php
error_reporting(0);
$day = 1;

$check = mysqli_query($link, "SELECT * FROM emp_permission WHERE tid = '" . $_SESSION['tid'] . "' AND module_name = 'Borrower Details'") or die ("Error" . mysqli_error($link));
$get_check = mysqli_fetch_array($check);
$pupdate = $get_check['pupdate'];
$pread = $get_check['pread'];
$pdelete = $get_check['pdelete'];
if (isset($_GET['action'])) {
    $action = $_GET['action'];
}


// implemeting the delete on product
if($action === "delete"){
   $productid = $_GET['productid'];
   $result =  mysqli_query($link, "DELETE FROM products WHERE product_id = '$productid'") or die(mysqli_error($link));

   if($result){
       echo '<div class="alert alert-success" >
                <a href = "#" class = "close" data-dismiss= "alert"> &times;</a>
                Successfully removed a product !&nbsp; &nbsp;&nbsp;
            </div>';
   }
   else{
       echo '<div class="alert alert-danger" >
                <a href = "#" class = "close" data-dismiss= "alert"> &times;</a>
                Failed to remove a product !&nbsp; &nbsp;&nbsp;
            </div>';
   }
}


if (isset($_POST['saveProduct'])) {
    $documents = [];
    $eachDocument = 0;
    foreach ($_POST['document'] as $key => $value) {
        $documents[$eachDocument]['documentType'] = $value['name'];
        if ($value['required']==="on") {
            $required=true;
        } else {
            $required=false;
        }
        $documents[$eachDocument]['required'] = $required;
        $eachDocument++;
    }

    $loanFees = [];
    $eachFee = 0;
    foreach ($_POST['loanFees'] as $key => $value) {
        $loanFees[$eachFee]['feeName'] = $value['fee_name'];
        $loanFees[$eachFee]['minLoan'] = $value['min_loan'];
        $loanFees[$eachFee]['maxLoan'] = $value['max_loan'];
        $loanFees[$eachFee]['feeAmount'] = $value['fee_amount'];
        $loanFees[$eachFee]['chargeTerm'] = $value['charge_term'];
        if ($value['deductible']==="on") {
            $deductible=true;
        } else {
            $deductible=false;
        }
        $loanFees[$eachFee]['deductible'] = $deductible;
        $eachFee++;
    }

    $loanAdditionalFees = [];
    $eachAddFee = 0;
    foreach ($_POST['loanAdditionalFees'] as $key => $value) {
        $loanAdditionalFees[$eachAddFee]['feeDescription'] = $value['fee_description'];
        $loanAdditionalFees[$eachAddFee]['percentage'] = $value['percentage'];
        $loanAdditionalFees[$eachAddFee]['minFixedAmount'] = $value['min_fixed_amount'];
        $loanAdditionalFees[$eachAddFee]['maxFixedAmount'] = $value['max_fixed_amount'];
        $loanAdditionalFees[$eachAddFee]['chargeTerm'] = $value['charge_term'];
        $loanAdditionalFees[$eachAddFee]['glCode'] = $value['gl_code'];
        //$loanAdditionalFees[$eachAddFee]['fromAge'] = $value['fromAge'];
        $eachAddFee++;
    }

    $productObject = array_merge(
        $_POST['product'],
        array(
                "disbursmentMethods" => $_POST['loan_disbursed_by_id'],
            "repaymentMethods" => $_POST['loan_payment_scheme_id'],
            "requiredDocuments" => $documents,
            "fixedFees" => $loanFees,
            "branches"=> $_POST['branches'],
            "productPercentageFees" => $loanAdditionalFees)
    );

    $productConfig=json_encode($productObject);
    $productName = $_POST['product']['productName'];
    $productType = $_POST['product']['productType'];
    $accountType = $_POST['product']['accountType'];
    if (isset($_GET['action'])) {
        $action = $_GET['action'];
    }
    
    if (isset($action)) {
        $productid = $_GET['productid'];
        $result =  mysqli_query($link, "UPDATE products SET product_name='$productName', product_type='$productType', accountType='$accountType', product_configuration='$productConfig' WHERE product_id='$productid'") or die(mysqli_error($link));
        if ($result) {
            echo '<div class="alert alert-success" >
                    <a href = "#" class = "close" data-dismiss= "alert"> &times;</a>
                    Successfully updated a product configuration !&nbsp; &nbsp;&nbsp;
                </div>';
        } else {
            echo '<div class="alert alert-danger" >
                     <a href = "#" class = "close" data-dismiss= "alert"> &times;</a>
                        Failed to update a product configation !&nbsp; &nbsp;&nbsp;
                    </div>';
        }
    }
    else{
        $saveProduct = mysqli_query($link, "insert into products values(0,'$productName','$productType','$accountType','$productConfig','')");
        $id = $_POST['id'];
        if ($id == "0") {
            echo '<div class="alert alert-success" ><a href = "#" class = "close" data-dismiss= "alert"> &times;</a>Default Loan Settings Successfully Configured!&nbsp; &nbsp;&nbsp;</div>';
        } else {
            echo '<div class="alert alert-success" ><a href = "#" class = "close" data-dismiss= "alert"> &times;</a>Default Loan Settings Successfully Updated!&nbsp; &nbsp;&nbsp;</div>';
        }
    }
}
$get = mysqli_query($link, "SELECT * FROM loan_settings order by id") or die (mysqli_error($link));

// checking if edit button is click
 if($action === "edit") {
     $productId = $_GET['productid'];
     $sqlquery = mysqli_query($link, "SELECT * FROM products WHERE product_id='$productId'") or die(mysqli_error($link));
     if (mysqli_num_rows($sqlquery)) {
         $productInfo = mysqli_fetch_assoc($sqlquery);
     }
 }
?>
<style>
    #createProduct {
        display: none;
    }
</style>

<div class="box">
    <div class="box-body">
        <div class="panel panel-success">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-cogs"></i> Products Settings</h3>
            </div>
            <div class="box-body">
                <div class="panel panel-default">
                    <div class="panel-body bg-gray-light text-bold"><i class="fa fa-money"></i> Products Configurations
                </div>
                    <div class="btn-group-horizontal">
                        <?php
                        if(!isset($_GET['action'])){ ?>
                            <button class="btn bg-gray margin" type="button" id="addProduct">Add Product</button>
                        <?php } ?>
                        <form <?php if(!isset($_GET['action'])){ ?> id="createProduct" <?php } ?> action="#" class="form-horizontal" method="post" enctype="multipart/form-data" onsubmit="return selectAll()" id="form">
                            <div class="box-body">
                                <?php
                                    if(isset($productInfo['product_configuration'])){
                                        $configuration  = json_decode($productInfo, true);
                                    }

                                $configuration = json_decode($productInfo['product_configuration'], false);
                                ?>
                                <div class="form-group">

                                    <label for="inputName" class="col-sm-3 control-label">Product Name</label>
                                    <div class="col-sm-5">
                                        <input type="text" name="product[productName]" class="form-control" id="inputName" placeholder="Product Name" value="<?php
                                            if(isset($productInfo['product_name'])){
                                                echo $productInfo['product_name'];
                                            }  ?>" required="">
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="inputName" control-label">For Regular?</label>
                                        <input type="checkbox" name="product[members]" id="" <?php
                                        if($configuration->members=="Yes"){
                                            echo "checked";
                                        }  ?> value="Yes">
                                        <!--<input type="radio" name="product[productType]" id="" value="Savings" required=""> Savings-->
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="inputName" class="col-sm-3 control-label">GL Code</label>
                                    <?php
                                    $gl = mysqli_query($link,"select * from gl_codes where portfolio ='LOAN PORTFOLIO'");
                                    $receivables = mysqli_query($link,"select * from gl_codes where portfolio ='RECEIVABLES'");
                                    $receivablesInterest = mysqli_query($link,"select * from gl_codes where portfolio ='RECEIVABLES'");


                                    ?>
                                    <div class="col-sm-5">
                                       <select name="product[gl_code]" class="form-control" required>
                                           <option value="" selected disabled>Select</option>
                                          <?php
                                          while($row = mysqli_fetch_assoc($gl)){?>
                                              <option value="<?php echo $row['code'] ?>" <?php
                                              if($configuration->gl_code==$row['code']){
                                                  echo "selected";
                                              }  ?>><?php echo $row['code'] ?>&nbsp;-&nbsp;<?php echo $row['name'] ?></option>
                                        <?php  }
                                          ?>
                                       </select>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label for="inputName" class="col-sm-3 control-label">Product Code</label>
                                    <div class="col-sm-5">
                                        <input type="text" name="product[productCode]" class="form-control" id="inputName" placeholder="Product Code" value="<?php
                                                if(isset($configuration->productCode)){
                                                    echo $configuration->productCode;
                                                }
                                                ?>" required="">
                                    </div>
                                </div>
                                <div  style="display: block" id="typeLoan"><!--Change here to enable when multiple product options are available -->
                                <div class="form-group">
                                    <label for="" class="col-sm-3 control-label">Compuscan/Bureau Equivalent</label>
                                    <div class="col-sm-5">
                                        <select class="form-control" name="product[accountType]" id="loanProduct" required >
                                            <option value="" disabled selected>--Select--</option>
                                            <option value="T" <?php if($configuration->accountType === "T"){echo "selected"; }?>>Student Loan</option>
                                            <option value="P" <?php if($configuration->accountType === "P"){echo "selected"; }?>>Personal loan</option>
                                            <option value="H" <?php if($configuration->accountType === "H"){echo "selected"; }?>>Home loan</option>
                                            <option value="B" <?php if($configuration->accountType === "B"){echo "selected"; }?>>Building Loan</option>
                                            <option value="M" <?php if($configuration->accountType === "M"){echo "selected"; }?>>One Month Personal Loan</option>
                                            <option value ="Y" <?php if($configuration->accountType === "Y"){echo "selected"; }?>>Vehicle Asset Finance</option>
                                            <option value="Z" <?php if($configuration->accountType === "Y"){echo "selected"; }?>>Revolving Non Store Card</option>
                                        </select>

                                    </div>
                                </div>
                                    <div class="form-group">

                                    <label for="" class="col-sm-3 control-label">Affordability Provider</label>
                                    <div class="col-sm-5">
                                        <select class="form-control" name="product[affordabilityProvider]" id="loanProduct">
                                        <option value="" disabled selected>--Select--</option>
                                        <?php
                                        //Get Providers
                                        $providers=mysqli_query($link,"select * from affordability_check");
                                        while($row=mysqli_fetch_assoc($providers)) {
                                        ?>
                                            <option value="<?php echo $row['provider']; ?>" ><?php echo $row['provider']; ?></option>
                                        <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-5 margin">
                                        <label class="text-bold text-blue">
                                            <h4><input type="checkbox" name="loan_enable_parameters" value="Show" class="show_hide"> <b>Enable Below Parameters</b></h4>
                                        </label>
                                    </div>
                                </div>

                                <div class="panel panel-default"><div class="panel-body bg-gray text-bold">Advance Settings (optional):</div></div>


                                <div class="slidingDiv">
                                    <p class="text-red margin"><b>Principal Amount:</b></p>

                                    <div class="form-group">
                                        <label for="inputDisbursedById" class="col-sm-3 control-label">Disbursed By</label>
                                        <div class="col-sm-5">
                                            <input class="inputDisbursedById" type="checkbox" name="loan_disbursed_by_id[]" value="Cheque"
                                                <?php
                                                if (isset($configuration->disbursmentMethods[0])){
                                                   echo "checked = 'checked'";
                                                }
                                                ?>
                                            > Cheque
                                            <input class="inputDisbursedById" type="checkbox" name="loan_disbursed_by_id[]" value="Mobile Money"
                                                <?php
                                                if (isset($configuration->disbursmentMethods[1])){
                                                    echo "checked = 'checked'";
                                                }
                                                ?>
                                            > Mobile Money
                                            <input class="inputDisbursedById" type="checkbox" name="loan_disbursed_by_id[]" value="Online Transfer"
                                                   <?php
                                                       if (isset($configuration->disbursmentMethods[2])){
                                                             echo "checked = 'checked'";
                                                        }
                                                    ?>
                                            > Online Transfer
                                         </div>
                                    </div>

                                    <div class="form-group">

                                        <label for="inputMinLoanPrincipalAmount" class="col-sm-3 control-label">Minimum Principal Amount</label>
                                        <div class="col-sm-5">
                                            <input type="number" min="0" name="product[minLoanPrincipalAmount]" class="form-control decimal-2-places" id="inputMinLoanPrincipalAmount" placeholder="Minimum Amount" value="<?php if(isset($configuration->minLoanPrincipalAmount))  {echo $configuration->minLoanPrincipalAmount;} ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">

                                        <label for="inputMaxLoanPrincipalAmount" class="col-sm-3 control-label">Maximum Principal Amount</label>
                                        <div class="col-sm-5">
                                            <input type="number" min="0" name="product[maxLoanPrincipalAmount]" class="form-control decimal-2-places" id="inputMaxLoanPrincipalAmount" placeholder="Maximum Amount" value="<?php if(isset($configuration->maxLoanPrincipalAmount))  {echo $configuration->maxLoanPrincipalAmount;} ?>">
                                        </div>
                                    </div>
                                    <hr>
                                    <p class="text-red margin"><b>Interest:</b></p>
                                    <div class="form-group">
                                        <label for="inputLoanInterestMethod" class="col-sm-3 control-label">Interest Method</label>
                                        <div class="col-sm-5">
                                            <select class="form-control" name="product[loanInterestMethod]" id="inputLoanInterestMethod" onchange="enableDisableInterestTypes();">
                                                <option value="FLAT_RATE" <?php if($configuration->loanInterestMethod === "FLAT_RATE"){echo "selected"; } ?>> Flat Rate</option>
                                                <option value="REDUCING_RATE_EQUAL_INSTALLMENTS" <?php if($configuration->loanInterestMethod === "REDUCING_RATE_EQUAL_INSTALLMENTS"){echo "selected"; } ?>>Reducing Balance - Equal Installments</option>
                                                <option value="REDUCING_RATE_EQUAL_PRINCIPAL" <?php if($configuration->loanInterestMethod === "REDUCING_RATE_EQUAL_PRINCIPAL"){echo "selected"; } ?>>Reducing Balance - Equal Principal</option>
                                                <option value="INTEREST_ONLY" <?php if($configuration->loanInterestMethod === "INTEREST_ONLY"){echo "selected"; } ?>>Interest-Only</option>
                                                <option value="COMPOUND_INTEREST" <?php if($configuration->loanInterestMethod === "COMPOUND_INTEREST"){echo "selected"; } ?>>Compound Interest</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputLoanInterestType" class="col-sm-3 control-label">Interest Type</label>
                                        <div class="col-sm-5">
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="product[interestType]" id="inputInterestTypePercentage" value="percentage"
                                                           checked="<?php
                                                                if(isset($configuration->interestType)){
                                                                    echo "checked";
                                                                }
                                                            ?>">Interest to be percentage % based

                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputLoanInterestPeriod" id="inputLoanInterestLabel" class="col-sm-3 control-label">Loan Interest Period</label>
                                        <div class="col-sm-5">
                                            <select class="form-control" name="product[interestPeriod]" id="inputLoanInterestPeriod" onchange="setInterestPeriod();">
                                                <option value=""></option>
                                                <option value="01">Per Week</option>
                                                <option value="03" selected>Per Month</option>
                                                <option value="02">Per Fortnight</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputDefaultLoanInterest" id="inputDefaultLoanInterestLabel" class="col-sm-3 control-label">Default Loan Interest</label>
                                        <div class="col-sm-2">
                                            <input type="number" min="0" step="0.01" name="product[defaultLoanInterest]" class="form-control decimal-4-places" id="inputDefaultLoanInterest" placeholder="" value="<?php if(isset($configuration->defaultLoanInterest)){ echo $configuration->defaultLoanInterest; } ?>">
                                        </div>
                                        <div class="col-sm-3">
                                            <select class="form-control" name="product[interestGlCode]" id="interestGlCode"">
                                                <option value="" selected disabled>select GL Code</option>
                                                <?php while($row=mysqli_fetch_assoc($receivablesInterest)){ ?>
                                                    <option value="<?php echo $row['code']; ?>" <?php if($row['code']=="12003"){ echo "selected";} ?>><?php echo $row['code'] ?>&nbsp;-&nbsp;<?php echo $row['name']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <hr>
                                    <p class="text-red margin"><b>Duration:</b></p>
                                    <div class="form-group">

                                        <label for="inputLoanDurationPeriod" class="col-sm-3 control-label">Loan Duration Period</label>
                                        <div class="col-sm-5">
                                            <select class="form-control" name="product[loanDurationPeriod]" id="inputLoanDurationPeriod" onchange="setLoanDurationPeriod()">
                                                <option value="01" <?php if($configuration->loanDurationPeriod == "01"){echo "selected"; }  ?>>Weeks</option>
                                                <option selected value="03" <?php if($configuration->loanDurationPeriod == "03"){echo "selected"; }  ?>>Months</option>
                                                <option value="06" <?php if($configuration->loanDurationPeriod == "06"){echo "selected"; }  ?>>Years</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">

                                        <label for="inputMinLoanDuration" class="col-sm-3 control-label">Minimum Loan Duration</label>

                                        <div class="col-sm-2">
                                            <select class="form-control" name="product[minLoanDuration]" id="inputMinLoanDuration">
                                                <option value="">Any</option>
                                                <?php
                                                    if(isset($configuration->minLoanDuration)){
                                                        $day = $configuration->minLoanDuration;
                                                    }
                                                foreach (range(1, 360, 1) as $number) { ?> +
                                                    <option <?php if ($day == $number) {
                                                        echo "selected";

                                                    } ?> value="<?php echo $number ?>"><?php echo $number ?></option> +
                                                <?php } ?>
                                            </select>

                                        </div>
                                        <div class="col-sm-3">
                                            <div id="inputMinLoanDurationPeriod" class="margin">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">

                                        <label for="inputDefaultLoanDuration" class="col-sm-3 control-label">Default Loan Duration</label>
                                        <div class="col-sm-2">
                                            <select class="form-control" name="product[defaultLoanDuration]" id="inputDefaultLoanDuration">
                                                <option value="">Any</option>
                                                <?php
                                                if(isset($configuration->defaultLoanDuration)){
                                                    $day = $configuration->defaultLoanDuration;
                                                }
                                                foreach (range(1, 360, 1) as $number) { ?> +
                                                    <option <?php if ($day == $number) {
                                                        echo "selected";
                                                    } ?> value="<?php echo $number ?>"><?php echo $number ?></option> +
                                                <?php } ?>
                                            </select>

                                        </div>
                                        <div class="col-sm-3">
                                            <div id="inputDefaultLoanDurationPeriod" class="margin">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">

                                        <label for="inputMaxLoanDuration" class="col-sm-3 control-label">Maximum Loan Duration</label>
                                        <div class="col-sm-2">
                                            <select class="form-control" name="product[maxLoanDuration]" id="inputMaxLoanDuration" onchange="setMaxLoanDurationPeriod()">
                                                <option value="">Any</option>
                                                <?php
                                                if(isset($configuration->maxLoanDuration)){
                                                    $day = $configuration->maxLoanDuration;
                                                }
                                                foreach (range(1, 360, 1) as $number) { ?> +
                                                    <option <?php if ($day == $number) {
                                                        echo "selected";
                                                    } ?> value="<?php echo $number ?>"><?php echo $number ?></option> +
                                                <?php } ?>
                                            </select>

                                        </div>
                                        <div class="col-sm-3">
                                            <div id="inputMaxLoanDurationPeriod" class="margin">

                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <p class="text-red margin"><b>Repayments:</b></p>
                                    <div class="form-group">
                                        <label for="inputLoanPaymentSchemeId" class="col-sm-3 control-label">Repayment Cycle</label>
                                        <div class="col-sm-3">
                                                    <input class="classLoanPaymentSchemeId" type="checkbox" name="loan_payment_scheme_id[]" value="Monthly"
                                                        <?php
                                                         if($configuration->repaymentMethods[0]){
                                                            echo  "checked='checked'";
                                                         }
                                                        ?>
                                                    > Monthly
                                                    <input class="classLoanPaymentSchemeId" type="checkbox" name="loan_payment_scheme_id[]" value="Lump-Sum"
                                                        <?php
                                                            if($configuration->repaymentMethods[1]){
                                                                echo  "checked='checked'";
                                                            }
                                                        ?>
                                                    > Lump-Sum
                                            </div>
                                        </div>
                                    </div>

                                    <hr>
                                    <p class="text-red margin"><b>Fees (Fixed Charges):</b>  <a class="btn btn-primary btn-xs pull-right" data-toggle="collapse" data-target="#loan_fee_schedule">Help</a></p>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <div id="loan_fee_schedule" class="collapse panel panel-default">
                                                <div class="panel-heading">There are 2 types of Loan Fees:</div>
                                                <div class="panel-body">
                                                    <ul>
                                                        <li><strong>Non Deductable Fees</strong>
                                                            <br>The loan fee will be added to the total loan due (Principal Amount + Interest + Penalty) and the borrower will have to pay back this fee. You will see a <u>schedule box</u> next to <strong>Non Deductable Fees</strong> on how the fees should be shown in the loan schedule. You can select from:<br>

                                                            <ul>
                                                                <li><strong>Don't include in the loan schedule</strong>
                                                                    <br>The fee will not be included in the loan schedule. You will have to manually edit the loan schedule and add the fee.
                                                                    <br>
                                                                    <br>
                                                                </li>
                                                                <li><strong>Distribute Fee Evenly Among All Repayments</strong>
                                                                    <br>For example, if the fee entered is 1,000 and there are 10 due repayments in the loan schedule, each repayment will have 100 fee added to it (1,000/10).
                                                                    <br>
                                                                    <br>
                                                                </li>
                                                                <li><strong>Charge Fee on the Released Date<br></strong>For example, if the fee entered is 1,000, a due repayment of 1,000 will be added in the loan schedule on the loan released date.
                                                                    <br>
                                                                    <br>
                                                                </li>
                                                                <li><strong>Charge Fee on the First Repayment<br></strong>For example, if the fee entered is 1,000, the first repayment in the loan schedule will have 1,000 fee added to it.
                                                                    <br>
                                                                    <br>
                                                                </li>
                                                                <li><strong>Charge Fee on the Last Repayment<br></strong>For example, if the fee entered is 1,000, the last repayment in the loan schedule will have 1,000 fee added to it.
                                                                    <br>
                                                                    <br>
                                                                </li>
                                                                <li><strong>Charge Same Fee on All Repayments<br></strong>For example, if the fee entered is 1,000 and there are 10 due repayments in the loan schedule, each repayment will have 1,000 fee added to it. Hence the total fee will be 10,000 (1,000 x 10).
                                                                    <br>
                                                                    <br>
                                                                </li>
                                                                <li><strong>Used for Lump-Sum Loans<br></strong>This option can only be used if you have selected <b>Lump-Sum</b> in <b>Repayment Cycle</b>. Let's say you have a lump-sum loan for 3 months duration. So in the <b>Loan Duration</b> field, you will select 3 months. The loan will only have 1 repayment cycle at end of 3 months but you want the fee to be calculated for each duration and then the total should be added at end of 3 months. So if the fee is 1000, a total of 3000(1000 x 3 months) will be added at end of 3 months with this option.
                                                                    <br>
                                                                    <br>
                                                                </li>
                                                                <li>
                                                                    If you have selected <b>percentage</b> based fees, you will see the following option:<br><br>
                                                                    <b>Useful for Tax Calculations:</b><br>
                                                                    You can charge fees for each repayment based on the amount due in that repayment. Let's assume that $100 Principal is due on first installment and $50 Principal is due on second installment and fee is 10%. In this case, $10 (10% of $100) will be added for first installment and $5 (10% of $50)  will be added for second installment.<br>
                                                                    <ul>
                                                                        <li>Charge Fee Each Repayment on the Due Principal Amount</li>
                                                                        <li>Charge Fee Each Repayment on the Due Interest Amount</li>
                                                                        <li>Charge Fee Each Repayment on the Due Principal and Interest Amount</li>
                                                                    </ul>
                                                                    <br>
                                                                </li>
                                                            </ul>
                                                        </li>
                                                        <li><strong>Deductable Fees</strong>

                                                            <ul>
                                                                <li>The loan fee will <u><strong>NOT</strong></u> be added to the loan due. It is assumed that the borrower has already paid back this fee. Many cooperative lending companies use deducable fees.</li>
                                                                <li>For example, if you give a loan for $1000 and the deductable fee is $10. Then the actual principal amount that is given to borrower would be $1000 - $10 = <u>$990</u>. But the borrower still has to pay back <u>$1000</u>. So even though you only gave $990 to the borrower, he/she has to pay back $1000.</li>
                                                            </ul>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <div class="box-body">

                                                <div class="table-responsive" data-pattern="priority-columns">

                                                    <table cellspacing="0" id="loan-fees"
                                                           class="table table-small-font table-bordered table-striped">

                                                        <thead>
                                                        <tr>
                                                            <td width="2%" align="center"><input id="checkAll_loanFees" class="formcontrol"
                                                                                                 type="checkbox"></td>
                                                            <td align="center"><strong>Description</strong></td>
                                                            <td align="center"><strong>Minimum Loan</strong></td>
                                                            <td align="center"><strong>Maximum Loan</strong></td>
                                                            <td align="center"><strong>Fee</strong></td>
                                                            <td style="width: 18%;" align="center"><strong>Charge Term</strong></td>
                                                            <td align="center"><strong>Deductible?</strong>
                                                            </td>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                                $count = 0;
                                                                $length = count($configuration->fixedFees);
                                                                if(isset($configuration->fixedFees)){
                                                                    while($count < $length){
                                                                ?>

                                                                    <tr>
                                                                        <input type="hidden" name="loanFees['<?php echo $count ?>'][id]" value="" hidden>
                                                                        <td>
                                                                            <input name="itemRow_loanFees"  class='itemRow_loanFees' type="checkbox">
                                                                        </td>
                                                                        <td>
                                                                            <input type="text"
                                                                            name="loanFees['<?php echo $count?>']['fee_name']"
                                                                            id="fee_name_<?php echo $count?>"
                                                                            class="form-control"
                                                                            autocomplete="off"
                                                                            value="<?php
                                                                                if(isset($configuration->fixedFees[$count]->feeName)){
                                                                                    echo $configuration->fixedFees[$count]->feeName;
                                                                                }?>"
                                                                            required>
                                                                        </td>
                                                                        <td>
                                                                            <input type="number"
                                                                            name="loanFees[<?php echo $count?>][min_loan]"
                                                                            id="min_loan_<?php echo $count?>"
                                                                            min="0>"
                                                                            class="form-control"
                                                                            autocomplete="off"
                                                                            value="<?php
                                                                                    if(isset($configuration->fixedFees[$count]->minLoan)){
                                                                                        echo $configuration->fixedFees[$count]->minLoan;
                                                                                    }?>"
                                                                            required/>
                                                                        </td>
                                                                        <td>
                                                                            <input type="number"
                                                                            name="loanFees[<?php echo $count?>][max_loan]"
                                                                            id="max_loan_<?php echo $count?>"
                                                                            min="0>"
                                                                            class="form-control"
                                                                            autocomplete="off"
                                                                            value="<?php
                                                                                   if(isset($configuration->fixedFees[$count]->maxLoan)){
                                                                                       echo $configuration->fixedFees[$count]->maxLoan;
                                                                                   }
                                                                                   ?>"
                                                                            required />
                                                                        </td>
                                                                        <td>
                                                                            <input type="number"
                                                                            name="loanFees[<?php echo $count?>][fee_amount]"
                                                                            id="fee_amount_<?php echo $count?>"
                                                                            min="0"
                                                                            class="form-control"
                                                                            autocomplete="off"
                                                                            value="<?php
                                                                                   if(isset($configuration->fixedFees[$count]->feeAmount)){
                                                                                       echo $configuration->fixedFees[$count]->feeAmount;
                                                                                   }?>"
                                                                            required/>
                                                                        </td>
                                                                        <td align="center"><select class="form-control" name="loanAdditionalFees[<?php echo $count?>][charge_term]">
                                                                                <option value="">--Select--</option>
                                                                                <option value="FREQUENCY" <?php if($configuration->chargeTerm == "FREQUENCY") {echo "selected";} ?>>Repayment Frequency</option>
                                                                                <option value="PRINCIPAL" <?php if($configuration->chargeTerm == "PRINCIPAL") {echo "selected";} ?>>Principal</option>
                                                                                <option value="PRINCIPAL_INTEREST" <?php if($configuration->chargeTerm == "PRINCIPAL_INTEREST") {echo "selected";} ?>>Principal + Interest Due</option>
                                                                                <option value="PRINCIPAL_DUE_INTEREST_DUE" <?php if($configuration->chargeTerm == "PRINCIPAL_DUE_INTEREST_DUE") {echo "selected";} ?>>Principal Due + Interest Due</option>
                                                                                <option value="PRINCIPAL_DUE_INTEREST_DUE_FEES_DUE" <?php if($configuration->chargeTerm == "PRINCIPAL_DUE_INTEREST_DUE_FEES_DUE") {echo "selected";} ?>>Principal Due + Fees Due + Interest Due</option>
                                                                            </select>
                                                                        </td>
                                                                        <td align="center">
                                                                            <input type="checkbox"
                                                                            name="loanFees[<?php echo $count?>][deductible]"
                                                                            data-value="<?php echo $count?>"
                                                                            id="deductible<?php echo $count?>"
                                                                            class="iswitch iswitch-md iswitch-primary toggle-event"
                                                                                <?php
                                                                                    if($configuration->fixedFees[$count]->deductible === true){
                                                                                        echo "checked";
                                                                                    }
                                                                                ?>
                                                                            >

                                                                        </td>
                                                                    </tr>

                                                               <?php $count += 1;
                                                                    }
                                                                }
                                                                ?>
                                                        </tbody>


                                                    </table>
                                                </div>
                                            </div>
                                            <div align="Left">
                                                <button id="addRows_loanFees" type="button" class="btn btn-success btn-flat"><i
                                                            class="fa fa-plus">&nbsp;Add Fee</i>
                                                </button>
                                                <button id="removeRows_loanFees" type="button" class="btn btn-danger btn-flat"><i
                                                            class="fa fa-trash">&nbsp;Remove Fee</i>
                                                </button>


                                            </div>
                                        </div>
                                    </div>

                                    <!--                                   <p class="text-red margin"><b>Extend Loan After Maturity Until Fully Paid:</b></p>

                                                                     <div class="well">If you select <b>Yes</b> below, the system will automatically add interest after the maturity date if the loan is not fully paid.</div>

                                                                       <div class="form-group">
                                                                           <label form="" class="col-sm-3 control-label">Extend Loan After Maturity</label>
                                                                           <div class="col-sm-5">
                                                                               <div class="radio">
                                                                                   <label>
                                                                                       <input type="radio" name="after_maturity_extend_loan" value="0" id="inputExtendLoanNo" checked="">No
                                                                                   </label>
                                                                                   &nbsp;&nbsp;&nbsp;&nbsp;
                                                                                   <label>
                                                                                       <input type="radio" name="after_maturity_extend_loan" value="1" id="inputExtendLoanYes">Yes
                                                                                   </label>
                                                                               </div>
                                                                           </div>
                                                                       </div>

                                                                       <div class="form-group">
                                                                           <label form="" class="col-sm-3 control-label">Interest Type</label>
                                                                           <div class="col-sm-7">
                                                                               <div class="radio">
                                                                                   <label>
                                                                                       <input type="radio" name="after_maturity_percentage_or_fixed" id="inputAmPercentage" value="percentage" checked="">
                                                                                       I want Interest to be percentage % based
                                                                                   </label>
                                                                               </div>
                                                                               <div class="radio">
                                                                                   <label>
                                                                                       <input type="radio" name="after_maturity_percentage_or_fixed" id="inputAmFixed" value="fixed"> I want Interest to be a fixed amount

                                                                                   </label>
                                                                               </div>
                                                                           </div>
                                                                       </div>
                                                                       <div class="form-group">
                                                                           <label form="" id="inputAMCalculateInterestOnLabel" class="col-sm-3 control-label">Calculate Interest on</label>
                                                                           <div class="col-sm-5">
                                                                               <select class="form-control" name="after_maturity_calculate_interest_on" id="inputAmCalculateInterestOn">

                                                                                   <option value="0" selected=""></option>
                                                                                   <option value="1">Overdue Principal Amount</option>
                                                                                   <option value="2">Overdue (Principal + Interest) Amount</option>
                                                                                   <option value="3">Overdue (Principal + Interest + Fees) Amount</option>
                                                                                   <option value="4">Overdue (Principal + Interest + Fees + Penalty) Amount</option>
                                                                                   <option value="5">Total Principal Amount Released</option>
                                                                               </select>
                                                                           </div>
                                                                       </div>
                                                                       <div class="form-group">
                                                                           <label form="inputAmInterest" id="inputAMInterestOrFixedLabel" class="col-sm-3 control-label">Loan Interest Rate After Maturity %</label>
                                                                           <div class="col-sm-5">
                                                                               <input type="text" class="form-control decimal-4-places" name="after_maturity_loan_interest" id="inputAmInterest" placeholder="Numbers or decimal only" value="">
                                                                           </div>
                                                                       </div>
                                                                       <div class="form-group">
                                                                           <label form="inputAmRecurringPeriod" class="col-sm-3 control-label">Recurring Period  After Maturity</label>
                                                                           <div class="col-sm-2">
                                                                               <div class="input-group bootstrap-touchspin"><span class="input-group-btn"><button class="btn btn-default bootstrap-touchspin-down" type="button">-</button></span><span class="input-group-addon bootstrap-touchspin-prefix" style="display: none;"></span><input type="text" class="form-control" name="after_maturity_recurring_period_num" id="inputAmRecurringPeriod" value="" style="display: block;"><span class="input-group-addon bootstrap-touchspin-postfix" style="display: none;"></span><span class="input-group-btn"><button class="btn btn-default bootstrap-touchspin-up" type="button">+</button></span></div>
                                                                           </div>
                                                                           <div class="col-sm-2">
                                                                               <select class="form-control" name="after_maturity_recurring_period_payment_scheme_id" id="inputAmLoanPaymentSchemeId">
                                                                                   <option value=""></option>
                                                                                   <option value="6">Daily</option>
                                                                                   <option value="4">Weekly</option>
                                                                                   <option value="9">Biweekly</option>
                                                                                   <option value="3">Monthly</option>
                                                                                   <option value="12">Bimonthly</option>
                                                                                   <option value="13">Quarterly</option>
                                                                                   <option value="781">Every 4 Months</option>
                                                                                   <option value="14">Semi-Annual</option>
                                                                                   <option value="11">Yearly</option>
                                                                                   <option value="10">Lump-Sum</option>
                                                                               </select>
                                                                           </div>
                                                                       </div>

                                                                       <div class="form-group">
                                                                           <label form="" class="col-sm-3 control-label">Include Fees After Maturity</label>

                                                                           <div class="col-sm-9">
                                                                               <div class="radio">
                                                                                   <label>
                                                                                       <input type="radio" name="after_maturity_include_fees" value="0" checked="">No
                                                                                   </label>&nbsp;&nbsp;&nbsp;&nbsp;
                                                                                   <label>
                                                                                       <input type="radio" name="after_maturity_include_fees" value="1">Yes
                                                                                   </label>
                                                                               </div>
                                                                               Only Loan Fees that are selected as <b>Charge Same Fee on All Repayments (fixed)</b> or  <b>Charge Fee Each Repayment on the Due ... Amount</b> will be added.
                                                                           </div>
                                                                       </div>-->
                                    <hr>
                                    <div class="panel panel-default">
                                        <div class="panel-body bg-gray-light text-bold"><i class="fa fa-money"></i>&nbsp;Percentage Fees for this Product:
                                            <a href="#loan-additional-fees" class="show_hide_loan_setttings">Hide</a></div>
                                    </div>

                                    <div class="slidingLoanOptions" style="display: block;">


                                        <div class="box-body">

                                            <div class="table-responsive" data-pattern="priority-columns">

                                                <table cellspacing="0" id="loan-additional-fees"
                                                       class="table table-small-font table-bordered table-striped">

                                                    <thead>
                                                    <tr>
                                                        <td width="2%" align="center"><input id="checkAll_loanAdditionalFees"
                                                                                             class="formcontrol"
                                                                                             type="checkbox"></td>
                                                        <td align="center"><strong>Description</strong></td>
                                                        <td align="center"><strong>GL Code</strong></td>
                                                        <td align="center"><strong>Percentage</strong></td>
                                                        <td align="center"><strong>Min Fee</strong></td>
                                                        <td align="center"><strong>Max Fee</strong></td>
                                                        <td align="center"><strong>Charge Term</strong></td>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                            $count = 0;
                                                            $length = count($configuration->productPercentageFees);
                                                                if(isset($configuration->productPercentageFees)){
                                                                    while($count < $length){
                                                                ?>
                                                                        <tr>
                                                                            <input type="hidden" name="loanAdditionalFees[<?php echo $count ?>][id]" value="">
                                                                            <td>
                                                                                <input class="itemRow_loanAdditionalFees" type="checkbox">
                                                                            </td>
                                                                            <td>
                                                                                <input type="text"
                                                                                       name="loanAdditionalFees[<?php echo $count ?>][fee_description]"
                                                                                       id="fee_description_<?php echo $count ?>"
                                                                                       class="form-control"
                                                                                       autocomplete="off"
                                                                                       value="<?php if(isset($configuration->productPercentageFees[$count]->feeDescription)) { echo $configuration->productPercentageFees[$count]->feeDescription; }  ?>"
                                                                                       required/>
                                                                            </td>
                                                                            <td>
                                                                                <select name="loanAdditionalFees[<?php echo $count ?>][gl_code]" class="form-control">
                                                                                    <option value="" selected disabled>Select</option>
                                                                                    <?php
                                                                                    $receivables = mysqli_query($link,"select * from gl_codes where portfolio ='RECEIVABLES'");
                                                                                    while($row=mysqli_fetch_assoc($receivables)){ ?>
                                                                                    <option value="<?php echo $row['code']; ?>" <?php if($configuration->productPercentageFees[$count]->glCode==$row['code']) echo "selected"; ?>><?php echo $row['code'] ?>&nbsp;-&nbsp;<?php echo $row['name']; ?></option>
                                                                                    <?php } ?>
                                                                                </select>
                                                                            </td>
                                                                            <td>
                                                                                <input
                                                                                        type="number"
                                                                                        name="loanAdditionalFees[<?php echo $count ?>][percentage]"
                                                                                        id="percentage_<?php echo $count ?>"
                                                                                        min="0"
                                                                                        step="0.01"
                                                                                        class="form-control"
                                                                                        autocomplete="off"
                                                                                        value="<?php if(isset($configuration->productPercentageFees[$count]->percentage)) { echo $configuration->productPercentageFees[$count]->percentage; }  ?>"
                                                                                        required/>
                                                                            </td>
                                                                            <td>
                                                                                <input type="number"
                                                                                       name="loanAdditionalFees[<?php echo $count ?>][min_fixed_amount]"
                                                                                       id="min_fixed_amount_<?php echo $count ?>"
                                                                                       min="0"
                                                                                       step="0.01"
                                                                                       class="form-control"
                                                                                       autocomplete="off"
                                                                                       value="<?php if(isset($configuration->productPercentageFees[$count]->minFixedAmount)) { echo $configuration->productPercentageFees[$count]->minFixedAmount; }  ?>"
                                                                                />
                                                                            </td>
                                                                            <td>
                                                                                <input type="number"
                                                                                       name="loanAdditionalFees[<?php echo $count ?>][max_fixed_amount]"
                                                                                       id="max_fixed_amount_<?php echo $count ?>"
                                                                                       min="0"
                                                                                       step="0.01"
                                                                                       class="form-control"
                                                                                       autocomplete="off"
                                                                                       value="<?php if(isset($configuration->productPercentageFees[$count]->maxFixedAmount)) { echo $configuration->productPercentageFees[$count]->maxFixedAmount; }  ?>"
                                                                                />
                                                                            </td>
                          <!--                                                  <td>
                                                                                <input type="number"
                                                                                       name="loanAdditionalFees[<?php /*echo $count */?>][fromAge]"
                                                                                       id="max_fixed_amount_<?php /*echo $count */?>"
                                                                                       min="<?php /*echo $count */?>"
                                                                                       step="0.01"
                                                                                       class="form-control"
                                                                                       autocomplete="off"
                                                                                       value="<?php /*if(isset($configuration->productPercentageFees[$count]->fromAge)) { echo $configuration->productPercentageFees[$count]->fromAge; }  */?>"
                                                                                />
                                                                            </td>-->
                                                                            <td align="center">
                                                                                <select class="form-control" name="loanAdditionalFees[<?php echo $count ?>][charge_term]">
                                                                                    <option value="">--Select--</option>
                                                                                    <option value="FREQUENCY" <?php if($configuration->productPercentageFees[$count]->chargeTerm == "FREQUENCY") {echo "selected";} ?>>Repayment Frequency</option>
                                                                                    <option value="PRINCIPAL" <?php if($configuration->productPercentageFees[$count]->chargeTerm == "PRINCIPAL") {echo "selected";} ?>>Principal</option>
                                                                                    <option value="PRINCIPAL_INTEREST" <?php if($configuration->productPercentageFees[$count]->chargeTerm == "PRINCIPAL_INTEREST") {echo "selected";} ?>>Principal + Interest Due</option>
                                                                                    <option value="PRINCIPAL_DUE_INTEREST_DUE" <?php if($configuration->productPercentageFees[$count]->chargeTerm == "PRINCIPAL_DUE_INTEREST_DUE") {echo "selected";} ?>>Principal Due + Interest Due</option>
                                                                                    <option value="PRINCIPAL_DUE_INTEREST_DUE_FEES_DUE" <?php if($configuration->productPercentageFees[$count]->chargeTerm == "PRINCIPAL_DUE_INTEREST_DUE_FEES_DUE") {echo "selected";} ?>>Principal Due + Fees Due + Interest Due</option>
                                                                                </select>
                                                                            </td></tr>

                                                        <?php   $count += 1;
                                                                    }
                                                                }
                                                        ?>
                                                    </tbody>
                                                </table>

                                            </div>
                                        </div>


                                        <div align="Left">
                                            <button id="addRows_loanAdditionalFees" type="button" class="btn btn-success btn-flat"><i
                                                        class="fa fa-plus">&nbsp;Add Fee</i>
                                            </button>
                                            <button id="removeRows_loanAdditionalFees" type="button" class="btn btn-danger btn-flat"><i
                                                        class="fa fa-trash">&nbsp;Remove Fee</i>
                                            </button>


                                        </div>


                                        <hr>
                                    </div>
                                    <div class="panel panel-default">
                                        <div class="panel-body bg-gray-light text-bold"><i class="fa fa-user"></i>
                                            Documents Required for this product: <a href="#"
                                                                            class="show_hide_document_settings">&nbsp;Hide</a>
                                        </div>
                                    </div>
                                    <div class="slidingDivDocumentSettings" style="display: block;">

                                        <div class="box-body">

                                            <div class="table-responsive" data-pattern="priority-columns">

                                                <table width="50%" cellspacing="0" id="documents"
                                                       class="table table-small-font table-bordered table-striped">

                                                    <thead>
                                                    <tr>
                                                        <th width="2%"><input id="checkAll_documenet"
                                                                              class="formcontrol"
                                                                              type="checkbox">
                                                        </th>
                                                        <th width="20%">Document Name</th>
                                                        <th width="20%">Must Have?</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                        $count = 0;
                                                        $length = count($configuration->requiredDocuments);
                                                        if(isset($configuration->requiredDocuments)){
                                                            while($count < $length){
                                                        ?>
                                                        <tr>
                                                            <td>
                                                                <input class="itemRow_document" 
                                                                       type="checkbox" 
                                                                       name="selector[]" 
                                                                       value="<?php $count;?>">
                                                            </td>
                                                            <td>
                                                                <input type="text" 
                                                                placeholder="Document Name" 
                                                                name="document[<?php echo $count;?>][name]"
                                                                id="Name0" 
                                                                class="form-control" 
                                                                autocomplete="off" 
                                                                required
                                                                value="<?php if(isset($configuration->requiredDocuments[$count]->documentType)){ echo $configuration->requiredDocuments[$count]->documentType; } ?>" />
                                                            </td>
                                                            
                                                            <td>
                                                                <input type="checkbox"
                                                                name="document[<?php echo $count;?>][required]"
                                                                <?php 
                                                                    if($configuration->requiredDocuments[$count]->required === true){
                                                                        echo "checked='checked'";
                                                                    }
                                                                    ?>
                                                                >

                                                            </td>
                                                        </tr>
                                                    <?php $count += 1;
                                                        }

                                                    }
                                                    ?>
                                                    </tbody>

                                                </table>
                                            </div>
                                        </div>
                                        <div align="left">
                                            <button id="addRows_document" type="button"
                                                    class="btn btn-success"><i class="fa fa-plus">&nbsp;Add</i></button>
                                            <button name="delrowDocuments" id="removeRows_document" type="button" class="btn btn-danger">
                                                <i
                                                        class="fa fa-trash">&nbsp;Delete Record</i></button>

                                        </div>


                                    </div>
                                    <hr>
                                    <div class="form-group">
                                        <label for="" class="col-sm-3 control-label">Require Collateral on Loan?</label>
                                        <div class="col-sm-6">
                                            <input type="radio" name="product[Collateral]" id="No" onclick="ShowHideDiv()"
                                                   value="No" <?php if ($rows['collateral'] === "No"  || $configuration->Collateral === "No") {
                                                echo "checked";
                                            } ?> checked required/> No
                                            <input type="radio" name="product[Collateral]" id="Yes" onclick="ShowHideDiv()"
                                                   value="Yes" <?php if ($rows['collateral'] === "Yes"  || $configuration->Collateral === "No") {
                                                echo "checked";
                                            } ?>/> Yes


                                        </div>
                                    </div>
                                    <div class="form-group" style="<?php if ($rows['collateral'] === "Yes") {
                                        echo "display: block;";
                                    } else {
                                        echo "display: none;";
                                    } ?>" id="textboxes">
                                        <label for="" class="col-sm-3 control-label">Minimum Loan for Collateral?</label>
                                        <div class="col-sm-6">
                                            <input type="number" min="0" class="form-control"
                                                   placeholder="Minimum Loan to require collateral"
                                                   value="<?php echo $rows['minimum_loan_collateral']; ?>"
                                                   name="product[minLoanCollateral]"/>
                                        </div>
                                    </div>
                                    <p class="text-red margin"><b>Branches:</b></p>
                                    <div class="form-group">

                                        <label for="inputBranch" class="col-sm-3 control-label">Access to Branch</label>
                                        <div class="col-sm-5">
                                            <div class="checkbox">
                                                <label>
                                                    <?php
                                                    $get_branches=mysqli_query($link,"select * from branches");
                                                    while($branch=mysqli_fetch_assoc($get_branches)){
                                                    ?>
                                                    <input type="checkbox" name="branches[]" value="<?php echo $branch['code']; ?>" checked=""> <?php echo $branch['name']; ?><br>
                                                    <?php
                                                    }
                                                    ?>
                                                </label>
                                            </div>
                                            <div class="callout callout-danger">
                                                <p><b>Warning:</b> If you do not select any branch, then this loan product will not be available to any branch</p>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="box-footer" align="center">

                                <button type="submit" name="saveProduct" class="btn btn-info pull-md-none" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Please Wait">Submit</button>
                            </div><!-- /.box-footer -->
                        </form>


                    </div>
                    <div class="box box-info">
                        <div class="row" style="margin-right:0.2%;margin-left:0.2%;margin-top: 1%;">
                            <div class="col-sm-12 table-responsive">
                                <style>
                                    th {
                                        padding-top: 12px;
                                        padding-bottom: 12px;
                                        text-align: left;
                                        background-color: #D1F9FF;
                                    }
                                </style>
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th><input type="checkbox" id="select_all"/></th>
                                        <th>Product Name</th>
                                        <th>Type</th>
                                        <th>Fees</th>
                                        <th>Penalty Settings</th>
                                        <th style="text-align: center">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $get_products=mysqli_query($link,"select * from products");
                                    while($product=mysqli_fetch_assoc($get_products)){
                                        $productConfig = $product['product_configuration'];
                                        $productId=$product['product_id'];
                                    ?>
                                            <tr>
                                                <td><input id="optionsCheckbox" class="checkbox"
                                                           name="selector[]"
                                                           type="checkbox" value="<?php echo $id; ?>">
                                                </td>
                                                <td><?php echo $product['product_name']; ?></td>
                                                <td><?php echo $product['product_type']; ?></td>
                                                <td> <a href="#myModal <?php echo $productId; ?>" data-target="#myModal<?php echo $productId; ?>" data-toggle="modal">
                                                        View
                                                    </a>
                                                </td>

                                                <td><a href="add_penalty.php?productId=<?php echo $product['product_id']; ?>&productName=<?php echo $product['product_name']; ?>">Set Penalty</a></td>
                                                <td align="center">
                                                    <?php echo ($pupdate == '1') ? '<a href="#?id='.$id . '&&mid=' . base64_encode("403") . '&&action=view"><i class="fa fa-eye"></i> View</a>' : ''; ?>&nbsp;
                                                    <?php echo ($pupdate == '1') ? '<a href="loansettings.php?id=' . $id . '&&mid=' . base64_encode("403") . '&&action=edit&&productid='.$productId.'"><i class="fa fa-pencil"></i> Edit</a>' : ''; ?>&nbsp;
                                                    <?php echo ($pdelete == '1') ? '<a href="loansettings.php?id='.$id.'&&mid='.base64_encode("403") .'&&action=delete&&productid='.$productId.'"><i class="fa fa-trash"></i> Delete</a>' : ''; ?>
                                                </td>

                                            </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        $('input[name="type"]').on('click', function () {
            if ($(this).val() == 'Yes') {
                $('#textboxes').show();
            } else {
                $('#textboxes').hide();
            }
        });
    });
</script>

<script>
    function ShowHideDiv() {
        var Yes = document.getElementById("Yes");
        var dvtext = document.getElementById("dvtext");
        dvtext.style.display = Yes.checked ? "block" : "none";
    }
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function () {
        $(document).on('click', '#checkAll_loanFees', function () {
            $(".itemRow_loanFees").prop("checked", this.checked);
        });
        $(document).on('click', '.itemRow_loanFees', function () {
            if ($('.itemRow_loanFees:checked').length == $('.itemRow_loanFees').length) {
                $('#checkAll_loanFees').prop('checked', true);
            } else {
                $('#checkAll_loanFees').prop('checked', false);
            }
        });
        var count = $(".itemRow_loanFees").length;
        $(document).on('click', '#addRows_loanFees', function () {
            var htmlRows = '';
            htmlRows += '<tr>';
            htmlRows += '<input type="hidden" name="loanFees[' + count + '][id]" value="">';
            htmlRows += '<td><input class="itemRow_loanFees" type="checkbox"></td>';
            htmlRows += '<td><input type="text" name="loanFees[' + count + '][fee_name]" id="fee_name_' + count + '" class="form-control" autocomplete="off" required></td>';
            htmlRows += '<td><input type="number" name="loanFees[' + count + '][min_loan]" id="min_loan_' + count + '" min="0" class="form-control" autocomplete="off" required></td>';
            htmlRows += '<td><input type="number" name="loanFees[' + count + '][max_loan]" id="max_loan_' + count + '" min="0" class="form-control" autocomplete="off" required></td>';
            htmlRows += '<td><input type="number" name="loanFees[' + count + '][fee_amount]" id="fee_amount_' + count + '" min="0" class="form-control" autocomplete="off" required></td>';
            htmlRows += '<td align="center"><select class="form-control" name="loanAdditionalFees[' + count + '][charge_term]">\n' +
                '                                                <option value="">--Select--</option>\n' +
                '                                                    <option value="FREQUENCY">Repayment Frequency</option>\n' +
                '                                                    <option value="PRINCIPAL">Principal</option>\n' +
                '                                                    <option value="PRINCIPAL_INTEREST">Principal + Interest Due</option>\n' +
                '                                                    <option value="PRINCIPAL_DUE_INTEREST_DUE">Principal Due + Interest Due</option>\n' +
                '                                                    <option value="PRINCIPAL_DUE_INTEREST_DUE_FEES_DUE">Principal Due + Interest Due + Fees Due</option>\n' +
                '                                                </select> </td>';
            htmlRows += '<td align="center"><input type="checkbox" name="loanFees[' + count + '][deductible]"  data-value=" ' + count + '" id="deductible_' + count + '"  class="iswitch iswitch-md iswitch-primary toggle-event"> </td>';
            htmlRows += '</tr>';
            $('#loan-fees').append(htmlRows);
            count++;
        });
        $(document).on('click', '#removeRows_loanFees', function () {
            $(".itemRow_loanFees:checked").each(function () {
                $(this).closest('tr').remove();
            });
            $('#checkAll_loanFees').prop('checked', false);
            calculateTotal();
        });

        $(document).on('click', '.deleteRow_loanFees', function () {
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
<script>
    $(document).ready(function () {
        $(document).on('click', '#checkAll_loanAdditionalFees', function () {
            $(".itemRow_loanAdditionalFees").prop("checked", this.checked);
        });
        $(document).on('click', '.itemRow_loanAdditionalFees', function () {
            if ($('.itemRow_loanAdditionalFees:checked').length == $('.itemRow_loanAdditionalFees').length) {
                $('#checkAll_loanAdditionalFees').prop('checked', true);
            } else {
                $('#checkAll_loanAdditionalFees').prop('checked', false);
            }
        });
        var count = $(".itemRow_loanAdditionalFees").length;
        $(document).on('click', '#addRows_loanAdditionalFees', function () {
            var htmlRows = '';
            htmlRows += '<tr>';
            htmlRows += '<input type="hidden" name="loanAdditionalFees[' + count + '][id]" value="">';
            htmlRows += '<td><input class="itemRow_loanAdditionalFees" type="checkbox"></td>';
            htmlRows += '<td><input type="text" name="loanAdditionalFees[' + count + '][fee_description]" id="fee_description_' + count + '" class="form-control" autocomplete="off" required></td>';
            htmlRows += '<td align="center"><select class="form-control" name="loanAdditionalFees[' + count + '][gl_code]">\n' +
                '                                                <option value="" selected disabled>Select</option>\n' +
                    <?php while($row=mysqli_fetch_assoc($receivables)){ ?>
                '                                                    <option value="<?php echo $row['code']; ?>"><?php echo $row['code'] ?>&nbsp;-&nbsp;<?php echo $row['name']; ?></option>\n' +
                                <?php } ?>
                '                                                </select> </td>';
            htmlRows += '<td><input type="number" name="loanAdditionalFees[' + count + '][percentage]" id="percentage_' + count + '" min="0" step="0.01" class="form-control" autocomplete="off" required></td>';
            htmlRows += '<td><input type="number" name="loanAdditionalFees[' + count + '][min_fixed_amount]" id="min_fixed_amount_' + count + '" min="0" step="0.01" class="form-control" autocomplete="off"></td>';
            htmlRows += '<td><input type="number" name="loanAdditionalFees[' + count + '][max_fixed_amount]" id="max_fixed_amount_' + count + '" min="0" step="0.01" class="form-control" autocomplete="off"></td>';
           // htmlRows += '<td><input type="number" name="loanAdditionalFees[' + count + '][fromAge]" id="max_fixed_amount_' + count + '" min="0" step="0.01" class="form-control" autocomplete="off"></td>';
            htmlRows += '<td align="center"><select class="form-control" name="loanAdditionalFees[' + count + '][charge_term]">\n' +
                '                                                <option value="">--Select--</option>\n' +
                '                                                    <option value="FREQUENCY">Repayment Frequency</option>\n' +
                '                                                    <option value="PRINCIPAL">Principal</option>\n' +
                '                                                    <option value="PRINCIPAL_INTEREST">Principal + Interest Due</option>\n' +
                '                                                    <option value="PRINCIPAL_DUE_INTEREST_DUE">Principal Due + Interest Due</option>\n' +
                '                                                    <option value="PRINCIPAL_DUE_INTEREST_DUE_FEES_DUE">Principal Due + Interest Due + Fees Due</option>\n' +
                '                                                </select> </td>';
            htmlRows += '</tr>';
            $('#loan-additional-fees').append(htmlRows);
            count++;
        });
        $(document).on('click', '#removeRows_loanAdditionalFees', function () {
            $(".itemRow_loanAdditionalFees:checked").each(function () {
                $(this).closest('tr').remove();
            });
            $('#checkAll_loanAdditionalFees').prop('checked', false);
            calculateTotal();
        });

        $(document).on('click', '.deleteRow_loanAdditionalFees', function () {
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
        var count = $(".itemRow_document").length;
        $(document).on('click', '#addRows_document', function () {
            var htmlRows = '';
            htmlRows += '<tr>';
            htmlRows += '<td><input class="itemRow_document" type="checkbox" name="selector[]" value="' + count + '"></td>';
            htmlRows += '<td align="center"><select class="form-control" name="document[' + count + '][name]">\n' +
                '                                                <option value="" selected disabled>Select</option>\n' +
                '                                                    <option value="Proof of residence">Proof of residence</option>\n' +
                '                                                    <option value="Proof of income">Proof (Source) of income</option>\n' +
                '                                                    <option value="Latest 3 months personal bank statement">Latest 3 months personal bank statement</option>\n' +
                '                                                    <option value="Copy of National ID/Passport">Copy of National ID/Passport</option>\n' +
                '                                                    <option value="Confirmation of Employment">Confirmation of Employment</option>\n' +
                '                                                    <option value="Application Letter">Application Letter</option>\n' +
                '                                                    <option value="Latest rates/Levy Statement/Utility account statement.">Latest rates/Levy Statement/Utility account statement.</option>\n' +
                '                                                    <option value="Copy of Marriage Certificate or ANC Contract">Copy of Marriage Certificate or ANC Contract</option>\n' +
                '                                                </select> </td>';
            htmlRows += '<td><input type="checkbox" name="document[' + count + '][required]"  data-value=" ' + count + '" id="required_' + count + '"  class="iswitch iswitch-md iswitch-primary toggle-event"></td>';
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
    $(document).ready(function () {
        $(".slidingDivDocumentSettings").show();
        $('.show_hide_document_settings').click(function (e) {
            $(".slidingDivDocumentSettings").slideToggle("fast");
            var val = $(this).text() == "Hide" ? "Show" : "Hide";
            $(this).hide().text(val).fadeIn("fast");
            e.preventDefault();
        });
    });
</script>
<script type="text/javascript">
    function showfield(name) {
        <?php
        $get = mysqli_query($link, "SELECT * FROM loan_settings order by id") or die (mysqli_error($link));
        $get_day = mysqli_fetch_assoc($get);
        $day = $get_day['share_data_day'];
        ?>
        if (name == 'Weekly') {
            document.getElementById('share_data_date').innerHTML = '<div class="form-group">\n' +
                '                            <label for="" class="col-sm-3 control-label">Day of Week</label>\n' +
                '                            <div class="col-sm-6">' +
                '<select name="share_data_date" class="form-control" required>' +
                '<option>--Select day of week--</option>' +
                '<option <?php if ($day == "1") {
                    echo "selected";
                } ?> value="1">Sunday</option>' +
                '<option <?php if ($day == "2") {
                    echo "selected";
                } ?> value="2">Monday</option>' +
                '<option <?php if ($day == "3") {
                    echo "selected";
                } ?> value="3">Tuesday</option>' +
                '<option <?php if ($day == "4") {
                    echo "selected";
                } ?> value="4">Wednesday</option>' +
                '<option <?php if ($day == "5") {
                    echo "selected";
                } ?> value="5">Thursday</option>' +
                '<option <?php if ($day == "6") {
                    echo "selected";
                } ?> value="6">Friday</option>' +
                '<option <?php if ($day == "7") {
                    echo "selected";
                } ?> value="7">Saturday</option>' +
                '</select>' +
                '</div>' +
                '</div>';
        } else if (name == 'Monthly') {
            document.getElementById('share_data_date').innerHTML = '<div class="form-group">\n' +
                '                            <label for="" class="col-sm-3 control-label">Day of Month</label>\n' +
                '                            <div class="col-sm-6">' +
                '<select name="share_data_date" class="form-control" required>' +
                '<option selected="selected">--Select day of Month--</option>' +
                '<?php foreach (range(1, 31, 1) as $number) { ?>' +
                '<option <?php if ($day == $number) {
                    echo "selected";
                } ?> value="<?php echo $number ?>"><?php echo $number ?></option>' +
                '<?php } ?>' +
                '</select>' +
                '</div>' +
                '</div>';
        } else document.getElementById('share_data_date').innerHTML = '';
    }
</script>
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
        $(".slidingDivAdvanceSettings").hide();
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
        $(".slidingDivAutomatedPayments").hide();
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
        $(".slidingLoanOptions").show();
        $('.show_hide_loan_setttings').click(function (e) {
            $(".slidingLoanOptions").slideToggle("fast");
            var val = $(this).text() == "Hide" ? "Show" : "Hide";
            $(this).hide().text(val).fadeIn("fast");
            e.preventDefault();
        });
    });
</script>

<script>
    $(document).ready(function () {
        $(".slidingLoanProducts").hide();
        $('.show_hide_loan_products').click(function (e) {
            $(".slidingLoanProducts").slideToggle("fast");
            var val = $(this).text() == "Hide" ? "Show" : "Hide";
            $(this).hide().text(val).fadeIn("fast");
            e.preventDefault();
        });
    });
</script>

<script>
    $(document).ready(function () {
        $(".slidingDivExtendedLoan").hide();
        $('.show_hide_extended_loan').click(function (e) {
            $(".slidingDivExtendedLoan").slideToggle("fast");
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

<script>
    var minimum = parseInt(document.getElementById('minimumLoan').value);
    var maximum = parseInt(document.getElementById('maximumLoan').value);

    function validatePassword() {
        if (minimum < maximum) {
            minimum.setCustomValidity('Maximum can not be les than minimum ');
        } else {
            minimum.setCustomValidity('');
        }
    }

    minimum.addEventListener('change', validatePassword);
    maximum.addEventListener('keyup', validatePassword);
</script>
<script
        src="https://code.jquery.com/jquery-3.3.1.js"
        integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
        crossorigin="anonymous"></script>
<script>
    $(document).ready(function () {
        $("#addProduct").click(function () {
            $("#createProduct").toggle(1000);
        });
    });
    $(document).ready(function () {
        $("#addCollateral").click(function () {
            $("#collateral").toggle();
        });
    });

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
        $(".slidingDiv :input").attr("disabled", true);
        $('.show_hide').click(function (e) {
            if ($('.show_hide').is(":checked"))
                $(".slidingDiv :input").attr("disabled", false);
            else
                $(".slidingDiv :input").attr("disabled", true);
        });
    });
</script>
<script>
    function up() {
        var selectedOpts = $('#to option:selected');
        if (selectedOpts.length == 0) {

            alert("Select a column");

            e.preventDefault();

        }
        var selected = $("#to").find(":selected");
        var before = selected.prev();
        if (before.length > 0)
            selected.detach().insertBefore(before);
    }

    function down() {
        var selectedOpts = $('#to option:selected');
        if (selectedOpts.length == 0) {

            alert("Select a column");

            e.preventDefault();

        }
        var selected = $("#to").find(":selected");
        var next = selected.next();
        if (next.length > 0)
            selected.detach().insertAfter(next);
    }
    function  selectAll(){
        var listbox = document.getElementById('to');
        for(var count=0; count < listbox.options.length; count++) {
            listbox.options[count].selected = true;
        }
    }
</script>

<script>
    function enableDisablePostingPeriod() {
        var inputPaymentPostingPeriod = document.getElementById("inputPaymentPostingPeriod");
        if (document.getElementById("inputAutomaticPaymentsYes").checked)
        {
            inputPaymentPostingPeriod.disabled = false;
        }
        else if (document.getElementById("inputAutomaticPaymentsNo").checked)
        {
            inputPaymentPostingPeriod.disabled = true;
            document.getElementById("inputPaymentPostingPeriod").selectedIndex=0;
        }
    }
</script>
<script>
    $(function () {
        //Initialize Select2 Elements
        $(".fee_schedule_select").select2({
            placeholder: "Select a value"


        });
    });
</script>

<script>
    $(document).ready(function () {
        function updateLoan() {

            var maximumLoan = parseFloat("<?php echo $maximumLoan; ?>");
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            $("input[type='radio']").change(function () {
                if ($(this).val() === "Loan") {
                    $("#typeLoan").show();
                } else {
                    $("#typeLoan").hide();
                }
            });
        });
    </script>