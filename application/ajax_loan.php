<?php
// Establish Connection with MYSQL
include("../config/connect.php");
if ($_POST['id']) {
    $id = $_POST['id'];


    $sql = mysqli_query($link, "select * from loan_info where baccount='$id'");
    while ($row = mysqli_fetch_array($sql)) {
        $strJsonFileContents = file_get_contents('include/packages.json');
        $arrayOfTypes = json_decode($strJsonFileContents, true);
        $loan_product = $row['loan_product'];
        foreach ($arrayOfTypes['accountType'] as $key => $value) {
            if ($loan_product == $key) {
                $loan_product = $value;
            }
        }
        ///Get Balance and Put on Session for temp
        $maxdate = mysqli_fetch_assoc(mysqli_query($link, "SELECT max(pay_date) FROM payments WHERE account='$id'"));
        $lastPaid = $maxdate['max(pay_date)'];
        $maxBalance = mysqli_fetch_assoc(mysqli_query($link, "SELECT balance FROM payments WHERE account='$id' and pay_date='$lastPaid'"));
        $currentBalance = $maxBalance['balance'];
        $allowDigits = strlen($currentBalance);
        if ($currentBalance == "") {
            $currentBalance = $row['balance'];
            $allowDigits = strlen($row['balance']);
        }
        $_SESSION['currentBalance'] = $currentBalance;
        $_SESSION['allowedDigits'] = $allowDigits;
        echo '<option value="' . $_SESSION['currentBalance'] . '">' . $loan_product. " - (" . $row['balance'] . "-" . "&nbsp;" . "Balance: " .$_SESSION['currentBalance'] . ")" . '</option>';
    }
}
?>