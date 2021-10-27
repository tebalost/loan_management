<?php
include "../config/session.php"; 
$SQL = "SELECT id_number, passport, date_of_birth, baccount, 
                                        lname, fname, gender, phone, employer, application_date,loan_info.balance, 
                                        amount_topay, loan_duration,loan_duration_period, loan_info.status 
                                        FROM loan_info, borrowers WHERE borrowers.id = loan_info.borrower 
                                        AND loan_info.status='Active'";

$filename = mysqli_fetch_assoc(mysqli_query($link,"select * from systemset"));
$srn = $filename['srn'];
$compuS = $filename['recipient'];
$date = date('Ymd');

$header = '';
$result ='';
$exportData = mysqli_query ($link,$SQL ) or die ("Sql error : " . mysqli_error($link));
 
$fields = mysqli_num_fields ( $exportData );
 
foreach ( $feilds as $column=>$value)
{
   $header .= $column."\t";
}
 
while( $row = mysqli_fetch_row( $exportData ) )
{
    $line = '';
    foreach( $row as $value )
    {                                            
        if ( ( !isset( $value ) ) || ( $value == "" ) )
        {
            $value = "\t";
        }
        else
        {
            $value = str_replace( '"' , '""' , $value );
            $value = '"' . $value . '"' . "\t";
        }
        $line .= $value;
    }
    $result .= trim( $line ) . "\n";
}
$result = str_replace( "\r" , "" , $result );
 
if ( $result == "" )
{
    $result = "\nNo Record(s) Found!\n";                        
}
 
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=$srn$compuS$date.xls");
header("Pragma: no-cache");
header("Expires: 0");
print "$header\n$result";
 
?>