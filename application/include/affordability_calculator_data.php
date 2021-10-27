<?php
$company = mysqli_fetch_assoc(mysqli_query($link, "SELECT * FROM systemset")) or die(mysqli_error($link));
$companyName = explode($company['name'])[0];

$result = mysqli_query($link, "SELECT * FROM affordability_check") or die(mysqli_error($link));

$query = mysqli_query($link, "SELECT (scoring) FROM systemset") or die(mysqli_error($link));
$scoring = mysqli_fetch_array($query, MYSQLI_ASSOC);


$affordability = 0.0;
$otherInstalments = 0.0;
$borrower = "";
$borrower = "";

unset($_SESSION['identity']);
unset($_SESSION['dateofbirth']);
unset($_SESSION['employer']);
unset($_SESSION['member']);
unset($_SESSION['status']);
unset($_SESSION['member']);
unset($_SESSION['gender']);
unset($_SESSION['addrs1']);
unset($_SESSION['addrs2']);
unset($_SESSION['name']);
unset($_SESSION['surname']);

unset($_SESSION['affordabilityCheck']);

if (isset($_POST['affordability'])) {
    $newSearch = mysqli_real_escape_string($link, $_POST['newSearch']);
    if (strpos("$newSearch", "@") !== false) {
        $memberQuery = mysqli_query($link, "SELECT * FROM borrowers WHERE '$newSearch' IN(email)") or mysqli_error($link);
        $borrower = mysqli_fetch_array($memberQuery);
        $id = $borrower['id'];
        $_SESSION['name'] = $borrower['fname'];
        $_SESSION['surname'] = $borrower['lname'];

        //Get Borrowers Affordability
        $afford = mysqli_fetch_assoc(mysqli_query($link, "select * from borrowers_salaries where borrower='$id'"));
        $max_available = $afford['max_available'];
        $basic_pay = $afford['basic_pay'];
        $additional_fixed_allowance = $afford['additional_fixed_allowance'];
        $gross_pay = $afford['gross_pay'];
        $statutory_deductions = $afford['statutory_deductions'];
        $net_pay = $afford['net_pay'];
        $loan_instalments = $afford['loan_instalments'];
        $other_bank_loans = $afford['other_bank_loans'];
        $monthly_living_expenses = $afford['monthly_living_expenses'];

        //echo "$max_available";

        $_SESSION['identity'] = "";
        if ($borrower['id_number'] !== "") {
            $_SESSION['identity'] = $borrower['id_number'];
        } else if ($borrower['passport'] !== "") {
            $_SESSION['identity'] = $borrower['passport'];
        }

        $_SESSION['dateofbirth'] = $borrower['date_of_birth'];
        $_SESSION['employer'] = $borrower['employer'];
        $_SESSION['member'] = $borrower['member'];
        $_SESSION['status'] = $borrower['status'];
        $_SESSION['member'] = $borrower['member'];
        $_SESSION['gender'] = $borrower['gender'];
        $_SESSION['postal'] = $borrower['postal'];
        $_SESSION['addrs1'] = $borrower['addrs1'];
        $_SESSION['addrs2'] = $borrower['addrs2'];
        $_SESSION['id'] = $borrower['id'];
        $status = $borrower['status'];
        switch ($status) {
            case "Active":
                $status = "<i class=\"fa fa-check-square text-green\"></i> $status";
                break;
            case "Partial":
                $status = "<i class=\"fa fa-user-plus text-orange\"></i> $status";
                break;
            case "Inactive":
                $status = "<i class=\"fa fa-user-times text-red\"></i> $status";
                break;
            default:
                $status = "<i class=\"fa fa-exclamation-triangle text-orange\"></i> $status";
                break;
        }


    } else {
        $memberQuery = mysqli_query($link, "SELECT * FROM borrowers WHERE '$newSearch' IN(emp_code, id_number, passport, id, phone, email, telephone)");
        $borrower = mysqli_fetch_array($memberQuery);
        $id = $borrower['id'];

        //Get Borrowers Affordability
        $afford = mysqli_fetch_assoc(mysqli_query($link, "select * from borrowers_salaries where borrower='$id'"));
        $max_available = $afford['max_available'];
        $basic_pay = $afford['basic_pay'];
        $additional_fixed_allowance = $afford['additional_fixed_allowance'];
        $gross_pay = $afford['gross_pay'];
        $statutory_deductions = $afford['statutory_deductions'];
        $net_pay = $afford['net_pay'];
        $loan_instalments = $afford['loan_instalments'];
        $other_bank_loans = $afford['other_bank_loans'];
        $monthly_living_expenses = $afford['monthly_living_expenses'];

        $_SESSION['name'] = $borrower['fname'];
        $_SESSION['surname'] = $borrower['lname'];

        $_SESSION['identity'] = "";
        if ($borrower['id_number'] !== "") {
            $_SESSION['identity'] = $borrower['id_number'];
        } else if ($borrower['passport'] !== "") {
            $_SESSION['identity'] = $borrower['passport'];
        }

        $_SESSION['dateofbirth'] = $borrower['date_of_birth'];
        $_SESSION['employer'] = $borrower['employer'];
        $_SESSION['member'] = $borrower['member'];
        $_SESSION['status'] = $borrower['status'];
        $_SESSION['member'] = $borrower['member'];
        $_SESSION['postal'] = $borrower['postal'];
        $_SESSION['gender'] = $borrower['gender'];
        $_SESSION['addrs1'] = $borrower['addrs1'];
        $_SESSION['addrs2'] = $borrower['addrs2'];
        $_SESSION['id'] = $borrower['id'];
        $status = $_SESSION['status'];
    }
}

$bureau = "";
if (isset($_GET['affordability'])) {
    $bureau = $_GET['affordability'];
}
if (isset($_POST['affordability'])) {
    $bureau = $_POST['affordability'];
}


if ($scoring['scoring'] == 1) {
    if (isset($bureau)) {
        $newSearch = mysqli_real_escape_string($link, $_GET['newSearch']);
        switch (strtolower($bureau)) {
            // including the cdas module to check affordability
            case 'cdas':
                include "include/final.php";
                $cdas = $cdas = new cdasIntegration($_GET['newSearch']);
                $cdas->login();
                $borrower = $cdas->getEmployeeDetails();
                $cdasAffordability = $cdas->getAffordability();     // user affordability as outlined by cdas

                $_SESSION['name'] = $borrower['Name'];
                $_SESSION['surname'] = $borrower['Surname'];
                $_SESSION['employer'] = $borrower['Department'];
                $_SESSION['identity'] = $_GET['newSearch'];
                $_SESSION['dateofbirth'] = $borrower['DOB'];

                break;

            case 'Compuscan':
                //include "include/compuscanApi.php";
                //making a class instance;

                $borrower = $compuscan->getBorrowerInfo();
                $_SESSION['name'] = $borrower['CRIT_NAME'];
                $_SESSION['surname'] = $borrower['CRIT_SURNAME'];
                $_SESSION['employer'] = $compuscan->getEmpoyer();
                $_SESSION['identity'] = $_GET['CRIT_IDNUMBER'];
                $_SESSION['dateofbirth'] = $borrower['DOB'];
                break;

        }
    }
}

?>

<style type="text/css">
    .form-group {
        border: none;
    }

    #searchField {
        padding-top: 30px;
    }

    .hide {
        display: none;
    }
</style>


<div class="box">
    <div class="box-body">
        <div class="panel panel-success">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-user"></i> Affordability Criteria</h3>
            </div>
            <div class="box-body">
                <h3 class="inline text-center" style="margin: 0 auto"> Affordability calculation with national credit
                    information providers</h3>

                <div class="row" id="searchField">
                    <div class="col-sm-10 col-lg-offset-1">
                        <div class="form-control text-center">
                            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                <div class="form-check inline text-center">
                                    <input class="form-check-input" type="radio" name="affordability"
                                           id='<?php echo $row['provider']; ?>' value="<?php echo $row['provider']; ?>">
                                    <label class="form-check-label"
                                           for="inlineRadio1"><?php echo $row['provider']; ?> </label>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                </div>
                            <?php } ?>
                            <div class="form-check inline">
                                <input class="form-check-input" type="radio" name="affordability" value="local">
                                <label class="form-check-label" for="inlineRadio1"> Locally</label>
                            </div>
                            &nbsp; &nbsp;
                            <?php if (mysqli_num_rows($result) > 1) { ?>
                                <div class="form-check inline">
                                    <input class="form-check-input" type="radio" name="affordability" value="both">
                                    <label class="form-check-label" for="inlineRadio1">All </label>
                                </div>
                            <?php } ?>

                            &nbsp;&nbsp;&nbsp;&nbsp;


                        </div>
                        <?php
                        if ($_GET['affordability'] == "Compuscan") {
                            $instalment = explode("/", $_GET['id'])[4];
                            $name = explode("/", $_GET['id'])[1];
                            $identity = explode("/", $_GET['id'])[2];
                            $surname = explode("/", $_GET['id'])[3];
                            $DOB = explode("/", $_GET['id'])[6];
                            $accounts = explode("/", $_GET['id'])[5];
                            $allAccounts = explode("/", $_GET['id'])[0];
                            $allAccounts = json_decode(base64_decode($allAccounts), true);

                            $_SESSION['name'] = $name;
                            $_SESSION['surname'] = $surname;
                            $_SESSION['identity'] = $identity;
                            $_SESSION['dateofbirth'] = $DOB;
                        }
                        ?>

                        <br/>
                        <?php
                        $member = "";
                        if (isset($_SESSION['identity']) || isset($_SESSION['name'])) {
                            echo "<h4>Search Results</h4><hr><h4 style=\"color: #1b7e5a\">";
                            if (isset($_SESSION['member'])) {
                                if (mysqli_num_rows($memberQuery) > 0)
                                    $member = "<br><br><p style=\"color: green\"><i class=\"fa fa-check-circle text-green\"></i> Regular | $status</p><hr>";
                            } else {
                                if (mysqli_num_rows($memberQuery) > 0)
                                    $member = "<br><br><p style=\"color: orange\"><i class=\"fa fa-check-circle text-orange\"></i> Non-Regular</p> | $status<hr>";
                            }

                            if ($_SESSION['identity'] !== "") {
                                echo $_SESSION['identity'] . ", " . $_SESSION['name'] . "&nbsp;" . $_SESSION['surname'] . "&nbsp;" . $member . "</h4>";
                            } else {
                                echo $_SESSION['name'] . "&nbsp;" . $_SESSION['surname'] . "&nbsp;" . $member . "</h4>";
                            }
                            if ($instalment == 0) {
                                if ($_GET['affordability'] === "Compuscan") {
                                    echo '<b><p style="color: red"><i class="fa fa-remove text-red"></i>No Active Credit</p> </b>';
                                }
                            } else {
                                echo "<b><p style=\color: green\"><i class=\"fa fa-check-circle text-green\"></i> is having $accounts open accounts</p>";
                                ?>
                                <table class="table table-borderless">
                                    <thead>
                                    <th>Subscriber</th>
                                    <th>Account Type</th>
                                    <th>Instalment</th>
                                    <th>Current Balance</th>
                                    <th>Last Payment</th>
                                    </thead>
                                    <?php
                                    $totalInstalment = $totalBalance = 0;
                                    foreach ($allAccounts['activeAccounts'] as $key => $values) {
                                        $totalInstalment += $values['instalment'];
                                        $totalBalance += $values['currentBalance'];
                                        ?>
                                        <tr>
                                            <td><?php echo $values['subscriber'] ?></td>
                                            <td><?php echo $values['accountType'] ?></td>
                                            <td><?php echo number_format($values['instalment'], "2", ".", ","); ?></td>
                                            <td><?php echo number_format($values['currentBalance'], "2", ".", ","); ?></td>
                                            <td><?php echo $values['lastPayment'] ?></td>
                                        </tr>
                                    <?php } ?>
                                    <tfoot>
                                    <tr>
                                        <td><strong>Total</strong></td>
                                        <td></td>
                                        <td><b><?php echo number_format($totalInstalment, "2", ".", ","); ?></b></td>
                                        <td><b><?php echo number_format($totalBalance, "2", ".", ","); ?></b></td>
                                        <td></td>
                                    </tr>
                                    </tfoot>
                                </table>

                                <?php
                            }
                        } else {
                            if (!isset($borrower))
                                echo "<i class=\"fa fa-user-times text-red\"></i><i style=\"font-family: inherit; color: red;\"> No user found with the specified details, please try again.</i> ";

                        }
                        ?>
                        <form class="form-horizontal" id="myForm" method="GET" enctype="multipart/form-data">
                            <div class="form-group <?php if (!isset($_GET['newSearch'])) {
                                echo 'hide';
                            } ?>" id="search">
                                <label for="newSearch" class="col-sm-3 control-label">Borrowers Identity
                                    Code</label>
                                <input type="hidden" name="affordability" value="cdas"/>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <input type="text"
                                               maxlength="50"
                                               class="form-control"
                                               placeholder="Search by  Employee Code "
                                               name="newSearch"
                                               value="<?php if (isset($_GET['affordability'])) {
                                                   echo $_POST['newSearch'];
                                               } ?>"
                                               required>
                                        <div class="input-group-addon">
                                            <a form="myForm" id="searchButton" class="submit"><i
                                                        class="fa fa-search"></i> Search</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <form class="form-horizontal" id="dbForm" method="post" enctype="multipart/form-data">
                            <div class="form-group <?php if (!isset($_POST['local'])) {
                                echo 'hide';
                            } ?>" id="local">
                                <label for="newSearch" class="col-sm-3 control-label">Borrowers Identity
                                    Code</label>
                                <input type="hidden" name="affordability[provider]" value="local"/>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <input type="text"
                                               maxlength="50"
                                               class="form-control"
                                               placeholder="Search by National ID/Passport, Employee Code, Email, Cellphone"
                                               name="newSearch"
                                               value="<?php if (isset($_GET['affordability'])) {
                                                   echo $_POST['newSearch'];
                                               } ?>"
                                               required>
                                        <div class="input-group-addon">
                                            <a form="myForm" id="searchDb" class="submit"><i
                                                        class="fa fa-search"></i> Search</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <form method="post" action="include/compuscanApi.php">
                            <div class="form-group <?php if (!isset($_POST['affordability'])) {
                                echo 'hide';
                            } ?>" id="searchCompuScan">
                                <div class="panel panel-default">
                                    <div class="panel-heading">Compuscan Credit Assessment</div>
                                    <div class="panel-body">
                                        <div class="col-md-6">
                                            <input type="hidden" name="affordability[provider]" value="compuscan"
                                                   id="myaffordability"/>
                                            <div class="form-group">
                                                <label for="identity">National Identity or Passport</label>
                                                <input type="text"
                                                       value="<?php echo $_SESSION['identity']; ?>"
                                                       class="form-control"
                                                       name="identity"
                                                       placeholder="ID or passport">
                                            </div>

                                            <div class="form-group">
                                                <label for="firstname">First Name</label>
                                                <input type="text" value="<?php echo $_SESSION['name'] ?>" required
                                                       class="form-control" name="name"
                                                       placeholder="First Name">
                                            </div>

                                            <div class="form-group">
                                                <label for="firstname">Last Name</label>
                                                <input type="text" value="<?php echo $_SESSION['surname'] ?>" required
                                                       class="form-control" name="surname"
                                                       placeholder="Last Name">
                                            </div>

                                            <div class="form-group">
                                                <label for="address1">Address Line 1</label>
                                                <input type="text" value="<?php echo $_SESSION['addrs1']; ?>"
                                                       class="form-control" name="addr1"
                                                       placeholder="First Address">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="gender">Gender</label>
                                                <select class=" form-control custom-select" name="gender" required>
                                                    <option selected disabled>Select</option>
                                                    <option value="F" <?php if ($_SESSION['gender'] == "Female") echo "selected"; ?>>
                                                        Female
                                                    </option>
                                                    <option value="M" <?php if ($_SESSION['gender'] == "Male") echo "selected"; ?>>
                                                        Male
                                                    </option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="date of birth">Date Of Birth</label>
                                                <input
                                                        type="date"
                                                        name="DOB"
                                                        required
                                                        value="<?php echo $_SESSION['dateofbirth'] ?>"
                                                        max="<?php echo date("Y-m-d", strtotime('-18 years')); ?>"
                                                        class="form-control"
                                                        placeholder="Date of birth">
                                            </div>

                                            <div class="form-group">
                                                <label for="address2">Address Line 2</label>
                                                <input type="text" value="<?php echo $_SESSION['addrs2']; ?>"
                                                       class="form-control" required name="addr2"
                                                       placeholder="Second Address">
                                            </div>

                                            <div class="form-group">
                                                <label for="postal">Postal Code</label>
                                                <input type="number" required name="postal"
                                                       value="<?php echo $_SESSION['postal'] ?>" width="100"
                                                       class="form-control"
                                                       placeholder="postal code">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div align="center">
                                    <div class="box-footer">
                                        <button type="submit" name="Search" value="Search" class="btn btn-primary"><i
                                                    class="fa fa-search">&nbsp;Search</i>
                                        </button>
                                    </div>
                                </div>

                            </div>
                    </div>

                    </form>
                </div>

                <br/> <br/>

                <form action="newloans.php?id=<?php echo $_SESSION['tid']; ?>&mid=<?php echo base64_encode("405"); ?>"
                      class="form-horizontal" method="post">


                    <div class="form-group">
                        <label for="membership" class="col-sm-3 control-label">Basic Pay *</label>
                        <div class="col-sm-6">
                            <input type="number"
                                   name="affordabilityCheck[basicPay]"
                                   class="form-control"
                                   style="text-align:right; font-weight: bold;"
                                   step="0.01"
                                   id="basicPay"
                                   required
                                   onkeyup="updateMaximumAvailable()"
                                   placeholder="Basic Pay"
                                   value="<?php echo $basic_pay; ?>"/>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="membership" class="col-sm-3 control-label">Additional Fixed Allowance</label>
                        <div class="col-sm-6">
                            <input type="number"
                                   name="affordabilityCheck[additionalFixed]"
                                   class="form-control"
                                   style="text-align:right;"
                                   step="0.01"
                                   onkeyup="updateMaximumAvailable()"
                                   id="additionalFixed"
                                   placeholder="Additional Fixed Allowance"
                                   value="<?php if (isset($additional_fixed_allowance)) {
                                       echo $additional_fixed_allowance;
                                   } ?>"/></td>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="membership" class="col-sm-3 control-label">Gross Pay</label>
                        <div class="col-sm-6">
                            <input type="number"
                                   readonly
                                   name="affordabilityCheck[grossPay]"
                                   class="form-control"
                                   style="text-align:right; font-weight: bold;"
                                   id="grossPay"
                                   onkeyup="updateMaximumAvailable()"
                                   step="0.01"
                                   placeholder="Gross Pay"
                                   value="<?php if (isset($gross_pay)) {
                                       echo $gross_pay;
                                   } ?>"/>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="membership" class="col-sm-3 control-label">Statutory and Non-Statutory
                            Deductions</label>
                        <div class="col-sm-6">
                            <input type="number"
                                   name="affordabilityCheck[statutory]"
                                   class="form-control"
                                   id="statutory"
                                   onkeyup="updateMaximumAvailable()"
                                   style="text-align:right;"
                                   step="0.01"
                                   placeholder="Statutory and Non-Statutory Deductions"
                                   value="<?php if (isset($statutory_deductions)) {
                                       echo $statutory_deductions;
                                   } ?>"/>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="membership" class="col-sm-3 control-label">Loan Instalments to be
                            consolidated</label>
                        <div class="col-sm-6">
                            <input type="number"
                                   name="affordabilityCheck[loanInstalments]"
                                   class="form-control"
                                   id="loanInstalments"
                                   onkeyup="updateMaximumAvailable()"
                                   style="text-align:right;"
                                   step="0.01"
                                   placeholder="Loan Instalments to be consolidated"
                                   value="<?php if (isset($otherInstalments)) {
                                       echo $otherInstalments;
                                   } ?>"/>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="membership" class="col-sm-3 control-label">Net Pay</label>
                        <div class="col-sm-6">
                            <input type="number"
                                   name="affordabilityCheck[netPay]"
                                   readonly
                                   style="text-align:right; font-weight: bold;"
                                   id="netPay"
                                   onkeyup="updateMaximumAvailable()"
                                   step="0.01"
                                   class="form-control"
                                   placeholder="Net Pay"
                                   value="<?php if (isset($net_pay)) {
                                       echo $net_pay;
                                   } ?>"/>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="membership" class="col-sm-3 control-label">Other Bank loan instalments</label>
                        <div class="col-sm-6">
                            <input type="number"
                                   name="affordabilityCheck[otherBankLoans]"
                                   class="form-control"
                                   style="text-align:right;"
                                   step="0.01"
                                   id="otherBankInstalments"
                                   onkeyup="updateMaximumAvailable()"
                                   placeholder="Other Bank loan instalments"
                                   value="<?php if (isset($other_bank_loans)) {
                                       echo $other_bank_loans;
                                   } ?>"/>
                        </div>
                    </div>


                    <div class="form-group <?php
                    if (!isset($instalment)) {
                        echo 'hide';
                    }
                    ?>" id="compuscan">
                        <label for="membership" class="col-sm-3 control-label">Other loan instalments from
                            Compuscan</label>
                        <div class="col-sm-6">
                            <input type="number"
                                   style="text-align:right; font-weight: bold;"
                                   name="affordabilityCheck[compuscan]"
                                   class="form-control"
                                   id="compuscanInstalments"
                                   onkeyup="updateMaximumAvailable()"
                                   readonly
                                   placeholder="0"
                                   value="<?php echo round($instalment, 2); ?>"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="membership" class="col-sm-3 control-label">Monthly Living Expenses</label>
                        <div class="col-sm-6">
                            <input type="number"
                                   name="affordabilityCheck[monthly_living_expenses]"
                                   class="form-control"
                                   id="monthlyLivingExpenses"
                                   onkeyup="updateMaximumAvailable()"
                                   style="text-align:right;"
                                   step="0.01"
                                   placeholder="Monthly Living Expenses"
                                   value="<?php if (isset($monthly_living_expenses)) {
                                       echo $monthly_living_expenses;
                                   } ?>"/>
                        </div>
                    </div>


                    <div class="form-group <?php if ((!isset($_GET['newSearch'])) && (strtoupper($_GET['affordability']) != "cdas")) {
                        echo "hide";
                    } ?>" id="cdas">
                        <label for="membership" class="col-sm-3 control-label">Affordability from cdas</label>
                        <div class="col-sm-6">
                            <input type="number"
                                   style="text-align:right; font-weight: bold;"
                                   name="affordabilityCheck[cdas]"
                                   class="form-control"
                                   id="cdasaffordability"
                                   readonly
                                   step="0.01"
                                   placeholder="0"
                                   value="<?php if (isset($cdasAffordability)) {
                                       echo (float)$cdasAffordability;
                                   } ?>"/>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="membership" class="col-sm-3 control-label">Maximum available
                            for <?php echo $companyName; ?> Loan repayment</label>
                        <div class="col-sm-6">
                            <input type="text"
                                   style="text-align:right; font-weight: bold;"
                                   name="affordabilityCheck[max_available]"
                                   class="form-control"
                                   id="maxAvailable"
                                   value="<?php if (isset($max_available)) {
                                       echo $max_available;
                                   } ?>"
                                   onkeyup="updateMaximumAvailable()"
                                   readonly
                                   step="0.01"
                                   placeholder="Maximum available for Loan repayment"
                            />
                        </div>
                    </div>

                    <div align="center">
                        <div class="box-footer">
                            <button type="reset" class="btn btn-primary"><i
                                        class="fa fa-times">&nbsp;Reset</i>
                            </button>
                            <button name="save_borrowers_salary" type="submit"
                                    class="btn btn-success "><i
                                        class="fa fa-save">&nbsp;Create New Loan</i>
                            </button>

                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>


<!--// dealing with affordability criteria-->
<script>
    $(document).ready(function () {
        $("input[type='radio']").change(function () {
            if ($(this).attr("name") === "affordability") {
                if ($(this).val().toLowerCase() === 'cdas') {
                    $('#cdas').removeClass('hide');
                    $("#search").removeClass('hide');
                    $('#searchCompuScan').addClass('hide');
                    $('#local').addClass('hide');

                }
                if ($(this).val().toLowerCase() === 'compuscan') {
                    $('#searchCompuScan').removeClass('hide');
                    $('#cdas').addClass('hide');
                    $('#search').addClass('hide');
                    $('#local').addClass('hide');
                    $('#myaffordability').val($(this.val()));
                }

                if ($(this).val().toLowerCase() === 'local') {
                    $('#local').removeClass('hide');
                    $('#searchCompuScan').addClass('hide');
                    $('#cdas').addClass('hide');
                    $('#search').addClass('hide');
                    $('#myaffordability').val($(this.val()));
                }
            }
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(() => {
        var cdas = $('#cdas').val()
        var current = $('#cdasaffordability').val();
        var maxAvailable = parseFloat(current) + parseFloat(cdas);
        $('#cdasaffordability').val(cdasaffordability);

        $('#searchButton').click(() => {
            $('#myForm').submit();
        })

        $('#searchDb').click(() => {
            $('#dbForm').submit();
        })
    })
</script>
<script type="text/javascript">
    function updateMaximumAvailable() {
        var grossPay = document.getElementById("grossPay").value;
        if (grossPay == "")
            grossPay = 0;
        var basicPay = document.getElementById("basicPay").value;
        if (basicPay == "")
            basicPay = 0;
        var netPay = document.getElementById("netPay").value;
        if (netPay == "")
            netPay = 0;
        var additionalFixed = document.getElementById("additionalFixed").value;
        if (additionalFixed == "")
            additionalFixed = 0;
        var statutory = document.getElementById("statutory").value;
        if (statutory == "")
            statutory = 0;
        var loanInstalments = document.getElementById("loanInstalments").value;
        if (loanInstalments == "")
            loanInstalments = 0;
        var otherBankInstalments = document.getElementById("otherBankInstalments").value;
        if (otherBankInstalments == "")
            otherBankInstalments = 0;
        var monthlyLivingExpenses = document.getElementById("monthlyLivingExpenses").value;
        if (monthlyLivingExpenses == "")
            monthlyLivingExpenses = 0;//compuscanInstalments

        var compuscanInstalments = document.getElementById("compuscanInstalments").value;
        if (compuscanInstalments == "")
            compuscanInstalments = 0;

        var cdasAffordability = document.getElementById("cdasaffordability").value;
        ;
        if (cdasAffordability == "")
            cdasAffordability = 0;
        var deductions = parseFloat(statutory) + parseFloat(loanInstalments);
        grossPay = parseFloat(basicPay) + parseFloat(additionalFixed);
        netPay = (parseFloat(grossPay) - deductions).toFixed(2);
        var maximumAvailable = ((parseFloat(netPay) -
            (parseFloat(otherBankInstalments) + parseFloat(monthlyLivingExpenses) + parseFloat(compuscanInstalments)))
            + parseFloat(cdasAffordability)).toFixed(2);
        $("#grossPay").val(grossPay);
        $("#netPay").val(netPay);
        if(maximumAvailable=="") {
            $("#maxAvailable").val(0);
        }
        if(maximumAvailable!=="") {
            $("#maxAvailable").val(maximumAvailable);
        }
    }

</script>