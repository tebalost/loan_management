<?php

require_once "../data/InsuranceData.php";
require_once "../vendor/autoload.php";
require_once "../../../config/connect.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
global $link;


function dateDifference($dateOfBirth, $today, $differenceFormat = '%y')
{
    $datetime1 = date_create($dateOfBirth);
    $datetime2 = date_create($today);

    $interval = date_diff($datetime1, $datetime2);

    return $interval->format($differenceFormat);
    //echo $interval;

}

function calculateInsurancePremium($amount, $yearsNo){
    $clientNumberOfyear = $yearsNo;
    if(($clientNumberOfyear >= 18) && ($clientNumberOfyear < 65)) {
        return $amount * 0.00105;
    } else {
        return $amount * 0.00210;
    }
}


$worksheet =  new Spreadsheet();
$sheet =  $worksheet->getActiveSheet();
$header = ["Surname","Name","Date of Birth","Account no.","Loan Date","Loan Term","Sum Assured","Premium"	,"Commission","Due to MetLes"];
$insuranceRecords = InsuranceData::getInsuranceRecord();

// Setting the column width to auto adjust
$worksheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$worksheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$worksheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$worksheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
$worksheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
$worksheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
$worksheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
$worksheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
$worksheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
$worksheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
$worksheet->getActiveSheet()->getStyle('A1:J1')->getFont()->setSize(14)->setBold(true);

// making my columns
$worksheet->getActiveSheet()
    ->setCellValue('A1', $header[0])
    ->setCellValue('B1', $header[1])
    ->setCellValue('C1', $header[2])
    ->setCellValue('D1', $header[3])
    ->setCellValue('E1', $header[4])
    ->setCellValue('F1', $header[5])
    ->setCellValue('G1', $header[6])
    ->setCellValue('H1', $header[7])
    ->setCellValue('I1', $header[8])
    ->setCellValue('J1', $header[9]);

$count = 2;
$totalPremium = 0;
$totalAmountInsured = 0;
$numberOfloans = 0;
while($row = mysqli_fetch_array($insuranceRecords)){
    $numberOfYears = dateDifference($row[2], $today = date("Y-m-d"));
    $premium = calculateInsurancePremium($row[6], $numberOfYears);
    $totalPremium += $premium;
    $totalAmountInsured += $row[6];
    $numberOfloans++;
    $worksheet->getActiveSheet()
        ->setCellValue('A'.$count, $row[0])
        ->setCellValue('B'.$count, $row[1])
        ->setCellValue('C'.$count, $row[2])
        ->setCellValue('D'.$count,  "'".$row[3])
        ->setCellValue('E'.$count, $row[4])
        ->setCellValue('F'.$count, $row[5])
        ->setCellValue('G'.$count, $row[6])
        ->setCellValue('H'.$count, round($premium,2))
        ->setCellValue('I'.$count,'-')
        ->setCellValue('J'.$count, round($premium,2));
    $count++;
}

$worksheet->getActiveSheet()->getStyle('A'.$count.':J'.$count)->getFont()->setSize(12)->setBold(true);

$worksheet->getActiveSheet()
    ->setCellValue('G'.$count, round($totalAmountInsured,2))
    ->setCellValue('H'.$count, round($totalPremium,2))
    ->setCellValue('J'.$count, round($totalPremium,2));

$filename = "insurance_file_".date('Y_m').'.xlsx';
// first update database
mysqli_query($link, "INSERT  INTO insurance_loan_life_cover VALUES (0,'$filename',NOW(), $totalPremium,$numberOfloans)") or die(mysqli_error($link));

$writer = new Xlsx($worksheet);
$writer->save('../files-to-insurance/'.$filename);


