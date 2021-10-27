<?php
require_once "../../include/phpmailer/PHPMailer.php";
require_once "../../include/phpmailer/Exception.php";
require_once "../../include/phpmailer/SMTP.php";
require_once '../../include/tcpdf/tcpdf.php';
require_once '../../../config/connect.php';

// constructing the pdf
class Stament extends TCPDF {
    //array to hold my final data
    private $debit = [];
    private $credit = [];
    private $dates = [];
    public  $trasactions = [];
    private $transationId = [];
    private $balance = [];
    public $account = 0;
    public $outStanding = 0;

    public function __construct($orientation = 'P', $unit = 'mm', $format = 'A4', $unicode = true, $encoding = 'UTF-8', $diskcache = false, $pdfa = false)
    {
        parent::__construct($orientation, $unit, $format, $unicode, $encoding, $diskcache, $pdfa);
    }


    public function Statement(){
        global $link;
        $getSchedule = mysqli_query($link, "SELECT * FROM system_transactions where account='$this->account'");
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
        global $link;
        $data = null;
        $client = mysqli_query($link, "SELECT * FROM borrowers WHERE id='$id'") or die (mysqli_error($link));
        $loan = mysqli_query($link, "SELECT * FROM loan_info WHERE borrower='$id' and id='$loanId'") or die (mysqli_error($link));
        if ($client && $loan) {
            $clientInfo = mysqli_fetch_assoc($client);
            $loanInfo = mysqli_fetch_assoc($loan);
            $data = array_merge($clientInfo, $loanInfo);
            $this->account = $loanInfo['baccount'];
        }

        $totalPayments = mysqli_fetch_assoc(mysqli_query($link, "SELECT sum(amount_to_pay) FROM payments WHERE customer = '$id' and account='$this->account'"));
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

    public function getProcessedAddress($data){
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
}