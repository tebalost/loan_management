<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
require_once "IndividualStament.php";

// create new PDF document
$pdf = new Stament(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false, '');

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

$data = $pdf->LoadData($id, $loanId);

// Print text using writeHTMLCell()
$pdf->writeHTMLCell(0, 0, 15, 20, $html, 0, 1, 0, true, '', true);
$pdf->Image('../view/logo-pfs.png', '67', '17', 60, 25, '', '', 'T', false, 300, '', false, false, 1, false, false, false);
$pdf->writeHTMLCell(0, 0, 140, 20, $address, 0, 1, 0, true, '', true);

$pdf->cell( 0,10,"Registration Number :  98484", 0,1,'C', '', '','1', true, '','center');
$pdf->cell( 0,10,"LOAN STATEMENT", 1,1,'C', '', '','1', true, '','center');


$address = $pdf->getProcessedAddress($data );
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
global $link;
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

        $mail->addAddress($email, $fullname);

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
        $mail->Body = $htmlbody;
        $mail->send();

    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

