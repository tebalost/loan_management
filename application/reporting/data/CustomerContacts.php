<?php
require_once "../../../config/connect.php";

class CustomerContacts
{
    // get all user number of the loans that due soon to noticefy the users
    public function getAllLoanDueTomorrow(){
        global $link;
        $records = [];
        $result = mysqli_query($link, "SELECT loan_info.id,title,lname,fname,baccount,pay_schedule.balance,phone 
                                            FROM pay_schedule,loan_info,borrowers 
                                            WHERE schedule=DATE_ADD(CURDATE(), INTERVAL 1 DAY) 
                                            AND payment<pay_schedule.balance
                                            AND pay_schedule.get_id = loan_info.id
                                            AND loan_info.borrower = borrowers.id");

        if(mysqli_num_rows($result)) {
            while ($record = mysqli_fetch_assoc($result))
                array_push($records, $record);
            return $records;
        }else
            return null;
    }


    public function getOverDueClientContacts(){
        global $link;
        $records = [];
        $result = mysqli_query($link, "SELECT loan_info.id,title,lname,fname,baccount,pay_schedule.balance,phone, DATEDIFF(CURDATE(),schedule) as number_of_days
                                            FROM pay_schedule,loan_info,borrowers 
                                            WHERE schedule<CURDATE() 
                                            AND payment<>pay_schedule.balance
                                            AND pay_schedule.get_id = loan_info.id
                                            AND loan_info.borrower = borrowers.id");
        if(mysqli_num_rows($result) > 0){
            while($record = mysqli_fetch_assoc($result))
                array_push($records, $record);
            return $records;
        }

        else
            return null;

    }
}
//$info =  new ClientSmsInfo;
//print_r($info->getAllLoanDueTomorrow());