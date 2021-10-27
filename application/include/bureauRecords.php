<?php
if (isset($_GET['action'])) {
    error_reporting(0);
    //Data Segments
    ///Come Here with the batch Code
    $batch = $_GET['batch'];
    /// Get All Records then dump them into a batches table
    $selectBatchRecords=mysqli_query($link,"select * from bureau_records where batch='$batch'");
    if(mysqli_num_rows($selectBatchRecords)=="0"){
        $SQL = "insert into bureau_records 
                SELECT 0,$batch,borrowers.id, id_number, passport, baccount, district, country, reason,bureauAccountType, modified_date, ownershipType,
                                        lname, fname, gender, phone, employer, application_date,loan_info.balance, loan_repayment_method, loan_payment_scheme, postal, ownership_type,
                                        amount_topay, loan_duration,loan_duration_period, loan_info.status, date_of_birth, branch, title, addrs2, addrs1
                                        FROM loan_info, borrowers WHERE borrowers.id = loan_info.borrower 
                                        AND loan_info.status not in('DECLINED','Pending','Pending Disbursement')";
        mysqli_query($link,$SQL);
    }

    $action = $_GET['action'];

    //

    $SQL = "SELECT borrowers.id, id_number, passport, date_of_birth, baccount, district, country, reason,bureauAccountType, modified_date, ownershipType,
                                        lname, fname, gender, phone, employer, application_date,loan_info.balance, loan_repayment_method, loan_payment_scheme, postal, ownership_type,
                                        amount_topay, loan_duration,loan_duration_period, telephone, loan_info.status, date_of_birth, branch, title, addrs2, addrs1
                                        FROM loan_info, borrowers WHERE borrowers.id = loan_info.borrower 
                                        AND loan_info.status not in('DECLINED','Pending','Pending Disbursement')";

    $lastDay = date('Y-m-d');
    $today = date('Y-m-d');
    function dateDifference($lastDay, $today, $differenceFormat = '%m Months')
    {
        $datetime1 = date_create($lastDay);
        $datetime2 = date_create($today);

        $interval = date_diff($datetime1, $datetime2);

        return $interval->format($differenceFormat);
        //echo $interval;
    }


    $dataSegmentsData = mysqli_query($link, $SQL) or die ("Sql error : " . mysqli_error($link));
    $api = "http://35.225.221.35:8080/compuscan-api/send-to-compuscan";
    $dataSegments = [];
    $eachDataSegment = 0;
    while ($segment = mysqli_fetch_assoc($dataSegmentsData)) {

        //Get Max Payment
        $strJsonFileContents = file_get_contents('include/packages.json');
        $arrayOfTypes = json_decode($strJsonFileContents, true);
        $loan_payment_scheme = $segment['loan_payment_scheme'];

        foreach ($arrayOfTypes['repaymentFrequencyCode'] as $key => $value) {
            if ($loan_payment_scheme == $value) {
                $repayment_payment_frequency = $key;
            }
        }

        $account = $segment['baccount'];
        $borrower = $segment['id'];
        $maxDay = mysqli_fetch_assoc(mysqli_query($link, "select max(pay_date), sum(amount_to_pay) from payments where account_no='$account'"));
        $loan = mysqli_fetch_assoc(mysqli_query($link, "select id from loan_info where baccount = '$account'"));
        $loanId = $loan['id'];
        $remainingBalance = $segment['balance'] - $maxDay['sum(amount_to_pay)'];
        if ($remainingBalance > 0) {
            $balanceType = "D";
        } else {
            $remainingBalance*=(-1);
            $balanceType = "C";
        }

        $lastDay = $maxDay['max(pay_date)'];
        if ($lastDay == "") {
            $lastDay = "00000000";
        } else {
            $lastDay = $lastDay = substr($row['application_date'],0,10);;
        }

        //Get Occupation Information
        $getOccupation = mysqli_query($link, "select * from fin_info where get_id = '$borrower'");
        $income = 0;
        $occupation = "";
        $frequency = "";
        while ($allIncomes = mysqli_fetch_assoc($getOccupation)) {
            $income += $allIncomes['mincome'];
            $occupation = $allIncomes['occupation'];
            $frequency = $allIncomes['frequency'];
        }
        $dataSegments[$eachDataSegment]['accountNo'] = $account;
        $dataSegments[$eachDataSegment]['dateOnWhichLastPaymentWasReceived'] = $lastDay;
        if($lastDay==="00000000"){
            $lastDay = date("Ymd", strtotime($segment['application_date']));
        }
        $dataSegments[$eachDataSegment]['accountSoldToThirdParty'] = "";
        $dataSegments[$eachDataSegment]['amountOverdue'] = round($segment['amount_topay']*dateDifference($lastDay, $today, $differenceFormat = '%m'),0);
        //GET Branch Code
        $branch = $segment['branch'];
        $branchC = mysqli_fetch_assoc(mysqli_query($link,"select * from branches where code='$branch'"));
        $branchCode = $branchC['code'];
        $subAccountNo = $branchC['sub_account'];
        if($subAccountNo==""){
            $subAccountNo="0000";
        }

        $dataSegments[$eachDataSegment]['branchCode'] = $branch;
        $dataSegments[$eachDataSegment]['cellularTelephone'] = $segment['phone'];
        $dataSegments[$eachDataSegment]['currentBalance'] = round($remainingBalance, 0);
        $dataSegments[$eachDataSegment]['currentBalanceIndicator'] = "$balanceType"; //What are different Balance indicators?
        $dataSegments[$eachDataSegment]['data'] = "D";
        $dataSegments[$eachDataSegment]['dateAccountOpened'] = date("Ymd", strtotime($segment['application_date']));
        $dataSegments[$eachDataSegment]['dateOfBirth'] = date("Ymd", strtotime($segment['date_of_birth']));

        $dataSegments[$eachDataSegment]['deferredPaymentDate'] = "00000000";
        $dataSegments[$eachDataSegment]['employerDetail'] = $segment['employer'];
        $dataSegments[$eachDataSegment]['filler'] = "";
        $dataSegments[$eachDataSegment]['foreNameOrInitial1'] = $segment['fname'];;
        $dataSegments[$eachDataSegment]['foreNameOrInitial2'] = "";
        $dataSegments[$eachDataSegment]['foreNameOrInitial3'] = "";
        $dataSegments[$eachDataSegment]['gender'] = substr($segment['gender'], 0, 1);
        $dataSegments[$eachDataSegment]['homeTelephone'] = $segment['telephone'];
        $dataSegments[$eachDataSegment]['idNumber'] = $segment['id_number'];
        $dataSegments[$eachDataSegment]['income'] = round($income,0); //Sum The Income from Income table
        $dataSegments[$eachDataSegment]['incomeFrequency'] = "$frequency";//Add Income Frequency when adding borrower income information
        $dataSegments[$eachDataSegment]['instalmentAmount'] = round($segment['amount_topay'], 0);
        $dataSegments[$eachDataSegment]['loanReasonCode'] = $segment['reason'];
        $dataSegments[$eachDataSegment]['monthsInArrears'] = dateDifference($lastDay, $today, $differenceFormat = '%m');
        $dataSegments[$eachDataSegment]['noOfParticipantsInJointLoan'] = "";
        $dataSegments[$eachDataSegment]['occupation'] = "$occupation"; //Get Occupation Info From Occupation table
        $dataSegments[$eachDataSegment]['oldAccountNumber'] = "";
        $dataSegments[$eachDataSegment]['oldSubAccountNumber'] = "";
        $dataSegments[$eachDataSegment]['oldSupplierBranchCode'] = "";
        $dataSegments[$eachDataSegment]['oldSupplierReferenceNumber'] = "";
        $dataSegments[$eachDataSegment]['openingBalanceOrCreditLimit'] = round($segment['balance'], 0);//Balance Before Payment//To Calculate
        $dataSegments[$eachDataSegment]['otherIdNumberOrPassportNumber'] = $segment['passport'];
        $dataSegments[$eachDataSegment]['ownerOrTenant'] = $segment['ownershipType'];; //FIXME Add the fields, Owner/tenant, Ownership type and payment type? {Owner[O], Tenant [T]}
        $dataSegments[$eachDataSegment]['ownershipType'] = $segment['ownership_type'];;//FIXME Ownership Type {00-Other, 01-Sole Proprietor, 02, Joint Loan}
        $dataSegments[$eachDataSegment]['paymentType'] = $segment['loan_repayment_method'];
        $dataSegments[$eachDataSegment]['postalAddressLine1'] = substr(explode("\r\n", $segment['addrs2'])[0],0,25);
        $dataSegments[$eachDataSegment]['postalAddressLine2'] = substr(explode("\r\n", $segment['addrs2'])[1],0,25);
        $dataSegments[$eachDataSegment]['postalAddressLine3'] = substr(explode("\r\n", $segment['addrs2'])[2],0,25);
        $dataSegments[$eachDataSegment]['postalAddressLine4'] = $segment['district'];
        $dataSegments[$eachDataSegment]['postalCodeOfPostalAddress'] = $segment['postal'];;
        $dataSegments[$eachDataSegment]['postalCodeOfResidentialAddress'] = "";
        $dataSegments[$eachDataSegment]['repaymentFrequency'] = $repayment_payment_frequency;
        $dataSegments[$eachDataSegment]['residentialAddressLine1'] = substr(explode("\r\n", $segment['addrs1'])[0],0,25);
        $dataSegments[$eachDataSegment]['residentialAddressLine2'] = substr(explode("\r\n", $segment['addrs1'])[1],0,25);;
        $dataSegments[$eachDataSegment]['residentialAddressLine3'] = substr(explode("\r\n", $segment['addrs1'])[2],0,25);
        $dataSegments[$eachDataSegment]['residentialAddressLine4'] = $segment['district'];;
        //Get Status Code
        //mysqli_query($link,"select status, max(added_date) from loan_statuses where loan='$loanId'");

        if ($segment['status'] !== "") {
            $maxDateActive = mysqli_fetch_assoc(mysqli_query($link, "select max(added_date) from loan_statuses where loan='$loanId' and status!=''"));
            $statusDate = substr($maxDateActive['max(added_date)'],0,10);
            $statusChangeDate = date("Ymd", strtotime($statusDate));
        } else {
            $statusChangeDate = "00000000";
        }

        $dataSegments[$eachDataSegment]['statusCode'] = $segment['status']; //Loan Statues (Disputed, Terminated, Paid Up), FIX it as edit the Loan Level
        $dataSegments[$eachDataSegment]['statusDate'] = $statusChangeDate;//Date Last Modified, Only if applicable
        $dataSegments[$eachDataSegment]['subAccountNo'] = $subAccountNo; //Loan Account Number
        $dataSegments[$eachDataSegment]['surname'] = $segment['lname'];
        $dataSegments[$eachDataSegment]['terms'] = $segment['loan_duration'];
        $dataSegments[$eachDataSegment]['thirdPartyName'] = "";
        $dataSegments[$eachDataSegment]['tittle'] = $segment['title'];
        $dataSegments[$eachDataSegment]['typeOfAccount'] = $segment['bureauAccountType'];//Fix All Existing Loans
        $dataSegments[$eachDataSegment]['workTelephone'] = "";


        $eachDataSegment++;
    }


    //Header Information
    $provider = mysqli_fetch_assoc(mysqli_query($link, "select * from systemset"));

    //File Naming
    $srn = $provider['srn'];
    $recipient = $provider['recipient'];
    $date = date('Ymd');
    $frequency = $provider['submission_cycle'];
    $fileType = $provider['file_type'];

    $headSegment = [];
    $headSegment['header'] = "H";
    $headSegment['dateFileWasCreated'] = date("Ymd");
    $headSegment['monthEndDate'] = date("Ymt", strtotime($date));
    $headSegment['supplierReferenceNumber'] = $provider['srn'];
    $headSegment['tradingNameOrBrandName'] = $provider['trading_name'];
    $headSegment['versionNumber'] = "01";
    $headSegment['filler'] = "";

    $tailSegment = [];
    $tailSegment['filler'] = "";
    $tailSegment['numberOfRecordsSupplied'] = mysqli_num_rows($dataSegmentsData);
    $tailSegment['trailer'] = "T";

    $fileName = $srn . "_" . $recipient . "_" . $fileType . "_" . $frequency . "_" . date("Ymt", strtotime($date)) . "_01_01.txt";

    $compuscanSegment = [];
    $compuscanSegment['destinationEmail'] = $provider['bureau_email'];
    $compuscanSegment['fileName'] = "$fileName";
    $compuscanSegment['mode'] = "$action";
    $compuscanSegment['sftpPassword'] = $provider['sftp_password'];
    $compuscanSegment['sftpUsername'] = $provider['sftp_username'];

    $segmentDto = array_merge(array("headSegment" => $headSegment), array("tailSegment" => $tailSegment), array("dataSegments" => $dataSegments));

    $bureauData = json_encode(array("compuscanHeaderDto" => $compuscanSegment, 'segmentDto' => $segmentDto));

    //print_r($bureauData);

    function postToAPI($dataString, $api)
    {
        $ch = curl_init($api);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($dataString))
        );
        return json_decode(curl_exec($ch), true);
    }

    $bureauFile = postToAPI($bureauData, $api);
    $fileContents = json_encode($bureauFile);

    $numRecords=mysqli_num_rows($dataSegmentsData);
    $tid=$_SESSION['tid'];

    mysqli_query($link,"update bureau_submissions set status='$bureauFile', action_date=NOW(), loan_records='$numRecords', action_by='$tid' where batch='$batch'");
    $menu=  base64_encode("405");
    $URL = "bureausubmissions.php?id=$tid&mid=$menu";
    echo "<script type='text/javascript'>document.location.href='{$URL}';</script>";
    echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $URL . '">';

/*
    $createfile = file_put_contents("../bureau_files/$fileName.txt", "");

    $submissionFile = fopen("../bureau_files/$fileName.txt", "w") or die("Unable to open file!");
    $header = $bureauFile['headRow'] . "\n";
    fwrite($submissionFile, $header);
    foreach ($bureauFile['dataRows'] as $value) {
        $dataDetails = $value['dataRow'] . "\n";
        fwrite($submissionFile, $dataDetails);
        //print_r($dataDetails);
    }
    $tail = $bureauFile['tailRow'];
    fwrite($submissionFile, $tail);
    fclose($submissionFile);
    $nameOfFile = "$fileName.txt";

    $filepath = "../bureau_files/" . $nameOfFile;
    // Process download
    if ($action == "Download") {
        if (file_exists($filepath)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($filepath) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filepath));
            flush(); // Flush system output buffer
            readfile($filepath);
            die();
        } else {
            http_response_code(404);
            die();
        }
    }
    else if ($action == "Email") {
        if (file_exists($filepath)) {
            //Send Email Code Here
            $file = "../bureau_files/" . $nameOfFile;
            $content = file_get_contents($file);
            $content = chunk_split(base64_encode($content));
            $uid = md5(uniqid(time()));
            $name = basename($file);

            $from_name = "SBS Eazy Loan";
            $from_mail = "data@sbs-eazy.loans";
            $replyto = "data@sbs-eazy.loans";

            $emailTo = mysqli_fetch_assoc(mysqli_query($link, "select * from systemset"));
            $mailto = $emailTo['bureau_email'];

            $subject = "Compuscan Test File";

            $message = "This is the body of the message";

// header
            $header = "From: " . $from_name . " <" . $from_mail . ">\r\n";
            $header .= "Reply-To: " . $replyto . "\r\n";
            $header .= "MIME-Version: 1.0\r\n";
            $header .= "Content-Type: multipart/mixed; boundary=\"" . $uid . "\"\r\n\r\n";

// message & attachment
            $nmessage = "--" . $uid . "\r\n";
            $nmessage .= "Content-type:text/plain; charset=iso-8859-1\r\n";
            $nmessage .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
            $nmessage .= $message . "\r\n\r\n";
            $nmessage .= "--" . $uid . "\r\n";
            $nmessage .= "Content-Type: application/octet-stream; name=\"" . $nameOfFile . "\"\r\n";
            $nmessage .= "Content-Transfer-Encoding: base64\r\n";
            $nmessage .= "Content-Disposition: attachment; filename=\"" . $nameOfFile . "\"\r\n\r\n";
            $nmessage .= $content . "\r\n\r\n";
            $nmessage .= "--" . $uid . "--";

            if (mail($mailto, $subject, $nmessage, $header)) {
                flush(); // Flush system output buffer

                echo "<div class=\"alert alert-success\" >
                                        <a href = \"#\" class = \"close\" data-dismiss= \"alert\"> &times;</a>
                                        successfully send: $file to $mailto&nbsp; &nbsp;&nbsp;
                                           </div>";
            } else {
                echo "<div class=\"alert alert-danger\" >
                                        <a href = \"#\" class = \"close\" data-dismiss= \"alert\"> &times;</a>
                                        Failed to send: $file to $mailto&nbsp; &nbsp;&nbsp;
                                           </div>";
                flush(); // Flush system output buffer
            }
        }
    }
    else if ($action == "SFTP") {
        if (file_exists($filepath)) {
            $file = "../bureau_files/" . $nameOfFile;
            $remote_file = $nameOfFile;

            //Get SFTP Server Details
            $sftp = mysqli_fetch_assoc(mysqli_query($link, "select * from systemset"));

            $ftp_server = $sftp['sftp_url'];
// set up basic connection
            $conn_id = ftp_connect($ftp_server);
            $ftp_user_name = $sftp['sftp_username'];
            $ftp_user_pass = $sftp['sftp_password'];
// login with username and password
            $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

// upload a file
            if (ftp_put($conn_id, $remote_file, $file, FTP_ASCII)) {
                echo "<div class=\"alert alert-success\" >
                                        <a href = \"#\" class = \"close\" data-dismiss= \"alert\"> &times;</a>
                                        successfully uploaded $file\n&nbsp; &nbsp;&nbsp;
                                           </div>";
            } else {
                echo "<div class=\"alert alert-danger\" >
                                        <a href = \"#\" class = \"close\" data-dismiss= \"alert\"> &times;</a>
                                        There was a problem while uploading $file\n&nbsp; &nbsp;&nbsp;
                                           </div>";
            }

// close the connection
            ftp_close($conn_id);

        }
    }*/
}
?>
