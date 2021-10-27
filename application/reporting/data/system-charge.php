<?php
include_once "../../../config/connect.php";

define("HOSTING_ITEM_DESCRIPTION", "Hosting Charge");
define('LOAN_TRANSACTION_DESCRIPTION', 'Loans Transaction Charge');
define('SMS_CHARGE', 'Sms Charge');


class SystemCharge{
    private $hostingCharge;
    private $loanTransactionsCharge;
    private $smsCharge;
    private $clientBillingDetails = [];
	private $invoiceNumber;

	//count of items
    private $numberOfSMSs;
    private $loanNumber;     // number of loans charged this month


    public function setHostingCharge($hostingCharge){
        $this->hostingCharge = $hostingCharge;
    }

    public function getHostingCharge(){
          return $this->hostingCharge;
    }


     public function setSmsCharge($smsCharge){
        $this->smsCharge = $smsCharge;
    }

    public function getSmsCharge(){
          return $this->smsCharge;
    }

    public function setclientBillingDetails($clientBillingDetails){
        $this->clientBillingDetails = $clientBillingDetails;
    }

    public function getclientBillingDetails(){
          return $this->clientBillingDetails;
    }

    public function setLoanTransactionsCharge($loanTransactionsCharge){
       $this->loanTransactionsCharge = $loanTransactionsCharge;
    }

    public function getLoanTransactionsCharge(){
        return $this->loanTransactionsCharge;
    }

    public function getTotalCharge(){
        return $this->smsCharge
            + $this->loanTransactionsCharge
            + $this->hostingCharge;
    }

    public function setNumberOfSMSs($smsnumber){
        $this->numberOfSMSs = $smsnumber;
    }

    public function getNumberOfSMSs(){
        return $this->numberOfSMSs;
    }

    public function setNumberOfLoanCharged($loanCount){
        $this->loanNumber = $loanCount;
    }

    public function getNumberOfLoanCharged(){
        return $this->loanNumber;
    }

	public function getInvoiceDetails($date){
		global $link;
		$this->recordIvoiceSend($date);
		$result = mysqli_query($link, "SELECT * FROM invoice WHERE invoice_number='$this->invoiceNumber'") or die(mysqli_error($link));
		if(mysqli_num_rows($result)){
			return mysqli_fetch_assoc($result);
		}
	}

	private function getNumberOfItem(){
		$count = 0;
		if($this->hostingCharge > 0)
			$count += 1;
		if($this->loanTransactionsCharge > 0)
			$count += 1;
		if($this->smsCharge > 0)
			$count += 1;
		return $count;
	}

	private function recordIvoiceSend($date){
		global $link;
		$amount = $this->getTotalCharge();
		$numberOfItems = $this->getNumberOfItem();

		//datetime object
		$dateTimeObject = new DateTime();
		 $dueDate = $dateTimeObject->modify('+6 days')->format('Y-m-d');
		 $invoiceNumber = mysqli_query($link, "SELECT invoice_number FROM invoice ORDER BY invoice_number DESC LIMIT 1")or die("Could not not get invoice");
		 if(mysqli_num_rows($invoiceNumber)){
			$lastInvoiceNumber = mysqli_fetch_assoc($invoiceNumber);

			$invoiceNo = intval(intval($lastInvoiceNumber['invoice_number']) + 1);
		 }
		 else{    //else if the there is invoice generate new invoice number
			 $invoiceNo = intval(date('Ymd').'01');
		 }
		$this->invoiceNumber = $invoiceNo;
		$invoiceName = "Invoice_".date('Y_m_d')."_".$dueDate.".pdf";
		 $result = mysqli_query($link, "INSERT INTO invoice SET invoice_number='$invoiceNo', amount='$amount', invoice_name='$invoiceName', issue_date=NOW(), number_of_items='$numberOfItems',due_date=DATE_ADD(NOW(), INTERVAL 5 DAY)")or die("Could not update the invoice table");
//		}
	 }
										   
}
?>