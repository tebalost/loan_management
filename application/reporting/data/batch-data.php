<?php
include_once "../../../config/connect.php";
   class BundleDataWithBatch{

      private function getRecordsToBeBatched(){
          $dataSegments = [];
		  global $link;
          $batch = date('Ym');
          $selectBatchRecords=mysqli_query($link, "select * from bureau_records where batch='$batch'") or die("could not select get th records to be bundled");
          if (mysqli_num_rows($selectBatchRecords)=="0") {
              $SQL = "insert into bureau_records 
                     SELECT 0,$batch,borrowers.id, id_number, passport, baccount, district, country, reason,bureauAccountType, modified_date, ownershipType,
                                             lname, fname, gender, phone, employer, application_date,loan_info.balance, loan_repayment_method, loan_payment_scheme, postal, ownership_type,
                                             amount_topay, loan_duration,loan_duration_period, loan_info.status, date_of_birth, branch, title, addrs2, addrs1
                                             FROM loan_info, borrowers WHERE borrowers.id = loan_info.borrower 
                                             AND loan_info.status not in('DECLINED','Pending','Pending Disbursement')";
              $result = mysqli_query($link, $SQL);
              if ($result) {
                  return true;
              } else {
                  return false;
              }
          }
      }
      


      public function getBatchedRecords(){
		  global $link;
         if(true){              // making sure that th file does read record for testing purpose  $this->getRecordsToBeBatched($batch)
            $SQL = "SELECT borrowers.id, id_number, passport, date_of_birth, baccount, district, country, reason,bureauAccountType, modified_date, ownershipType,
                     lname, fname, gender, phone, employer, application_date,loan_info.balance, loan_repayment_method, loan_payment_scheme, postal, ownership_type,
                     amount_topay, loan_duration,loan_duration_period, loan_info.status, date_of_birth, branch, title, addrs2, addrs1
                     FROM loan_info, borrowers WHERE borrowers.id = loan_info.borrower 
                     AND loan_info.status not in('DECLINED','Pending','Pending Disbursement')";
            $buddledRecord = mysqli_query($link, $SQL) or die ("Sql error : " . mysqli_error($link));
            return $buddledRecord;
         }
         else
            return null;
      }
      
   }


?>