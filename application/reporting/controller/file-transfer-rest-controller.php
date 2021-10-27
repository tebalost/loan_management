<?php
include_once "../model/bureau-segments.php";
//include_once "../model/HttpStatusCode.php";
include_once "../../../config/connect.php";
include_once "controllerCommon.php";


// disabling warning
error_reporting(E_ALL & ~E_NOTICE);

class FileTransferRestController {

	public function dropRecordToSftp(){
		$dataToBeSend = $this->generateFileParam();
		$endPoint = $this->getEndPoint();
		$response = $this->SendParamToBeFormattedParamsToSftp($dataToBeSend, $endPoint);
		$dataRecord = json_decode($dataToBeSend, false);
		$numberOfRecord = count($dataRecord->segmentDto->dataSegments);
		if($response == '"SUCCESSFULLY_SUBMITTED"')
			$status = "Scheduled";
		else
			$status = "Something went wrong";
		$this->updateSchedulerTable($numberOfRecord, $status);
		echo $response;	
	}
	
	//getting the information scheduled to sent of bureau for complience
	private function generateFileParam(){
		$bureauData =  new BureauSegments;
		$data =  $bureauData->getDataString();
		return $data;
	}
	
	
	private function getFileParamToToBeFormatted(){
		$batchNumber =  date('Ym');
		$bureauData =  new BureauInformation;
		$data =  $bureauData->getDataToBeFormatted($batchNumber);
		return $data;
	}

	// rest call to the micro service that process data into file and drop it 
	private function SendParamToBeFormattedParamsToSftp($data, $endPoint){
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => $endPoint,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => $data,
		  CURLOPT_HTTPHEADER => array(
			"Content-Type: application/json",
			'Content-Length: ' . strlen($data)
		  ),
		));

		$response = curl_exec($curl);
		curl_close($curl);
		return $response;

	}
	
	private function updateSchedulerTable($recordNo, $log){
		global $link;
        $batch = date('Ym');
		$result = mysqli_query($link, "INSERT INTO bureau_submissions VALUES(0,'$batch', $recordNo,'$log',NOW(), 'System Scheduler')") or die(mysqli_error($link));
	}

	private function getEndPoint(){
	    $file = json_decode(file_get_contents('../resources/x-bridge-endpoint.json', DEFAULT_INCLUDE_PATH), true);
	    return $file['url'];
    }
}
DEFINE('MESSAGE_TYPE', 'SYSTEM');
$dataParam = json_decode(file_get_contents('php://input'), true);
//controllerCommon::validateInput($dataParam['messageType'], MESSAGE_TYPE);

// TODO Business logic
// TODO return good response and Asyc the following operations
//    $requestContentType = $_SERVER['CONTENT_TYPE'];
//    if($requestContentType !== "application/json"){
//        HttpStatusCode::setHttpHeaders($requestContentType, 415);
//        echo json_decode(array("response"=>"wrong media type"));
//    }
    $fileController = new FileTransferRestController;
    $fileController->dropRecordToSftp();
?>