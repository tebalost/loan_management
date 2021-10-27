<style>
    /* The container */
    .container {
        display: block;
        position: relative;
        padding-left: 35px;
        margin-bottom: 12px;
        cursor: pointer;
        font-size: 22px;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    /* Hide the browser's default radio button */
    .container input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }

    /* Create a custom radio button */
    .checkmark {
        position: absolute;
        top: 0;
        left: 0;
        height: 25px;
        width: 25px;
        background-color: #eee;
        border-radius: 50%;
    }

    /* On mouse-over, add a grey background color */
    .container:hover input ~ .checkmark {
        background-color: #ccc;
    }

    /* When the radio button is checked, add a blue background */
    .container input:checked ~ .checkmark {
        background-color: #2196F3;
    }

    /* Create the indicator (the dot/circle - hidden when not checked) */
    .checkmark:after {
        content: "";
        position: absolute;
        display: none;
    }

    /* Show the indicator (dot/circle) when checked */
    .container input:checked ~ .checkmark:after {
        display: block;
    }

    /* Style the indicator (dot/circle) */
    .container .checkmark:after {
        top: 9px;
        left: 9px;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: white;
    }
</style>
<?php
if(isset($_POST['sendMessage'])){
    $messageType=$_POST['messageType'];

    if($messageType==="SMS") {
        $to = $_POST['to'];
        $telephones = $_POST['telephones'];
        $mobiles = $_POST['mobiles'];

        $content = str_replace("<p>", "", $_POST['message']);
        $content = str_replace("</p>", "", $content);
        if (isset($_POST['phoneNumber'])) {
            $phone = "266" . $_POST['phoneNumber'];
        }

        class MyMobileAPI
        {

            public function __construct()
            {
                $this->url = 'http://api.smsportal.com/api5/http5.aspx';
                $this->username = 'serumula'; //your login username
                $this->password = '5erumul@2020'; //your login password
                //$this->validityperiod = '24'; //optional- set desired validity (represents hours)
            }

            public function checkCredits()
            {
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
                    echo '</br>Credits: ' . $response->data->credits;
                    return $response->data->credits;
                }
            }

            public function sendSms($mobile_number, $msg)
            {
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
            private function querySmsServer($data, $optional_headers = null)
            {

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
            private function returnResult($response)
            {
                $return = new StdClass();
                $return->pass = NULL;
                $return->msg = '';
                if ($response == NULL) {
                    $return->pass = FALSE;
                    $return->msg = 'SMS connection error.';
                } elseif ($response->call_result->result) {
                    $return->pass = 'CallResult: ' . TRUE . '</br>';
                    $return->msg = 'EventId: ' . $response->send_info->eventid . '</br>Error: ' . $response->call_result->error;
                } else {
                    $return->pass = 'CallResult: ' . FALSE . '</br>';
                    $return->msg = 'Error: ' . $response->call_result->error;
                }
                //echo $return->pass;
                //echo $return->msg;
                return $return;
            }

        }

        $sendSMS = new MyMobileAPI();
        if ($to == "Individual") {
            $sendSMS->sendSms("$phone", "$content");
            $smsLength = strlen($content);
            $messages = ceil($smsLength / 160);
            mysqli_query($link, "insert into sms_messages values(0,'$phone','$content',NOW(),'','','$tid','$smsLength','$messages')");
            echo "<div class=\"alert alert-success\" >
                                                <a href = \"#\" class = \"close\" data-dismiss= \"alert\"> &times;</a>
                                                Successfully Send an SMS to $phone!&nbsp; &nbsp;&nbsp;
                                                </div>";
        }
        else if ($to == "Regular" || $to == "Non-Regular") {
            if ($to == "Regular") {
                $member = 1;
            } else {
                $member = 0;
            }
            $count = 0;

            if ($telephones == "on" || $mobiles == "on") {
                if ($telephones == "on") {
                    $getTelephones = mysqli_query($link, "select * from borrowers where member = $member and telephone >0 and length(telephone)=8");

                    while ($row = mysqli_fetch_assoc($getTelephones)) {
                        $phone = "266" . $row['telephone'];
                        $sendSMS->sendSms("$phone", "$content");
                        $smsLength = strlen($content);
                        $messages = ceil($smsLength / 160);
                        mysqli_query($link, "insert into sms_messages values(0,'$phone','$content',NOW(),'','','$tid','$smsLength','$messages')");
                        $count += 1;
                    }
                }
                if ($mobiles == "on") {
                    $getMobiles = mysqli_query($link, "select * from borrowers where member = $member and phone >0 and length(phone)=8");
                    while ($row = mysqli_fetch_assoc($getMobiles)) {
                        $phone = "266" . $row['phone'];
                        $sendSMS->sendSms("$phone", "$content");
                        $smsLength = strlen($content);
                        $messages = ceil($smsLength / 160);
                        mysqli_query($link, "insert into sms_messages values(0,'$phone','$content',NOW(),'','','$tid','$smsLength','$messages')");
                        $count += 1;
                    }
                }
            }
            echo mysqli_error($link);
            echo "<div class=\"alert alert-success\" >
                                                <a href = \"#\" class = \"close\" data-dismiss= \"alert\"> &times;</a>
                                                Successfully Send <b>$count</b> messages!&nbsp; &nbsp;&nbsp;
                                                </div>";
        }
    }
    if($messageType==="EMAIL") {
        $api=$baseURL."api/schedular/system-mailer/emails";
        function postToAPI($dataString, $api)
        {
            $ch = curl_init($api);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($dataString))
            );
            return json_decode(curl_exec($ch), true);
        }

        $to = $_POST['email'];
        $subject=$_POST['subject'];
        $content=mysqli_real_escape_string($link, $_POST['message']);;
        if ($to == "Individual") {
            $email=$_POST['emailAddress'];
            $emailData="{\"bcclist\":[\"$email\"],\"message\":{\"subject\":\"$subject\",\"body\":\"$content\"},\"sender\":\"owner\"}";
            $sendEmail=postToAPI($emailData, $api);
            print_r($sendEmail);
            $result=$sendEmail['response'];
            if(strstr($result, "Success")) {
                mysqli_query($link, "insert into sms_messages values(0,'$to','$subject-$content',NOW(),'','','$tid','0','0')");
                echo "<div class=\"alert alert-success\" >
                                                <a href = \"#\" class = \"close\" data-dismiss= \"alert\"> &times;</a>
                                                Successfully send an email to $email!&nbsp; &nbsp;&nbsp;
                                                </div>";
            }else{
                echo "<div class=\"alert alert-danger\" >
                                                <a href = \"#\" class = \"close\" data-dismiss= \"alert\"> &times;</a>
                                                Failed to send an email to $email. Error:-$result!&nbsp; &nbsp;&nbsp;
                                                </div>";
            }
        }
        else if ($to == "Regular" || $to == "Non-Regular") {
            //Get all member emails and form and array
            if($to == "Regular") {
                $email = mysqli_query($link, "select email from borrowers where member = 1 and email!='' and email like '%@%'");
                $emails = [];
                while($row=mysqli_fetch_assoc($email)){
                    $emails[]=$row['email'];
                }
                $allEmails=json_encode($emails);
            }
            else{
                $email = mysqli_query($link, "select email from borrowers where member = 0 and email!='' and email like '%@%'");
                $emails = [];
                while($row=mysqli_fetch_assoc($email)){
                    $emails[]=$row['email'];
                }
                $allEmails=json_encode($emails);
            }
            $emailData="{\"bcclist\":$allEmails,\"message\":{\"subject\":\"$subject\",\"body\":\"$content\"}}";
            $sendEmail=postToAPI($emailData, $api);
            print_r($emailData);
            print_r($api);
            $result=$sendEmail['response'];
            if(strstr($result, "Success")) {
                mysqli_query($link, "insert into sms_messages values(0,'$to','$subject-$content',NOW(),'','','$tid','0','0')");
                echo "<div class=\"alert alert-success\" >
                                                <a href = \"#\" class = \"close\" data-dismiss= \"alert\"> &times;</a>
                                                Successfully send an email to $email!&nbsp; &nbsp;&nbsp;
                                                </div>";
            }else{
                echo "<div class=\"alert alert-danger\" >
                                                <a href = \"#\" class = \"close\" data-dismiss= \"alert\"> &times;</a>
                                                Failed to send an email to $email. Error:-$result!&nbsp; &nbsp;&nbsp;
                                                </div>";
            }
        }
    }
}
?>
<div class="box">

    <div class="box-body">
        <div class="panel panel-success">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-envelope"></i> New Message</h3>
            </div>
            <div class="box-body">
                <form class="form-horizontal" method="post" enctype="multipart/form-data" action="">
                      <div class="box-body">
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Message Type</label>
                            <div class="col-sm-7">
                                <select
                                        name="messageType"
                                        class="form-control"
                                        onchange="showAudience(this.options[this.selectedIndex].value)">
                                    <option>
                                        <option selected disabled>Select</option>
                                        <option value="EMAIL">Email</option>
                                        <option <?php if (isset($_GET['mobile'])){ echo "selected";} ?> value="SMS">SMS</option>
                                </select>
                            </div>
                        </div>
                         <?php if(isset($_GET['mobile'])){ ?>
                             <div class="form-group">
                                 <label for="" class="col-sm-2 control-label">SMS Audience</label>
                                 <div class="col-sm-7">
                                     <select
                                             name="to"
                                             id="to"
                                             class="form-control"
                                             onchange="get_numbers();"
                                             readonly=""
                                             style="width:100%">
                                         <option value="" selected="selected"></option>
                                         <option value="Regular">Regular Members</option>
                                         <option value="Non-Regular">Non-Regular Members</option>
                                         <option <?php if (isset($_GET['mobile'])){ echo "selected";} ?> value="Individual">Individual Borrower</option>
                                     </select>
                                 </div>
                             </div>
                          <div class="form-group">
                              <label class="col-sm-2 control-label">Phone Numbers:</label>
                              <div class="col-sm-7">
                                  <div class="input-group">
                                      <div class="input-group-addon">
                                          <i class="fa fa-phone"></i> <b>266</b>
                                      </div>
                                      <input type="number"
                                             name="phoneNumber"
                                             readonly
                                             value="<?php echo $_GET['mobile']; ?>"
                                             class="form-control" >
                                  </div>
                              </div>
                          </div>
                         <?php } ?>
                          <!--
                       <div class="form-group" id="smsField" style="display: none">
                           <label for="" class="col-sm-2 control-label">Select SMS Audience</label>
                           <div class="col-sm-7">
                               <select
                                       name="to"
                                       id="to"
                                       class="form-control"
                                       onchange="get_numbers();"
                                       required
                                       style="width:100%">
                                   <option value="" selected="selected"></option>
                                   <option value="Regular">Regular Members</option>
                                   <option value="Non-Regular">Non-Regular Members</option>
                                   <option value="Individual">Individual Borrower</option>
                               </select>
                           </div>
                       </div>
                     <div class="form-group" id="emailField" style="display: none">
                           <label for="" class="col-sm-2 control-label">Select Email Audience</label>
                           <div class="col-sm-7">
                               <select
                                       name="to"
                                       id="email"
                                       class="form-control"
                                       onchange="get_emails();"
                                       required
                                       style="width:100%">
                                   <option value="" selected="selected"></option>
                                   <option value="Regular">Regular Members</option>
                                   <option value="Non-Regular">Non-Regular Members</option>
                                   <option value="Individual">Individual Borrower</option>
                               </select>
                           </div>
                       </div>-->
                          <div id="div_contacts"></div>
                          <div id="get_numbers"></div>
                          <div id="get_emails"></div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Content</label>
                            <div class="col-sm-7">
                                <textarea name="message" id="editor1" class="form-control" rows="4"
                                          cols="80"></textarea>
                            </div>
                        </div>

                    </div>

                    <div align="center">
                        <div class="box-footer">
                            <button type="reset" class="btn btn-primary"><i class="fa fa-times">&nbsp;Reset</i>
                            </button>
                            <button name="sendMessage" type="submit" class="btn btn-success"><i class="fa fa-save">&nbsp;Send</i>
                            </button>

                        </div>
                    </div>
                </form>


            </div>
        </div>
    </div>
</div>


<script>
    function showAudience(name) {
        if (name == 'SMS')
            document.getElementById('div_contacts').innerHTML = '<div class="form-group">\n' +
                '                           <label for="" class="col-sm-2 control-label">Select SMS Audience</label>\n' +
                '                           <div class="col-sm-7">\n' +
                '                               <select\n' +
                '                                       name="to"\n' +
                '                                       id="to"\n' +
                '                                       class="form-control"\n' +
                '                                       onchange="get_numbers();"\n' +
                '                                       required\n' +
                '                                       style="width:100%">\n' +
                '                                   <option value="" selected="selected"></option>\n' +
                '                                   <option value="Regular">Regular Members</option>\n' +
                '                                   <option value="Non-Regular">Non-Regular Members</option>\n' +
                '                                   <option value="Individual">Individual Borrower</option>\n' +
                '                               </select>\n' +
                '                           </div>\n' +
                '                       </div>';
        else if (name == 'EMAIL')
            document.getElementById('div_contacts').innerHTML = '<div class="form-group">\n' +
                '                            <label for="" class="col-sm-2 control-label">Subject</label>\n' +
                '                            <div class="col-sm-7">\n' +
                '                                <input type="text" name="subject" id="editor1" class="form-control" required></input>\n' +
                '                            </div>\n' +
                '                        </div>' +
                '' +
                '<div class="form-group">\n' +
                '                           <label for="" class="col-sm-2 control-label">Select Email Audience</label>\n' +
                '                           <div class="col-sm-7">\n' +
                '                               <select\n' +
                '                                       name="email"\n' +
                '                                       id="email"\n' +
                '                                       class="form-control"\n' +
                '                                       onchange="get_emails();"\n' +
                '                                       required\n' +
                '                                       style="width:100%">\n' +
                '                                   <option value="" selected="selected"></option>\n' +
                '                                   <option value="Regular">Regular Members</option>\n' +
                '                                   <option value="Non-Regular">Non-Regular Members</option>\n' +
                '                                   <option value="Individual">Individual Borrower</option>\n' +
                '                               </select>\n' +
                '                           </div>\n' +
                '                       </div>';
        else document.getElementById('div_contacts').innerHTML = '';
    }
</script>
<script type="text/javascript">
    function get_numbers() { // Call to ajax function
        var to = $('#to').val();
        var dataString = "to="+to;
        console.log(to);
        $.ajax({
            type: "POST",
            url: "getnumbers.php", // Name of the php files
            data: dataString,
            success: function(html)
            {
                $("#get_numbers").html(html);
            }
        });
    }

    function get_emails() { // Call to ajax function
        var to = $('#email').val();
        var dataString = "to="+to;
        console.log(to);
        $.ajax({
            type: "POST",
            url: "getemails.php", // Name of the php files
            data: dataString,
            success: function(html)
            {
                $("#get_emails").html(html);
            }
        });
    }


    $("input[type='radio']").change(function(){

        if($(this).val()=="sms")
        {
            $("#smsField").show();
        }else{
            $("#smsField").hide();
        }
        if($(this).val()=="email")
        {
            $("#emailField").show();
        }else{
            $("#emailField").hide();
        }

    });
</script>