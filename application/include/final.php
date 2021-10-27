<?php
include_once "../../config/connect.php";
 class cdasIntegration
 {
     private $username;
     private $password;
     private $url;
     private $ch;
     private $employeeId;
     private $token;

     public function __construct($employeeNo)
     {
         $credetialsArray = json_decode(file_get_contents('credentials.json', true), true);
         $this->username = $credetialsArray['Credentials']['username'];
         $this->password = $credetialsArray['Credentials']['password'];
         $this->url = $credetialsArray['Credentials']['url'];
         $this->employeeId = $employeeNo;

         // Initializing the curl library with required data set
         $this->ch = curl_init();
         curl_setopt($this->ch, CURLOPT_POST, 1);
         curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
     }

     public function login()
     {
         $endpoint = "security/login";
         // Giving curl required parameters to complete a api required request
         curl_setopt($this->ch, CURLOPT_URL, $this->url . $endpoint);
         curl_setopt($this->ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json-patch+json'));
         curl_setopt($this->ch, CURLOPT_POSTFIELDS, json_encode(array('username' => $this->username, 'password' => $this->password)));
         try {
             $response = curl_exec($this->ch);
             if (($status = curl_getinfo($this->ch, CURLINFO_HTTP_CODE)) != 200) {
                 throw new Exception($status);
             }
             $result = json_decode($response, true);
             $this->token =  $result['Authorization'];
             return;
         } catch (Exception $e) {
             echo "Exception" . $e->getMessage();  // this is for diagnosing message users cannot see
             switch ($status) {
                 case 400:
                 case 500:
                     echo "<h3 class=\"text-danger\"> The critical error <b>Contact system Administrators </p></h3>";
                     exit(1);
                     break;
                 case 401:
                 case 402:
                     echo "<h3 class=\"text-waring\"> Timeout please reload before proceeding....</h3>";
                     $this->login();
                     exit(1);
                     break;
             }

         }
     }

     public function getEmployeeDetails()
     {
         $endpoint = "employee/getDetails";
         curl_setopt($this->ch, CURLOPT_URL, $this->url . $endpoint);
         curl_setopt($this->ch, CURLOPT_POSTFIELDS, json_encode(array('EmployeeNo' => '' . $this->employeeId))); // Set the posted fields
         curl_setopt($this->ch, CURLOPT_HTTPHEADER, array('accept: application/json', 'Authorization:' . $this->token, 'Content-Type: application/json-patch+json'));

         try {
             $response = curl_exec($this->ch);
             if (($status = curl_getinfo($this->ch, CURLINFO_HTTP_CODE)) != 200) {
                 throw new Exception($status);
             }
             return json_decode($response, true);
         } catch (Exception $e) {
             switch ($status) {
                 case 502:
                     echo "Exception" . $e->getMessage();
                 case 429:
                 case 406:
                 case 417:
                 case 419:
                     sleep(180); // wait 3 minute before retry
                     $this->getEmployeeDetails();
                     exit(1);
                     break;
                 case 404:
                     echo "Exception" . $e->getMessage();
                     echo "<h3 class=\"text-warning\">Failure (Employee Number not found in Govt. System)</h3>";
                     break;
                 case 400:
                     echo "<h3 class=\"text-warning\">Please provide the employeeId</h3>";
                 case 500:
                 case 406:
                 case 417:
                 case 419:
                     echo "Exception" . $e->getMessage();
                     echo "<h3 class=\"text-warning\">Failure (Employee Number not found in Govt. System)</h3>";
                     $this->getEmployeeDetails();
                     exit(1);
                     break;
             }
         }
     }

     public function getAffordability()
     {
         $endpoint = "employee/check-affordability";
         curl_setopt($this->ch, CURLOPT_URL, $this->url . $endpoint); // set url
         curl_setopt($this->ch, CURLOPT_HTTPHEADER, array('accept: application/json', 'Authorization:'.$this->token, 'Content-Type: application/json-patch+json'));
         curl_setopt($this->ch, CURLOPT_POSTFIELDS, json_encode(array('EmployeeNo' => '' . $this->employeeId))); // Set the posted fields

         try {
             $result = curl_exec($this->ch); // Execute the cURL statement
             if (($status = curl_getinfo($this->ch, CURLINFO_HTTP_CODE)) !== 200) {
                 echo $status;
                 throw new Exception($status);
             }
             return $result;
         } catch (Exception $e) {
             switch ($status) {
                 case 429:
                 case 429:
                 case 406:
                 case 417:
                 case 419:
                     sleep(180); // wait 3 minute before retry
                     $this->getAffordability();
                     exit();
                 case 400:
                 case 406:
                 case 417:
                 case 419:
                 case 429:
                 case 500:
                     echo "<p class=\"text-danger\"> An error has occured please contact the system administrator</p>";
                     $this->getEmployeeDetails();
                     exit();
                     break;
                 case 404:
                     echo "<br><h4 class=\"text-danger bold\"> <i class=\"fa fa-exclamation-triangle\"></i> The employee is not found in Government database</h4> <br>";
                     return;
             }
         } finally {
             curl_close($this->ch);
         }
     }

    public function performDeduction($requestType){
        $endpoint = "policy/add-update-deduction";
       // getting record from db
        include_once("BureauRecord.php");
       $bureauRecord = new BureauRecord();
       $record = $bureauRecord->genereteData($this->employeeId);

        curl_setopt($this->ch, CURLOPT_URL, $this->url.$endpoint);
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, array('accept: application/json', 'Authorization:'.$this->token,'Content-Type: application/json-patch+json'));
        $data = '{
            "RequestType":'.$this->getRequestCode($requestType).',
            "DeductionID": 0,
            "EmployeeNo":"'.$this->employeeId.'",
            "LoanPolicy": 1,
            "ItemCode": "9999",
            "DeductionAmount":0,
            "PrincipalAmount":'.$record['amount'].',
            "TotalInstallment":"'.$record['balance'].'",
            "EffectiveMonth": "'.Date('Y-m').'",
            "ReferenceNo": "Ref-1",
            "Notes": "test",
            "Email": "'.$record['email'].'",
            "CellPhone":"'.$record['phone'].'",
            "AccountNo": "'.$record['accountNumber'].'",
            "BankName": '.$record['bankName'].',
            "BranchCode": '.$record['branchCode'].',
            "RegistryBy": "admin",
            "ReviewBy": "string",
            "ApproveBy": "admin",
            "CancelBy": "string",
            "SettleBy": "string",
            "CancelReason": 0,
            "UpdatedBy": "string",
            "SettleReason": 0,
            "ReviewDate": "'.gmdate("Y-m-d\TH:i:s\.\95\Z").'",
            "ApproveDate": "'.gmdate("Y-m-d\TH:i:s\.\95\Z").'",
            "CancelDate": "'.gmdate("Y-m-d\TH:i:s\.\95\Z").'",
            "SettleDate": "'.gmdate("Y-m-d\TH:i:s\.\95\Z").'",
            "UpdateDate": "'.gmdate("Y-m-d\TH:i:s\.\95\Z").'",
            "SuspensionDate": "'.gmdate("Y-m-d\TH:i:s\.\95\Z").'",
            "SettlementDate": "'.gmdate("Y-m-d\TH:i:s\.\95\Z").'"
          }';

        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data); // Set the posted fields
        $response = curl_exec($this->ch); // Execute the cURL statement
        $status = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);

        if($status != 200){
            echo $response;
        }
        else {
            return json_decode($response);
        }
    }

    // generating the reference code
     private function getRequestCode($action){
         $code = 0;
         switch ($action){
             case "registration":
                 $code = 1;
                 break;
             case "reject":
                 $code = 2;
                 break;
             case "review":
                 $code = 3;
                 break;
             case "approve":
                 $code = 4;
                 break;
             case "Settle":
                 $code = 7;
                 break;
             case "delete":
                 $code = 9;
                 break;
             case "change":
             case "update":
                 $code = 10;
                 break;
             default:
                 echo "Unknown request type";
         }

         return $code;
     }

     // generating Deduction status
     private function getDeductionCode($action){
         $code = 0;
         switch ($action){
             case "registered":
                 $code = 1;
                 break;
             case "rejected":
                 $code = 2;
                 break;
             case "reviewed":
                 $code = 3;
                 break;
             case "approved":
                 $code = 4;
                 break;
             case "settled":
                 $code = 7;
                 break;
             case "deleted":
                 $code = 9;
                 break;
             case "canceled":
                 $code = 6;
             case "auto settled":
                 $code = 8;
                 break;
             default:
                 echo "Unknown deduction status code";
                 exit(1);
         }
         return $code;
     }

     // settlement reasoning
     private function getReasoningCode($reason){
         switch($reason){
             case "Policy Expired":
                 $code = 1;
                 break;
             case "Paid By Employee":
                 $code = 2;
                 break;
             case "Consolidation":
                 $code = 3;
                 break;
             case "Deceased Employee":
                 $code = 4;
                 break;
         }
         return $code;
     }

     //Deduction Type Definition
     private function getDeductionTypeDefinition($reason){
         switch($reason){
             case "loan":
                 $code = 1;
                 break;
             case "policy":
                 $code = 2;
                     break;
             default:
                 echo "There can be on two deduction type (Policy or Loan)";
         }
         return $code;
     }

     // Document Type Definition
     private function getDocumentTypeDefinition($reason){
         $code = 0;
         switch($reason){
             case "output File":
                 $code = 1;
                 break;
             case "statement":
                 $code = 2;
                 break;
             default:
                 echo "There can be on two file types (Output File or Statement)";
         }
         return $code;
     }
 }

$beruea =  new cdasIntegration('0030968');
$beruea->login();
print_r($beruea->performDeduction("registration"));


?>