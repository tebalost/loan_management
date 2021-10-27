<?php

require_once '../../include/tcpdf/tcpdf.php';
require_once '../data/system-charge-data.php';

$systemcharge = SystemChargeData::getSystemChargeByDate();

class MYPDF extends TCPDF {
    public function ColoredTable($systemcharge) {

        //global $systemcharge;

        $this->SetFillColor(255,165,0);
        $this->SetTextColor(255);
        $this->SetDrawColor(128, 0, 0);
        $this->SetLineWidth(0.3);
        $this->SetFont('', 'B');
        // Header
        $widthOfFieldInTable = array(10, 60, 40, 25, 45);
        $fill = 1;
        //setting table header
        $this->Cell($widthOfFieldInTable[0], 6, 'No', '', 0, 'C', $fill);
        $this->Cell($widthOfFieldInTable[1], 6, 'Items', '', 0, 'C', $fill);
        $this->Cell($widthOfFieldInTable[2], 6, 'Quantity', '', 0, 'C', $fill);
        $this->Cell($widthOfFieldInTable[3], 6, 'Price', '', 0, 'R', $fill);
        $this->Cell($widthOfFieldInTable[4], 6, 'Amount' , '', 0, 'R', $fill);
        $this->Ln();

        $fill = 0;
        $count = 0;
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(220, 220, 220)));
        $fill = 0;

        $count = 1;

        // Hosting Charge
        $this->Cell($widthOfFieldInTable[0], 6, $count++, 'B', 0, 'C', $fill);
        $this->Cell($widthOfFieldInTable[1], 6, HOSTING_ITEM_DESCRIPTION, 'B', 0, 'L', $fill);
        $this->Cell($widthOfFieldInTable[2], 6, 1, 'B', 0, 'C', $fill);
        $this->Cell($widthOfFieldInTable[3], 6, number_format($systemcharge->getHostingCharge(), 2, '.', ','), 'B', 0, 'R', $fill);
        $this->Cell($widthOfFieldInTable[4], 6, number_format($systemcharge->getHostingCharge(), 2, '.', ','), 'B', 0, 'R', $fill);
        $this->Ln();
        // Loan Transaction Charge
        $this->Cell($widthOfFieldInTable[0], 6, $count++, 'B', 0, 'C', $fill);
        $this->Cell($widthOfFieldInTable[1], 6, LOAN_TRANSACTION_DESCRIPTION , 'B', 0, 'L', $fill);
        $this->Cell($widthOfFieldInTable[2], 6, $systemcharge->getNumberOfLoanCharged(), 'B', '', 'C', $fill);
        $this->Cell($widthOfFieldInTable[3], 6, number_format($systemcharge->getLoanTransactionsCharge(), 2, '.', ','), 'B', 0, 'R', $fill);
        $this->Cell($widthOfFieldInTable[4], 6, number_format($systemcharge->getLoanTransactionsCharge(), 2, '.', ','), 'B', 0, 'R', $fill);
        $this->Ln();

        // sms Charge
        $this->Cell($widthOfFieldInTable[0], 6, $count++, 'B', 0, 'C', $fill);
        $this->Cell($widthOfFieldInTable[1], 6, SMS_CHARGE, 'B', 0, 'L', $fill);
        $this->Cell($widthOfFieldInTable[2], 6, $systemcharge->getNumberOfSMSs(), 'B', 0, 'C', $fill);
        $this->Cell($widthOfFieldInTable[3], 6, number_format($systemcharge->getSmsCharge(), 2, '.', ','), 'B', 0, 'R', $fill);
        $this->Cell($widthOfFieldInTable[4], 6, number_format($systemcharge->getSmsCharge(), 2, '.', ','), 'B', 0, 'R', $fill);
        $this->Ln();


        // Total
        $this->Cell($widthOfFieldInTable[0], 6, '', '', 0, 'L', $fill);
        $this->Cell($widthOfFieldInTable[1], 6, '', '', 0, 'L', $fill);
        $this->Cell($widthOfFieldInTable[2], 6, '', '', 0, 'R', $fill);
        $this->Cell($widthOfFieldInTable[3], 6, 'Total:', 'B', 0, 'R', $fill);
        $this->Cell($widthOfFieldInTable[4], 6, number_format($systemcharge->getTotalCharge(), 2, '.',','), 'B', 0, 'R', $fill);
        $this->Ln();

        $this->Cell($widthOfFieldInTable[0], 6, '', '', 0, 'L', $fill);
        $this->Cell($widthOfFieldInTable[1], 6, '', '', 0, 'L', $fill);
        $this->Cell($widthOfFieldInTable[2], 6, '', '', 0, 'R', $fill);
        $this->Cell($widthOfFieldInTable[3], 6, 'Amount Due (ZAR):', 'B', 0, 'R', $fill);
        $this->Cell($widthOfFieldInTable[4], 6, number_format($systemcharge->getTotalCharge(), 2, '.',','), 'B', 0, 'R', $fill);
        $this->Ln();

    }

    public function printStatement(){
        global $link;
        $this->SetFillColor(255,165,0);
        $this->SetTextColor(255);
        $this->SetDrawColor(128, 0, 0);
        $this->SetLineWidth(0.3);
        $this->SetFont('', 'B');

        // Header
        $widthOfFieldInTable = array(10, 60, 40, 25, 45);
        $fill = 1;
        //setting table header
        $this->Cell($widthOfFieldInTable[0], 6, 'No', '', 0, 'C', $fill);
        $this->Cell($widthOfFieldInTable[1], 6, 'Release Date', '', 0, 'C', $fill);
        $this->Cell($widthOfFieldInTable[2], 6, 'Loan Account', '', 0, 'C', $fill);
        $this->Cell($widthOfFieldInTable[3], 6, 'Principal', '', 0, 'R', $fill);
        $this->Cell($widthOfFieldInTable[4], 6, 'Fee' , '', 0, 'R', $fill);
        $this->Ln();

        $fill = 0;
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(220, 220, 220)));

        $regular = mysqli_query($link, "SELECT added_date, baccount, amount, fee_amount 
                                            FROM `loan_fees`,loan_statuses, loan_info 
                                            WHERE `fee_name` LIKE 'System Charge on loan' 
                                            AND loan_statuses.loan=loan_fees.loan 
                                            AND loan_statuses.loan = loan_info.id
                                            AND loan_statuses.status='' 
                                            AND DATE_FORMAT(date_added, '%Y-%m') = date_format(now(), '%Y-%m')") or die(mysqli_error($link));

        $counter = 1;
        $total = 0;
        while ($data = mysqli_fetch_assoc($regular)){
            $this->Cell($widthOfFieldInTable[0], 6, $counter++, 'BL', 0, 'C', $fill);
            $this->Cell($widthOfFieldInTable[1], 6, $data['added_date'], 'B', 0, 'C', $fill);
            $this->Cell($widthOfFieldInTable[2], 6, $data['baccount'], 'B', 0, 'C', $fill);
            $this->Cell($widthOfFieldInTable[3], 6, number_format($data['amount'],2,'.',','), 'B', 0, 'R', $fill);
            $this->Cell($widthOfFieldInTable[4], 6, number_format($data['fee_amount'],2,'.',','), 'BR', 0, 'R', $fill);
            $this->Ln();
            $total += $data['fee_amount'];
        }

        $this->SetFillColor(220,220,220);
        $this->SetTextColor(0);
        $this->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(220, 220, 220)));
        $fill = 1;

        // Total
        $this->Cell($widthOfFieldInTable[0], 6, '', 'L', 0, 'L', $fill);
        $this->Cell($widthOfFieldInTable[1], 6, '', '', 0, 'L', $fill);
        $this->Cell($widthOfFieldInTable[2], 6, '', '', 0, 'R', $fill);
        $this->Cell($widthOfFieldInTable[3], 6, 'Total:', 'LBT', 0, 'R', $fill);
        $this->Cell($widthOfFieldInTable[4], 6, number_format($total, 2, '.',','), 'BTR', 0, 'R', $fill);
        $this->Ln();

    }
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('sbs eazyloan');
$pdf->SetTitle('system invoice');
$pdf->SetSubject('Invoice for the system usage');


// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', '', 10);

// add the first page
$pdf->AddPage();

// logo
$pdf->Image('logo.png', 20, 35, 50, 15, 'png', 'http://www.tcpdf.org', '', true, 150, '', false, false, 0, false, false, false);
$date = date('F, Y');
// address
$pdf->writeHTML('
<style>
   .company {
      font-weight: bold;
      font-size: 13px;
   }
        
   .address {
      text-align: right;
   }
   div{
      margin-bottom: 5px;
   }
</style>

 <div class="address">
        <h4 class="company">Serumula Business Solutions</h4>
        <p class="address">136 2nd Street <br> Randjespark Midrand, <br/>Gauteng 1685<br/> South Africa <br/> (+27) 82 2072 730 <br/> sales@serumula.com </p>
</div>
<h3 style="text-align: center;"> Invoice for the month of '.$date.' </h3>
<hr> <br/>
', true, false, true, false, '');


function prepareAddress($address){
    $addr = "";
    for ($i = 0; $i < strlen($address); $i++) {
        $addr .= $address[$i];
        if ($address[$i] === ',') {
            $addr .= "<br/>";
        }
    }
    return $addr;
}

$companyInfo = $systemcharge->getclientBillingDetails();

$html = prepareAddress($companyInfo['address']);



# loading the inserted number
$invoiceInfo  = $systemcharge->getInvoiceDetails($date);
$pdf->writeHTML('
<style>

</style>

<table border="0">
	<tr>
		<td><h4>'.$companyInfo['name'].'</h4>'.$html.'</td>
		<td>
			<table>
				<tr>
					<th> <b>Invoice Number:</b></th>
					<td>'.$invoiceInfo['invoice_number'].'</td>
				</tr>
				<tr>
					<th></th>
					<td></td>
				</tr>
				<tr>
					<th> <b>Invoice Date:</b> </th>
					<td>'.date('F d, Y', strtotime($invoiceInfo['issue_date'])).'</td>
				</tr>
				<tr>
					<th></th>
					<td></td>
				</tr>
				<tr>
					<th> <b>Payment Due:</b> </th>
					<td>'.date('F d, Y', strtotime($invoiceInfo['due_date'])).' </td>
				</tr>
				<tr>
					<th></th>
					<td></td>
				</tr>
				<tr>
					<th> <b>Amount Due (ZAR):</b> </th>
					<td>'.number_format($invoiceInfo['Amount'], 2, '.',',').'</td>
				</tr>
		
			</table>
		</td>
	</tr>
</table>
', true, false, true, false, '');



// print colored table
$pdf->ColoredTable($systemcharge);

$pdf->writeHTML('
<pre style="margin-top: 100px"><p style="font-weight: bold">
<h2>Notes / Terms</h2><span style="font-weight: normal">
Serumula Business Solutions,                            Golden Walk branch 250242 <br/>
Business Cheque Account,                                62808936704  <br/>
FNB South Africa,                                       Swift code: FIRNZAJJ <br/></span>
</p>
</pre>
', true, false, true, false, '');

// ---------------------------------------------------------
// adding the second page
$pdf->AddPage();

// logo
$pdf->Image('logo.png', 20, 35, 50, 15, 'png', 'http://www.tcpdf.org', '', true, 150, '', false, false, 0, false, false, false);

// address
$pdf->writeHTML('
<style>
   .company {
      font-weight: bold;
      font-size: 13px;
   }
        
   .address {
      text-align: right;
   }
   div{
      margin-bottom: 5px;
   }
</style>

 <div class="address">
        <h4 class="company">Serumula Business Solutions</h4>
        <p class="address">136 2nd Street <br> Randjespark Midrand, <br/>Gauteng 1685<br/> South Africa <br/> (+27) 82 2072 730 <br/> sales@serumula.com </p>
</div>
<h3 style="text-align: center;"> All active loans for '.$date.' </h3>
<hr> <br/>
', true, false, true, false, '');
$pdf->printStatement();


// close and output PDF document
$base64pdf = $pdf->Output('system_usage_invoice.pdf', 'S');     // FixME form filename using date parameter

//============================================================+
// END OF FILE
//==================