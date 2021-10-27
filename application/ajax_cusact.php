<?php
// Establish Connection with MYSQL
include("../config/connect.php");
if ($_POST['id']) {
    $id = $_POST['id'];
    $sql = mysqli_query($link, "select * from loan_info where borrower='$id'");
    echo '<option selected="selected">--Select Account Number--</option>';
    while ($row = mysqli_fetch_array($sql)) {
        echo '<option value="' . $row['baccount'] . '">' . $row['baccount'] . '</option>';
    }
}

?>