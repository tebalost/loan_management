<?php
include "../config/session.php";
$date1 = explode(">",base64_decode($_GET['printReq']))[0];
$date2 = explode(">",base64_decode($_GET['printReq']))[1];
$SQL = "SELECT
   lname AS 'Last Name',
   fname AS 'First Name',
   gender AS 'Gender',
   phone as 'Phone',
   employer as 'Employer',
   id_number as 'ID Number',
   passport as 'Passport',
   date_of_birth as 'Date of Birth',
   baccount AS 'Loan Account',
   amount as 'Principal',
   loan_info.balance as 'Total Expected',
   interest_value as 'Interest',
   fees as 'Loan Fees',
   amount_topay AS 'Instalment',
   date_release as 'Start Date',
   loan_maturity as 'Loan Maturity',
   loan_duration AS 'Loan Duration',
   loan_duration_period AS 'Duration Period',
   telephone AS 'Telephone',
   branch AS 'Branch' 
FROM
   loan_info,
   borrowers 
WHERE
   borrowers.id = loan_info.borrower 
   AND loan_info.status not in
   (
      'DECLINED',
      'Pending'
   )
   AND date_release between '$date1' and '$date2'"; ///Add SQL HERE
$header = '';
$result = '';
$exportData = mysqli_query($link, $SQL) or die ("Sql error : " . mysqli_error($link));

$fields = mysqli_fetch_assoc($exportData);

foreach ($fields as $column => $data) {
    $header .= $column . "\t";
}

while ($row = mysqli_fetch_row($exportData)) {
    $line = '';
    foreach ($row as $value) {
        if ((!isset($value)) || ($value == "")) {
            $value = "\t";
        } else {
            $value = str_replace('"', '""', $value);
            $value = '"' . $value . '"' . "\t";
        }
        $line .= $value;
    }
    $result .= trim($line) . "\n";
}
$result = str_replace("\r", "", $result);

if ($result == "") {
    $result = "\nNo Record(s) Found!\n";
}
$filename=date('dMY_His');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Loans_Export_$filename.xls");
header("Pragma: no-cache");
header("Expires: 0");
print "$header\n$result";

?>