<?php include "../config/session.php"; ?>

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
    $id = $_GET['id'];
    $del = mysqli_query($link, "update payments set status='C' WHERE id = '$id'") or die (mysqli_error($link));
    if (!$del) {
        echo '<meta http-equiv="refresh" content="2;url=mywallet.php?tid=' . $_SESSION['tid'] . '">';
        echo '<br>';
        echo '<span class="itext" style="color: #FF0000">Unable to Delete Record!...Please try again later!!</span>';
    } else {
        echo '<meta http-equiv="refresh" content="2;url=listpayment.php?tid=' . $_SESSION['tid'] . '&&act=reversedPayments">';
        echo '<br>';
        echo '<span class="itext" style="color: #FF0000">Payment reversal Successfully Cancelled!...</span>';
    }
    ?>
</div>
</body>
</html>
