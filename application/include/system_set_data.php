<?php

if (isset($_POST['saveOptions'])) {
    $id = $_SESSION['systemId'];
    $scoring = $_POST['scoring'];
    $bureauSubmision = $_POST['bureau_submission'];

    $options = mysqli_query($link, "UPDATE systemset SET scoring='$scoring', bureau_submission='$bureauSubmision' and sysid='$id'") or die(mysqli_error());
    if ($options) {
        echo '<div class="alert alert-success" >
                                        <a href = "#" class = "close" data-dismiss= "alert"> &times;</a>
                                         System Options Successfully Saved!&nbsp; &nbsp;&nbsp;
                                           </div>';
    } else {
        echo '<div class="alert alert-warning" >
                                        <a href = "#" class = "close" data-dismiss= "alert"> &times;</a>
                                         System Options Failed to save!&nbsp; &nbsp;&nbsp;
                                           </div>';
    }

}

if(isset($_POST['saveCharts'])) {
    //mysqli_query($link,"delete from gl_codes");
    foreach ($_POST['accounting'] as $key => $value) {
        $gl = $value['glCode'];
        $name = $value['accountName'];
        $type = $value['accountType'];

        $existingAcc = mysqli_query($link, "select * from gl_codes where code='$gl'");
        if(mysqli_num_rows($existingAcc)==0) {
            $save = mysqli_query($link, "insert into gl_codes values(0,'$gl','$name','$type','$type','0')");
        }

    }

    echo '<div class="alert alert-success" >
                                        <a href = "#" class = "close" data-dismiss= "alert"> &times;</a>
                                         Successfully saved Chart of Accounts!&nbsp; &nbsp;&nbsp;
                                           </div>';
}

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

if (isset($_POST['saveDocuments'])) {
    mysqli_query($link, "delete from documents_required");
    foreach ($_POST['document'] as $key => $value) {
        $name = $value['name'];
        $documents = mysqli_query($link, "INSERT into documents_required values(0,'$name','Active')") or die(mysqli_error());
    }

    if ($documents) {
        echo '<div class="alert alert-success" >
                                        <a href = "#" class = "close" data-dismiss= "alert"> &times;</a>
                                         Required Documents Successfully Saved!&nbsp; &nbsp;&nbsp;
                                           </div>';
    } else {
        echo '<div class="alert alert-warning" >
                                        <a href = "#" class = "close" data-dismiss= "alert"> &times;</a>
                                         Required Documents Failed to save!&nbsp; &nbsp;&nbsp;
                                           </div>';
    }

}

if (isset($_POST['save'])) {
    try {
        $id = $_SESSION['systemId'];
        $fname = $_POST['fname'];
        $number = $_POST['number'];
        $email = $_POST['email'];
        $title = $_POST['title'];
        $footer = $_POST['footer'];
        $abb = $_POST['abb'];
        $currency = $_POST['currency'];
        $address = $_POST['address'];
        $fax = $_POST['fax'];
        $website = $_POST['website'];
        $map = $_POST['map'];
        $timezone = $_POST['timezone'];
        $sms_charges = $_POST['sms_charges'];
        //$bureauSubmision = $_POST['bureau_submission'];
        //$scoring = $_POST['scoring'];
        $registration = $_POST['registration'];
        $_SESSION['companyId'] = $id;

        //this handles uploading of rentals image
        //$image = addslashes(file_get_contents($_FILES['image']['tmp_name']));

        if ($sms_charges < 0) {
            throw new UnexpectedValueException();
        } else {

            if ($_FILES["image2"]["name"] !== "") {
                $target_dir2 = "../image/";
                $target_file2 = $target_dir2 . basename($_FILES["image2"]["name"]);
                $imageFileType2 = pathinfo($target_file2, PATHINFO_EXTENSION);

                $sourcepath2 = $_FILES["image2"]["tmp_name"];
                $targetpath2 = "../image/" . $_FILES["image2"]["name"];
                move_uploaded_file($sourcepath2, $targetpath2);
                $stamp = $_FILES["image2"]["name"];

                mysqli_query($link, "UPDATE systemset SET name='$fname',mobile='$number',email='$email',title='$title',footer='$footer', registration='$registration',
															abb='$abb',currency='$currency',address='$address',fax='$fax',website='$website',map='$map',timezone='$timezone'
												, stamp='$stamp', sms_charges='$sms_charges' WHERE sysid ='$id'") or die(mysqli_error($link));
            }

            if ($_FILES["image"]["name"] !== "") {
                $target_dir = "../img/";
                $target_file = $target_dir . basename($_FILES["image"]["name"]);
                $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
                $check = getimagesize($_FILES["image"]["tmp_name"]);

                $sourcepath = $_FILES["image"]["tmp_name"];
                $targetpath = "../img/" . $_FILES["image"]["name"];
                move_uploaded_file($sourcepath, $targetpath);
                $image = $_FILES["image"]["name"];


                mysqli_query($link, "UPDATE systemset SET name='$fname',mobile='$number',email='$email',title='$title',footer='$footer', registration='$registration',
															abb='$abb',currency='$currency',address='$address',fax='$fax',website='$website',map='$map',timezone='$timezone'
												, image='$targetpath', sms_charges='$sms_charges' WHERE sysid ='$id'") or die(mysqli_error($link));
            }
            mysqli_query($link, "UPDATE systemset SET name='$fname',mobile='$number',email='$email',title='$title',footer='$footer', registration='$registration',
															abb='$abb',currency='$currency',address='$address',fax='$fax',website='$website',map='$map',timezone='$timezone'
												, sms_charges='$sms_charges' WHERE sysid ='$id'") or die(mysqli_error($link));
            echo mysqli_error($link);
            echo '<div class="alert alert-success" >
                                        <a href = "#" class = "close" data-dismiss= "alert"> &times;</a>
                                         System Configured Successfully!&nbsp; &nbsp;&nbsp;
                                           </div>';

        }
    } catch (UnexpectedValueException $ex) {
        echo '<div class="alert alert-warning" >
                                        <a href = "#" class = "close" data-dismiss= "alert"> &times;</a>
                                         Invalid Amount Entered! (avoid entering negative number like -20, -50 etc.)!&nbsp; &nbsp;&nbsp;
                                           </div>';
    }
}

if (isset($_POST['saveBureauInfo'])) {

    try {

        $id = $_SESSION['systemId'];
        $companyTrading = $_POST['companyTrading'];
        $srn = $_POST['srn'];
        $recipient = $_POST['recipient'];
        $submission_cycle = $_POST['submission_cycle'];
        $sftpUrl = $_POST['sftpUrl'];
        $sftpPassword = $_POST['sftpPassword'];
        $bureauEmail = $_POST['bureauEmail'];
        $emailSFTP = $_POST['emailSFTP'];
        $sftpPort = $_POST['sftpPort'];
        $sftpUsername = $_POST['sftpUsername'];
        $file_type = $_POST['file_type'];

        if ($submission_cycle == "Daily") {
            $day_of_submission = "0";
        }
        if (isset($_POST['day_of_submission'])) {
            $day_of_submission = $_POST['day_of_submission'];
            mysqli_query($link, "UPDATE systemset SET trading_name='$companyTrading',srn='$srn',recipient='$recipient',submission_cycle='$submission_cycle',sftp_url='$sftpUrl', file_type = '$file_type',
															sftp_port='$sftpPort',bureau_email='$bureauEmail',submission_method='$emailSFTP', day_of_submission = '$day_of_submission', sftp_password='$sftpPassword', sftp_username='$sftpUsername' WHERE sysid ='$id'") or die(mysqli_error());

        } else {
            mysqli_query($link, "UPDATE systemset SET trading_name='$companyTrading',srn='$srn',recipient='$recipient',submission_cycle='$submission_cycle',sftp_url='$sftpUrl', file_type = '$file_type',
															sftp_port='$sftpPort',bureau_email='$bureauEmail',submission_method='$emailSFTP', sftp_password='$sftpPassword', sftp_username='$sftpUsername' WHERE sysid ='$id'") or die(mysqli_error());
        }

        echo '<div class="alert alert-success" >
                                        <a href = "#" class = "close" data-dismiss= "alert"> &times;</a>
                                         System Configured Successfully with Bureau Information!&nbsp; &nbsp;&nbsp;
                                           </div>';
    } catch (UnexpectedValueException $ex) {
        echo '<div class="alert alert-success" >
                                        <a href = "#" class = "close" data-dismiss= "alert"> &times;</a>
                                         Failed to save information!&nbsp; &nbsp;&nbsp;
                                           </div>';
    }
}

if (isset($_POST['saveAffordabilityCheck'])) {

    try {
        $company = $_POST['provider'];
        $url = $_POST['url'];
        $username = $_POST['username'];
        $password = $_POST['password'];

        mysqli_query($link, "insert into affordability_check values (0,'$company','$url','$username','$password','Active')") or die(mysqli_error($link));

        echo '<div class="alert alert-success" >
                                        <a href = "#" class = "close" data-dismiss= "alert"> &times;</a>
                                         System Configured Successfully with Bureau Information!&nbsp; &nbsp;&nbsp;
                                           </div>';

    } catch (UnexpectedValueException $ex) {
        echo "<div class='alert alert-danger'>Failed to Save Information!</div>";
    }
}

if (isset($_POST['delrow'])) {
    $idm = $_GET['id'];
    $id = $_POST['selector'];
    $N = count($id);
    if ($N == 0) {
        echo "<script>alert('Row Not Selected!!!'); </script>";
        // echo "<script>window.location='updateborrowers.php?id=" . $idm . "&&mid=" . base64_encode("403") . "'; </script>";
    } else {
        for ($i = 0; $i < $N; $i++) {
            $result = mysqli_query($link, "DELETE FROM branches WHERE id ='$id[$i]'");
            //echo "<script>window.location='updateborrowers.php?id=" . $idm . "&&mid=" . base64_encode("403") . "'; </script>";
        }
    }
}

if (isset($_POST['delrowDocuments'])) {
    $idm = $_GET['id'];
    $id = $_POST['selector'];
    $N = count($id);
    if ($N == 0) {
        echo "<script>alert('Row Not Selected!!!'); </script>";
    } else {
        for ($i = 0; $i < $N; $i++) {
            $result = mysqli_query($link, "DELETE FROM required_documents WHERE id ='$id[$i]'");
        }
    }
}

if (isset($_POST['saveBranches'])) {
    $id = $_GET['id'];
    $tid = $_SESSION['tid'];
    $count = 1;
    foreach ($_POST['branch'] as $key => $value) {
        $location = $value['location'];
        $code = $value['code'];
        $name = $value['name'];

        if (strlen($code) < 8) {
            $code += 10000000;
        }
        $subAccount = 1000 + $count;
        $count++;
        //Check if code exists
        $get = mysqli_query($link, "select * from branches where code='$code'");
        if (mysqli_num_rows($get) == 0) {
            $insert = mysqli_query($link, "insert into branches values (0,'$name','$location','$code','Active','$subAccount')") or die(mysqli_error($link));
        } else {
            $insert = mysqli_query($link, "update branches set name ='$name', location='$location',code='$code', sub_account='$subAccount' where code='code'") or die(mysqli_error($link));
        }

    }

    if ($insert) {

        echo '<div class="alert alert-success" >
                                        <a href = "#" class = "close" data-dismiss= "alert"> &times;</a>
                                         Branches Successfully Saved!&nbsp; &nbsp;&nbsp;
                                           </div>';
    } else {
        echo '<div class="alert alert-warning" >
                                        <a href = "#" class = "close" data-dismiss= "alert"> &times;</a>
                                         Failed to save branches!&nbsp; &nbsp;&nbsp;
                                           </div>';
    }
}
?>
<div class="box">
    <div class="box-body">
        <div class="panel panel-success">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-gear"></i>&nbsp;Company Setup</h3>
            </div>
            <div class="box-body">

                <?php
                $call = mysqli_query($link, "SELECT * FROM systemset");
                while ($row = mysqli_fetch_assoc($call)) {
                    ?>
                    <div class="box-body">
                        <div class="col-md-14">

                            <div class="col-lg-12">
                                <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                    <table width="80%">
                                        <tr>
                                            <td align="center"><b>Scoring Check?</b></td>
                                            <td><input name="scoring" type="radio"
                                                    <?php if ($row ['scoring'] == 1) {
                                                        echo "checked";
                                                    } ?> value="1" required> Yes
                                                <input name="scoring" type="radio"
                                                    <?php if ($row ['scoring'] == 0) {
                                                        echo "checked";
                                                    } ?> value="0" required> No
                                            </td>
                                            <td align="center"><b>Bureau Submission?</b></td>
                                            <td>
                                                <p align="left">
                                                    <input type="hidden" value="<?php echo $row ['sysid']; ?>"
                                                           name="sysid">
                                                    <input name="bureau_submission" type="radio"
                                                        <?php if ($row ['bureau_submission'] == 1) {
                                                            echo "checked";
                                                        } ?> value="1" required> Yes
                                                    <input name="bureau_submission" type="radio"
                                                        <?php if ($row ['bureau_submission'] == 0) {
                                                            echo "checked";
                                                        } ?> value="0" required> No
                                                </p>
                                            </td>
                                            <td>
                                                <button type="submit" class="btn btn-success" name="saveOptions">
                                                    <i class="fa fa-save">&nbsp;Update Settings</i>
                                                </button>
                                            </td>
                                        </tr>
                                    </table>
                                </form>
                            </div>


                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#tab_1" data-toggle="tab">Company Information</a></li>
                                    <?php if ($row ['bureau_submission'] == 1) { ?>
                                        <li><a href="#tab_2" data-toggle="tab">Bureau Information</a></li>
                                    <?php } ?>
                                    <?php if ($row ['scoring'] == 1) { ?>
                                        <li><a href="#tab_3" data-toggle="tab">Scoring Information</a></li>
                                    <?php } ?>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1">
                                        <div class="panel panel-default">
                                            <div class="panel-body bg-gray-light text-bold"><i
                                                        class="fa fa-info-circle"></i>
                                                Company Information <a href="#" class="show_hide_company_settings">&nbsp;Show</a>
                                            </div>
                                        </div>
                                        <div class="slidingDivCompanySettings" style="display: none;">
                                            <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                                <input type="hidden" value="<?php echo $row ['sysid']; ?>" name="sysid">
                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">Company Logo</label>
                                                    <div class="col-sm-6">

                                                        <input type='file' name="image" onChange="readURL(this);">
                                                        <img id="blah" src="<?php echo $row ['image']; ?>"
                                                             alt="System Logo Here"
                                                             height="100" width="100"/>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">Company Name</label>
                                                    <div class="col-sm-6">
                                                        <input name="fname" type="text" class="form-control"
                                                               value="<?php echo $row ['name']; ?>" required>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">Company Phone</label>
                                                    <div class="col-sm-6">
                                                        <input name="number" type="text" class="form-control"
                                                               value="<?php echo $row ['mobile']; ?>" required>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">Company Email</label>
                                                    <div class="col-sm-6">
                                                        <input type="email" name="email" type="text"
                                                               class="form-control"
                                                               value="<?php echo $row ['email']; ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">Company Title</label>
                                                    <div class="col-sm-6">
                                                        <input type="text" name="title" type="text" class="form-control"
                                                               value="<?php echo $row ['title']; ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group" style="display: none">
                                                    <label for="" class="col-sm-3 control-label">Company Footer</label>
                                                    <div class="col-sm-6">
                                                        <input type="text" name="footer" type="text"
                                                               class="form-control"
                                                               value="<?php echo $row ['footer']; ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">Company
                                                        Abbreviation</label>
                                                    <div class="col-sm-6">
                                                        <input type="text" name="abb" type="text" class="form-control"
                                                               value="<?php echo $row ['abb']; ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">Company
                                                        Currency</label>
                                                    <div class="col-sm-6">
                                                        <input name="currency" type="text" class="form-control"
                                                               value="<?php echo $row ['currency']; ?>" required>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">Company Address</label>
                                                    <div class="col-sm-6">
                                                <textarea name="address" class="form-control" rows="4"
                                                          cols="80"><?php echo $row ['address']; ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">Fax</label>
                                                    <div class="col-sm-6">
                                                        <input name="fax" type="text" class="form-control"
                                                               value="<?php echo $row ['fax']; ?>"
                                                               >
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">Website</label>
                                                    <div class="col-sm-6">
                                                        <input name="website" type="text" class="form-control"
                                                               value="<?php echo $row ['website']; ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">Map</label>
                                                    <div class="col-sm-6">
                                <textarea name="map" class="form-control" rows="4"
                                          cols="80"><?php echo $row ['map']; ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">Timezone</label>
                                                    <div class="col-sm-6">
                                                        <select name="timezone" class="form-control" required>
                                                            <option timeZoneId="1" gmtAdjustment="GMT-12:00"
                                                                    useDaylightTime="0"
                                                                    value="-12">
                                                                (GMT-12:00) International Date Line West
                                                            </option>
                                                            <option timeZoneId="2" gmtAdjustment="GMT-11:00"
                                                                    useDaylightTime="0"
                                                                    value="-11">
                                                                (GMT-11:00) Midway Island, Samoa
                                                            </option>
                                                            <option timeZoneId="3" gmtAdjustment="GMT-10:00"
                                                                    useDaylightTime="0"
                                                                    value="-10">
                                                                (GMT-10:00) Hawaii
                                                            </option>
                                                            <option timeZoneId="4" gmtAdjustment="GMT-09:00"
                                                                    useDaylightTime="1"
                                                                    value="-9">
                                                                (GMT-09:00) Alaska
                                                            </option>
                                                            <option timeZoneId="5" gmtAdjustment="GMT-08:00"
                                                                    useDaylightTime="1"
                                                                    value="-8">
                                                                (GMT-08:00) Pacific Time (US & Canada)
                                                            </option>
                                                            <option timeZoneId="6" gmtAdjustment="GMT-08:00"
                                                                    useDaylightTime="1"
                                                                    value="-8">
                                                                (GMT-08:00) Tijuana, Baja California
                                                            </option>
                                                            <option timeZoneId="7" gmtAdjustment="GMT-07:00"
                                                                    useDaylightTime="0"
                                                                    value="-7">
                                                                (GMT-07:00) Arizona
                                                            </option>
                                                            <option timeZoneId="8" gmtAdjustment="GMT-07:00"
                                                                    useDaylightTime="1"
                                                                    value="-7">
                                                                (GMT-07:00) Chihuahua, La Paz, Mazatlan
                                                            </option>
                                                            <option timeZoneId="9" gmtAdjustment="GMT-07:00"
                                                                    useDaylightTime="1"
                                                                    value="-7">
                                                                (GMT-07:00) Mountain Time (US & Canada)
                                                            </option>
                                                            <option timeZoneId="10" gmtAdjustment="GMT-06:00"
                                                                    useDaylightTime="0" value="-6">
                                                                (GMT-06:00) Central America
                                                            </option>
                                                            <option timeZoneId="11" gmtAdjustment="GMT-06:00"
                                                                    useDaylightTime="1" value="-6">
                                                                (GMT-06:00) Central Time (US & Canada)
                                                            </option>
                                                            <option timeZoneId="12" gmtAdjustment="GMT-06:00"
                                                                    useDaylightTime="1" value="-6">
                                                                (GMT-06:00) Guadalajara, Mexico City, Monterrey
                                                            </option>
                                                            <option timeZoneId="13" gmtAdjustment="GMT-06:00"
                                                                    useDaylightTime="0" value="-6">
                                                                (GMT-06:00) Saskatchewan
                                                            </option>
                                                            <option timeZoneId="14" gmtAdjustment="GMT-05:00"
                                                                    useDaylightTime="0" value="-5">
                                                                (GMT-05:00) Bogota, Lima, Quito, Rio Branco
                                                            </option>
                                                            <option timeZoneId="15" gmtAdjustment="GMT-05:00"
                                                                    useDaylightTime="1" value="-5">
                                                                (GMT-05:00) Eastern Time (US & Canada)
                                                            </option>
                                                            <option timeZoneId="16" gmtAdjustment="GMT-05:00"
                                                                    useDaylightTime="1" value="-5">
                                                                (GMT-05:00) Indiana (East)
                                                            </option>
                                                            <option timeZoneId="17" gmtAdjustment="GMT-04:00"
                                                                    useDaylightTime="1" value="-4">
                                                                (GMT-04:00) Atlantic Time (Canada)
                                                            </option>
                                                            <option timeZoneId="18" gmtAdjustment="GMT-04:00"
                                                                    useDaylightTime="0" value="-4">
                                                                (GMT-04:00) Caracas, La Paz
                                                            </option>
                                                            <option timeZoneId="19" gmtAdjustment="GMT-04:00"
                                                                    useDaylightTime="0" value="-4">
                                                                (GMT-04:00) Manaus
                                                            </option>
                                                            <option timeZoneId="20" gmtAdjustment="GMT-04:00"
                                                                    useDaylightTime="1" value="-4">
                                                                (GMT-04:00) Santiago
                                                            </option>
                                                            <option timeZoneId="21" gmtAdjustment="GMT-03:30"
                                                                    useDaylightTime="1" value="-3.5">
                                                                (GMT-03:30) Newfoundland
                                                            </option>
                                                            <option timeZoneId="22" gmtAdjustment="GMT-03:00"
                                                                    useDaylightTime="1" value="-3">
                                                                (GMT-03:00) Brasilia
                                                            </option>
                                                            <option timeZoneId="23" gmtAdjustment="GMT-03:00"
                                                                    useDaylightTime="0" value="-3">
                                                                (GMT-03:00) Buenos Aires, Georgetown
                                                            </option>
                                                            <option timeZoneId="24" gmtAdjustment="GMT-03:00"
                                                                    useDaylightTime="1" value="-3">
                                                                (GMT-03:00) Greenland
                                                            </option>
                                                            <option timeZoneId="25" gmtAdjustment="GMT-03:00"
                                                                    useDaylightTime="1" value="-3">
                                                                (GMT-03:00) Montevideo
                                                            </option>
                                                            <option timeZoneId="26" gmtAdjustment="GMT-02:00"
                                                                    useDaylightTime="1" value="-2">
                                                                (GMT-02:00) Mid-Atlantic
                                                            </option>
                                                            <option timeZoneId="27" gmtAdjustment="GMT-01:00"
                                                                    useDaylightTime="0" value="-1">
                                                                (GMT-01:00) Cape Verde Is.
                                                            </option>
                                                            <option timeZoneId="28" gmtAdjustment="GMT-01:00"
                                                                    useDaylightTime="1" value="-1">
                                                                (GMT-01:00) Azores
                                                            </option>
                                                            <option timeZoneId="29" gmtAdjustment="GMT+00:00"
                                                                    useDaylightTime="0" value="0">
                                                                (GMT+00:00) Casablanca, Monrovia, Reykjavik
                                                            </option>
                                                            <option timeZoneId="30" gmtAdjustment="GMT+00:00"
                                                                    useDaylightTime="1" value="0">
                                                                (GMT+00:00) Greenwich Mean Time : Dublin, Edinburgh,
                                                                Lisbon,
                                                                London
                                                            </option>
                                                            <option timeZoneId="31" gmtAdjustment="GMT+01:00"
                                                                    useDaylightTime="1" value="1">
                                                                (GMT+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm,
                                                                Vienna
                                                            </option>
                                                            <option timeZoneId="32" gmtAdjustment="GMT+01:00"
                                                                    useDaylightTime="1" value="1">
                                                                (GMT+01:00) Belgrade, Bratislava, Budapest, Ljubljana,
                                                                Prague
                                                            </option>
                                                            <option timeZoneId="33" gmtAdjustment="GMT+01:00"
                                                                    useDaylightTime="1" value="1">
                                                                (GMT+01:00) Brussels, Copenhagen, Madrid, Paris
                                                            </option>
                                                            <option timeZoneId="34" gmtAdjustment="GMT+01:00"
                                                                    useDaylightTime="1" value="1">
                                                                (GMT+01:00) Sarajevo, Skopje, Warsaw, Zagreb
                                                            </option>
                                                            <option timeZoneId="35" gmtAdjustment="GMT+01:00"
                                                                    useDaylightTime="1" value="1">
                                                                (GMT+01:00) West Central Africa
                                                            </option>
                                                            <option timeZoneId="36" gmtAdjustment="GMT+02:00"
                                                                    useDaylightTime="1" value="2">
                                                                (GMT+02:00) Amman
                                                            </option>
                                                            <option timeZoneId="37" gmtAdjustment="GMT+02:00"
                                                                    useDaylightTime="1" value="2">
                                                                (GMT+02:00) Athens, Bucharest, Istanbul
                                                            </option>
                                                            <option timeZoneId="38" gmtAdjustment="GMT+02:00"
                                                                    useDaylightTime="1" value="2">
                                                                (GMT+02:00) Beirut
                                                            </option>
                                                            <option timeZoneId="39" gmtAdjustment="GMT+02:00"
                                                                    useDaylightTime="1" value="2">
                                                                (GMT+02:00) Cairo
                                                            </option>
                                                            <option timeZoneId="40" gmtAdjustment="GMT+02:00"
                                                                    useDaylightTime="0" value="2"
                                                                    selected>(GMT+02:00) Harare, Pretoria
                                                            </option>
                                                            <option timeZoneId="41" gmtAdjustment="GMT+02:00"
                                                                    useDaylightTime="1" value="2">
                                                                (GMT+02:00) Helsinki, Kyiv, Riga, Sofia, Tallinn,
                                                                Vilnius
                                                            </option>
                                                            <option timeZoneId="42" gmtAdjustment="GMT+02:00"
                                                                    useDaylightTime="1" value="2">
                                                                (GMT+02:00) Jerusalem
                                                            </option>
                                                            <option timeZoneId="43" gmtAdjustment="GMT+02:00"
                                                                    useDaylightTime="1" value="2">
                                                                (GMT+02:00) Minsk
                                                            </option>
                                                            <option timeZoneId="44" gmtAdjustment="GMT+02:00"
                                                                    useDaylightTime="1" value="2">
                                                                (GMT+02:00) Windhoek
                                                            </option>
                                                            <option timeZoneId="45" gmtAdjustment="GMT+03:00"
                                                                    useDaylightTime="0" value="3">
                                                                (GMT+03:00) Kuwait, Riyadh, Baghdad
                                                            </option>
                                                            <option timeZoneId="46" gmtAdjustment="GMT+03:00"
                                                                    useDaylightTime="1" value="3">
                                                                (GMT+03:00) Moscow, St. Petersburg, Volgograd
                                                            </option>
                                                            <option timeZoneId="47" gmtAdjustment="GMT+03:00"
                                                                    useDaylightTime="0" value="3">
                                                                (GMT+03:00) Nairobi
                                                            </option>
                                                            <option timeZoneId="48" gmtAdjustment="GMT+03:00"
                                                                    useDaylightTime="0" value="3">
                                                                (GMT+03:00) Tbilisi
                                                            </option>
                                                            <option timeZoneId="49" gmtAdjustment="GMT+03:30"
                                                                    useDaylightTime="1" value="3.5">
                                                                (GMT+03:30) Tehran
                                                            </option>
                                                            <option timeZoneId="50" gmtAdjustment="GMT+04:00"
                                                                    useDaylightTime="0" value="4">
                                                                (GMT+04:00) Abu Dhabi, Muscat
                                                            </option>
                                                            <option timeZoneId="51" gmtAdjustment="GMT+04:00"
                                                                    useDaylightTime="1" value="4">
                                                                (GMT+04:00) Baku
                                                            </option>
                                                            <option timeZoneId="52" gmtAdjustment="GMT+04:00"
                                                                    useDaylightTime="1" value="4">
                                                                (GMT+04:00) Yerevan
                                                            </option>
                                                            <option timeZoneId="53" gmtAdjustment="GMT+04:30"
                                                                    useDaylightTime="0" value="4.5">
                                                                (GMT+04:30) Kabul
                                                            </option>
                                                            <option timeZoneId="54" gmtAdjustment="GMT+05:00"
                                                                    useDaylightTime="1" value="5">
                                                                (GMT+05:00) Yekaterinburg
                                                            </option>
                                                            <option timeZoneId="55" gmtAdjustment="GMT+05:00"
                                                                    useDaylightTime="0" value="5">
                                                                (GMT+05:00) Islamabad, Karachi, Tashkent
                                                            </option>
                                                            <option timeZoneId="56" gmtAdjustment="GMT+05:30"
                                                                    useDaylightTime="0" value="5.5">
                                                                (GMT+05:30) Sri Jayawardenapura
                                                            </option>
                                                            <option timeZoneId="57" gmtAdjustment="GMT+05:30"
                                                                    useDaylightTime="0" value="5.5">
                                                                (GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi
                                                            </option>
                                                            <option timeZoneId="58" gmtAdjustment="GMT+05:45"
                                                                    useDaylightTime="0" value="5.75">
                                                                (GMT+05:45) Kathmandu
                                                            </option>
                                                            <option timeZoneId="59" gmtAdjustment="GMT+06:00"
                                                                    useDaylightTime="1" value="6">
                                                                (GMT+06:00) Almaty, Novosibirsk
                                                            </option>
                                                            <option timeZoneId="60" gmtAdjustment="GMT+06:00"
                                                                    useDaylightTime="0" value="6">
                                                                (GMT+06:00) Astana, Dhaka
                                                            </option>
                                                            <option timeZoneId="61" gmtAdjustment="GMT+06:30"
                                                                    useDaylightTime="0" value="6.5">
                                                                (GMT+06:30) Yangon (Rangoon)
                                                            </option>
                                                            <option timeZoneId="62" gmtAdjustment="GMT+07:00"
                                                                    useDaylightTime="0" value="7">
                                                                (GMT+07:00) Bangkok, Hanoi, Jakarta
                                                            </option>
                                                            <option timeZoneId="63" gmtAdjustment="GMT+07:00"
                                                                    useDaylightTime="1" value="7">
                                                                (GMT+07:00) Krasnoyarsk
                                                            </option>
                                                            <option timeZoneId="64" gmtAdjustment="GMT+08:00"
                                                                    useDaylightTime="0" value="8">
                                                                (GMT+08:00) Beijing, Chongqing, Hong Kong, Urumqi
                                                            </option>
                                                            <option timeZoneId="65" gmtAdjustment="GMT+08:00"
                                                                    useDaylightTime="0" value="8">
                                                                (GMT+08:00) Kuala Lumpur, Singapore
                                                            </option>
                                                            <option timeZoneId="66" gmtAdjustment="GMT+08:00"
                                                                    useDaylightTime="0" value="8">
                                                                (GMT+08:00) Irkutsk, Ulaan Bataar
                                                            </option>
                                                            <option timeZoneId="67" gmtAdjustment="GMT+08:00"
                                                                    useDaylightTime="0" value="8">
                                                                (GMT+08:00) Perth
                                                            </option>
                                                            <option timeZoneId="68" gmtAdjustment="GMT+08:00"
                                                                    useDaylightTime="0" value="8">
                                                                (GMT+08:00) Taipei
                                                            </option>
                                                            <option timeZoneId="69" gmtAdjustment="GMT+09:00"
                                                                    useDaylightTime="0" value="9">
                                                                (GMT+09:00) Osaka, Sapporo, Tokyo
                                                            </option>
                                                            <option timeZoneId="70" gmtAdjustment="GMT+09:00"
                                                                    useDaylightTime="0" value="9">
                                                                (GMT+09:00) Seoul
                                                            </option>
                                                            <option timeZoneId="71" gmtAdjustment="GMT+09:00"
                                                                    useDaylightTime="1" value="9">
                                                                (GMT+09:00) Yakutsk
                                                            </option>
                                                            <option timeZoneId="72" gmtAdjustment="GMT+09:30"
                                                                    useDaylightTime="0" value="9.5">
                                                                (GMT+09:30) Adelaide
                                                            </option>
                                                            <option timeZoneId="73" gmtAdjustment="GMT+09:30"
                                                                    useDaylightTime="0" value="9.5">
                                                                (GMT+09:30) Darwin
                                                            </option>
                                                            <option timeZoneId="74" gmtAdjustment="GMT+10:00"
                                                                    useDaylightTime="0" value="10">
                                                                (GMT+10:00) Brisbane
                                                            </option>
                                                            <option timeZoneId="75" gmtAdjustment="GMT+10:00"
                                                                    useDaylightTime="1" value="10">
                                                                (GMT+10:00) Canberra, Melbourne, Sydney
                                                            </option>
                                                            <option timeZoneId="76" gmtAdjustment="GMT+10:00"
                                                                    useDaylightTime="1" value="10">
                                                                (GMT+10:00) Hobart
                                                            </option>
                                                            <option timeZoneId="77" gmtAdjustment="GMT+10:00"
                                                                    useDaylightTime="0" value="10">
                                                                (GMT+10:00) Guam, Port Moresby
                                                            </option>
                                                            <option timeZoneId="78" gmtAdjustment="GMT+10:00"
                                                                    useDaylightTime="1" value="10">
                                                                (GMT+10:00) Vladivostok
                                                            </option>
                                                            <option timeZoneId="79" gmtAdjustment="GMT+11:00"
                                                                    useDaylightTime="1" value="11">
                                                                (GMT+11:00) Magadan, Solomon Is., New Caledonia
                                                            </option>
                                                            <option timeZoneId="80" gmtAdjustment="GMT+12:00"
                                                                    useDaylightTime="1" value="12">
                                                                (GMT+12:00) Auckland, Wellington
                                                            </option>
                                                            <option timeZoneId="81" gmtAdjustment="GMT+12:00"
                                                                    useDaylightTime="0" value="12">
                                                                (GMT+12:00) Fiji, Kamchatka, Marshall Is.
                                                            </option>
                                                            <option timeZoneId="82" gmtAdjustment="GMT+13:00"
                                                                    useDaylightTime="0" value="13">
                                                                (GMT+13:00) Nuku'alofa
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">Upload Stamp</label>
                                                    <div class="col-sm-6">

                                                        <input type='file' name="image2">
                                                        <img src="../image/<?php echo $row ['stamp']; ?>"
                                                             alt="Bank Stamp Here"
                                                             height="100"
                                                             width="100"/>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">SMS
                                                        Charges(Monthly)</label>
                                                    <div class="col-sm-6">
                                                        <input name="sms_charges" type="number" class="form-control"
                                                               value="<?php echo $row ['sms_charges']; ?>" required>
                                                    </div>
                                                </div>

                                                <div align="center">
                                                    <div class="box-footer">
                                                        <button type="reset" class="btn btn-primary"><i
                                                                    class="fa fa-times">&nbsp;Reset</i>
                                                        </button>
                                                        <button name="save" type="submit"
                                                                class="btn btn-success"><i
                                                                    class="fa fa-upload">&nbsp;Save</i></button>

                                                    </div>
                                                </div>
                                            </form>

                                        </div>
                                        <div class="panel panel-default">
                                            <div class="panel-body bg-gray-light text-bold"><i class="fa fa-money"></i>
                                                Branches Information <a href="#" class="show_hide_advance_settings">&nbsp;Show</a>
                                            </div>
                                        </div>
                                        <div class="slidingDivAdvanceSettings" style="display: none;">
                                            <form action="" method="post">
                                                <div class="box-body">

                                                    <div class="table-responsive" data-pattern="priority-columns">

                                                        <table cellspacing="0" id="branches"
                                                               class="table table-small-font table-bordered table-striped">

                                                            <thead>
                                                            <tr>
                                                                <th width="2%"><input id="checkAll_occupation"
                                                                                      class="formcontrol"
                                                                                      type="checkbox">
                                                                </th>
                                                                <th width="20%">Branch Name</th>
                                                                <th width="20%">Location</th>
                                                                <th width="20%">Code</th>
                                                            </tr>
                                                            </thead>

                                                            <tbody>

                                                            <?php
                                                            //Get all Settings
                                                            $count = 0;
                                                            $branches = mysqli_query($link, "SELECT * FROM branches WHERE status = 'active'");
                                                            while ($branchInfo = mysqli_fetch_assoc($branches)) {
                                                                $id = $branchInfo['id'];
                                                                $idm = $_GET['id'];
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
                                                                                name="branch[<?php echo $count; ?> ][name]"
                                                                                type="text"
                                                                                class="form-control"
                                                                                placeholder="Code"
                                                                                value="<?php echo $branchInfo['name']; ?>">
                                                                    </td>
                                                                    <td width="300">
                                                                        <select name="branch[<?php echo $count; ?> ][location]"
                                                                                class="form-control" required>
                                                                            <option value="">Select a district&hellip;
                                                                            </option>
                                                                            <option <?php if ($branchInfo['location'] == "Butha Buthe") {
                                                                                echo "selected";
                                                                            } ?> value="Butha Buthe">Butha Buthe
                                                                            </option>
                                                                            <option <?php if ($branchInfo['location'] == "Leribe") {
                                                                                echo "selected";
                                                                            } ?> value="Leribe">Leribe
                                                                            </option>
                                                                            <option <?php if ($branchInfo['location'] == "Berea") {
                                                                                echo "selected";
                                                                            } ?> value="Berea">Berea
                                                                            </option>
                                                                            <option <?php if ($branchInfo['location'] == "Maseru") {
                                                                                echo "selected";
                                                                            } ?> value="Maseru">Maseru
                                                                            </option>
                                                                            <option <?php if ($branchInfo['location'] == "Mafeteng") {
                                                                                echo "selected";
                                                                            } ?> value="Mafeteng">Mafeteng
                                                                            </option>
                                                                            <option <?php if ($branchInfo['location'] == "Mohales Hoek") {
                                                                                echo "selected";
                                                                            } ?> value="Mohales Hoek">Mohales Hoek
                                                                            </option>
                                                                            <option <?php if ($branchInfo['location'] == "Quthing") {
                                                                                echo "selected";
                                                                            } ?> value="Quthing">Quthing
                                                                            </option>
                                                                            <option <?php if ($branchInfo['location'] == "Qachas Nek") {
                                                                                echo "selected";
                                                                            } ?> value="Qachas Nek">Qachas Nek
                                                                            </option>
                                                                            <option <?php if ($branchInfo['location'] == "Thaba Tseka") {
                                                                                echo "selected";
                                                                            } ?> value="Thaba Tseka">Thaba Tseka
                                                                            </option>
                                                                            <option <?php if ($branchInfo['location'] == "Mokhotlong") {
                                                                                echo "selected";
                                                                            } ?> value="Mokhotlong">Mokhotlong
                                                                            </option>
                                                                        </select>
                                                                    </td>
                                                                    <td width="300">

                                                                        <input
                                                                                name="branch[<?php echo $count; ?> ][code]"
                                                                                type="text"
                                                                                class="form-control"
                                                                                placeholder="Code"
                                                                                maxlength="8"
                                                                                value="<?php echo $branchInfo['code']; ?>">

                                                                    </td>
                                                                </tr>
                                                            <?php } ?>

                                                            <tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div align="left">
                                                    <button id="addRows_occupation" type="button"
                                                            class="btn btn-success"><i class="fa fa-plus">&nbsp;Add
                                                            Branch</i></button>
                                                    <button name="delrow" type="submit" class="btn btn-danger">
                                                        <i
                                                                class="fa fa-trash">&nbsp;Delete Branch</i></button>

                                                </div>
                                                <div class="box-footer" align="center">
                                                    <button type="submit" class="btn btn-info"
                                                            name="saveBranches">
                                                        <i class="fa fa-save">&nbsp;Update Branch Information</i>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>


                                        <div class="panel panel-default">
                                            <div class="panel-body bg-gray-light text-bold"><i class="fa fa-user"></i>
                                                Charts Of Accounts Accounting <a href="#"
                                                                                 class="show_hide_accounting_settings">&nbsp;Show</a>
                                            </div>
                                        </div>
                                        <div class="slidingDivAccountingSettings" style="display: none;">
                                            <div align="left">
                                                <button data-target= "#c" data-toggle="modal" type="button" class="btn btn-success"><i class="fa fa-plus"></i>&nbsp;Add New Code</button>

                                            </div>
                                            <div class="box-body">
                                                <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css">
                                                <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.6/css/responsive.bootstrap4.min.css">

                                                <form action="" method="post">
                                                    <div class="table-responsive" data-pattern="priority-columns">

                                                        <!--<table  id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">

                                                            <thead>
                                                            <tr>
                                                                <th width="2%"><input id="checkAll_document"
                                                                                      class="formcontrol"
                                                                                      type="checkbox">
                                                                </th>
                                                                <th width="20%">GL Code</th>
                                                                <th width="20%">Account Name</th>
                                                                <th width="20%">Account Type</th>
                                                                <th width="20%">Action</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php
/*                                                            $count = 0;
                                                            $accounts=mysqli_query($link,"select * from gl_codes");

                                                            while($row=mysqli_fetch_assoc($accounts)){
                                                                */?>
                                                                <tr>
                                                                    <td>
                                                                        <input class="itemRow_document"
                                                                               type="checkbox"
                                                                               name="selector[]"
                                                                               value="<?php /*echo $count;*/?>">
                                                                    </td>
                                                                    <td><?php /*echo $code=$row['code']; */?>
                                                                    </td>

                                                                    <td><?php /*echo $row['name']; */?>
                                                                    </td>

                                                                    <td><?php /*echo $row['type']; */?>
                                                                    </td>
                                                                    <td><?php /*echo '<a href="viewborrowersloan.php?id=' . $code . '&&mid=' . base64_encode("405") . '&&loanId=' . $id . '"><i class="fa fa-pencil"></i></a>'; */?>
                                                                    </td>
                                                                </tr>
                                                                <?php /*$count++;
                                                            }
                                                            */?>
                                                            </tbody>

                                                        </table>-->

                                                        <script type="text/javascript" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
                                                        <script type="text/javascript" src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
                                                        <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.6/js/dataTables.responsive.min.js"></script>
                                                        <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.6/js/responsive.bootstrap4.min.js"></script>
                                                        <script>
                                                            $(document).ready(function() {
                                                                var table = $('#example').DataTable( {
                                                                    responsive: true
                                                                } );

                                                                new $.fn.dataTable.FixedHeader( table );
                                                            } );
                                                        </script>
                                                    </div>
                                            </div>


                                            </form>

                                        </div>
                                    </div>

                                    <?php if ($row ['bureau_submission'] == 1) { ?>
                                        <div class="tab-pane" id="tab_2">
                                            <!--FIXME, Make a Drop Down of all credit providers then update Bureau File Accordingly. -->
                                            <form class="form-horizontal" method="post" action="" enctype="multipart/form-data">
                                                <input type="hidden" value="<?php echo $row ['sysid']; ?>" name="sysid">
                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">Company Trading
                                                        Name</label>
                                                    <div class="col-sm-6">
                                                        <input name="companyTrading" type="text" class="form-control"
                                                               value="<?php echo $row ['trading_name']; ?>" required>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">Supplier Reference
                                                        Number</label>
                                                    <div class="col-sm-6">
                                                        <input name="srn" type="text" class="form-control"
                                                               value="<?php echo $row ['srn']; ?>" required>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">Recipient</label>
                                                    <div class="col-sm-6">
                                                        <input name="recipient" type="text" class="form-control"
                                                               value="<?php echo $row ['recipient']; ?>" required>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">Bureau Submission
                                                        Cycle</label>
                                                    <div class="col-sm-6">
                                                        <select class="form-control" id="compuscan_cycle"
                                                                name="submission_cycle" style="width: 100%;"
                                                                onchange="showfield(this.options[this.selectedIndex].value)"
                                                                required>
                                                            <option selected="selected">--Select--</option>
                                                            <option value="D" <?php if ($row['submission_cycle'] == "D") {
                                                                echo "selected";
                                                            } ?>>Daily
                                                            </option>
                                                            <option value="A" <?php if ($row['submission_cycle'] == "A") {
                                                                echo "selected";
                                                            } ?>>Adhoc
                                                            </option>
                                                            <option value="M" <?php if ($row['submission_cycle'] == "M") {
                                                                echo "selected";
                                                            } ?>>Monthly
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="pull-left" style="margin-top:5px">
                                                            <a href="#">
                                                                <?php if ($row['submission_cycle'] == "Weekly" || $row['submission_cycle'] == "M") {
                                                                    $day = $row['day_of_submission'];
                                                                    if ($row['submission_cycle'] == "Weekly") {
                                                                        if ($day == 1) {
                                                                            $day = "Sunday";
                                                                        }
                                                                        if ($day == 2) {
                                                                            $day = "Monday";
                                                                        }
                                                                        if ($day == 3) {
                                                                            $day = "Tuesday";
                                                                        }
                                                                        if ($day == 4) {
                                                                            $day = "Wednesday";
                                                                        }
                                                                        if ($day == 5) {
                                                                            $day = "Thursday";
                                                                        }
                                                                        if ($day == 6) {
                                                                            $day = "Friday";
                                                                        }
                                                                        if ($day == 7) {
                                                                            $day = "Saturday";
                                                                        }
                                                                        echo "<h4><span class='label label-success'> on " . $day . "</span></h4>";
                                                                    } else {
                                                                        echo "<h4><span class='label label-success'> on day " . $row['day_of_submission'] . "</span></h4>";
                                                                    }
                                                                } ?>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="share_data_date"></div>
                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">File Type</label>
                                                    <div class="col-sm-6">
                                                        <select class="form-control" id="file_type"
                                                                name="file_type" style="width: 100%;"
                                                                required>
                                                            <option selected="selected">--Select--</option>
                                                            <option value="T702" <?php if ($row['file_type'] == "T702") {
                                                                echo "selected";
                                                            } ?>>Test File
                                                            </option>
                                                            <option value="L702" <?php if ($row['file_type'] == "L702") {
                                                                echo "selected";
                                                            } ?>>Live File
                                                            </option>
                                                        </select>
                                                    </div>


                                                </div>

                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">Submission
                                                        Method</label>
                                                    <div class="col-sm-6">
                                                        <input name="emailSFTP" type="radio"
                                                               value="Email" <?php if ($row['submission_method'] == "Email") {
                                                            echo "checked";
                                                        } ?>> Email
                                                        <input name="emailSFTP" type="radio"
                                                               value="SFTP" <?php if ($row['submission_method'] == "SFTP") {
                                                            echo "checked";
                                                        } ?>> SFTP
                                                    </div>
                                                </div>
                                                <div style="<?php if ($row['submission_method'] === "SFTP") {
                                                    echo "display: block;";
                                                } else {
                                                    echo "display: none;";
                                                } ?>" id="sftpSubmissionMethod">
                                                    <div class="form-group">
                                                        <label for="" class="col-sm-3 control-label">SFTP URL</label>
                                                        <div class="col-sm-6">
                                                            <input name="sftpUrl" type="text" class="form-control"
                                                                   value="<?php echo $row ['sftp_url']; ?>"
                                                            <?php if ($row['submission_method'] === "SFTP") {
                                                                    echo "required";
                                                             }
                                                             ?>
                                                            >
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="" class="col-sm-3 control-label">SFTP
                                                            Username</label>
                                                        <div class="col-sm-6">
                                                            <input name="sftpUsername" type="text" class="form-control"
                                                                   value="<?php echo $row ['sftp_username']; ?>"
                                                                <?php if ($row['submission_method'] === "SFTP") {
                                                                    echo "required";
                                                                }
                                                                ?>
                                                                   >
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="" class="col-sm-3 control-label">SFTP PORT</label>
                                                        <div class="col-sm-6">
                                                            <input name="sftpPort" type="text" class="form-control"
                                                                   value="<?php echo $row ['sftp_port']; ?>"
                                                                <?php if ($row['submission_method'] === "SFTP") {
                                                                echo "required";
                                                            }
                                                            ?>
                                                            >
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="" class="col-sm-3 control-label">SFTP
                                                            Password</label>
                                                        <div class="col-sm-6">
                                                            <input name="sftpPassword" type="password" class="form-control"
                                                                   value="<?php echo $row ['sftp_password']; ?>"
                                                                <?php if ($row['submission_method'] === "SFTP") {
                                                                    echo "required";
                                                                }
                                                                ?>
                                                            >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group"
                                                     style="<?php if ($row['submission_method'] === "Email") {
                                                         echo "display: block;";
                                                     } else {
                                                         echo "display: none;";
                                                     } ?>" id="emailSubmissionMethod">
                                                    <label for="" class="col-sm-3 control-label">Bureau Email</label>
                                                    <div class="col-sm-6">
                                                        <input name="bureauEmail" type="text" class="form-control"
                                                               value="<?php echo $row ['bureau_email']; ?>"
                                                            <?php if ($row['submission_method'] === "Email") {
                                                                echo "required";
                                                            }
                                                            ?>
                                                        >
                                                    </div>
                                                </div>

                                                <div align="center">
                                                    <div class="box-footer">
                                                        <button type="reset" class="btn btn-primary"><i
                                                                    class="fa fa-times">&nbsp;Reset</i></button>
                                                        <button name="saveBureauInfo" value="saveBureauInfo"
                                                                type="submit" class="btn btn-success"><i
                                                                    class="fa fa-upload">&nbsp;Save</i></button>
                                                    </div>
                                                </div>
                                            </form>

                                        </div>
                                    <?php } ?>
                                    <?php if ($row ['scoring'] == 1) { ?>
                                        <div class="tab-pane" id="tab_3">
                                            <form class="form-horizontal" method="post" enctype="multipart/form-data">
                                                <input type="hidden" value="<?php echo $row ['sysid']; ?>" name="sysid">
                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">Affordability Check Provider</label>
                                                    <div class="col-sm-6">
                                                        <input name="provider" type="text" class="form-control"
                                                               placeholder="Provider Name"
                                                               value="<?php //echo $row ['branch_name']; ?>" required>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">End Point URL</label>
                                                    <div class="col-sm-6">
                                                        <input name="url" type="text" class="form-control"
                                                               placeholder="URL"
                                                               value="<?php //echo $row ['branch_code']; ?>" required>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">Username</label>
                                                    <div class="col-sm-6">
                                                        <input name="username" type="text" class="form-control"
                                                               placeholder="Username"
                                                               value="<?php //echo $row ['branch_code']; ?>" required>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="" class="col-sm-3 control-label">Password</label>
                                                    <div class="col-sm-6">
                                                        <input name="password" type="password" class="form-control"
                                                               placeholder="Password"
                                                               value="<?php //echo $row ['branch_code']; ?>" required>
                                                    </div>
                                                </div>
                                                <div align="center">
                                                    <div class="box-footer">
                                                        <button type="reset" class="btn btn-primary"><i
                                                                    class="fa fa-times">&nbsp;Reset</i>
                                                        </button>
                                                        <button name="saveAffordabilityCheck" type="submit"
                                                                class="btn btn-success"><i
                                                                    class="fa fa-upload">&nbsp;Save Now</i></button>

                                                    </div>
                                                </div>
                                            </form>
                                            <table id="example1" class="table table-bordered table-striped">
                                                <thead>
                                                <tr role="row">
                                                    <th>Provider</th>
                                                    <th>URL</th>
                                                    <th>Username</th>
                                                    <th>Password</th>
                                                    <th>Status</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                $getProviders = mysqli_query($link, "select * from affordability_check");
                                                while ($row = mysqli_fetch_assoc($getProviders)) {
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $row['provider']; ?></td>
                                                        <td><?php echo $row['endpoint']; ?></td>
                                                        <td><?php echo $row['username']; ?></td>
                                                        <td><?php echo base64_encode($row['password']); ?></td>
                                                        <td><?php echo $row['status']; ?></td>
                                                    </tr>
                                                <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>

                    </div>
                    <?php
                }
                ?>

            </div>
        </div>
    </div>
</div>

<script>
    var minLoan = document.getElementById('min_loan');
    var maxLoan = document.getElementById('max_loan');

    function validatePassword() {
        if (minLoan.value > maxLoan.value) {
            maxLoan.setCustomValidity('');
        } else {
            maxLoan.setCustomValidity('Minimum amount cannot be more than maximum amount');
        }
    }

    minLoan.addEventListener('change', validatePassword);
    maxLoan.addEventListener('keyup', validatePassword);
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        $("input[type='radio']").change(function () {
            if ($(this).val() === "Email") {
                $("#emailSubmissionMethod").show();
            } else {
                $("#emailSubmissionMethod").hide();
            }
            if ($(this).val() === "SFTP") {
                $("#sftpSubmissionMethod").show();
            } else {
                $("#sftpSubmissionMethod").hide();
            }
        });
    });
</script>

<script type="text/javascript">
    function showfield(name) {
        <?php
        $get = mysqli_query($link, "SELECT * FROM systemset order by sysid") or die (mysqli_error($link));
        $get_day = mysqli_fetch_assoc($get);
        $day = $get_day['day_of_submission'];
        ?>
        if (name == 'A') {
            document.getElementById('share_data_date').innerHTML = '<div class="form-group">\n' +
                '                            <label for="" class="col-sm-3 control-label">Day of Week</label>\n' +
                '                            <div class="col-sm-6">' +
                '<select name="day_of_submission" class="form-control" required>' +
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
        } else if (name == 'M') {
            document.getElementById('share_data_date').innerHTML = '<div class="form-group">\n' +
                '                            <label for="" class="col-sm-3 control-label">Day of Month</label>\n' +
                '                            <div class="col-sm-6">' +
                '<select name="day_of_submission" class="form-control" required>' +
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
            htmlRows += '<td><input type="text" placeholder="Branch Name" name="branch[' + count + '][name]" id="Name' + count + '" class="form-control" autocomplete="off" required></td>';
            htmlRows += '' +
                '<td>' +
                '<select name="branch[' + count + '][location]" class="form-control" required> ' +
                '<option value="">Select a district&hellip;</option> ' +
                '<option value="Butha Buthe">Butha Buthe</option> ' +
                '<option value="Leribe">Leribe</option> ' +
                '<option value="Berea">Berea</option> ' +
                '<option value="Maseru">Maseru</option> ' +
                '<option value="Mafeteng">Mafeteng</option> ' +
                '<option value="Mohales Hoek">Mohales Hoek</option> ' +
                '<option value="Quthing">Quthing</option> ' +
                '<option value="Qachas Nek">Qachas Nek</option> ' +
                '<option value="Thaba Tseka">Thaba Tseka</option> ' +
                '<option value="Mokhotlong">Mokhotlong</option> ' +
                '</select>' +
                '</td>';
            htmlRows += '<td><input type="text" maxlenght="8" placeholder="Branch Code" name="branch[' + count + '][code]" maxlength="8" id="code' + count + '" min="0" class="form-control" autocomplete="off" required></td>';
            htmlRows += '</tr>';
            $('#branches').append(htmlRows);
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
            htmlRows += '<td><input type="text" placeholder="Document Name" name="document[' + count + '][name]" id="Name' + count + '" class="form-control" autocomplete="off" required></td>';
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
        $(".slidingDivDocumentSettings").hide();
        $('.show_hide_document_settings').click(function (e) {
            $(".slidingDivDocumentSettings").slideToggle("fast");
            var val = $(this).text() == "Hide" ? "Show" : "Hide";
            $(this).hide().text(val).fadeIn("fast");
            e.preventDefault();
        });
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
        $(".slidingDivCompanySettings").hide();
        $('.show_hide_company_settings').click(function (e) {
            $(".slidingDivCompanySettings").slideToggle("fast");
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
