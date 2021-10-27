<?php
include "../config/session.php"; 
$SQL = "SELECT  * from loan_info";
$header = '';
$result ='';
$date = date('dmYHisA');
$exportData = mysqli_query ($link,$SQL ) or die ("Sql error : " . mysqli_error($link) );
 
$fields = mysqli_fetch_assoc($exportData);
 
foreach($fields as $column=>$data)
{
   $header .= $column . "\t";
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
header("Content-Disposition: attachment; filename=Loan_Submissions_Export_$date.xls");
header("Pragma: no-cache");
header("Expires: 0");
print "$header\n$result";
 
?>