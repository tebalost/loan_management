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
    if (isset($_POST['emp'])) {
        $name = mysqli_real_escape_string($link, $_POST['name']);
        $email = mysqli_real_escape_string($link, $_POST['email']);
        $gender = mysqli_real_escape_string($link, $_POST['gender']);
        $id_number = mysqli_real_escape_string($link, $_POST['idNumber']);
        $passport = mysqli_real_escape_string($link, $_POST['passport']);
        $phone = mysqli_real_escape_string($link, $_POST['phone']);
        $addr1 = mysqli_real_escape_string($link, $_POST['addr1']);
        $addr2 = mysqli_real_escape_string($link, $_POST['addr2']);
        $district = mysqli_real_escape_string($link, $_POST['district']);
        $country = mysqli_real_escape_string($link, $_POST['country']);
        $comment = mysqli_real_escape_string($link, $_POST['comment']);
        $username = mysqli_real_escape_string($link, $_POST['username']);
        $password = mysqli_real_escape_string($link, $_POST['password']);
        $cpaswword = mysqli_real_escape_string($link, $_POST['cpassword']);
        $role = mysqli_real_escape_string($link, $_POST['role']);
        $dateofbirth = mysqli_real_escape_string($link, $_POST['dateofbirth']);
        $branch = mysqli_real_escape_string($link, $_POST['branch']);
        $role = mysqli_real_escape_string($link, $_POST['role']);

        unset($_SESSION['branch']);
        //$_SESSION['branch'] = $branch;

        if ($_FILES["image"]["name"] != "") {
            $target_dir = "../img/";
            $target_file = $target_dir . basename($_FILES["image"]["name"]);
            $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
            $check = getimagesize($_FILES["image"]["tmp_name"]);

            $id = "Loan" . "=" . rand(10000000, 340000000);

            $sourcepath = $_FILES["image"]["tmp_name"];
            $targetpath = "../img/" . $_FILES["image"]["name"];
            move_uploaded_file($sourcepath, $targetpath);

            $location = "img/" . $_FILES['image']['name'];
        } else {
            $location = "";
        }

        $encrypt = base64_encode($password);
        $id = "Loan" . "=" . rand(10000000, 340000000);

        if ($password != $cpaswword) {
            echo "<script>alert('The 2 Password does not match!'); </script>";
        } else {

            $insert = mysqli_query($link, "INSERT INTO user VALUES(0,'$name','$email','$gender','$id_number','$phone','$addr1','$addr2','$district','$country','$comment','$username','$encrypt','$id','$location','$role','$dateofbirth','$passport','$branch')") or die (mysqli_error($link));
            if (!$insert) {
                echo '<meta http-equiv="refresh" content="2;url=newemployee.php?tid=' . $_SESSION['tid'] . '">';
                echo '<br>';
                echo '<span class="itext" style="color: orange">Unable to register employee!</span>';
            } else {
                $get = mysqli_query($link, "SELECT * FROM systemset order by sysid") or die (mysqli_error($link));
                $getData = mysqli_fetch_assoc($get);
                $company = $getData['name'];

                $content = "PFS: Hi $name, welcome to $company. Your username is: $username, password: $password. Your password has been set please login and change your password to something more easy to you but secure.";
                ?>

                <?php
                class MyMobileAPI
                {

                    public function __construct() {
                        $this->url = 'http://api.smsportal.com/api5/http5.aspx';
                        $this->username = 'serumula'; //your login username
                        $this->password = '5erumul@2020'; //your login password
                        //$this->validityperiod = '24'; //optional- set desired validity (represents hours)
                    }

                    public function checkCredits() {
                        $data = array(
                            'Type' => 'credits',
                            'Username' => $this->username,
                            'Password' => $this->password
                        );
                        $response = $this->querySmsServer($data);
                        // NULL response only if connection to sms server failed or timed out
                        if ($response == NULL) {
                            return '???';
                        } elseif ($response->call_result->result) {
                            echo '</br>Credits: ' .  $response->data->credits;
                            return $response->data->credits;
                        }
                    }

                    public function sendSms($mobile_number, $msg) {
                        $data = array(
                            'Type' => 'sendparam',
                            'Username' => $this->username,
                            'Password' => $this->password,
                            'numto' => $mobile_number, //phone numbers (can be comma seperated)
                            //'validityperiod' => $this->validityperiod, //the duration of validity
                            'data1' => $msg //your sms message

                        );
                        $response = $this->querySmsServer($data);
                        return $this->returnResult($response);
                    }

                    // query API server and return response in object format
                    private function querySmsServer($data, $optional_headers = null) {

                        $ch = curl_init($this->url);
                        curl_setopt($ch, CURLOPT_POST, true);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        // prevent large delays in PHP execution by setting timeouts while connecting and querying the 3rd party server
                        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 2000); // response wait time
                        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 2000); // output response time
                        $response = curl_exec($ch);
                        if (!$response) return NULL;
                        else return new SimpleXMLElement($response);
                    }

                    // handle sms server response
                    private function returnResult($response) {
                        $return = new StdClass();
                        $return->pass = NULL;
                        $return->msg = '';
                        if ($response == NULL) {
                            $return->pass = FALSE;
                            $return->msg = 'SMS connection error.';
                        } elseif ($response->call_result->result) {
                            $return->pass = 'CallResult: '.TRUE . '</br>';
                            $return->msg = 'EventId: '.$response->send_info->eventid .'</br>Error: '.$response->call_result->error;
                        } else {
                            $return->pass = 'CallResult: '.FALSE. '</br>';
                            $return->msg = 'Error: '.$response->call_result->error;
                        }
                        echo $return->pass;
                        echo $return->msg;
                        return $return;
                    }
red
                }


                $sendSMS = new MyMobileAPI();
                $sendSMS->sendSms("$phone","$content");

                $smsLength=strlen($content);
                $messages=ceil($smsLength/160);
                mysqli_query($link, "insert into sms_messages values(0,'$phone','$content',NOW(),'','','$username','$smsLength','$messages')");

                $to = "$email";
                $subject = "Welcome to $company";
                $body = "Hi $name,";
                $body .= "\nYou are welcome to $company.";
                $body .= "\nHere is your account information please keep this as you may need this at a later stage when you login to the system:";
                $body .= "\n";
                $body .= "\nYour username is: $username";
                $body .= "\nYour password is: $password";
                $body .= "\n";
                $body .= "\nYour password has been set please login and change your password to something more easy to you but secure.";
                $body .= "\n";
                $body .= "\nRegards.";
                $additionalheaders = "From: admin@sbs-eazy.loans";
                mail($to, $subject, $body, $additionalheaders);
                echo '<meta http-equiv="refresh" content="2;url=listemployee.php?tid=' . $_SESSION['tid'] . '">';
                echo '<br>';
                echo '<span class="itext" style="color: orange">Saving Employee.....Please Wait!</span>';
            }
        }
    }
    ?>
</div>
</body>
</html>
