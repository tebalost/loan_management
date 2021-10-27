<?php
require_once('../include/tcpdf/tcpdf.php');
require_once '../../config/connect.php';

class MYPDF extends TCPDF
{

    //local connection varible
    private $link = null;
    private $payments = [];
    private $paydates = [];


    //field array that form the colunm data in every records
    private $count = [];
    private $shedule = [];
    private $payTypes = [];
    private $principalDue = [];
    private $interest = [];
    private $fees = [];
    private $balance = [];
    private $total_due = [];
    private $principalBalance = [];


    //total that
    private $principal = 0;
    private $initialLoan  = 0;

    private $totalProncipalDue = 0;
    private $totalInterest = 0;
    private $totalFees = 0;
    private $totalInstalment = 0;
    private $totalDue = 0;
    private $monthToDateDueTotal = 0;
    private $totalPaid = 0;

    // varible to hold account of the borrower
    private $account = 0;
    public $outStanding = 0;

    // Load table data from file
    public function __construct($orientation = 'P', $unit = 'mm', $format = 'A4', $unicode = true, $encoding = 'UTF-8', $diskcache = false, $pdfa = false, $link)
    {
        parent::__construct($orientation, $unit, $format, $unicode, $encoding, $diskcache, $pdfa);
        $this->link = $link;
    }

    // retrive payment data from database to and process to be ready for report display format
    public function getPayment($borrower)
    {
        $selectPayments = mysqli_query($this->link, "SELECT * FROM payments WHERE customer = '$borrower' and account='$this->account'") or die (mysqli_error($this->link));
        while ($payment = mysqli_fetch_assoc($selectPayments)) {
            $this->totalPaid += $payment['amount_to_pay'];
            array_push($this->paydates, substr($payment['pay_date'], 0, 10));
            array_push($this->payments, $payment['amount_to_pay']);
        }
    }

    //retriving the scedule data from data and processing it
    public function loanSchedule($loanId)
    {
        $i = 1;
        $getSchedule = mysqli_query($this->link, "SELECT * FROM pay_schedule where get_id='$loanId'") or die($this->link);
        while ($schedule = mysqli_fetch_assoc($getSchedule)) {
            array_push($this->count, $i);
            array_push($this->shedule, $schedule['schedule']);
            array_push($this->payTypes, $schedule['pay_type']);

            $principal_due = round($schedule['principal_due'], 2);
            array_push($this->principalDue, $principal_due);
            $this->totalProncipalDue += $principal_due;


            $interest_due = round($schedule['interest'], 2);
            array_push($this->interest, $interest_due);
            $this->totalInterest += $interest_due;

            $fees_due = round($schedule['fees'], 2);
            array_push($this->fees, $fees_due);
            $this->totalFees += $fees_due;

            $totaldue = $schedule['balance'];
            array_push($this->balance, $totaldue);
            $this->totalInstalment += $totaldue;

            $monthToDateDue = $schedule['total_due'];
            array_push($this->total_due, $monthToDateDue);
            $this->totalDue += $monthToDateDue;

            $this->monthToDateDueTotal += $monthToDateDue;

            $this->principal = $this->principal - $schedule['principal_due'];
            array_push($this->principalBalance, $this->principal);
            $i++;
        }
    }

    //get princpal
    public function getPrincipal($loanId)
    {
        $selectLoan = mysqli_query($this->link, "SELECT * FROM loan_info WHERE id = '$loanId'") or die (mysqli_error($this->link));
        $loan = mysqli_fetch_array($selectLoan);
        $this->principal = $loan['amount'];
        $this->initialLoan = $loan['amount'];
    }

    public function LoadData($id, $loanId)
    {
        $data = null;
        $client = mysqli_query($this->link, "SELECT * FROM borrowers WHERE id='$id'") or die (mysqli_error($this->link));
        $loan = mysqli_query($this->link, "SELECT * FROM loan_info WHERE borrower='$id' and id='$loanId'") or die (mysqli_error($this->link));
        if ($client && $loan) {
            $clientInfo = mysqli_fetch_assoc($client);
            $loanInfo = mysqli_fetch_assoc($loan);

            $data = array_merge($clientInfo, $loanInfo);
            $this->account = $loanInfo['baccount'];

            $totalPayments = mysqli_fetch_assoc(mysqli_query($this->link, "SELECT sum(amount_to_pay) FROM payments WHERE customer = '$id' and account='$this->account'"));
            $totalPaid = $totalPayments['sum(amount_to_pay)'];
            $expectedBalance = $data['balance'];
            $this->outStanding = $expectedBalance - $totalPaid;
        }

        return $data;
    }

    // Colored table
    public function ColoredTable($header) {
        // Colors, line width and bold font
        $this->SetFillColor(255, 0, 0);
        $this->SetTextColor(255);
        $this->SetDrawColor(128, 0, 0);
        $this->SetLineWidth(0.3);
        $this->SetFont('', 'B');
        // Header
        $w = array(4, 18, 25, 23, 20, 15, 18, 17, 20, 15);
        $num_headers = count($header);
        for($i = 0; $i < $num_headers; ++$i) {
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);
        }
        $this->Ln();
        // Color and font restoration
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');

        $fill = 0;
        //check if the loan is set
        if(isset($this->principal)){
            $this->Cell($w[0], 6, '', 'L', 0, 'L', $fill);
            $this->Cell($w[1], 6, '', 0, 0, 'L', $fill);
            $this->Cell($w[2], 6, '', 0, 0, 'R', $fill);
            $this->Cell($w[3], 6, '', 0, 0, 'L', $fill);
            $this->Cell($w[4], 6, '', 0, 0, 'R', $fill);
            $this->Cell($w[5], 6, '', 0, 0, 'R', $fill);
            $this->Cell($w[6], 6, '', 0, 0, 'R', $fill);
            $this->Cell($w[7], 6, '', 0, 0, 'R', $fill);
            $this->Cell($w[8], 6, '', 0, 0, 'R', $fill);
            $this->Cell($w[9], 6,  number_format($this->initialLoan ,2,'.',','), 'LR', 0, 'R', $fill);
            $this->Ln();
            $fill=!$fill;
        }


        // counter for number of payment;
        $index = 0;
        $count = count($this->paydates);

        // printing the avaible paymnets
        while($index < $count){
            $this->Cell($w[0], 6, '', 'LRTB', 0, 'L', $fill);
            $this->Cell($w[1], 6, $this->paydates[$index], 'LRTB', 0, 'L', $fill);
            $this->Cell($w[2], 6, 'Payment', 'LRTB', 0, 'LR', $fill);
            $this->Cell($w[3], 6, '', 'LRTB', 0, 'LR', $fill);
            $this->Cell($w[4], 6, '', 'LRTB', 0, 'LR', $fill);
            $this->Cell($w[5], 6, '', 'LRTB', 0, 'LR', $fill);
            $this->Cell($w[6], 6, '', 'LRTB', 0, 'LR', $fill);
            $this->Cell($w[7], 6, number_format($this->payments[$index]), 'LTRB', 0, 'LR', $fill);
            $this->Cell($w[8], 6, '', 'LRTB', 0, 'LR', $fill);
            $this->Cell($w[9], 6, '', 'LRTB', 0, 'LR', $fill);
            $this->Ln();
            $fill=!$fill;

            // incrementing the index
            $index++;
        }


        // writting the repayment schedule
        $count = count($this->count);
        $counter = 1;
        $index = 0;
        while($index < $count){
            $this->Cell($w[0], 6, $counter, 'LRTB', 0, 'L', $fill);
            $this->Cell($w[1], 6, $this->shedule[$index], 'LRTB', 0, 'L', $fill);
            $this->Cell($w[2], 6, 'Repayment', 'LRTB', 0, 'LR', $fill);
            $this->Cell($w[3], 6, number_format($this->principalDue[$index],2,'.', ','), 'LRTB', 0, 'R', $fill);
            $this->Cell($w[4], 6, number_format($this->interest[$index],2,'.', ','), 'LRTB', 0, 'R', $fill);
            $this->Cell($w[5], 6, number_format($this->fees[$index],2,'.', ','), 'LRTB', 0, 'R', $fill);
            $this->Cell($w[6], 6, number_format($this->balance[$index],2,'.', ','), 'LRTB', 0, 'R', $fill);
            $this->Cell($w[7], 6, 0, 'LTRB', 0, 'R', $fill);
            $this->Cell($w[8], 6,  number_format($this->total_due[$index],2,'.', ','), 'LRTB', 0, 'R', $fill);
            $this->Cell($w[9], 6, number_format($this->principalBalance[$index],2,'.', ','), 'LRTB', 0, 'R', $fill);
            $this->Ln();
            $fill=!$fill;
            $counter++;
            $index++;
        }
        if(isset($this->totalDue)){
            $this->Cell($w[0], 6, '', 'LRTB', 0, 'L', $fill);
            $this->Cell($w[1], 6, '', 'LRTB', 0, 'L', $fill);
            $this->Cell($w[2], 6, 'Total Due', 'LRTB', 0, 'LR', $fill);
            $this->Cell($w[3], 6, number_format($this->totalProncipalDue,2,'.',','), 'LRTB', 0, 'R', $fill);
            $this->Cell($w[4], 6, number_format($this->totalInterest,2,'.',','), 'LRTB', 0, 'R', $fill);
            $this->Cell($w[5], 6, number_format($this->totalFees,2,'.',','), 'LRTB', 0, 'R', $fill);
            $this->Cell($w[6], 6, number_format($this->totalInstalment,2,'.',','), 'LTRB', 0, 'R', $fill);
            $this->Cell($w[7], 6,  '', 'LRTB', 0, 'LR', $fill);
            $this->Cell($w[8], 6, '', 'LRTB', 0, 'LR', $fill);
            $this->Cell($w[9], 6, '', 'LRTB', 0, 'LR', $fill);
            $this->Ln();
            $fill=!$fill;
        }
        if(isset($this->totalPaid)){
            $this->Cell($w[0], 6, '', 'LRTB', 0, 'L', $fill);
            $this->Cell($w[1], 6, '', 'LRTB', 0, 'L', $fill);
            $this->Cell($w[2], 6, 'Total Paid', 'LRTB', 0, 'LR', $fill);
            $this->Cell($w[3], 6, '', 'LRTB', 0, 'LR', $fill);
            $this->Cell($w[4], 6, '', 'LRTB', 0, 'LR', $fill);
            $this->Cell($w[5], 6, '', 'LRTB', 0, 'LR', $fill);
            $this->Cell($w[6], 6, '', 'LRTB', 0, 'LR', $fill);
            $this->Cell($w[7], 6, $this->totalPaid, 'LTRB', 0, 'LR', $fill);
            $this->Cell($w[8], 6,  '', 'LRTB', 0, 'LR', $fill);
            $this->Cell($w[9], 6, '', 'LRTB', 0, 'LR', $fill);
            $this->Ln();
            $fill=!$fill;
        }

        if(isset($this->totalDue)){
            $this->Cell($w[0], 6, '', 'LRTB', 0, 'L', $fill);
            $this->Cell($w[1], 6, '', 'LRTB', 0, 'L', $fill);
            $this->Cell($w[2], 6, 'Total Pending', 'LTB', 0, 'LR', $fill);
            $this->Cell($w[3], 6, 'due', 'LTB', 0, 'LR', $fill);
            $this->Cell($w[4], 6, '', 'LRTB', 0, 'LR', $fill);
            $this->Cell($w[5], 6, '', 'LRTB', 0, 'LR', $fill);
            $this->Cell($w[6], 6, '', 'LRTB', 0, 'LR', $fill);
            $this->Cell($w[7], 6, $this->monthToDateDueTotal, 'LTRB', 0, 'LR', $fill);
            $this->Cell($w[8], 6,  '', 'LRTB', 0, 'LR', $fill);
            $this->Cell($w[9], 6, '', 'LRTB', 0, 'LR', $fill);
            $this->Ln();
            $fill=!$fill;
        }

    }
}


// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false,'', $link);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 011');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');



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


// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', '', 9);

// add a page
$pdf->AddPage();

// column titles
$header = array('#', 'Date', 'Description', 'Principal Due', 'Interest','Fees','Installment', 'Paid', 'Pending due', 'Principal');

// data loading
function getProcessedAddress($data){
    $address = $data['addrs1'];
    $result = "<p>";
    for ($i = 0; $i < strlen($address); $i++) {
        $result .= $address[$i];
        if ($address[$i] === ',') {
            $result .= "<br>";
        }
    }
}

// grepping the required ids to get the borrower information
$id = $_GET['id'];
$loanid = $_GET['loanId'];

// data loading
$data = $pdf->LoadData('291', '178');
getProcessedAddress($data);


$html = <<<EOD
<h5 style="">Ground Floor,<br>Options Building 240<br>Pioneer Road Europa,<br>Maseru 100,<br>Lesotho</h5>
EOD;



$address = <<<ADRR
<p><b>Email</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;info@pulamaliboho-fs.com <br>
<b>Website</b>&nbsp;&nbsp; pulamaliboho-fs.com <br>
<b>Contacts </b> 22321577
</p>
ADRR;



// Print text using writeHTMLCell()
$pdf->writeHTMLCell(0, 0, 15, 20, $html, 0, 1, 0, true, '', true);
$pdf->Image('logo.png', '67', '17', 60, 25, '', '', 'T', false, 300, '', false, false, 1, false, false, false);
$pdf->writeHTMLCell(0, 0, 140, 20, $address, 0, 1, 0, true, '', true);

$pdf->cell( 0,10,"Registration Number :  98484", 0,1,'C', '', '','1', true, '','center');
$pdf->cell( 0,10,"LOAN REPAYMENT SCHEDULE", 1,1,'C', '', '','1', true, '','center');

$pdf->writeHTML('
<style>
string{
 font-size: 20px;
 text-transform: capitalize;
}
</style>

<section class="">
 <br/><br/><strong>'. $data['title'].'&nbsp;'.$data['fname'].'&nbsp;'.$data['lname'].'</strong> <br>'.$address.'
</section>', true, 0, true, 0);

$pdf->writeHTML('
<style type="text/css">
    .box_title_print{
        text-align: center;
        font-weight: bold;
    }
    
    .pull-right{
        text-align: right;
    }
    
    .client-info{
        width: 100%;
        padding-top: 10px;
    }
    .nowrap { white-space: nowrap; }
    
</style>
<section class="invoice">
        <h4 class="box_title_print">Loan Terms</h4>
        <hr/>
        <table class="client-info">
            <tr>
                <td><strong>Loan #</strong></td>
                <td><span class="pull-right"> '.$data['baccount'].'</span></td>
                 <td><strong>Loan Period</strong></td>
                <td><span class="pull-right">'.$data['loan_duration']."&nbsp;".$data['loan_duration_period'].'</span></td>
            </tr>
            <tr>
                <td><strong>Released Date</strong></td>
                <td><span class="pull-right">'.date("d/m/Y", strtotime($data['date_release'])).'</span></td>   
                <td class="nowrap"><strong>Instalment</strong></td>
                <td><span class="pull-right">'.number_format($data['amount_topay'],2,'.',',').'</span></td>
            </tr>
            <tr>
                <td><strong>Maturity Date</strong></td>
                <td><span class="pull-right">'.date("d/m/Y", strtotime($data['loan_maturity'])).'</span></td>
                <td><strong>Loan Balance to Maturity</strong> </td>
                <td><span class="pull-right"> '.number_format($data['balance'], 2, ".", ",").'</span></td>
            </tr>
            <tr>
                <td><strong>Repayment Cycle</strong></td>
                <td><span class="pull-right">'.$data['loan_payment_scheme'].'</span></td>
                <td> </td>
                <td> </td>
            </tr>
            <tr>
                <td><strong>Principal Amount</strong></td>
                <td><span class="pull-right">'.number_format($data['amount'], 2, ".", ",").'</span></td>
                <td><strong>Loan Balance</strong> </td>
                <td><span class="pull-right">'.number_format($pdf->outStanding,2,'.',',').'</span></td>
            </tr>
            
            <tr>
                <td><strong>Interest Rate</strong> </td>
                <td><span class="pull-right">'.$data['loan_interest'].'</span></td>
            </tr>
        </table>
    </section>

',true,0, true,0);

// output the HTML content
$pdf->writeHTML('
<style type="text/css">
.box_title_print{
        text-align: center;
        font-weight: bold;
    }
</style>
<section class="invoice">
<h4 class="box_title_print">Schedule</h4>
<hr><br>', true, true, true, true, 'right');


$id = 286;
$loanid = 173;
$account = 2020090100001;
// get the prinpa
$pdf->getPrincipal($loanid);


//loang the payment made
$pdf->getPayment($id, $account);
$pdf->loanSchedule($loanid);
$pdf->ColoredTable($header);
// close and output PDF document
$pdf->Output('example_011.pdf', 'I');
