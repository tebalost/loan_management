<?php

class CentralBankCommon
{
    static function getClientName(){
        global $link;
        $result = mysqli_query($link, "SELECT name FROM systemset") OR die(mysqli_error($link));
        $company = mysqli_fetch_assoc($result);
        return $company['name'];
    }
}