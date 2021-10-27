<?php

$id = $_GET["id"];


$employer = mysqli_query($link, "SELECT * FROM employer_details WHERE id='$id'") or die(mysqli_error($link));
$employerInfo = mysqli_fetch_assoc($employer);



if(isset($_POST['saveBankingDetails'])){
    $disbursementData = json_encode($_POST['recipient']);
    $detailsId = $_POST['detailsId'];

    $update=mysqli_query($link,"update loan_disbursements set transaction ='$disbursementData' where pay_id='$detailsId'");
    if (!$update) {
        echo '<div class="alert alert-danger" >
                                                                <a href = "#" class = "close" data-dismiss= "alert"> &times;</a>
                                                                Failed to update borrower banking details!&nbsp; &nbsp;&nbsp;
                                                                </div>';
    } else {
        echo '<div class="alert alert-success" >
                                                                <a href = "#" class = "close" data-dismiss= "alert"> &times;</a>
                                                                Borrower Banking Details Successfully Updated!&nbsp; &nbsp;&nbsp;
                                                                </div>';
    }
}

//Get banking Details
$bankingDetails = mysqli_fetch_array(mysqli_query($link, "SELECT pay_id, transaction, disbursement_method FROM loan_disbursements WHERE loan in (select max(id) from loan_info where borrower='$id' )"));
$bankDetails = json_decode($bankingDetails['transaction'], true);
$disburseMethod = $bankingDetails['disbursement_method'];

switch ($disburseMethod) {
    case "Online Transfer":
        $bankName = str_replace("_"," ", $bankDetails['bankName']);
        $accountName = $bankDetails['accountName'];
        $detailsId = $bankingDetails['pay_id'];
        $branchName = str_replace("_"," ", $bankDetails['branchName']);;
        $accountNumber = $bankDetails['accountNumber'];
        $branchCode = $bankDetails['branchCode'];
        $typeOfAccount = $bankDetails['accountType'];
        break;
    case "Mobile Money":
        $bankName = $bankDetails['bankName'];
        $detailsId = $bankingDetails['pay_id'];
        $accountNumber = $bankDetails['accountNumber'];
        break;
}

if (isset($_POST['save_empinfo'])) {
    $employerNo = $_POST['employeeNo'];
    $employerName = $_POST['employerName'];
    $department = $_POST['department'];
    $employerCode = $_POST['employerCode'];
    $designation = $_POST['designation'];
    $engagementDate = $_POST['engageDate'];
    $employmentStatus = $_POST['employmentStatus'];
    $retirement = $_POST['retirement'];
    $employerContact = $_POST['employerContact'];
    $telephoneNo = $_POST['emp_num'];
    $employerDesignation = $_POST['employerDesignation'];

    if (mysqli_num_rows($employer) === 0) {
        mysqli_query($link, "INSERT INTO employer_details (id, employee_no, employer_name, department, employer_code, designation, engagement_date, employment_status, retirement, employer_contact, telephone,employer_designation) VALUES(
        '$id', 
        '$employerNo', 
        '$employerName', 
        '$department',
        '$employerCode',
        '$designation',
        '$engagementDate', 
        '$employmentStatus', 
        '$retirement', 
        '$employerContact', 
        '$telephoneNo', 
        '$employerDesignation'
    )") or die(mysqli_error($link));
        $employer = mysqli_query($link, "SELECT * FROM employer_details WHERE id='$id'") or die(mysqli_error($link));
        $employerInfo = mysqli_fetch_assoc($employer);
    } else {
        mysqli_query($link, "UPDATE employer_details SET employee_no = '$employerNo', employer_name='$employerName', department='$department', employer_code='$employerCode', designation='$designation', engagement_date='$engagementDate', employment_status='$employmentStatus', retirement='$retirement', employer_contact='$employerContact', telephone='$telephoneNo', employer_designation='$employerDesignation' WHERE id='$id'");

        $employer = mysqli_query($link, "SELECT * FROM employer_details WHERE id='$id'") or die(mysqli_error($link));
        $employerInfo = mysqli_fetch_assoc($employer);
    }
}

$nextOfKin = mysqli_query($link, "SELECT * FROM next_of_kin_details WHERE borrower='$id'") or die(mysqli_error($link));
$salary = mysqli_query($link, "SELECT * FROM borrowers_salaries WHERE borrower='$id'") or die(mysqli_error($link));

if (isset($_POST['save_nextOfKin'])) {

    $nextOfKinName = $_POST['names'];
    $nextOfKinAddress = $_POST['address'];
    $nextOfKinContact = $_POST['contact'];
    $nextOfKinEmployer = $_POST['employer'];
    $email = $_POST['email'];
    $employer = $_POST['employer'];

    if (mysqli_num_rows($nextOfKin) === 0) {
        mysqli_query($link, "INSERT INTO next_of_kin_details (id,borrower, names, address, contact, email, employer) VALUES(
        0,
        '$id', 
        '$nextOfKinName', 
        '$nextOfKinAddress',
        '$nextOfKinContact',
        '$email',
        '$employer'
    )") or die(mysqli_error($link));
    } else {
        mysqli_query($link, "UPDATE next_of_kin_details SET names='$nextOfKinName', address='$nextOfKinAddress', email='$$email', contact='$nextOfKinContact', employer='$nextOfKinContact' WHERE id='$id'");

        $nextOfKin = mysqli_query($link, "SELECT * FROM next_of_kin_details WHERE borrower='$id'") or die(mysqli_error($link));
        $nextOfKinInfo = mysqli_fetch_assoc($nextOfKin);
    }
}


if (isset($_POST['save_borrowers_salary'])) {

    $basicPay = $_POST['basicPay'];
    $additionalFixed = $_POST['additionalFixed'];
    $grossPay = $_POST['grossPay'];
    $statutory = $_POST['statutory'];
    $loanInstalments = $_POST['loanInstalments'];
    $netPay = $_POST['netPay'];
    $otherBankLoans = $_POST['otherBankLoans'];
    $monthly_living_expenses = $_POST['monthly_living_expenses'];
    $max_available = $_POST['max_available'];

    if (mysqli_num_rows($salary) === 0) {
        mysqli_query($link, "INSERT INTO borrowers_salaries(id, borrower, basic_pay, additional_fixed_allowance, gross_pay, statutory_deductions, loan_instalments, net_pay, other_bank_loans, monthly_living_expenses, max_available) 
                VALUES 
                (0,'$id','$basicPay','$additionalFixed','$grossPay','$statutory','$loanInstalments','$netPay','$otherBankLoans','$monthly_living_expenses','$max_available')") or die(mysqli_error($link));
    } else {
        mysqli_query($link, "UPDATE borrowers_salaries SET  basic_pay='$basicPay', additional_fixed_allowance='$additionalFixed', 
gross_pay='$grossPay',statutory_deductions='$statutory',loan_instalments='$loanInstalments',net_pay='$netPay', 
other_bank_loans='$otherBankLoans',monthly_living_expenses='$monthly_living_expenses',max_available='$max_available' WHERE borrower='$id'");

        $salary = mysqli_query($link, "SELECT * FROM borrowers_salaries WHERE borrower='$id'") or die(mysqli_error($link));
        $salaryInfo = mysqli_fetch_assoc($salary);
    }
}
$nextOfKin = mysqli_query($link, "SELECT * FROM next_of_kin_details WHERE borrower='$id'") or die(mysqli_error($link));
$nextOfKinInfo = mysqli_fetch_assoc($nextOfKin);

$salary = mysqli_query($link, "SELECT * FROM borrowers_salaries WHERE borrower='$id'") or die(mysqli_error($link));
$salaryInfo = mysqli_fetch_assoc($salary);
?>


<div class="row">

    <section class="content">
        <div class="box box-success">
            <div class="box-body">
                <div class="panel panel-success">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-user"></i>&nbsp;Borrower Information</h3>
                    </div>
                    <div class="table-responsive">
                        <div class="box-body">
                            <?php


                            if (isset($_GET['product'])) {
                                $loanType = $_GET['product'];
                            } else {
                                $product = mysqli_fetch_assoc(mysqli_query($link, "select * from products order by product_id LIMIT 1 "));
                                $loanType = $product['product_id'];
                            }


                            $documents_required = mysqli_query($link, "select (product_configuration) from products where product_id = '$loanType'") or die("Could not select the required documents");
                            $docType = mysqli_fetch_array($documents_required);

                            $requiredDocs = json_decode($docType['product_configuration'], true);
                            if (isset($_POST['upload'])) {
                                $id = $_GET['id'];
                                $tid = $_SESSION['tid'];
                                // Configure upload directory and allowed file types
                                $upload_dir = 'bdocument/';
                                $allowed_types = array('jpg', 'png', 'jpeg', 'gif', 'pdf', 'doc', 'docx', 'xls', 'xlsx');
                                $erro = false;

                                // grabing the hidden file type as array
                                $doctypes = $_POST['filetype'];


                                // Define maxsize for files i.e 2MB
                                $maxsize = 10 * 1024 * 1024;
                                // Checks if user sent an empty form
                                if (!empty(array_filter($_FILES['files']['name']))) {
                                    //Add All Documents to DB
                                    // Loop through each file in files[] array
                                    $count = 0;
                                    foreach ($_FILES['files']['tmp_name'] as $key => $value) {
                                        $fileType = $doctypes[$count];
                                        $file_tmpname = $_FILES['files']['tmp_name'][$key];
                                        $file_name = $_FILES['files']['name'][$key];
                                        $file_size = $_FILES['files']['size'][$key];
                                        $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);

                                        // Set upload file path
                                        $filepath = $upload_dir . $file_name;

                                        // Check file type is allowed or not
                                        if (in_array(strtolower($file_ext), $allowed_types, true)) {

                                            // Verify file size - 2MB max
                                            if ($file_size > $maxsize) {
                                                echo "Error: File size is larger than the allowed limit.";
                                                exit();
                                            }

                                            // If file with name already exist then append time in
                                            // front of name of the file to avoid overwriting of file
                                            if (file_exists($filepath)) {
                                                $filepath = $upload_dir . time() . $file_name;

                                                if (move_uploaded_file($file_tmpname, $filepath)) {
                                                    // Fixme file type is required to be inserted th on the db
                                                    $insert = mysqli_query($link, "INSERT INTO battachment(id,get_id,tid,attached_file, date_time, document_type, file_size, file_ext) VALUES(0,'$id','$tid','$filepath',NOW(),'$fileType','$file_size','$file_ext')") or die(mysqli_error($link));  //
                                                    $count += 1;

                                                    //Confirm if fin_info is uploaded
                                                    $fin = mysqli_query($link, "select * from fin_info where get_id='$id'");
                                                    $borrower = mysqli_query($link, "select * from borrowers where id='$id' and modified_by !=''");


                                                    // $docs_required = mysqli_num_rows($documents_required);

                                                    //Get Attached Documents
                                                    $documents_required = mysqli_query($link, "select * from battachment where get_id='$id'");
                                                    $docs_uploaded = mysqli_num_rows($documents_required);

                                                    if (mysqli_num_rows($fin) > 0 && mysqli_num_rows($borrower) > 0 && mysqli_num_rows($documents_required) > 0) {
                                                        $insert = mysqli_query($link, "UPDATE borrowers SET status = 'Active' WHERE id = '$id'") or die (mysqli_error($link));
                                                    }

                                                } else {
                                                    echo "<div class=\"alert alert-danger\" >
                                                        <a href = \"#\" class = \"close\" data-dismiss= \"alert\"> &times;</a>
                                                        Failed to upload borrower document {$file_name}!&nbsp; &nbsp;&nbsp;
                                                        </div>";
                                                }
                                            } else {

                                                if (move_uploaded_file($file_tmpname, $filepath)) {
                                                    $insert = mysqli_query($link, "INSERT INTO battachment(id,get_id,tid,attached_file, date_time, document_type, file_size, file_ext) VALUES(0,'$id','$tid','$filepath',NOW(),'$fileType','$file_size','$file_ext')") or die(mysqli_error($link));
                                                    $count += 1;
                                                    //Confirm if fin_info is uploaded
                                                    $fin = mysqli_query($link, "select * from fin_info where get_id='$id'");
                                                    if (mysqli_num_rows($fin) > 0) {
                                                        $insert = mysqli_query($link, "UPDATE borrowers SET status = 'Active' WHERE id = '$id'") or die (mysqli_error($link));
                                                    }
                                                    if (!$insert) {
                                                        echo '<div class="alert alert-danger" >
                                                                <a href = "#" class = "close" data-dismiss= "alert"> &times;</a>
                                                                Failed to upload borrower documents!&nbsp; &nbsp;&nbsp;
                                                                </div>';
                                                    } else {
                                                        echo '<div class="alert alert-success" >
                                                                <a href = "#" class = "close" data-dismiss= "alert"> &times;</a>
                                                                Borrower Documents Uploaded Successfully!&nbsp; &nbsp;&nbsp;
                                                                </div>';
                                                    }
                                                } else {
                                                    echo "<div class=\"alert alert-danger\" >
                                                            <a href = \"#\" class = \"close\" data-dismiss= \"alert\"> &times;</a>
                                                            Failed to upload borrower document {$file_name}!&nbsp; &nbsp;&nbsp;
                                                            </div>";
                                                }
                                            }
                                        } else {

                                            // If file extention not valid
                                            echo "Error uploading {$file_name} ";
                                            echo "({$file_ext} file type is not allowed)<br / >";

                                            echo "<div class=\"alert-danger\" >
                                                <a href = \"#\" class = \"close\" data-dismiss= \"alert\"> &times;</a>
                                                Error uploading {$file_name} - ({$file_ext} file type is not allowed)!&nbsp; &nbsp;&nbsp;
                                                </div>";

                                        }
                                    }
                                }
                            }


                            ?>
                            <?php
                            if (isset($_POST['save'])) {
                                if ($_FILES["image"]["name"] != "") {
                                    $target_dir = "../img/";
                                    $target_file = $target_dir . basename($_FILES["image"]["name"]);
                                    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
                                    $check = getimagesize($_FILES["image"]["tmp_name"]);

                                    $id = "Loan" . "=" . rand(10000000, 340000000);

                                    $sourcepath = $_FILES["image"]["tmp_name"];
                                    $targetpath = "../img/" . $_FILES["image"]["name"];
                                    move_uploaded_file($sourcepath, $targetpath);

                                    $location = "img/" . $_FILES['image']['name'];
                                } else {
                                    $location = "";

                                }
                                $addrs1 = $_POST['physical1'] . "\r\n" . $_POST['physical2'];
                                $addrs2 = $_POST['postal1'] . "\r\n" . $_POST['postal2'];

                                $date = date('Y-m-d H:i:s');
                                $update = mysqli_query($link, "UPDATE borrowers set title='" . $_POST['title'] . "', fname='" . $_POST['fname'] . "',
                                     lname='" . $_POST['lname'] . "',  id_number='" . $_POST['id_number'] . "' , passport='" . $_POST['passport'] . "' , postal='" . $_POST['postal'] . "', ownershipType='" . $_POST['ownershipType'] . "' , phone='" . $_POST['phone'] . "',
                                      country='" . $_POST['country'] . "', comment='" . $_POST['comment'] . "', member='" . $_POST['membership'] . "', telephone = '" . $_POST['telephone'] . "', marital = '" . $_POST['borrower_marital_status'] . "', marriageType = '" . $_POST['marriageType'] . "',
                                     employment_status='" . $_POST['employment_status'] . "' ,employer='" . $_POST['employer'] . "' ,employer='" . $_POST['employer'] . "',email='" . $_POST['email'] . "' , date_of_birth = '" . $_POST['date_of_birth'] . "',
                                     addrs1='" . $addrs1 . "', addrs2='" . $addrs2 . "', image='" . $location . "', modified_by='" . $tid . "', modified_on='" . $date . "', district='" . $_POST['district'] . "', telephone='" . $_POST['telephone'] . "',
                                     gender='" . $_POST['gender'] . "' WHERE id='" . $_POST['id'] . "'")
                                or die (mysqli_error($link));
                                if (!$update) {
                                    echo '<div class="alert alert-info" >
                                                <a href = "#" class = "close" data-dismiss= "alert"> &times;</a>
                                                Updated Borrower Successfully!&nbsp; &nbsp;&nbsp;
                                                </div>';
                                } else {

                                    ?>

                                    <?php
                                    echo '<div class="alert alert-success" >
                                                <a href = "#" class = "close" data-dismiss= "alert"> &times;</a>
                                                Updated Borrower Successfully!&nbsp; &nbsp;&nbsp;
                                                </div>';
                                }
                            }
                            ?>
                            <?php
                            if (isset($_POST['delrow'])) {
                                $idm = $_GET['id'];
                                $id = $_POST['selector'];
                                $N = count($id);
                                if ($N == 0) {
                                    echo "<script>alert('Row Not Selected!!!'); </script>";
                                    echo "<script>window.location='updateborrowers.php?id=" . $idm . "&&mid=" . base64_encode("403") . "'; </script>";
                                } else {
                                    for ($i = 0; $i < $N; $i++) {
                                        $result = mysqli_query($link, "DELETE FROM fin_info WHERE id ='$id[$i]'");
                                        echo "<script>window.location='updateborrowers.php?id=" . $idm . "&&mid=" . base64_encode("403") . "'; </script>";
                                    }
                                }
                            }

                            if (isset($_POST['delDocument'])) {
                                $id = $_POST['delDocument'];

                                $file = mysqli_query($link, "SELECT attached_file FROM battachment WHERE id='$id'") or die(mysqli_error($link));
                                if (mysqli_num_rows($file) > 0) {
                                    $filepath = mysqli_fetch_array($file);
                                    $path = $filepath[0];


                                    unlink($path);
                                    $result = mysqli_query($link, "DELETE FROM battachment WHERE id ='$id'");
                                    if ($result) {
                                        echo '<div class="alert alert-success" >
                                                    <a href = "#" class = "close" data-dismiss= "alert"> &times;</a>
                                                    Borrower Document Removed Successfully!&nbsp; &nbsp;&nbsp;
                                                    </div>';
                                    }

                                }
                            }
                            ?>

                            <?php

                            ?>
                            <?php
                            if (isset($_POST['addIncome'])) {
                                $id = $_GET['id'];
                                $tid = $_SESSION['tid'];
                                mysqli_query($link, "delete from fin_info where get_id='$id'");
                                foreach ($_POST['occupationDetails'] as $key => $value) {
                                    $occupation = $value['occupation'];
                                    $income = $value['mincome'];
                                    $frequency = $value['frequency'];
                                    $date = date('Y-m-d H:i:s');
                                    $insert = mysqli_query($link, "insert into fin_info values(0, '$id', '$tid', '$occupation', '$income', '$frequency')");
                                    mysqli_query($link, "UPDATE borrowers SET occupation = '$occupation', modified_by='$tid', modified_on='$date' WHERE id = '$id'") or die (mysqli_error($link));

                                    //Confirm if fin_info is uploaded
                                    $fin = mysqli_query($link, "select * from fin_info where get_id='$id'");
                                    $borrower = mysqli_query($link, "select * from borrowers where id='$id' and modified_by !=''");


                                    //Get Attached Documents
                                    $documents_required = mysqli_query($link, "select * from battachment where get_id='$id'");

                                    if (mysqli_num_rows($fin) > 0 && mysqli_num_rows($borrower) > 0 && mysqli_num_rows($documents_required) > 0) {
                                        $insert = mysqli_query($link, "UPDATE borrowers SET status = 'Active' WHERE id = '$id'") or die (mysqli_error($link));
                                    }
                                }
                                if (!$insert) {
                                    echo '<div class="alert alert-warning" >
                                        <a href = "#" class = "close" data-dismiss= "alert"> &times;</a>
                                         Income Failed to Save!&nbsp; &nbsp;&nbsp;
                                           </div>';
                                } else {
                                    echo '<div class="alert alert-success" >
                                        <a href = "#" class = "close" data-dismiss= "alert"> &times;</a>
                                         Income Successfully Saved!&nbsp; &nbsp;&nbsp;
                                           </div>';
                                }
                            }
                            ?>
                            <?php
                            if (isset($_GET['document'])) {
                                $files = $_GET['document'];
                            } else {
                                $files = "";
                            }
                            ?>
                            <div class="col-md-14">
                                <div class="nav-tabs-custom">
                                    <ul class="nav nav-tabs">
                                        <?php if ($files == "") { ?>
                                            <li class=<?php if ((!isset($_POST['save']) || isset($_POST['save'])) && isset($_GET['document']) && !isset($_POST['addIncome']) && !isset($_POST['upload']) && !isset($_POST['save_empinfo']) && !isset($_POST['save_nextOfKin']) && !isset($_POST['save_borrowers_salary']) && !isset($_POST['delDocument']) && !isset($_POST['saveBankingDetails'])) {
                                                echo "active";
                                            } ?>><a href="#tab_1" data-toggle="tab">Personal Information</a></li>
                                            <li class="<?php if (isset($_POST['addIncome']) || isset($_POST['save_borrowers_salary'])) {
                                                echo "active";
                                            } ?>"><a href="#tab_2" data-toggle="tab">Financial Information</a></li>
                                            <li class="<?php if (isset($_POST['upload']) || isset($_POST['delDocument'])) {
                                                echo "active";
                                            } ?>"><a href="#tab_3" data-toggle="tab">Attachments</a></li>
                                            <li class="<?php if (isset($_POST['save_empinfo'])) {
                                                echo "active";
                                            } ?>"><a href="#tab_4" data-toggle="tab">Employer Details</a></li>
                                            <li class="<?php if (isset($_POST['save_nextOfKin'])) {
                                                echo "active";
                                            } ?>"><a href="#tab_5" data-toggle="tab">Next ok Kin Details</a></li>
                                            <li class="<?php if (isset($_POST['saveBankingDetails'])) {
                                                echo "active";
                                            } ?>"><a href="#tab_6" data-toggle="tab">Banking Details</a></li>
                                        <?php } else { ?>
                                            <li><a href="#tab_1" data-toggle="tab">Personal Information</a></li>
                                            <li><a href="#tab_2" data-toggle="tab">Financial Information</a></li>
                                            <li><a href="#tab_3" data-toggle="tab">Attachments</a></li>
                                            <li><a href="#tab_4" data-toggle="tab">Employer Information</a></li>
                                            <li><a href="#tab_5" data-toggle="tab">Next of Kin Details</a></li>
                                            <li><a href="#tab_6" data-toggle="tab">Banking Details</a></li>
                                        <?php } ?>
                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane <?php if ((!isset($_POST['save']) || isset($_POST['save'])) && isset($_GET['document']) && !isset($_POST['addIncome']) && !isset($_POST['upload']) && !isset($_POST['save_empinfo']) && !isset($_POST['save_nextOfKin']) && !isset($_POST['save_borrowers_salary']) && !isset($_POST['delDocument']) && !isset($_POST['saveBankingDetails'])) {
                                            echo "active";
                                        } ?>" id="tab_1">

                                            <?php
                                            $id = $_GET['id'];
                                            $borrowerId = $_GET['id'];
                                            $select = mysqli_query($link, "SELECT * FROM borrowers WHERE id = '$id'") or die (mysqli_error($link));
                                            while ($row = mysqli_fetch_array($select)) {
                                                ?>
                                                <form class="form-horizontal" method="post"
                                                      enctype="multipart/form-data">
                                                    <div class="box-body">
                                                        <div class="col-lg-6">

                                                            <input type="hidden" name="id" class="txtField"
                                                                   value="<?php echo $row['id']; ?>">
                                                            <div class="form-group">
                                                                <label for="" class="col-sm-3 control-label">Borrower
                                                                    Photo</label>
                                                                <div class="col-sm-9">
                                                                    <input type='file' name="image"
                                                                           onChange="readURL(this);"/>
                                                                    <?php if ($row['image'] !== "") { ?>
                                                                        <img id="blah" class="img-circle"
                                                                             src="../<?php echo $row['image']; ?>"
                                                                             alt="Image Here" height="100" width="100"/>
                                                                    <?php } else { ?>
                                                                        <img id="blah" src="../img/user.png"
                                                                             alt="Image Here4" height="100"
                                                                             width="100"/>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for=""
                                                                       class="col-sm-3 control-label">Title</label>
                                                                <div class="col-sm-9">
                                                                    <select name="title" class="form-control" required>
                                                                        <option value="">Select</option>
                                                                        <option value="MR" <?php if ($row['title'] == "MR") {
                                                                            echo "selected";
                                                                        } ?>>Mister
                                                                        </option>
                                                                        <option value="MRS" <?php if ($row['title'] == "MRS") {
                                                                            echo "selected";
                                                                        } ?>>Mrs.
                                                                        </option>
                                                                        <option value="MISS" <?php if ($row['title'] == "MISS") {
                                                                            echo "selected";
                                                                        } ?>>Miss
                                                                        </option>
                                                                        <option value="SIR" <?php if ($row['title'] == "SIR") {
                                                                            echo "selected";
                                                                        } ?>>Sir
                                                                        </option>
                                                                        <option value="ADV" <?php if ($row['title'] == "ADV") {
                                                                            echo "selected";
                                                                        } ?>>Advocate
                                                                        </option>
                                                                        <option value="DR" <?php if ($row['title'] == "DR") {
                                                                            echo "selected";
                                                                        } ?>>Doctor
                                                                        </option>
                                                                        <option value="PROF" <?php if ($row['title'] == "PROF") {
                                                                            echo "selected";
                                                                        } ?>>Professor
                                                                        </option>
                                                                        <option value="PAST" <?php if ($row['title'] == "PAST") {
                                                                            echo "selected";
                                                                        } ?>>Pastoor
                                                                        </option>
                                                                        <option value="REV" <?php if ($row['title'] == "REV") {
                                                                            echo "selected";
                                                                        } ?>>Reverend
                                                                        </option>
                                                                        <option value="LORD" <?php if ($row['title'] == "LORD") {
                                                                            echo "selected";
                                                                        } ?>>Lord
                                                                        </option>
                                                                        <option value="CAPT" <?php if ($row['title'] == "CAPT") {
                                                                            echo "selected";
                                                                        } ?>>Captain
                                                                        </option>
                                                                        <option value="LADY" <?php if ($row['title'] == "LADY") {
                                                                            echo "selected";
                                                                        } ?>>Lady
                                                                        </option>
                                                                        <option value="COL" <?php if ($row['title'] == "COL") {
                                                                            echo "selected";
                                                                        } ?>>Colonel
                                                                        </option>
                                                                        <option value="DS" <?php if ($row['title'] == "DS") {
                                                                            echo "selected";
                                                                        } ?>>Dominee
                                                                        </option>
                                                                        <option value="JUDGE" <?php if ($row['title'] == "JUDGE") {
                                                                            echo "selected";
                                                                        } ?>>Judge
                                                                        </option>
                                                                        <option value="KAPT" <?php if ($row['title'] == "KAPT") {
                                                                            echo "selected";
                                                                        } ?>>Kaptein
                                                                        </option>
                                                                        <option value="KOL" <?php if ($row['title'] == "KOL") {
                                                                            echo "selected";
                                                                        } ?>>Kolonel
                                                                        </option>
                                                                        <option value="LT" <?php if ($row['title'] == "LT") {
                                                                            echo "selected";
                                                                        } ?>>Lieutenant
                                                                        </option>
                                                                        <option value="MAJ" <?php if ($row['title'] == "MAJ") {
                                                                            echo "selected";
                                                                        } ?>>Major
                                                                        </option>
                                                                        <option value="ME" <?php if ($row['title'] == "ME") {
                                                                            echo "selected";
                                                                        } ?>>MEJ/MEV
                                                                        </option>
                                                                        <option value="MEJ" <?php if ($row['title'] == "MEJ") {
                                                                            echo "selected";
                                                                        } ?>>Mejufrou
                                                                        </option>
                                                                        <option value="MEV" <?php if ($row['title'] == "MEV") {
                                                                            echo "selected";
                                                                        } ?>>Mejufrou
                                                                        </option>
                                                                        <option value="SERS" <?php if ($row['title'] == "SERS") {
                                                                            echo "selected";
                                                                        } ?>>Sersant
                                                                        </option>
                                                                        <option value="SGT" <?php if ($row['title'] == "SGT") {
                                                                            echo "selected";
                                                                        } ?>>Sergeant
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="membership" class="col-sm-3 control-label">Regular</label>
                                                                <div class="col-sm-9">
                                                                    <input type="checkbox" id="membership"
                                                                           name="membership"
                                                                           value="1" <?php if ($row['member'] == "1") {
                                                                        echo "checked";
                                                                    } ?>>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="" class="col-sm-3 control-label">First
                                                                    Name
                                                                    <req style="color:red">&nbsp;*</req>
                                                                </label>
                                                                <div class="col-sm-9">
                                                                    <input name="fname" type="text" class="form-control"
                                                                           value="<?php echo $row['fname']; ?>"
                                                                           placeholder="First Name"
                                                                    >
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="" class="col-sm-3 control-label">Last
                                                                    Name
                                                                    <req style="color:red">&nbsp;*</req>
                                                                </label>
                                                                <div class="col-sm-9">
                                                                    <input name="lname" type="text"
                                                                           value="<?php echo $row['lname']; ?>"
                                                                           class="form-control" placeholder="Last Name"
                                                                           required>
                                                                </div>
                                                            </div>
                                                            <div class="form-group"><label
                                                                        class="col-sm-3 control-label">Marital
                                                                    Status</label>
                                                                <div class="col-sm-9"><select class="form-control"
                                                                                              name="borrower_marital_status"
                                                                                              id="inputBorrowerEORS"
                                                                                              onchange="showMaritalField(this.options[this.selectedIndex].value)"
                                                                                              required>
                                                                        <option value="" selected disabled></option>
                                                                        <option <?php if ($row['marital'] == "Single") {
                                                                            echo "selected";
                                                                        } ?> value="Single">Single
                                                                        </option>
                                                                        <option <?php if ($row['marital'] == "Married") {
                                                                            echo "selected";
                                                                        } ?> value="Married">Married
                                                                        </option>
                                                                        <option <?php if ($row['marital'] == "Widowed") {
                                                                            echo "selected";
                                                                        } ?> value="Widowed">Widowed
                                                                        </option>
                                                                        <option <?php if ($row['marital'] == "Divorced") {
                                                                            echo "selected";
                                                                        } ?> value="Divorced">Divorced
                                                                        </option>
                                                                        <option <?php if ($row['marital'] == "Separated") {
                                                                            echo "selected";
                                                                        } ?> value="Separated">Separated
                                                                        </option>
                                                                        <option <?php if ($row['marital'] == "Registered Partnership") {
                                                                            echo "selected";
                                                                        } ?> value="Registered Partnership">Registered
                                                                            Partnership
                                                                        </option>
                                                                    </select></div>
                                                            </div>

                                                            <div id="div_marital_status"></div>
                                                            <div class="form-group"><label
                                                                        class="col-sm-3 control-label">Working
                                                                    Status
                                                                    <req style="color:red">&nbsp;*</req>
                                                                </label>
                                                                <div class="col-sm-9"><select class="form-control"
                                                                                              name="employment_status"
                                                                                              id="inputBorrowerEORS"
                                                                                              required>
                                                                        <option value="" disabled>select</option>
                                                                        <option <?php if ($row['employment_status'] == "Employee") {
                                                                            echo "selected";
                                                                        } ?>>Employee
                                                                        </option>
                                                                        <option <?php if ($row['employment_status'] == "employment_status") {
                                                                            echo "selected";
                                                                        } ?>>Government Employee
                                                                        </option>
                                                                        <option <?php if ($row['employment_status'] == "Private Sector Employee") {
                                                                            echo "selected";
                                                                        } ?>>Private Sector Employee
                                                                        </option>
                                                                        <option <?php if ($row['employment_status'] == "Owner") {
                                                                            echo "selected";
                                                                        } ?>>Owner
                                                                        </option>
                                                                        <option <?php if ($row['employment_status'] == "Student") {
                                                                            echo "selected";
                                                                        } ?>>Student
                                                                        </option>
                                                                        <option <?php if ($row['employment_status'] == "Overseas Worker") {
                                                                            echo "selected";
                                                                        } ?>>Overseas Worker
                                                                        </option>
                                                                        <option <?php if ($row['employment_status'] == "Pensioner") {
                                                                            echo "selected";
                                                                        } ?>>Pensioner
                                                                        </option>
                                                                        <option <?php if ($row['employment_status'] == "Self-employed") {
                                                                            echo "selected";
                                                                        } ?>>Self-employed
                                                                        </option>
                                                                    </select></div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for=""
                                                                       class="col-sm-3 control-label">Employer
                                                                    <req style="color:red">&nbsp;*</req>
                                                                </label>
                                                                <div class="col-sm-9">
                                                                    <input name="employer" type="text"
                                                                           value="<?php echo $row['employer']; ?>"
                                                                           class="form-control"
                                                                           maxlength="60"
                                                                           placeholder="Institution"
                                                                           required>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="" class="col-sm-3 control-label">Date of
                                                                    Birth
                                                                    <req style="color:red">&nbsp;*</req>
                                                                </label>
                                                                <div class="col-sm-9">
                                                                    <input name="date_of_birth" type="date"
                                                                           value="<?php echo $row['date_of_birth']; ?>"
                                                                           class="form-control"
                                                                           placeholder="Date of Birth"
                                                                           max="<?php echo date("Y-m-d", strtotime('-18 years')); ?>"
                                                                           required>
                                                                </div>
                                                            </div>
                                                            <?php
                                                            $passport=$row['passport'];
                                                            $id_number=$row['id_number'];
                                                            ?>
                                                            <div class="form-group">
                                                                <label class="col-sm-3 control-label" for="Passport">ID /
                                                                    Passport <req style="color:red">&nbsp;*</req></label>
                                                                <div class="col-sm-9">
                                                                    <input type="radio" class="form-control_new id_pass"
                                                                           name="idNumber_passport" id="passport_no_1" value="ID No"
                                                                           <?php if ($row['id_number'] != ''){ ?>checked<?php } ?>>
                                                                    ID No
                                                                    <input type="radio" class="form-control_new id_pass"
                                                                           name="idNumber_passport" id="passport_no_2"
                                                                           value="Passport"
                                                                           <?php if ($row['passport'] != ''){ ?>checked<?php } ?>>
                                                                    Passport

                                                                    <div id="div_idno"></div>
                                                                    <div id="div_passport"></div>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for=""
                                                                       class="col-sm-3 control-label">Gender
                                                                    <req style="color:red">&nbsp;*</req>
                                                                </label>
                                                                <div class="col-sm-9">
                                                                    <select name="gender" class="form-control" required>
                                                                        <option value="">Select</option>
                                                                        <option <?php if ($row['gender'] == "Male") {
                                                                            echo "selected";
                                                                        } ?>>Male
                                                                        </option>
                                                                        <option <?php if ($row['gender'] == "Female") {
                                                                            echo "selected";
                                                                        } ?>>Female
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="form-group">
                                                                <label for=""
                                                                       class="col-sm-3 control-label">Email</label>
                                                                <div class="col-sm-9">
                                                                    <input type="email" name="email" type="text"
                                                                           value="<?php echo $row['email']; ?>"
                                                                           class="form-control"
                                                                           placeholder="Email">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="" class="col-sm-3 control-label">Mobile
                                                                    Number
                                                                    <req style="color:red">&nbsp;*</req>
                                                                </label>
                                                                <div class="col-sm-9">
                                                                    <input name="phone" type="number"
                                                                           value="<?php echo $row['phone']; ?>"
                                                                           class="form-control"
                                                                           oninput="maxLengthCheck(this)"
                                                                           maxlength="20"
                                                                           placeholder="Mobile Number"
                                                                           required>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="" class="col-sm-3 control-label">Telephone
                                                                    Number
                                                                    <req style="color:red">&nbsp;*</req>
                                                                </label>
                                                                <div class="col-sm-9">
                                                                    <input name="telephone"
                                                                           value="<?php echo $row['telephone']; ?>"
                                                                           type="number" class="form-control"
                                                                           placeholder="Telephone/Home Number"
                                                                           required>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="" class="col-sm-3 control-label">Physical
                                                                    Line 1
                                                                    <req style="color:red">&nbsp;*</req>
                                                                </label>
                                                                <div class="col-sm-9">
                                                                    <input name="physical1" type="text"
                                                                           value="<?php echo explode("\r\n", $row['addrs1'])[0]; ?>"
                                                                           maxlength="25" class="form-control"
                                                                           placeholder="Physical Address, Line 1"
                                                                           required>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="" class="col-sm-3 control-label">Physical
                                                                    Line 2</label>
                                                                <div class="col-sm-9">
                                                                    <?php if ($row['addrs1'] == null) { ?>
                                                                        <input name="physical2" type="text" value=""
                                                                               maxlength="25" class="form-control"
                                                                               placeholder="Physical Address, Line 2">
                                                                    <?php } else { ?>
                                                                        <input name="physical2" type="text"
                                                                               value="<?php echo explode("\r\n", $row['addrs1'])[1]; ?>"
                                                                               maxlength="25" class="form-control"
                                                                               placeholder="Physical Address, Line 2">
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="" class="col-sm-3 control-label">OwnerShip
                                                                    Type </label>
                                                                <div class="col-sm-9">
                                                                    <select name="ownershipType" class="form-control">
                                                                        <option value="">Select</option>
                                                                        <option value="O" <?php if ($row['ownershipType'] == "O") {
                                                                            echo "selected";
                                                                        } ?>>Owner
                                                                        </option>
                                                                        <option value="T"<?php if ($row['ownershipType'] == "T") {
                                                                            echo "selected";
                                                                        } ?>>Tenant
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="" class="col-sm-3 control-label">Postal Line
                                                                    1
                                                                    <req style="color:red">&nbsp;*</req>
                                                                </label>
                                                                <div class="col-sm-9">
                                                                    <?php if ($row['addrs2'] == null) { ?>
                                                                        <input name="postal1" type="text" value=""
                                                                               maxlength="25" class="form-control"
                                                                               placeholder="Postal Address, Line 1"
                                                                               required>
                                                                    <?php } else { ?>
                                                                        <input name="postal1" type="text"
                                                                               value="<?php echo explode("\r\n", $row['addrs2'])[0]; ?>"
                                                                               maxlength="25" class="form-control"
                                                                               placeholder="Postal Address, Line 1"
                                                                               required>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="" class="col-sm-3 control-label">Postal Line
                                                                    2</label>
                                                                <div class="col-sm-9">
                                                                    <?php if ($row['addrs2'] == null) { ?>
                                                                        <input name="postal2" type="text" value=""
                                                                               maxlength="25" class="form-control"
                                                                               placeholder="Postal Address, Line 2">
                                                                    <?php } else { ?>
                                                                        <input name="postal2" type="text"
                                                                               value="<?php echo explode("\r\n", $row['addrs2'])[1]; ?>"
                                                                               maxlength="25" class="form-control"
                                                                               placeholder="Postal Address, Line 2">
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="" class="col-sm-3 control-label">Postal Code
                                                                    <req style="color:red">&nbsp;*</req>
                                                                </label>
                                                                <div class="col-sm-9">
                                                                    <input name="postal" type="text"
                                                                           value="<?php echo $row['postal']; ?>"
                                                                           maxlength="4" class="form-control"
                                                                           placeholder="Postal Code of the Postal address"
                                                                           required>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for=""
                                                                       class="col-sm-3 control-label">Country
                                                                    <req style="color:red">&nbsp;*</req>
                                                                </label>
                                                                <?php
                                                                // PHP code to extract IP

                                                                function getVisIpAddr()
                                                                {

                                                                    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                                                                        return $_SERVER['HTTP_CLIENT_IP'];
                                                                    } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                                                                        return $_SERVER['HTTP_X_FORWARDED_FOR'];
                                                                    } else {
                                                                        return $_SERVER['REMOTE_ADDR'];
                                                                    }
                                                                }

                                                                // Store the IP address
                                                                $vis_ip = getVisIPAddr();
                                                                // Use JSON encoded string and converts
                                                                // it into a PHP variable
                                                                $ipdat = @json_decode(file_get_contents(
                                                                    "http://www.geoplugin.net/json.gp?ip=" . $vis_ip));

                                                                /*echo 'Country Name: ' . $ipdat->geoplugin_countryName . "\n";
                                                                echo 'Country Code: ' . $ipdat->geoplugin_countryCode . "\n";
                                                                echo 'City Name: ' . $ipdat->geoplugin_city . "\n";
                                                                echo 'Continent Name: ' . $ipdat->geoplugin_continentName . "\n";
                                                                echo 'Latitude: ' . $ipdat->geoplugin_latitude . "\n";
                                                                echo 'Longitude: ' . $ipdat->geoplugin_longitude . "\n";
                                                                echo 'Currency Symbol: ' . $ipdat->geoplugin_currencySymbol . "\n";
                                                                echo 'Currency Code: ' . $ipdat->geoplugin_currencyCode . "\n";
                                                                echo 'Timezone: ' . $ipdat->geoplugin_timezone;*/
                                                                ?>

                                                                <div class="col-sm-9">
                                                                    <select name="country" class="form-control"
                                                                            readonly>
                                                                        <?php if ($ipdat->geoplugin_countryName == "") { ?>
                                                                            <option value="">Select a country&hellip;
                                                                            </option>
                                                                        <?php } else { ?>
                                                                            <option value="<?php echo $ipdat->geoplugin_countryName; ?>"
                                                                                    selected="selected"><?php echo $ipdat->geoplugin_countryName; ?></option>
                                                                        <?php } ?>
                                                                        <option value="ZA">Lesotho</option>
                                                                        <option value="ZA">South Africa</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for=""
                                                                       class="col-sm-3 control-label">District
                                                                    <req style="color:red">&nbsp;*</req>
                                                                </label>
                                                                <div class="col-sm-9">
                                                                    <select name="district" class="form-control"
                                                                            required>
                                                                        <option value="">Select</option>
                                                                        <option <?php if ($row['district'] == "Butha Buthe") {
                                                                            echo "selected";
                                                                        } ?>>Butha Buthe
                                                                        </option>
                                                                        <option
                                                                            <?php if ($row['district'] == "Leribe") {
                                                                                echo "selected";
                                                                            } ?>value="Leribe">Leribe
                                                                        </option>
                                                                        <option <?php if ($row['district'] == "Berea") {
                                                                            echo "selected";
                                                                        } ?>>Berea
                                                                        </option>
                                                                        <option <?php if ($row['district'] == "Maseru") {
                                                                            echo "selected";
                                                                        } ?>>Maseru
                                                                        </option>
                                                                        <option <?php if ($row['district'] == "Mafeteng") {
                                                                            echo "selected";
                                                                        } ?>>Mafeteng
                                                                        </option>
                                                                        <option <?php if ($row['district'] == "Mohales Hoek") {
                                                                            echo "selected";
                                                                        } ?>>Mohales Hoek
                                                                        </option>
                                                                        <option <?php if ($row['district'] == "Quthing") {
                                                                            echo "selected";
                                                                        } ?>>Quthing
                                                                        </option>
                                                                        <option <?php if ($row['district'] == "Qachas Nek") {
                                                                            echo "selected";
                                                                        } ?>>Qachas Nek
                                                                        </option>
                                                                        <option <?php if ($row['district'] == "Thaba Tseka") {
                                                                            echo "selected";
                                                                        } ?>>Thaba Tseka
                                                                        </option>
                                                                        <option <?php if ($row['district'] == "Mokhotlong") {
                                                                            echo "selected";
                                                                        } ?>>Mokhotlong
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                            </div>


                                                            <div class="form-group">
                                                                <label for=""
                                                                       class="col-sm-3 control-label">Comment</label>
                                                                <div class="col-sm-9"><textarea name="comment"
                                                                                                class="form-control"
                                                                                                rows="2"
                                                                                                cols="80"><?php echo $row['comment']; ?></textarea>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div align="center">
                                                        <div class="box-footer">
                                                            <button type="reset" class="btn btn-primary"><i
                                                                        class="fa fa-times">&nbsp;Reset</i>
                                                            </button>
                                                            <button name="save" type="submit"
                                                                    class="btn btn-success "><i
                                                                        class="fa fa-save">&nbsp;Update</i>
                                                            </button>

                                                        </div>
                                                    </div>

                                                </form>
                                            <?php } ?>
                                        </div>
                                        <!-- /.tab-pane -->

                                        <div class="tab-pane <?php if (isset($_POST['addIncome']) || isset($_POST['save_borrowers_salary'])) {
                                            echo "active";
                                        } ?>" id="tab_2">


                                            <form class="form-horizontal" method="post"
                                                   enctype="multipart/form-data">
                                                <div class="box-body">

                                                    <div class="table-responsive" data-pattern="priority-columns">

                                                        <table cellspacing="0" id="loan-fees"
                                                               class="table table-small-font table-bordered table-striped">

                                                            <thead>
                                                            <tr>
                                                                <th width="2%"><input id="checkAll_occupation"
                                                                                      class="formcontrol"
                                                                                      type="checkbox">
                                                                </th>
                                                                <th width="20%">Occupation</th>
                                                                <th width="20%">Monthly Income</th>
                                                                <th width="20%">Income Frequency</th>
                                                            </tr>
                                                            </thead>

                                                            <tbody>

                                                            <?php
                                                            //Get all Settings
                                                            $count = 0;
                                                            $loan_fees = mysqli_query($link, "SELECT * FROM fin_info WHERE get_id = '$id'");

                                                            // Get the contents of the JSON file
                                                            $strJsonFileContents = file_get_contents('include/packages.json');
                                                            $arrayOfTypes = json_decode($strJsonFileContents, true);
                                                            //echo $arrayOfTypes;

                                                            while ($finInfo = mysqli_fetch_assoc($loan_fees)) {
                                                                $id = $finInfo['id'];
                                                                $idm = $_GET['id'];
                                                                $occupation = $finInfo['occupation'];
                                                                ?>
                                                                <input type="hidden"
                                                                       name="occupation[<?php echo $count; ?> ][id]"
                                                                       value="<?php echo $id; ?>">
                                                                <tr>
                                                                    <td width="30"><input id="optionsCheckbox"
                                                                                          class="uniform_on"
                                                                                          name="selector[]"
                                                                                          type="checkbox"
                                                                                          value="<?php echo $id; ?>"
                                                                        >
                                                                    </td>
                                                                    <td width="800"><input
                                                                                name="occupationDetails[<?php echo $count; ?> ][occupation]"
                                                                                type="text"
                                                                                class="form-control"
                                                                                maxlength="20"
                                                                                placeholder="Occupation"
                                                                                value="<?php echo $finInfo['occupation']; ?>">
                                                                    </td>
                                                                    <td width="300"><input
                                                                                name="occupationDetails[<?php echo $count; ?> ][mincome]"
                                                                                type="number"
                                                                                step="0.01"
                                                                                class="form-control"
                                                                                placeholder="Amount"
                                                                                value="<?php echo $finInfo['mincome']; ?>">
                                                                    </td>
                                                                    <td width="300">
                                                                        <select name="occupationDetails[<?php echo $count; ?> ][frequency]"
                                                                                class="form-control">
                                                                            <option value="<?php echo $finInfo['frequency']; ?>"
                                                                                    selected>--Select--
                                                                            </option>
                                                                            <?php
                                                                            foreach ($arrayOfTypes['incomeFrequencyCode'] as $key => $value) {
                                                                                if ($finInfo['frequency'] == $key) {
                                                                                    echo "<option value='$key' selected>$value</option>";
                                                                                }
                                                                                echo "<option value='$key'>$value</option>";
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>

                                                            <tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div align="Left">
                                                    <button id="addRows_occupation" type="button"
                                                            class="btn btn-success btn-flat"><i class="fa fa-plus">&nbsp;Add</i>
                                                    </button>
                                                    <button name="delrow" type="submit" class="btn btn-danger btn-flat">
                                                        <i
                                                                class="fa fa-trash">&nbsp;Delete Record</i></button>

                                                </div>
                                                <div align="center" class="box-footer">
                                                    <button type="submit" class="btn btn-info btn-flat"
                                                            name="addIncome">
                                                        <i class="fa fa-save">&nbsp;Update Financial Information</i>
                                                    </button>
                                                </div>
                                            </form>
                                            <br>
                                            <h3>Section G– Affordability Criteria</h3>
                                            <form action="" method="post">
                                                <table class="table table-borderless">
                                                    <tr>
                                                        <th>
                                                            <h4>Basic Pay</h4>
                                                        </th>
                                                        <td>
                                                            <input type="number"
                                                                   name="basicPay"
                                                                   class="form-control"
                                                                   style="text-align:right; font-weight: bold;"
                                                                   onkeyup="updateMaximumAvailable()"
                                                                   step="0.01"
                                                                   id="basicPay"
                                                                   placeholder="Basic Pay"
                                                                   value="<?php if ($salaryInfo['basic_pay']) {
                                                                       echo $salaryInfo['basic_pay'];
                                                                   } ?>"/>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>
                                                            Additional Fixed Allowance
                                                        </th>
                                                        <td><input type="number"
                                                                   name="additionalFixed"
                                                                   class="form-control"
                                                                   style="text-align:right;"
                                                                   step="0.01"
                                                                   onkeyup="updateMaximumAvailable()"
                                                                   id="additionalFixed"
                                                                   placeholder="Additional Fixed Allowance"
                                                                   value="<?php if ($salaryInfo['additional_fixed_allowance']) {
                                                                       echo $salaryInfo['additional_fixed_allowance'];
                                                                   } ?>"/></td>
                                                    </tr>
                                                    <tr>
                                                        <th>
                                                            <h4>Gross Pay</h4>
                                                        </th>
                                                        <td><input type="number"
                                                                   readonly
                                                                   name="grossPay"
                                                                   class="form-control"
                                                                   style="text-align:right; font-weight: bold;"
                                                                   id="grossPay"
                                                                   step="0.01"
                                                                   onkeyup="updateMaximumAvailable()"
                                                                   placeholder="Gross Pay"
                                                                   value="<?php if ($salaryInfo['gross_pay']) {
                                                                       echo $salaryInfo['gross_pay'];
                                                                   } ?>"/></td>
                                                    </tr>
                                                    <tr>
                                                        <th>
                                                            Statutory and Non-Statutory Deductions
                                                        </th>
                                                        <td><input type="number"
                                                                   name="statutory"
                                                                   class="form-control"
                                                                   id="statutory"
                                                                   style="text-align:right;"
                                                                   step="0.01"
                                                                   onkeyup="updateMaximumAvailable()"
                                                                   placeholder="Statutory and Non-Statutory Deductions"
                                                                   value="<?php if ($salaryInfo['statutory_deductions']) {
                                                                       echo $salaryInfo['statutory_deductions'];
                                                                   } ?>"/></td>
                                                    </tr>
                                                    <tr>
                                                        <th>
                                                            Loan Instalments to be consolidated
                                                        </th>
                                                        <td><input type="number"
                                                                   name="loanInstalments"
                                                                   class="form-control"
                                                                   id="loanInstalments"
                                                                   style="text-align:right;"
                                                                   step="0.01"
                                                                   onkeyup="updateMaximumAvailable()"
                                                                   placeholder="Loan Instalments to be consolidated"
                                                                   value="<?php if ($salaryInfo['loan_instalments']) {
                                                                       echo $salaryInfo['loan_instalments'];
                                                                   } ?>"/></td>
                                                    </tr>
                                                    <tr>
                                                        <th>
                                                            <h4>Net Pay</h4>
                                                        </th>
                                                        <td>
                                                            <input type="number"
                                                                   name="netPay"
                                                                   readonly
                                                                   style="text-align:right; font-weight: bold;"
                                                                   id="netPay"
                                                                   step="0.01"
                                                                   onkeyup="updateMaximumAvailable()"
                                                                   class="form-control"
                                                                   placeholder="Net Pay"
                                                                   value="<?php if ($salaryInfo['net_pay']) {
                                                                       echo $salaryInfo['net_pay'];
                                                                   } ?>"/></td>
                                                    </tr>
                                                    <tr>
                                                        <th>
                                                            Other Bank loan instalments
                                                        </th>
                                                        <td><input type="number"
                                                                   name="otherBankLoans"
                                                                   class="form-control"
                                                                   style="text-align:right;"
                                                                   step="0.01"
                                                                   onkeyup="updateMaximumAvailable()"
                                                                   id="otherBankInstalments"
                                                                   placeholder="Other Bank loan instalments"
                                                                   value="<?php if ($salaryInfo['other_bank_loans']) {
                                                                       echo $salaryInfo['other_bank_loans'];
                                                                   } ?>"/></td>
                                                    </tr>
                                                    <tr>
                                                        <th>
                                                            Other instalments from Compuscan
                                                        </th>
                                                        <td><input type="number"
                                                                   name="otherBankLoans"
                                                                   class="form-control"
                                                                   style="text-align:right;"
                                                                   step="0.01"
                                                                   onkeyup="updateMaximumAvailable()"
                                                                   id="otherBankInstalments"
                                                                   placeholder="Other Bank loan instalments"
                                                                   value="<?php if ($salaryInfo['compuscan']) {
                                                                       echo $salaryInfo['compuscan'];
                                                                   } ?>"/></td>
                                                    </tr>
                                                    <tr>
                                                        <th>
                                                            Monthly Living Expenses
                                                        </th>
                                                        <td><input type="number"
                                                                   name="monthly_living_expenses"
                                                                   class="form-control"
                                                                   id="monthlyLivingExpenses"
                                                                   style="text-align:right;"
                                                                   onkeyup="updateMaximumAvailable()"
                                                                   step="0.01"
                                                                   placeholder="Monthly Living Expenses"
                                                                   value="<?php if ($salaryInfo['monthly_living_expenses']) {
                                                                       echo $salaryInfo['monthly_living_expenses'];
                                                                   } ?>"/></td>
                                                    </tr>
                                                    <tr>
                                                        <th>
                                                            <h4>CDAS Maximum Available for Deduction</h4>
                                                        </th>
                                                        <td><input type="number"
                                                                   style="text-align:right; font-weight: bold;"
                                                                   name="max_available"
                                                                   class="form-control"
                                                                   id="cdasMaxAvailable"
                                                                   onkeyup="updateMaximumAvailable()"
                                                                   readonly
                                                                   step="0.01"
                                                                   placeholder="CDAS Maximum Available for Deduction"
                                                                   value="<?php if ($salaryInfo['cdas']) {
                                                                       echo $salaryInfo['cdas'];
                                                                   } ?>"/></td>
                                                    </tr>
                                                    <tr>
                                                        <th>
                                                            <h4>Maximum available for Loan repayment</h4>
                                                        </th>
                                                        <td><input type="number"
                                                                   style="text-align:right; font-weight: bold;"
                                                                   name="max_available"
                                                                   class="form-control"
                                                                   id="maxAvailable"
                                                                   onkeyup="updateMaximumAvailable()"
                                                                   readonly
                                                                   step="0.01"
                                                                   placeholder="Maximum available for Loan repayment"
                                                                   value="<?php if ($salaryInfo['max_available']) {
                                                                       echo $salaryInfo['max_available'];
                                                                   } ?>"/></td>
                                                    </tr>
                                                    <tr>
                                                </table>
                                                <div align="center">
                                                    <div class="box-footer">
                                                        <button type="reset" class="btn btn-primary"><i
                                                                    class="fa fa-times">&nbsp;Reset</i>
                                                        </button>
                                                        <button name="save_borrowers_salary" type="submit"
                                                                class="btn btn-success "><i
                                                                    class="fa fa-save">&nbsp;Update</i>
                                                        </button>

                                                    </div>
                                                </div>
                                            </form>
                                        </div>

                                        <!-- /.tab-pane -->

                                        <div class="tab-pane <?php if (isset($_POST['upload']) || isset($_POST['delDocument'])) {
                                            echo "active";
                                        } ?>" id="tab_3">


                                            <!-- attachment -->
                                            <?php
                                            if (isset($requiredDocs['requiredDocuments'])) {
                                                foreach ($requiredDocs['requiredDocuments'] as $key => $value) {
                                                    ?>
                                                    <form class="form-horizontal" method="post"
                                                    enctype="multipart/form-data">
                                                    <div class="form-group">
                                                        <label for=""
                                                               class="col-sm-3 control-label"> <?php echo $value['documentType'];
                                                            if ($value['required']) {
                                                                echo "<req style=\"color:red\">&nbsp;*</req>";
                                                            } ?></label>
                                                        <div class="col-sm-3">
                                                            <input name="files[]" type="file"
                                                                   class="btn btn-info" <?php if ($value['required']) {
                                                                echo "required";
                                                            } ?> />
                                                            <input type="text" name="filetype[]"
                                                                   value="<?php echo $value['documentType'] ?>" hidden/>
                                                        </div>
                                                    </div>
                                                <?php } ?>


                                                <div class="form-group text-center">
                                                    <button type="submit" class="btn btn-success btn-flat"
                                                            name="upload"><i class="fa fa-upload">&nbsp;Upload</i>
                                                </div>
                                                </form>
                                            <?php } ?>

                                            <div class="panel panel-success">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title"><i class="fa fa-user"></i>&nbsp;All Uploaded
                                                        Documents</h3>
                                                </div>

                                                <table id="example1" class="table table-bordered table-striped">
                                                    <thead>
                                                    <tr>
                                                        <th>
                                                            Date Uploaded
                                                        </th>
                                                        <th>
                                                            Document Type
                                                        </th>
                                                        <th>
                                                            Uploaded By
                                                        </th>
                                                        <th>
                                                            Type
                                                        </th>
                                                        <th>
                                                            Action
                                                        </th>
                                                    </thead>
                                                    </tr>
                                                    <?php
                                                    $id = $_GET['id'];
                                                    $se = mysqli_query($link, "SELECT * FROM battachment WHERE get_id='$id'") or die (mysqli_error($link));
                                                    while ($gete = mysqli_fetch_array($se)) {
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $gete['date_time']; ?></td>
                                                            <td><?php
                                                                //Get Document Type description
                                                                echo $gete['document_type']
                                                                ?>
                                                            </td>
                                                            <td><?php
                                                                //Get User
                                                                $user = $gete['tid'];
                                                                $username = mysqli_fetch_assoc(mysqli_query($link, "select * from user where id='$user'"));
                                                                echo $username['name'];

                                                                ?>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                $bytes = $gete['file_size'];
                                                                //Show Size of file
                                                                if ($bytes >= 1073741824) {
                                                                    $bytes = number_format($bytes / 1073741824, 1) . ' GB';
                                                                } elseif ($bytes >= 1048576) {
                                                                    $bytes = number_format($bytes / 1048576, 1) . ' MB';
                                                                } elseif ($bytes >= 1024) {
                                                                    $bytes = number_format($bytes / 1024, 1) . ' KB';
                                                                } elseif ($bytes > 1) {
                                                                    $bytes = $bytes . ' bytes';
                                                                }

                                                                $type = $gete['file_ext'];
                                                                $attachment = $gete['attached_file'];
                                                                //Show Size of file
                                                                if (strtolower($type) == "jpg" || strtolower($type) == ("png") || strtolower($type) == "jpeg") {
                                                                    $type = "<a href='" . $attachment . "'><i class='fa fa-file-image-o'></i></a>";
                                                                } elseif (strtolower($type) == "xls" || strtolower($type) == "xlsx") {
                                                                    $type = "<a href='" . $attachment . "'><i class='fa fa-file-excel-o'></i></a>";
                                                                } elseif (strtolower($type) == "doc" || strtolower($type) == "docx") {
                                                                    $type = "<a href='" . $attachment . "'><i class='fa fa-file-word-o'></i></a>";
                                                                } elseif (strtolower($type) == "pdf") {
                                                                    $type = "<a href='" . $attachment . "'><i class='fa fa-file-pdf-o'></i></a>";
                                                                }

                                                                echo $type . "&nbsp;" . $bytes . ""; ?>
                                                            </td>
                                                            <form action="" id="myForm" method="post">
                                                                <input type="hidden" name="delDocument"
                                                                       value="<?php echo $gete['id']; ?>">
                                                            </form>
                                                            <td>
                                                                <a href="<?php echo $gete['attached_file']; ?>">
                                                                    <i class="fa fa-download"></i>
                                                                </a>&nbsp;
                                                                <a form="myForm" class="submit"><i
                                                                            class="fa fa-trash"></i></a>
                                                            </td>
                                                        </tr>

                                                    <?php } ?>
                                                </table>
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
                                            </div>
                                        </div>
                                        <div class="tab-pane <?php if (isset($_POST['save_empinfo'])) {
                                            echo "active";
                                        } ?> " id="tab_4">
                                            <form method="POST" class="form">
                                                <table class="table table-borderless">
                                                    <tbody>
                                                    <tr>
                                                        <th>Employee No:</th>
                                                        <td>
                                                            <input type="number"
                                                                   name="employeeNo"
                                                                   class="form-control"
                                                                   placeholder="Employee number"
                                                                   value="<?php if ($employerInfo['employee_no']) {
                                                                       echo $employerInfo['employee_no'];
                                                                   } ?>" required/>
                                                        </td>
                                                        <th>Employer Name:</th>
                                                        <td>
                                                            <input type="text"
                                                                   name="employerName"
                                                                   class="form-control"
                                                                   placeholder="Employer's name"
                                                                   value="<?php if (isset($employerInfo['employer_name'])) {
                                                                       echo $employerInfo['employer_name'];
                                                                   } ?>" required/>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <th>Department:</th>
                                                        <td>
                                                            <input type="text"
                                                                   name="department"
                                                                   placeholder="Employer's name"
                                                                   class="form-control"
                                                                   value="<?php if (isset($employerInfo['department'])) {
                                                                       echo $employerInfo['department'];
                                                                   } ?>" required/>
                                                        </td>
                                                        <th>Employer Code:</th>
                                                        <td>
                                                            <input type="text"
                                                                   name="employerCode"
                                                                   class="form-control"
                                                                   placeholder="Employee's code"
                                                                   value="<?php if (isset($employerInfo['employer_code'])) {
                                                                       echo $employerInfo['employer_code'];
                                                                   } ?>" required/>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <th>Designation:</th>
                                                        <td>
                                                            <input type="text"
                                                                   name="designation"
                                                                   placeholder="Position of the employee"
                                                                   class="form-control"
                                                                   value="<?php if ($occupation) {
                                                                       echo $occupation;
                                                                   } ?>" required/>
                                                        </td>
                                                        <th>Engagement Date:</th>
                                                        <td>
                                                            <input type="date"
                                                                   name="engageDate"
                                                                   placeholder="Engagement date:"
                                                                   class="form-control"
                                                                   value="<?php if (isset($employerInfo['engagement_date'])) {
                                                                       echo $employerInfo['engagement_date'];
                                                                   } ?>" required/>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Employment Status:</th>
                                                        <td>
                                                            <select name="employmentStatus" class="form-control">
                                                                <option value="working">Working</option>
                                                            </select>
                                                        </td>
                                                        <th>Date of Retirement/Contract End Date</th>
                                                        <td>
                                                            <input type="date"
                                                                   name="retirement"
                                                                   placeholder="Date of Retirement/Contract End Date"
                                                                   class="form-control"
                                                                   value="<?php if (isset($employerInfo['retirement'])) {
                                                                       echo $employerInfo['retirement'];
                                                                   } ?>" required/>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <th>Employer Contact:</th>
                                                        <td>
                                                            <input type="number"
                                                                   name="employerContact"
                                                                   placeholder="Employer Contact"
                                                                   class="form-control"
                                                                   value="<?php if (isset($employerInfo['employer_contact'])) {
                                                                       echo $employerInfo['employer_contact'];
                                                                   } ?>" required/>
                                                        </td>
                                                        <th>Telephone No:</th>
                                                        <td>
                                                            <input type="number"
                                                                   name="emp_num"
                                                                   placeholder="Telephone number"
                                                                   class="form-control"
                                                                   value="<?php if (isset($employerInfo['telephone'])) {
                                                                       echo $employerInfo['telephone'];
                                                                   } ?>" required/>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <th>Employer Designation</th>
                                                        <td>
                                                            <input type="text"
                                                                   name="employerDesignation"
                                                                   placeholder="Employer Designation"
                                                                   class="form-control"
                                                                   value="<?php if (isset($employerInfo['employer_designation'])) {
                                                                       echo $employerInfo['employer_designation'];
                                                                   } ?>" required/>
                                                        </td>
                                                        <th></th>
                                                        <td></td>
                                                    </tr>


                                                    </tbody>
                                                </table>
                                                <div align="center">
                                                    <div class="box-footer">
                                                        <button type="reset" class="btn btn-primary"><i
                                                                    class="fa fa-times">&nbsp;Reset</i>
                                                        </button>
                                                        <button name="save_empinfo" type="submit"
                                                                class="btn btn-success "><i
                                                                    class="fa fa-save">&nbsp;Update</i>
                                                        </button>

                                                    </div>
                                                </div>
                                            </form>


                                        </div>
                                        <div class="tab-pane <?php if (isset($_POST['save_nextOfKin'])) {
                                            echo "active";
                                        } ?> " id="tab_5">
                                            <form method="POST" class="form">
                                                <table class="table table-borderless">
                                                    <tbody>
                                                    <tr>
                                                        <th>Name:</th>
                                                        <td>
                                                            <input type="text"
                                                                   name="names"
                                                                   class="form-control"
                                                                   placeholder="Next of Kin Names"
                                                                   value="<?php if ($nextOfKinInfo['names']) {
                                                                       echo $nextOfKinInfo['names'];
                                                                   } ?>" required/>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Contact Number:</th>
                                                        <td>
                                                            <input type="number"
                                                                   name="contact"
                                                                   class="form-control"
                                                                   placeholder="Contact Number"
                                                                   value="<?php if (isset($nextOfKinInfo['contact'])) {
                                                                       echo $nextOfKinInfo['contact'];
                                                                   } ?>" required/>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <th>Physical Address:</th>
                                                        <td>
                                                            <input type="text"
                                                                   name="address"
                                                                   placeholder="Address"
                                                                   class="form-control"
                                                                   value="<?php if (isset($nextOfKinInfo['address'])) {
                                                                       echo $nextOfKinInfo['address'];
                                                                   } ?>" required/>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Email:</th>
                                                        <td>
                                                            <input type="email"
                                                                   name="email"
                                                                   class="form-control"
                                                                   placeholder="Email"
                                                                   value="<?php if (isset($nextOfKinInfo['email'])) {
                                                                       echo $nextOfKinInfo['email'];
                                                                   } ?>" required/>
                                                        </td>
                                                    </tr>


                                                    <tr>
                                                        <th>Employer:</th>
                                                        <td>
                                                            <input type="text"
                                                                   name="employer"
                                                                   placeholder="Employer"
                                                                   class="form-control"
                                                                   value="<?php if (isset($nextOfKinInfo['employer'])) {
                                                                       echo $nextOfKinInfo['employer'];
                                                                   } ?>"/>
                                                        </td>
                                                    </tr>


                                                    </tbody>
                                                </table>
                                                <div align="center">
                                                    <div class="box-footer">
                                                        <button type="reset" class="btn btn-primary"><i
                                                                    class="fa fa-times">&nbsp;Reset</i>
                                                        </button>
                                                        <button name="save_nextOfKin" type="submit"
                                                                class="btn btn-success "><i
                                                                    class="fa fa-save">&nbsp;Update</i>
                                                        </button>

                                                    </div>
                                                </div>
                                            </form>


                                        </div>
                                        <div class="tab-pane <?php if (isset($_POST['saveBankingDetails'])) {
                                            echo "active";
                                        } ?> " id="tab_6">
                                            <form class="form-horizontal" method="post"
                                                  enctype="multipart/form-data">
                                                <input type="hidden" name="detailsId" value="<?php echo $detailsId; ?>">
                                                <div class="col-sm-6" <?php if($disburseMethod==="Online Transfer"){ ?> style="display: block" <?php } ?>
                                                    <div class="form-group">
                                                        <label for="" class="col-sm-5 control-label">Account
                                                            Name</label>
                                                        <div class="col-sm-7">
                                                            <input type="text" placeholder="Account Name"
                                                                   class="form-control" name="recipient[accountName]"
                                                                   id="accountName" value="<?php echo $accountName; ?>"
                                                                   required>
                                                        </div>
                                                    </div>

                                                <div class="col-sm-6">
                                                    <div class="form-group"><label for=""
                                                                                   class="col-sm-5 control-label">Account
                                                            Type</label>
                                                        <div class="col-sm-7"><input type="text"
                                                                                     placeholder="Account Type"
                                                                                     class="form-control"
                                                                                     name="recipient[accountType]"
                                                                                     id="accountType"
                                                                                     value=" <?php echo $typeOfAccount; ?>"
                                                                                     ></div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="" class="col-sm-5 control-label">Bank Name</label>
                                                        <div class="col-sm-7"><select class="form-control" name="recipient[bankName]" onchange="changeBank(this.options[this.selectedIndex].value)" id="bankName" required><option>--Select--</option>
                                                                <option <?php if($bankName=="FNB_Lesotho"){echo "selected";} ?> value="FNB_Lesotho">First National Bank Lesotho</option>
                                                                <option <?php if($bankName=="Nedbank_Lesotho"){echo "selected";} ?> value="Nedbank_Lesotho">Nedbank Lesotho</option>
                                                                <option <?php if($bankName=="Lesotho_Postbank"){echo "selected";} ?> value="Lesotho_Postbank">Lesotho Postbank</option>
                                                                <option <?php if($bankName=="Standard_Lesotho_Bank"){echo "selected";} ?> value="Standard_Lesotho_Bank">Standard Lesotho Bank</option>
                                                                <option <?php if($bankName=="ABSA"){echo "selected";} ?> value="ABSA">ABSA Bank</option>
                                                                <option <?php if($bankName=="Bank_Of_Athens"){echo "selected";} ?> value="Bank_Of_Athens">Bank of Athens</option>
                                                                <option <?php if($bankName=="Bidvest_Bank"){echo "selected";} ?> value="Bidvest_Bank">Bidvest Bank</option>
                                                                <option <?php if($bankName=="Capitec_Bank"){echo "selected";} ?> value="Capitec_Bank">Capitec Bank</option>
                                                                <option <?php if($bankName=="FNB_South_Africa"){echo "selected";} ?> value="FNB_South_Africa">FNB South Africa</option>
                                                                <option <?php if($bankName=="Investec_Private_Bank"){echo "selected";} ?> value="Investec_Private_Bank">Investec Private Bank</option>
                                                                <option <?php if($bankName=="Nedbank_South_Africa"){echo "selected";} ?> value="Nedbank_South_Africa">Nedbank South Africa</option>
                                                                <option <?php if($bankName=="SA_Postbank"){echo "selected";} ?> value="SA_Postbank">SA Postbank</option>
                                                                <option <?php if($bankName=="Standard_Bank_South_Africa"){echo "selected";} ?> value="Standard_Bank_South_Africa">Standard Bank South Africa</option>
                                                            </select></div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group"><label for=""
                                                                                   class="col-sm-5 control-label">Branch
                                                            Name</label>
                                                        <div class="col-sm-7">
                                                            <select class="form-control" name="recipient[branchName]"
                                                                    onchange="changeBranch(this.options[this.selectedIndex].value)"
                                                                    id="branchName" required><option value="" disabled selected>select</option>
                                                            </select>
                                                        </div>

                                                </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="" class="col-sm-5 control-label">Account
                                                            Number</label>
                                                        <div class="col-sm-7"><input type="number"
                                                                                     placeholder="Account No." min="0"
                                                                                     class="form-control"
                                                                                     name="recipient[accountNumber]"
                                                                                     id="bankAccountNumber"
                                                                                     value="<?php echo $accountNumber; ?>"
                                                                                     required></div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group"><label for=""
                                                                                   class="col-sm-5 control-label">Branch
                                                            Code</label>
                                                        <div class="col-sm-7"><select class="form-control" name="recipient[branchCode]" id="branchCode" required><option>Select</option></select></div>
                                                    </div>
                                                </div>
                                                <div align="center">
                                                    <div class="box-footer">

                                                        <button name="saveBankingDetails" type="submit"
                                                                class="btn btn-success "><i
                                                                    class="fa fa-save">&nbsp;Save</i>
                                                        </button>

                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <!-- /.tab-pane -->
                                    </div>
                                </div>
                                <!-- /.tab-content -->
                            </div>


                        </div>


                    </div>

                </div>
            </div>
        </div>
</div>
</div>

<script type="text/javascript">
    function showfield(name) {
        if (name != 'Pensioner' && name != 'Self-employed')
            document.getElementById('div_employment_status').innerHTML = '<div class="form-group">\n' +
                '                            <label for="" class="col-sm-3 control-label"></label>\n' +
                '                            <div class="col-sm-9"><input name="employer"type="text"  placeholder="Employer Name / Institution" class="form-control" name="employer" id="employer" required></div></div>';
        else document.getElementById('div_employment_status').innerHTML = '';
    }
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function () {
        $(document).on('click', '#checkAll_occupation', function () {
            $(".itemRow_occupation").prop("checked", this.checked);
        });
        $(document).on('click', '.itemRow_occupation', function () {
            if ($('.itemRow_occupation:checked').length == $('.itemRow_occupation').length) {
                $('#checkAll_occupation').prop('checked', true);
            } else {
                $('#checkAll_occupation').prop('checked', false);
            }
        });
        var count = $(".itemRow_occupation").length;
        $(document).on('click', '#addRows_occupation', function () {
            var htmlRows = '';
            htmlRows += '<tr>';
            htmlRows += '<td><input class="itemRow_occupation" type="checkbox" name="selector[]" value="' + count + '"></td>';
            htmlRows += '<td><input type="text" placeholder="Occupation" maxlength="20" name="occupationDetails[' + count + '][occupation]" id="occupation' + count + '" class="form-control" autocomplete="off" required></td>';
            htmlRows += '<td><input type="number" placeholder="Amount" name="occupationDetails[' + count + '][mincome]" id="mincome' + count + '" min="0" class="form-control" autocomplete="off" required></td>';
            htmlRows += '' +
                '<td>' +
                '<select name="occupationDetails[' + count + '][frequency]" class="form-control" required> ' +
                '<option value="">--Select--</option> ' +
                '<?php $strJsonFileContents = file_get_contents('include/packages.json');
                    $arrayOfTypes = json_decode($strJsonFileContents, true); ?>' +
                '<?php foreach($arrayOfTypes['incomeFrequencyCode'] as $key => $value){ ?>' +
                '<option value="<?php echo $key; ?>"><?php echo $value; ?></option> ' +
                '<?php } ?>' +
                '</select>' +
                '</td>';
            htmlRows += '</tr>';
            $('#loan-fees').append(htmlRows);
            count++;
        });
        $(document).on('click', '#removeRows_occupation', function () {
            $(".itemRow_occupation:checked").each(function () {
                $(this).closest('tr').remove();
            });
            $('#checkAll_occupation').prop('checked', false);
            calculateTotal();
        });

        $(document).on('click', '.deleteRow_occupation', function () {
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
    function showMaritalField(name) {
        if (name == 'Married')
            document.getElementById('div_marital_status').innerHTML = '<div class="form-group">\n' +
                '                            <label for="" class="col-sm-3 control-label"></label>\n' +
                '                            <div class="col-sm-9"><select  class="form-control" name="marriageType" required><option value="" disabled>Select</option><option>Community of property</option><option>Antenuptial Contract (ANC) </option></select></div></div>';
        else document.getElementById('div_marital_status').innerHTML = '';
    }
</script>
    <script src="https://code.jquery.com/jquery-1.9.1.js"
            integrity="sha256-e9gNBsAcA0DBuRWbm0oZfbiCyhjLrI6bmqAl5o+ZjUA="
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

        var branchNamesByBank = {
            Standard_Lesotho_Bank: ["BUTHA_BUTHE", 'CATHEDRAL', 'CITY', 'INDUSTRIAL', 'LERIBE', 'LESOTHO_OPC', 'MAFETENG', 'MAPUTSOE_SLB', 'MASERU_MALL', 'MOHALES_HOEK', 'MOKHOTLONG', 'PIONEER', 'QACHA', 'QUTHING', 'TEYATEYANG', 'THABA_TSEKA', 'TOWER'],
            FNB_Lesotho: ["FNB_UNIVERSAL"],
            Nedbank_Lesotho: ["BUTHA_BUTHA_NEDBANK", "HLOTSE", "MAPUTSOE", "MASERU_KINGSWAY", "MASERU_MALL_NEDBANK", "MOHALES_HOEK", "NEDBANK_BEREA", "NEDBANK_MAFETENG", "PIONEER_MALL", "ROMA"],
            Lesotho_Postbank: ["POSTBANK_UNIVERSAL"],
            ABSA: ["ABSA_UNIVERSAL"],
            Bank_Of_Athens: ["BANKOFATHENS_UNIVERSAL"],
            Bidvest_Bank: ["BIDVEST_UNIVERSAL"],
            Capitec_Bank: ["CAPITEC_UNIVERSAL"],
            FNB_South_Africa: ["FNB_SOUTH_AFRICA_UNIVERSAL"],
            Investec_Private_Bank: ["INVESTEC_UNIVERSAL"],
            Nedbank_South_Africa: ["NEDBANK_SA_UNIVERSAL"],
            SA_Postbank: ["SA_POSTBANK_UNIVERSAL"],
            Standard_Bank_South_Africa: ["STANDARD_BANK_SA_UNIVERSAL"],
        }

        function changeBank(value) {
            if (value.length == 0) document.getElementById("branchName").innerHTML = "<option></option>";

            else {
                var catOptions = "<option value='' disabled selected>Select</option>";
                for (branchNameId in branchNamesByBank[value]) {
                    catOptions += "<option>" + branchNamesByBank[value][branchNameId] + "</option>";
                }
                document.getElementById("branchName").innerHTML = catOptions;
            }
        }

        var codeByBranch = {
            BUTHA_BUTHE: ["061167"],
            CATHEDRAL: ["063067"],
            CITY: ["060667"],
            INDUSTRIAL: ["062367"],
            FNB_UNIVERSAL: ["280061"],
            LERIBE: ["060867"],
            LESOTHO_OPC: ["062867"],
            MAFETENG: ["060967"],
            MAPUTSOE_SLB: ["061067"],
            MASERU_MALL: ["063167"],
            MOHALES_HOEK: ["060767"],
            MOKHOTLONG: ["062567"],
            PIONEER: ["062967"],
            QACHA: ["062667"],
            QUTHING: ["062467"],
            TEYATEYANG: ["062167"],
            THABA_TSEKA: ["062767"],
            TOWER: ["062067"],
            BUTHA_BUTHA_NEDBANK: ["390561"],
            HLOTSE: ["390761"],
            MAPUTSOE: ["390261"],
            MASERU_KINGSWAY: ["390161"],
            MASERU_MALL_NEDBANK: ["390961"],
            MOHALES_HOEK_NEDBANK: ["390361"],
            NEDBANK_BEREA: ["390061"],
            NEDBANK_MAFETENG: ["390461"],
            PIONEER_MALL: ["390861"],
            ROMA: ["390661"],
            POSTBANK_UNIVERSAL: ["500100"],
            ABSA_UNIVERSAL: ["632005"],
            BANKOFATHENS_UNIVERSAL: ["410506"],
            BIDVEST_UNIVERSAL: ["462005"],
            CAPITEC_UNIVERSAL: ["470010"],
            FNB_SOUTH_AFRICA_UNIVERSAL: ["254005"],
            INVESTEC_UNIVERSAL: ["580105"],
            NEDBANK_SA_UNIVERSAL: ["198765"],
            SA_POSTBANK_UNIVERSAL: ["460005"],
            STANDARD_BANK_SA_UNIVERSAL: ["051001"],
        }

        function changeBranch(value) {
            if (value.length == 0) document.getElementById("branchCode").innerHTML = "<option></option>";

            else {
                var catOptions = "1";
                for (branchCodeId in codeByBranch[value]) {
                    catOptions += "<option>" + codeByBranch[value][branchCodeId] + "</option>";
                }
                document.getElementById("branchCode").innerHTML = catOptions;
            }
        }
    </script>
<script>
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
            monthlyLivingExpenses = 0;

        var deductions = parseFloat(statutory) + parseFloat(loanInstalments);
        grossPay = parseFloat(basicPay) + parseFloat(additionalFixed);
        var netPay = (parseFloat(grossPay) - deductions).toFixed(2);
        var maximumAvailable = (parseFloat(netPay) - (parseFloat(otherBankInstalments) + parseFloat(monthlyLivingExpenses))).toFixed(2);
        $("#grossPay").val(grossPay);
        $("#netPay").val(netPay);
        $("#maxAvailable").val(maximumAvailable);
    }
</script>
<script type="text/javascript">

    $('.id_pass').change(function () {
        if (this.value == 'ID No') {
            $("#div_passport").hide();
            $("#div_idno").show();
            $("#idno_other_2").hide();
            $("#passport_other_2").hide();
            $("#passport_other").hide();

            document.getElementById('div_idno').innerHTML = '<br/><input type="text" placeholder="ID No" class="form-control" minlength="12" maxlength="12" name ="id_number" id="idno_other" <?php if($row['id_number'] !== ''){?>value="<?php echo $row['id_number'];?>"<?php } ?> required>';
        } else if (this.value == 'Passport') {
            $("#div_idno").hide();
            $("#div_passport").show();
            $("#idno_other_2").hide();
            $("#passport_other_2").hide();
            $("#idno_other").hide();

            document.getElementById('div_passport').innerHTML = '<br/><input type="text" placeholder="Passport Number" minlength="8" maxlength="8" class="form-control" name ="passport" id="passport_other" <?php if($row['passport'] !== ''){?>value="<?php echo $row['passport'];?>"<?php } ?> required>';
        } else {

        }
    });


    function maxLengthCheck(object) {
        if (object.value.length > object.maxLength)
            object.value = object.value.slice(0, object.maxLength)
    }

</script>
<?php if ($id_number != '') { ?>
    <script>
        $(document).ready(function () {
            $("#div_idno").show();
            document.getElementById('div_idno').innerHTML = '<br/><input type="text" placeholder="ID Number" class="form-control" name ="id_number" id="idno_other_2" <?php if($id_number !== ''){?>value="<?php echo $id_number;?>"<?php } ?>>';
        });
    </script>
<?php } ?>

<?php if ($passport != '') { ?>
    <script>
        $(document).ready(function () {
            $("#div_passport").show();
            document.getElementById('div_passport').innerHTML = '<br/><input type="text" placeholder="Passport" class="form-control" name ="passport" id="passport_other_2" <?php if($passport !== ''){?>value="<?php echo $passport;?>"<?php } ?>>';
        });
    </script>
<?php } ?>