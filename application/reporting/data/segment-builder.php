<?php

include_once "batch-data.php";
class BureauSegmentBuilder{
   //private $BundledRecords
   private $provider;  // provader for the 
   private $buddledRecord;  // bunded record from builder class

   // different section needed by the file construction system
   private $dataSegment;
   private $headSegment;
   private $tailSegment;
   private $compuscanSegment;
   
   //constructor
   public function __construct(){
      global $link;
      $this->provider = mysqli_fetch_assoc(mysqli_query($link, "select * from systemset"));
      $recordBundler = new BundleDataWithBatch;
      $this->buddledRecord = $recordBundler->getBatchedRecords();
   }


   // public function getBureaDataTo($batch, $action){
   //    $recordsBudled = $this->buddledRecord->getBatchedRecords($batch);
   //    //getting different segment
   //    $datasegment = $this->prepareDataSegment($recordsBudled);
   //    $headsegment = $this->PrepareHeader($recordsBudled);
   //    $tailsegment = $this->prepareTailSegment($datasegment);
   //    $this->dataSegmentTo = array_merge(array("headSegment" => $headsegment), array("tailSegment" => $tailsegment), array("dataSegments" => $datasegment));
   //    $this->bureauData = $this->compuscanSegmentBuiding($recordsBudled, $action);

   // }

   private function BuildDataSegment(){ 
	   global $link;
      $eachDataSegment = 0;

      while ($segment = mysqli_fetch_assoc($this->buddledRecord)) {
         //Get Max Payment
         $strJsonFileContents = file_get_contents('../../include/packages.json');
         $arrayOfTypes = json_decode($strJsonFileContents, true);
         $loan_payment_scheme = $segment['loan_payment_scheme'];
         foreach ($arrayOfTypes['repaymentFrequencyCode'] as $key => $value) {
            if ($loan_payment_scheme == $value) {
               $repayment_payment_frequency = $key;
            }
         }

         $account = $segment['baccount'];
         $borrower = $segment['id'];
		 $sql = mysqli_query($link, "select max(pay_date), sum(amount_to_pay) from payments where account='$account'") or die(mysqli_error($link));
         $maxDay = mysqli_fetch_assoc($sql);
         $loan = mysqli_fetch_assoc(mysqli_query($link, "select id from loan_info where baccount = '$account'"));
         $loanId = $loan['id'];
         $remainingBalance = $segment['balance'] - $maxDay['sum(amount_to_pay)'];
         if ($remainingBalance > 0) {
               $balanceType = "D";
         } else {
            $remainingBalance*=(-1);
            $balanceType = "C";
         }
		  
		  

    	  $today = date('Y-m-d');

         $lastDay = $maxDay['max(pay_date)'];
         if ($lastDay == "") {
               $lastDay = "00000000";
         } else {
               $lastDay = date("Ymd", strtotime($maxDay['max(pay_date)']));
         }
		  

         //Get Occupation Information
         $getOccupation = mysqli_query($link, "select * from fin_info where get_id = '$borrower'");
         $income = 0;
         $occupation = "";
         $frequency = "";
         while ($allIncomes = mysqli_fetch_assoc($getOccupation)) {
            $occupation = $allIncomes['occupation'];
            $frequency = $allIncomes['frequency'];
         }
		  
		 
         $dataSegments[$eachDataSegment]['dateOnWhichLastPaymentWasReceived'] = $lastDay;
         $dataSegments[$eachDataSegment]['accountNo'] = $account;
         $dataSegments[$eachDataSegment]['accountSoldToThirdParty'] = "";
		  
		  if($lastDay === '00000000'){
			 $lastDay = date('Ymd', strtotime($segment['application_date']));
		 }
		  
         $dataSegments[$eachDataSegment]['amountOverdue'] = round($segment['amount_topay'] * $this->dateDifference($lastDay, $today, $differenceFormat = '%m'),0);
         // must get the branch code here
         $branch = $this->getBranchCode($segment['branch']);

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
         $dataSegments[$eachDataSegment]['homeTelephone'] = "";
         $dataSegments[$eachDataSegment]['idNumber'] = $segment['id_number'];
         $dataSegments[$eachDataSegment]['income'] = round($income,0); //Sum The Income from Income table
         $dataSegments[$eachDataSegment]['incomeFrequency'] = "$frequency";//Add Income Frequency when adding borrower income information
         $dataSegments[$eachDataSegment]['instalmentAmount'] = round($segment['amount_topay'], 0);
         $dataSegments[$eachDataSegment]['loanReasonCode'] = $segment['reason'];
		 
         $dataSegments[$eachDataSegment]['monthsInArrears'] = $this->dateDifference($lastDay, $today,'%m');
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
         $dataSegments[$eachDataSegment]['residentialAddressLine4'] = $segment['district'];
         //Get Status Code
         //mysqli_query($link,"select status, max(added_date) from loan_statuses where loan='$loanId'");

         if ($segment['status'] !== "") {
               $maxDateActive = mysqli_fetch_assoc(mysqli_query($link, "select max(added_date) from loan_statuses where loan='$loanId' and status!=''"));
               $statusDate = substr($maxDateActive['max(added_date)'],0,10);
               $statusChangeDate = date("Ymd", strtotime($statusDate));
         } else {
               $statusChangeDate = "00000000";
         }
         $subAccountNo = $this->getBranchCode($segment['branch']);
         //must get the branch account  subAccountNo
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

      $this->dataSegment = $dataSegments;
   }

   private function buildTailSegment(){
      $tailSegment = [];
      $tailSegment['filler'] = "";
      $tailSegment['numberOfRecordsSupplied'] = mysqli_num_rows($this->buddledRecord);
      $tailSegment['trailer'] = "T";
      $this->tailSegment = $tailSegment;
   }


  // fix me check if the provider is needed alsewhere and act accordingly
   private function buildHeaderSegment(){
      $date = date('Ymd');
      //Header Information
      $headSegment = [];
      $headSegment['header'] = "H";
      $headSegment['dateFileWasCreated'] = date("Ymd");
      $headSegment['monthEndDate'] = date("Ymt", strtotime($date));
      $headSegment['supplierReferenceNumber'] = $this->provider['srn'];
      $headSegment['tradingNameOrBrandName'] = $this->provider['trading_name'];
      $headSegment['versionNumber'] = "01";
      $headSegment['filler'] = "";
      $this->headSegment = $headSegment;

   }


   private function dateDifference($lastDay, $today, $differenceFormat = '%m Months'){
      $datetime1 = date_create($lastDay);
      $datetime2 = date_create($today);
      $interval = date_diff($datetime1, $datetime2);
      return $interval->format($differenceFormat);
      //echo $interval;
   }

   private function getBranchCode($branch){
      //GET Branch Code
       global $link;
      $branchC = mysqli_fetch_assoc(mysqli_query($link,"select * from branches where code='$branch'"));
      $branchCode = $branchC['code'];
      $subAccountNo = $branchC['sub_account'];
      if($subAccountNo==""){
            $subAccountNo="0000";
      }
      return $subAccountNo;
   }

   

      

   private function compuscanSegmentBuiding(){
      $compuscanSegment = [];
      $fileName = $this->generatingFileName($this->provider);
      $compuscanSegment['destinationEmail'] = $this->provider['bureau_email'];
      $compuscanSegment['fileName'] = "$fileName";
      $compuscanSegment['mode'] = $this->provider['submission_method'];
      $compuscanSegment['sftpPassword'] = $this->provider['sftp_password'];
      $compuscanSegment['sftpUsername'] = $this->provider['sftp_username'];
      $this->compuscanSegment = $compuscanSegment;
   }


   private function generatingFileName($provider){
      //File Naming
      $srn = $provider['srn'];
      $recipient = $provider['recipient'];
      $date = date('Ymd');
      $frequency = $provider['submission_cycle'];
      $fileType = $provider['file_type'];

      $fileName = $srn . "_" . $recipient . "_" . $fileType . "_" . $frequency . "_" . date("Ymt", strtotime($date)) . "_01_01.txt";
      return $fileName;
   }

   public function getBureauDataTobeSubmitted(){
	   $this->BuildDataSegment();
	   $this->buildTailSegment();
	   $this->buildHeaderSegment();
	   $this->compuscanSegmentBuiding();
	   
      $segmentDto = array_merge(array("headSegment" => $this->headSegment), array("tailSegment" => $this->tailSegment), array("dataSegments" => $this->dataSegment));
      $bureauData = json_encode(array("compuscanHeaderDto" => $this->compuscanSegment, 'segmentDto' => $segmentDto));
      return $bureauData;
   }
	
   public function getBureauDataToBeFormatted(){
	   $this->BuildDataSegment();
	   $this->buildTailSegment();
	   $this->buildHeaderSegment();
	   
	   $segmentDto = json_encode(array_merge(array("headSegment" => $this->headSegment), array("tailSegment" => $this->tailSegment), array("dataSegments" => $this->dataSegment)));
	   
	   return $segmentDto;
   }
}



?>