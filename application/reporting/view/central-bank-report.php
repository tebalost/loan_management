<?php
require_once "../vendor/autoload.php";
require_once "../../../config/connect.php";
require_once "../data/FinancialPosition.php";
require_once "../data/FinancialPerformance.php";
require_once "../data/cbl-financial-position/CentralBankCommon.php";
require_once "../data/LiquidityReturn.php";
require_once "../data/AdequacyReturn.php";
require_once "../data/cbl-debtors/Debtors.php";
require_once "../data/cbl-inclusion/DurationalLoans.php";
require_once "../data/cbl-inclusion/GenderBasedLoans.php";
require_once "../data/cbl-inclusion/LoanByStatus.php";
require_once "../data/cbl-inclusion/Indicators.php";


use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use \PhpOffice\PhpSpreadsheet\Reader\Exception;

// Loan the template
try {
    $reader = IOFactory::createReader('Xlsx');
} catch (Exception $e) {
    echo $e->getMessage();
}


$spreadsheet = $reader->load('../CBL_report/CBL submission Template.xlsx');
$time = time();

foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {

    $spreadsheet->setActiveSheetIndex($spreadsheet->getIndex($worksheet));

    $sheet = $spreadsheet->getActiveSheet();
    $cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(true);
    /** @var PHPExcel_Cell $cell */
    foreach ($cellIterator as $cell) {
        $sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
    }
}

// getting the data from generating classes
$currentAsset = FinancialPosition::getCurrentAssets();


$spreadsheet->getSheet(0)
    ->setCellValue('B5', CentralBankCommon::getClientName())
    ->setCellValue('B7', Date::PHPToExcel($time));

$spreadsheet->getActiveSheet()
    ->getStyle('B7')
    ->getNumberFormat()
    ->setFormatCode(NumberFormat::FORMAT_DATE_YYYYMMDD2);

// current assets section
$spreadsheet->getSheet(0)
    ->setCellValue('B12', $currentAsset->getBankDepositMaturity())
    ->setCellValue('B13', $currentAsset->getOtherDepositMaturity())
    ->setCellValue('B14', $currentAsset->getUnearnedInterest())
    ->setCellValue('B15', $currentAsset->getAccountReceivable())
    ->setCellValue('B16', $currentAsset->getLoanPayableLessThanAYear())
    ->setCellValue('B17', $currentAsset->getProvisionOfDoubtful())
    ->setCellValue('B19', $currentAsset->getOtherCurrentAssets());


// Non current assets section
$nonCurrentAssets = FinancialPosition::getNonCurrentAssets();
$spreadsheet->getSheet(0)
            ->setCellValue('B23', $nonCurrentAssets->getInvestments())
            ->setCellValue('B24', $nonCurrentAssets->getLongTermLoans())
            ->setCellValue('B27', $nonCurrentAssets->getOfficeFurnitureAndFittings())
            ->setCellValue('B30', $nonCurrentAssets->getPremises())
            ->setCellValue('B35', $nonCurrentAssets->getOtherNonCurrentAssets());



// liabilities section
$currentLiabilities = FinancialPosition::getCurrentLiabilities();
$spreadsheet->getSheet(0)
    ->setCellValue('B42', $currentLiabilities->getAmountPayableToCreditors());

// equity
$equity = FinancialPosition::getEquity();
$spreadsheet->getSheet(0)
            ->setCellValue('B54', $equity->getFundCapital())
            ->setCellValue('B59', $equity->getRetainedEarnings());
/** end of sheet one */


/** start of sheet two containing financial performance */
$spreadsheet->getActiveSheet()
    ->getStyle('B6')
    ->getNumberFormat()
    ->setFormatCode(NumberFormat::FORMAT_DATE_YYYYMMDD2);

// outputing the name and date
$spreadsheet->getSheet(1)
    ->setCellValue('B4', CentralBankCommon::getClientName())
    ->setCellValue('B6', Date::PHPToExcel($time));


// Income section
$incomes = FinancialPerformance::getIncomeData();
$spreadsheet->getSheet(1);
$spreadsheet->getActiveSheet()
    ->getColumnDimension('B')
    ->setAutoSize(true);

$spreadsheet->getSheet(1)
            ->setCellValue('B9', $incomes->getLoanInterest())
            ->setCellValue('B10', $incomes->getFeeIncome())
            ->setCellValue('B12', $incomes->getAnyOtherIncome());



// expenses section
$expenses = FinancialPerformance::getExpenses();
$spreadsheet->getSheet(1)
            ->setCellValue('B16', $expenses->getAccommodation())
            ->setCellValue('B18', $expenses->getBadDebts())
            ->setCellValue('B20', $expenses->getComputerCharges())
            ->setCellValue('B23',  $expenses->getSalariesAndPayAsYouEarn())
            ->setCellValue('B24', $expenses->getOtherExpenses());



/**  end of the financial performance */


$bankDeposit = LiquidityReturn::getBankingDeposit();
$spreadsheet->getActiveSheet()
    ->getStyle('B7')
    ->getNumberFormat()
    ->setFormatCode(NumberFormat::FORMAT_DATE_YYYYMMDD2);

$spreadsheet->getSheet(2)
            ->setCellValue('B4', CentralBankCommon::getClientName())
            ->setCellValue('B7', Date::PHPToExcel($time));



// formatting the data output



// setting the desposit for the banks ans non baking
$spreadsheet->getSheet(2)
            ->setCellValue('B11', $bankDeposit->getStandardBank() + $bankDeposit->getNetBankDeposit() + $bankDeposit->getFnbDeposit() + $bankDeposit->getPostBankDeposit() )
            ->setCellValue('B12', $bankDeposit->getStandardBank())
            ->setCellValue('B13', $bankDeposit->getNetBankDeposit())
            ->setCellValue('B14', $bankDeposit->getFnbDeposit())
            ->setCellValue('B15', $bankDeposit->getPostBankDeposit());


// setting the mobile money diposit
$mobileMoney = LiquidityReturn::getMobileMoneyDeposit();
$spreadsheet->getSheet(2)
            ->setCellValue('B16', $mobileMoney->getMpesa() + $mobileMoney->getEcocash() )
            ->setCellValue('A17','Name of NBFL 1. M-pesa')
            ->setCellValue('B17', $mobileMoney->getMpesa())
            ->setCellValue('A18', '2. Ecocash')
            ->setCellValue('B18', $mobileMoney->getEcocash());


/** Adeqaucy return sheet */
$adequacyReturn = AdequacyReturn::getAssets();
$spreadsheet->getActiveSheet()
    ->getStyle('B8')
    ->getNumberFormat()
    ->setFormatCode(NumberFormat::FORMAT_DATE_YYYYMMDD2);

$spreadsheet->getSheet(2)
    ->setCellValue('B6', CentralBankCommon::getClientName())
    ->setCellValue('B8', Date::PHPToExcel($time));


// inserting the assets
$spreadsheet->getSheet(3)
            ->setCellValue('B22', $adequacyReturn->getDepositWithOtherBanks())
            ->setCellValue('B23', $adequacyReturn->getDepositWithNBFI())
            ->setCellValue('B25', $adequacyReturn->getOfficeFurnitureAndEquipment());

/** end of financial adequacy */







/** Top ten debtors */
$debtors = Debtors::getTopDebtors();
$spreadsheet->getActiveSheet()
    ->getStyle('B3')
    ->getNumberFormat()
    ->setFormatCode(NumberFormat::FORMAT_DATE_YYYYMMDD2);

$spreadsheet->getSheet(2)
    ->setCellValue('B2', CentralBankCommon::getClientName())
    ->setCellValue('B3', Date::PHPToExcel($time));




// pululating the deboters
$index = 7;
while ($record = mysqli_fetch_assoc($debtors)){
    $spreadsheet->getSheet(6)
                ->setCellValue('B'.$index, $record['lname'].' '.$record['fname'])
                ->setCellValue('C'.$index, $record['loan_amount'])
                ->setCellValue('D'.$index, $record['amount'])
                ->setCellValue('E'.$index, $record['interest_value'])
                ->setCellValue('F'.$index, $record['amount_topay'])
                ->setCellValue('G'.$index, $record['balance']);
    $index++;
}
/** end top ten debtors sheet */

/** start of the financial inclusion sheet */
$spreadsheet->getActiveSheet()
    ->getStyle('C6')
    ->getNumberFormat()
    ->setFormatCode(NumberFormat::FORMAT_DATE_YYYYMMDD2);

$spreadsheet->getSheet(2)
    ->setCellValue('C4', CentralBankCommon::getClientName())
    ->setCellValue('C6', Date::PHPToExcel($time));


// Setting loans based on gender
$loansForMales = GenderBasedLoans::getMalesLoans();
$loansForFemales = GenderBasedLoans::getFemale();

$spreadsheet->getSheet(7)
            ->setCellValue('C12', $loansForMales['number_of_loans'])
            ->setCellValue('D12', $loansForMales['total_loans'])
            ->setCellValue('C13', $loansForFemales['number_of_loans'])
            ->setCellValue('D13', $loansForFemales['total_loans']);


//  setting loans based on the duration
$firstLevel = DurationalLoans::getFirstLevelLoans();
$secondLevel = DurationalLoans::getSecondLevelLoans();
$thirdLevel = DurationalLoans::getThirdLevelLoans();

$spreadsheet->getSheet(7)
            ->setCellValue('C29', $firstLevel['number_of_loans'])
            ->setCellValue('D29', $firstLevel['total_loans'])
            ->setCellValue('C30', $secondLevel['number_of_loans'])
            ->setCellValue('D30', $secondLevel['total_loans'])
            ->setCellValue('C31', $thirdLevel['number_of_loans'])
            ->setCellValue('D31', $thirdLevel['total_loans']);


/* loan additional information  */
$newlyDisbursed = LoanByStatus::getNewlyDisbursedLoans();
$settledLoans = LoanByStatus::getSettledLoans();
$passedMonthArreas  = LoanByStatus::getOverdueLoans();
$monthlyOverDueLoans = LoanByStatus::getDuebyThirtyDays();

$spreadsheet->getSheet(7)
            ->setCellValue('C36', $newlyDisbursed['number_of_loans'])
            ->setCellValue('D36', $newlyDisbursed['total_loans'])
            ->setCellValue('C37', $settledLoans['number_of_loans'])
            ->setCellValue('D37', $settledLoans['total_loans'])
            ->setCellValue('C38', $passedMonthArreas['number_of_loans'])
            ->setCellValue('D38', $passedMonthArreas['total_loans'])
            ->setCellValue('C39', $monthlyOverDueLoans['number_of_loans'])
            ->setCellValue('D39', $monthlyOverDueLoans['total_loans']);


// Qaulity indicators
$spreadsheet->getSheet(7)
            ->setCellValue('D44', Indicators::getMaxLoan())
            ->setCellValue('D45', Indicators::getMinLoan());
/** end financial inclusion sheet*/




// redirect output to client browser
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="cbl_report.xlsx"');

// letting the file be downloaded to the users localhost device
$writer = IOFactory::createWriter($spreadsheet,'Xlsx');
$writer->save('php://output');


