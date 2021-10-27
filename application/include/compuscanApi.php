<?php error_reporting(0); ?>
<!DOCTYPE html>
<html>
<head>

    <style>
        .loader {
            border: 16px solid #f3f3f3;
            border-radius: 50%;
            border-top: 16px solid orange;
            border-right: 16px solid green;
            border-bottom: 16px solid orange;
            border-left: 16px solid green;
            width: 120px;
            height: 120px;
            -webkit-animation: spin 2s linear infinite;
            animation: spin 2s linear infinite;
            margin: auto;

        }

        @-webkit-keyframes spin {
            0% {
                -webkit-transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>
<body>
<br><br><br><br><br><br><br><br><br>
<div style="width:100%;text-align:center;vertical-align:bottom">
    <div class="loader"></div>
<?php
include ("../../config/connect.php");
class CompuScan{
		private $response;
		private $endpoint;
		private $username;
		private $password;
		private $curl;


		// parameter to be used in the body to query the server
		private $identity;
		private $name;
		private $surname;
		private $dateOfBirth;
		private $address1;
		private $address2;
		private $postal;
		private $gender;


		//varuble to hold the extracted information from url
        private $information;


		function __construct($link){
			$credential = mysqli_query($link, "SELECT * FROM affordability_check WHERE provider='compuscan'") or die(mysqli_error($link));
			$credentialInfo = mysqli_fetch_assoc($credential);
			$this->endpoint = $credentialInfo['endpoint'];
			$this->username = $credentialInfo['username'];
			$this->password = $credentialInfo['password'];
			$this->curl = curl_init();

		}


		function getParameterFromTheForm(){
			$this->identity = $_POST['identity'];
			$this->name = $_POST['name'];
			$this->surname = $_POST['surname'];
			$this->address1 = $_POST['addr1'];
			$this->gender = $_POST['gender'];
			$mydate = date_create($_POST['DOB']);
			$this->dateOfBirth = date_format($mydate, 'Ymd');
			$this->address2 = $_POST['addr2'];
			$this->postal = $_POST['postal'];

		}

		function getInformationFromCompuscan(){
			curl_setopt_array($this->curl, array(
		    CURLOPT_URL => $this->endpoint,
		    CURLOPT_RETURNTRANSFER => true,
		    CURLOPT_ENCODING => "",
		    CURLOPT_MAXREDIRS => 10,
		    CURLOPT_TIMEOUT => 0,
		    CURLOPT_FOLLOWLOCATION => true,
		    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		    CURLOPT_CUSTOMREQUEST => "POST",
		    CURLOPT_POSTFIELDS =>"<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:web=\"http://webServices/\">
    <soapenv:Header />
    <soapenv:Body>
        <web:DoNormalEnquiryStream>
            <!--Optional:-->
            <request>
                <pUsrnme>$this->username</pUsrnme>
                <pPasswrd>$this->password</pPasswrd>
                <pVersion>1.0</pVersion>
                <pOrigin>SBS_LIVE</pOrigin>
                <pOrigin_Version>2.5.2</pOrigin_Version>
                <pInput_Format>XML</pInput_Format>
                <pTransaction><![CDATA[
					<Transactions>
					    <Search_Criteria>
					        <CS_Data>Y</CS_Data>
					        <CPA_Plus_NLR_Data></CPA_Plus_NLR_Data>
					        <Deeds_Data>N</Deeds_Data>
					        <Directors_Data>N</Directors_Data>
					        <Identity_number>$this->identity</Identity_number>
					        <Surname>$this->surname</Surname>
					        <Forename>$this->name</Forename>
					        <Forename2/>
					        <Forename3/>
					        <Gender>$this->gender</Gender>
					        <Passport_flag>N</Passport_flag>
					        <DateOfBirth>$this->dateOfBirth</DateOfBirth> 
					        <Address1>$this->address1</Address1>
					        <Address2>$this->address2</Address2>
					        <Address3/>
					        <Address4/>
					        <PostalCode>$this->postal</PostalCode>
					        <HomeTelCode/>
					        <HomeTelNo/>
					        <WorkTelCode/>
					        <WorkTelNo/>
					        <CellTelNo/>
					        <ResultType>JSON</ResultType>
					        <RunCodix>Y</RunCodix>
					        <CodixParams>
					            <PARAMS>
					                <PARAM_NAME>pNetSalary</PARAM_NAME>
					                <PARAM_VALUE>1200</PARAM_VALUE>
					            </PARAMS>
					        </CodixParams>
					        <Adrs_Mandatory>Y</Adrs_Mandatory>
					        <Enq_Purpose>12</Enq_Purpose>
					        <Run_CompuScore>N</Run_CompuScore>
					        <ClientConsent>Y</ClientConsent>
					    </Search_Criteria>
					</Transactions>
                ]]></pTransaction>
            </request>
        </web:DoNormalEnquiryStream>
    </soapenv:Body>
</soapenv:Envelope>",
                    CURLOPT_HTTPHEADER => array(
                      "Content-Type: text/xml"
//                      "Cookie: srv_id=b22d3e6bdcee214b633d2e41bbb63fb7"
                    ),
                  ));
			$this->response =  curl_exec($this->curl);
			curl_close($this->curl);
		}


		function extractDataFromReponse(){
			$start = strpos($this->response, '{');
			$end = strrpos($this->response, '}');
			$result = substr($this->response, $start, ($end - $start+2));
			$this->information  = json_decode($result, false);   // storing object containg json key as properties
		}

		// calculation other loan installments
		function getOtherLoanInstalment(){
            $count =  (int)$this->information->CC_RESULTS->EnqCC_ENQ_COUNTS[0]->CPACC;
            $installmentPayable = 0.0;
            $allAccounts = [];
            $eachAccount = 0;
            for ($i=0; $i <  $count; $i++) {
                if($this->information->CC_RESULTS->EnqCC_CPA_ACCOUNTS[$i]->STATUS_CODE == "")
                        $installmentPayable += $this->information->CC_RESULTS->EnqCC_CPA_ACCOUNTS[$i]->INSTALMENT_AMOUNT;
                        $allAccounts[$eachAccount]['subscriber'] = $this->information->CC_RESULTS->EnqCC_CPA_ACCOUNTS[$i]->SUBSCRIBER_NAME;
                        $allAccounts[$eachAccount]['instalment'] = $this->information->CC_RESULTS->EnqCC_CPA_ACCOUNTS[$i]->INSTALMENT_AMOUNT;
                        $allAccounts[$eachAccount]['accountType'] = $this->information->CC_RESULTS->EnqCC_CPA_ACCOUNTS[$i]->ACCOUNT_TYPE_DESC;
                        $allAccounts[$eachAccount]['lastPayment'] = $this->information->CC_RESULTS->EnqCC_CPA_ACCOUNTS[$i]->LAST_PAYMENT_DATE;
                        $allAccounts[$eachAccount]['currentBalance'] = $this->information->CC_RESULTS->EnqCC_CPA_ACCOUNTS[$i]->CURRENT_BAL;
                        $eachAccount++;
            }
            $_SESSION['CSInfo']['accounts']=$i;
            $_SESSION['CSInfo']['activeAccounts']=base64_encode(json_encode(array("activeAccounts" => $allAccounts)));

            return $installmentPayable;

        }

        function getBorrowerInfo(){
		    if($this->information->CC_RESULTS->EnqCC_ENQ_COUNTS[0]->DMATCHES > 0) {
                $_SESSION['CSInfo']['identity'] = $this->information->CC_RESULTS->EnqCC_SRCHCRITERIA[0]->CRIT_IDNUMBER;
                $_SESSION['CSInfo']['surname'] = $this->information->CC_RESULTS->EnqCC_SRCHCRITERIA[0]->CRIT_SURNAME;
                $_SESSION['CSInfo']['name'] = $this->information->CC_RESULTS->EnqCC_SRCHCRITERIA[0]->CRIT_NAME;
                $_SESSION['CSInfo']['dateofbirth'] = $this->information->CC_RESULTS->EnqCC_SRCHCRITERIA[0]->DOB;
                return $this->information->CC_RESULTS->EnqCC_SRCHCRITERIA[0];
            }else{
                $_SESSION['CSInfo']['name'] = "No active accounts available from Compuscan";
            }
        }

        function getEmpoyer(){
		    if($this->information->CC_RESULTS->EnqCC_ENQ_COUNTS[0]->EMPLOYER > 0)
		            return $this->information->CC_RESULTS->EMPLOYER[0]->EMP_NAME;
        }
	}

	$compuscan = new CompuScan($link);
	$compuscan->getParameterFromTheForm();
	$compuscan->getInformationFromCompuscan();
	$compuscan->extractDataFromReponse();
	$instalment = $compuscan->getOtherLoanInstalment();
    //echo "instalment is ".$instalment;

    $_SESSION['CSInfo']['instalment']=$instalment;

    $borrower = $compuscan->getBorrowerInfo();
    $_SESSION['CSInfo']['employer']=  $compuscan->getEmpoyer();
	//var_dump($compuscan->getBorrowerInfo());


    echo '<meta http-equiv="refresh" content="2;url=../affordability_calculator.php?affordability=Compuscan&id=' . $_SESSION['CSInfo']['activeAccounts'] . '/'.$_SESSION['CSInfo']['name'].'/' . $_SESSION['CSInfo']['identity'] . '/'.$_SESSION['CSInfo']['surname'].'/'.$_SESSION['CSInfo']['instalment'].'/'.$_SESSION['CSInfo']['accounts'].'/'.$_SESSION['CSInfo']['dateofbirth'].'">';
    echo '<br>';
    echo '<span class="itext" style="color: #FF0000">Getting Information from Compuscan for Credit Assessment,.....Please Wait!</span>';

?>
</div>
</body>
</html>