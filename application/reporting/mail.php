<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
require_once "include/phpmailer/PHPMailer.php";
require_once "include/phpmailer/Exception.php";
require_once "include/phpmailer/SMTP.php";
require_once 'include/tcpdf/tcpdf.php';
require_once '../config/connect.php';

// constructing the pdf
class MYPDF extends TCPDF {
    private $link = null;
    //array to hold my final data
    private $debit = [];
    private $credit = [];
    private $dates = [];
    public  $trasactions = [];
    private $transationId = [];
    private $balance = [];
    public $account = 0;
    public $outStanding = 0;

    public function __construct($orientation = 'P', $unit = 'mm', $format = 'A4', $unicode = true, $encoding = 'UTF-8', $diskcache = false, $pdfa = false, $link)
    {
        parent::__construct($orientation, $unit, $format, $unicode, $encoding, $diskcache, $pdfa);
        $this->link = $link;
    }


    public function Statement(){
        $getSchedule = mysqli_query($this->link, "SELECT * FROM system_transactions where account='$this->account'");
        while ($schedule = mysqli_fetch_assoc($getSchedule)) {
            if($schedule['debit']=="0.00"){
                array_push($this->debit, 0.0);
            }
            else{
                array_push($this->debit, $schedule['debit']);
            }
            if($schedule['credit']=="0.00"){
                array_push($this->credit, 0.0);
            }
            else{
                array_push($this->credit, $schedule['credit']);
            }

            array_push($this->dates,  $schedule['date']);
            array_push($this->transationId, $schedule['tx_id']);
            array_push($this->trasactions, $schedule['transaction']);
            array_push($this->balance, $schedule['balance']);
        }
    }

    // Load table data from file
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
        }

        $totalPayments = mysqli_fetch_assoc(mysqli_query($this->link, "SELECT sum(amount_to_pay) FROM payments WHERE customer = '$id' and account='$this->account'"));
        $totalPaid = $totalPayments['sum(amount_to_pay)'];
        $expectedBalance = $data['balance'];
        $this->outStanding = $expectedBalance - $totalPaid;


        return $data;
    }

    // Colored table
    public function ColoredTable($header) {
        // Colors, line width and bold font
        $this->SetFillColor(255, 0, 0);
        $this->SetTextColor(255);
        $this->SetDrawColor(128, 0, 0);
        $this->SetLineWidth(0.3);
        //$this->SetFont('', 'B');
        // Header
        $w = array(20, 35, 55, 20, 25, 30);
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


        $count = count($this->dates);
        $index = 0;
        while($index < $count) {
            $this->Cell($w[0], 6, date("d/m/Y", strtotime($this->dates[$index])), 'LRTB', 0, 'L', $fill);
            $this->Cell($w[1], 6, $this->transationId[$index], 'LRTB', 0, 'L', $fill);
            $this->Cell($w[2], 6, $this->trasactions[$index], 'LRTB', 0, 'L', $fill);
            if ($this->debit[$index] !== 0.0){
                $this->Cell($w[3], 6, number_format($this->debit[$index], 2, '.', ','), 'LRTB', 0, 'R', $fill);
            }else{
                $this->Cell($w[3], 6, '', 'LRTB', 0, 'R', $fill);
            }
            if($this->credit[$index] !== 0.0) {
                $this->Cell($w[4], 6, number_format($this->credit[$index], 2, '.', ','), 'LRTB', 0, 'R', $fill);
            }
            else{
                $this->Cell($w[4], 6, '', 'LRTB', 0, 'R', $fill);
            }
            $this->Cell($w[5], 6, number_format($this->balance[$index],2,'.',','), 'LRTB', 0, 'R', $fill);
            $this->Ln();
            $fill=!$fill;
            $index++;
        }
    }
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false, '',$link);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 011');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

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
$header = array('Date', 'Transaction', 'Description','Debit','Credit', 'Closing Balance');

// formatting the address
function getProcessedAddress($data){
    $result = "<p>";
    $result .= $data['addrs1'];
    $result .= '<br>';
    $result .= $data['postal'];
    $result .= '<br>';
    $result .= $data['district'];
    $result .= '<br>';
    $result .= $data['country'];

    return $result;
}


// ---------------------------------------------------------
$html = <<<EOD
<h5 style="width: 200px; height: 150px">Ground Floor,<br>Options Building 240<br> Pioneer Road Europa,<br>Maseru 100,<br>Lesotho</h5>
EOD;



$html = <<<EOD
<h5 style="">Ground Floor,<br>Options Building 240<br>Pioneer Road Europa,<br>Maseru 100,<br>Lesotho</h5>
EOD;



$address = <<<ADRR
<p><b>Email</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;service@pulamaliboho-fs.com <br>
<b>Website</b>&nbsp;&nbsp; pulamaliboho-fs.com <br>
<b>Contacts </b> 22321577
</p>
ADRR;

$id = $_GET['id'];
$loanId = $_GET['loanId'];
$data = $pdf->LoadData($id, $loanId);

// Print text using writeHTMLCell()
$pdf->writeHTMLCell(0, 0, 15, 20, $html, 0, 1, 0, true, '', true);
$pdf->Image('logo.png', '67', '17', 60, 25, '', '', 'T', false, 300, '', false, false, 1, false, false, false);
$pdf->writeHTMLCell(0, 0, 140, 20, $address, 0, 1, 0, true, '', true);

$pdf->cell( 0,10,"Registration Number :  98484", 0,1,'C', '', '','1', true, '','center');
$pdf->cell( 0,10,"LOAN STATEMENT", 1,1,'C', '', '','1', true, '','center');


$address = getProcessedAddress($data );
// output the HTML content
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
                <td><strong>Loan Balance</strong> </td>
                <td><span class="pull-right">'.number_format($pdf->outStanding,2,'.',',').'</span></td>
                
            </tr>
            <tr>
                <td><strong>Principal Amount</strong></td>
                <td><span class="pull-right">'.number_format($data['amount'], 2, ".", ",").'</span></td>
                
            </tr>
            
            <tr>
                <td><strong>Interest Rate</strong> </td>
                <td><span class="pull-right">'.$data['loan_interest'].'%</span></td>
            </tr>
        </table>
    </section>

',true,0, true,0);

$date = date('F j, Y');

// output the HTML content
$pdf->writeHTML('
<style type="text/css">
.box_title_print{
        text-align: center;
        font-weight: bold;
    }
</style>
<section class="invoice">
<h4 class="box_title_print">LOAN STATEMENT AS AT '.strtoupper($date).'</h4>
<hr><br>', true, true, true, true, 'right');


//getting data ready for display
$pdf-> Statement();

// print colored table
$pdf->ColoredTable($header);


// close and output PDF document
$loadFile = $pdf->Output('loanstatement.pdf', 'S');


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$id = $_GET['id'];

$result = mysqli_query($link, "SELECT * FROM borrowers WHERE id='$id'");
if($result){
    $borrowerInfo =  mysqli_fetch_assoc($result);
}
$email = $borrowerInfo['email'];

if(isset($email)){
    // Instantiation and passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings

        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = 'pulamaliboho-fs.com';                    // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = 'service@pulamaliboho-fs.com';                     // SMTP username
        $mail->Password   = '=l8PL5DwN{AZ';                               // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = 587;
        $mail->SMTPDebug = 0;
        $mail->isHTML(true);

        $fullname =  $data['fname'].' '.$data['lname'];
        // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
        //Recipients
        $mail->setFrom('service@pulamaliboho-fs.com', 'Pulamaliboho Financial Services');
        if(isset($email)){
            $mail->addAddress($email, $fullname);
        }
        else{
            echo "<div class=\"alert alert-danger\" >
                    <a href = \"#\" class = \"close\" data-dismiss= \"alert\"> &times;</a>
                   The borrower does not have email.
                    </div> ";
            exit();
        }
             // Add a recipient
        // $mail->addBCC('bcc@example.com');

        //============================================================+
        // END OF FILE
        //============================================================+

        // filename formation
        $filename = "Loanstatement_".date('F_Y_').$data['baccount'].'.pdf';
        $name = $data['title'].' '.$data['fname'].' '.$data['lname'];
        $month = date('F');
        $htmlbody = <<<THIS
        <div class="container" style="width: 40%; margin: 0 auto;width: fit-content;">
            <div class="content" style="padding: 10px;">
                <h3 style="float: left;">Your Loan Statement</h3>
                <img style="float: right; justify-self: right; width: 250px; ;" src="https://uat.pulamaliboho.sbs-eazy.loans/application/reporting/logo.png" alt="logo">
    
            </div>
    
            <div class="body" style=" padding: 0px 10px;">
                <h5 style="position: relative; padding-top: 50px;"> Dear <strong>{$name}</strong> <br> </strong>
                </h5>
                <h4>Attached, Please find your detailed {$month} statement.</h4>
                <p>To view this document you will need Adobe Reader 14.0. It is available for you to download at the following websiter <a href="http://get.adobe.com/reader">Adobe</a> </p>
            </div>
            <!--<img src="https://uat.pulamaliboho.sbs-eazy.loans/footer.png" alt="footer.png" style="max-height: 200px">-->
            <p> Should you require further assistance, please contact us on: +266 52595559. At Pulamaliboho Financial Services, we are committed to giving you exceptional service.<br><br> Kind regards <br>PFS Client Services
 </p>
        </div>
THIS;


        // Attachments
        $mail->addStringAttachment($loadFile, $filename);         // Add attachments

        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = 'Loan Statement of '.$borrowerInfo['title'].' '.$borrowerInfo['lname'].' '.$borrowerInfo['fname'];
        $mail->Body    = $htmlbody;
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        if($mail->send()){
            echo "<div class=\"alert alert-success\" >
                    <a href = \"#\" class = \"close\" data-dismiss= \"alert\"> &times;</a>
                    Email was succesful send to the client;
                    </div>";
        }
    } catch (Exception $e) {
        echo "<div class=\"alert alert-danger\" >
                    <a href = \"#\" class = \"close\" data-dismiss= \"alert\"> &times;</a>
                    Email was succesful send to the client {$mail->ErrorInfo}
                    </div> ";
    }
}

